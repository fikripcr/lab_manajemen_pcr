<?php
namespace Database\Seeders;

use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\Label;
use App\Models\Pemutu\LabelType;
use App\Models\Pemutu\OrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Pemutu\TimMutu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MainPemutuSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('MainPemutuSeeder started...');

        $this->truncateTables();

        // 1. Core Data
        $this->command->info('Seeding Labels & Org Units...');
        $this->seedLabels();
        $this->seedOrgUnits();
        $this->seedPeriodeSpmi();

        // 2. SPMI Documents Hierarchy
        $this->command->info('Seeding SPMI Documents & Relationships...');
        $this->seedSpmiData();

        $this->command->info('MainPemutuSeeder completed.');
    }

    private function truncateTables()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Dokumen::truncate();
        DokSub::truncate();
        Indikator::truncate();
        PeriodeSpmi::truncate();
        TimMutu::truncate();
        DB::table('pemutu_indikator_label')->truncate();
        DB::table('pemutu_indikator_orgunit')->truncate();
        DB::table('pemutu_indikator_doksub')->truncate();
        DB::table('pemutu_doksub_mapping')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedLabels()
    {
        $typeAkreditasi = LabelType::firstOrCreate(['name' => 'Standar Akreditasi'], ['description' => 'Standar BAN-PT / LAM']);
        $typeISO        = LabelType::firstOrCreate(['name' => 'Klausul ISO'], ['description' => 'ISO 9001:2015']);

        $labels = [
            $typeAkreditasi->labeltype_id => ['Kriteria 1: Visi Misi', 'Kriteria 2: Tata Pamong', 'Kriteria 3: Mahasiswa'],
            $typeISO->labeltype_id        => ['5. Kepemimpinan', '6. Perencanaan', '7. Dukungan'],
        ];

        foreach ($labels as $typeId => $names) {
            foreach ($names as $name) {
                Label::create(['type_id' => $typeId, 'name' => $name, 'slug' => Str::slug($name)]);
            }
        }
    }

    private function seedOrgUnits()
    {
        $pcr = OrgUnit::updateOrCreate(['code' => 'PCR'], ['name' => 'Politeknik Caltex Riau', 'type' => 'Institusi', 'level' => 1, 'seq' => 1]);
        $dir = OrgUnit::updateOrCreate(['code' => 'DIR'], ['name' => 'Direktur', 'type' => 'Pimpinan', 'parent_id' => $pcr->orgunit_id, 'level' => 2, 'seq' => 1]);

        $prodis = [
            ['code' => 'TI', 'name' => 'Teknik Informatika'],
            ['code' => 'SI', 'name' => 'Sistem Informasi'],
            ['code' => 'TK', 'name' => 'Teknik Komputer'],
            ['code' => 'TE', 'name' => 'Teknik Elektronika'],
            ['code' => 'ME', 'name' => 'Teknik Mesin'],
        ];

        foreach ($prodis as $idx => $prodi) {
            OrgUnit::updateOrCreate(['code' => $prodi['code']], [
                'name'      => $prodi['name'],
                'type'      => 'Prodi',
                'parent_id' => $dir->orgunit_id,
                'level'     => 3,
                'seq'       => $idx + 1,
            ]);
        }
    }

    private function seedPeriodeSpmi()
    {
        $now = now();
        PeriodeSpmi::create([
            'periode'         => date('Y'),
            'jenis_periode'   => 'Akademik',
            'penetapan_awal'  => $now->copy()->startOfYear(),
            'penetapan_akhir' => $now->copy()->addMonths(2),
            'ed_awal'         => $now->copy()->addMonths(2),
            'ed_akhir'        => $now->copy()->addMonths(4),
            'ami_awal'        => $now->copy()->addMonths(4),
            'ami_akhir'       => $now->copy()->addMonths(6),
        ]);
    }

    private function seedSpmiData()
    {
        $periode = date('Y');
        $indSeq  = 1;

        // A. KEBIJAKAN (Visi, Misi, RPJP, Renstra, Renop)
        $kebijakanTypes = ['visi', 'misi', 'rjp', 'renstra', 'renop'];
        $poinMap        = []; // To handle linkage

        foreach ($kebijakanTypes as $idx => $jenis) {
            $dok = Dokumen::create([
                'judul'      => pemutuJenisLabelFull($jenis),
                'periode'    => $periode,
                'jenis'      => $jenis,
                'level'      => 1,
                'seq'        => $idx + 1,
                'created_by' => 1,
            ]);

            $poinMap[$jenis] = [];
            for ($p = 1; $p <= 5; $p++) {
                $sub = DokSub::create([
                    'dok_id'                => $dok->dok_id,
                    'judul'                 => $dok->judul . " - Poin $p",
                    'kode'                  => strtoupper(substr($jenis, 0, 2)) . '-' . str_pad($p, 2, '0', STR_PAD_LEFT),
                    'seq'                   => $p,
                    'is_hasilkan_indikator' => ($jenis === 'renop'),
                    'created_by'            => 1,
                ]);
                $poinMap[$jenis][$p] = $sub;

                // Link to previous level policy
                if ($idx > 0) {
                    $prevJenis    = $kebijakanTypes[$idx - 1];
                    $mappedDokSub = $poinMap[$prevJenis][$p];
                    DB::table('pemutu_doksub_mapping')->insert([
                        'doksub_id'        => $sub->doksub_id,
                        'mapped_doksub_id' => $mappedDokSub->doksub_id,
                        'created_at'       => now(), 'updated_at' => now(),
                    ]);
                }

                // RENOP gets 5 indicators per point
                if ($jenis === 'renop') {
                    for ($i = 1; $i <= 5; $i++) {
                        $ind = Indikator::create([
                            'type'               => 'renop',
                            'kelompok_indikator' => 'Akademik',
                            'no_indikator'       => "RN-" . str_pad($indSeq++, 4, '0', STR_PAD_LEFT),
                            'indikator'          => "Indikator Renop $i untuk Poin $p",
                            'target'             => rand(70, 100) . '%',
                            'created_by'         => 1,
                        ]);
                        $ind->dokSubs()->attach($sub->doksub_id, ['is_hasilkan_indikator' => true]);
                    }
                }
            }
        }

        // B. STANDAR (5 Standards)
        $standarList = [
            'Standar Kompetensi Lulusan',
            'Standar Isi Pembelajaran',
            'Standar Proses Pembelajaran',
            'Standar Penilaian Pembelajaran',
            'Standar Dosen dan Tenaga Kependidikan',
        ];

        $standarPoints = [
            'Visi, Misi dan Tujuan', 'Rasional Standar', 'Definisi Istilah',
            'Pihak yang Bertanggungjawab', 'Pernyataan Isi Standar / Indikator Capaian',
            'Strategi Pelaksanaan', 'Dokumen Terkait', 'Referensi',
        ];

        foreach ($standarList as $idx => $judul) {
            $dok = Dokumen::create([
                'judul'      => $judul,
                'periode'    => $periode,
                'jenis'      => 'standar',
                'level'      => 1,
                'seq'        => $idx + 1,
                'kode'       => 'STD-' . str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
                'created_by' => 1,
            ]);

            foreach ($standarPoints as $pIdx => $pJudul) {
                $isIndikatorPoint = ($pIdx === 4); // Index 4 is point 5
                $sub              = DokSub::create([
                    'dok_id'                => $dok->dok_id,
                    'judul'                 => $pJudul,
                    'seq'                   => $pIdx + 1,
                    'is_hasilkan_indikator' => $isIndikatorPoint,
                    'created_by'            => 1,
                ]);

                if ($isIndikatorPoint) {
                    for ($i = 1; $i <= 5; $i++) {
                        $ind = Indikator::create([
                            'type'               => 'standar',
                            'kelompok_indikator' => 'Akademik',
                            'no_indikator'       => "ST-" . str_pad($indSeq++, 4, '0', STR_PAD_LEFT),
                            'indikator'          => "Indikator Standar $i untuk " . $judul,
                            'target'             => rand(80, 100) . '%',
                            'created_by'         => 1,
                        ]);
                        $ind->dokSubs()->attach($sub->doksub_id, ['is_hasilkan_indikator' => true]);
                    }
                }
            }
        }

        // C. FORMULIR & MANUAL PROSEDUR (5 each, No Points)
        for ($f = 1; $f <= 5; $f++) {
            Dokumen::create([
                'judul'      => "Formulir SPMI $f",
                'periode'    => $periode,
                'jenis'      => 'formulir',
                'level'      => 1,
                'seq'        => $f,
                'created_by' => 1,
            ]);
        }

        for ($m = 1; $m <= 5; $m++) {
            Dokumen::create([
                'judul'      => "Manual Prosedur SPMI $m",
                'periode'    => $periode,
                'jenis'      => 'manual_prosedur',
                'level'      => 1,
                'seq'        => $m,
                'created_by' => 1,
            ]);
        }
    }
}
