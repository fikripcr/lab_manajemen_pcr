<?php
namespace Database\Seeders;

use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\Label;
use App\Models\Pemutu\LabelType;
use App\Models\Pemutu\OrgUnit;
use App\Models\Pemutu\PeriodeKpi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainPemutuSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('MainPemutuSeeder started...');
        $this->command->info("DB Name: " . \DB::connection()->getDatabaseName());

        $this->truncateTables();

        // 1. Label Types & Labels
        $this->seedLabels();

        // 2. Org Units
        $this->seedOrgUnits();

        // 3. Periode KPI
        $this->seedPeriodeKpi();

        // 3b. Periode SPMI
        $this->seedPeriodeSpmi();

        // 4. Dokumen & Indikator (Hierarki Lengkap)
        $this->seedDokumen();

        $this->command->info('MainPemutuSeeder completed.');
    }

    private function truncateTables()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            LabelType::truncate();
            Label::truncate();
            // OrgUnit::truncate(); // Shared table, do not truncate
            // Pegawai::truncate(); // Shared table, do not truncate
            Dokumen::truncate();
            DokSub::truncate();
            Indikator::truncate();
            PeriodeKpi::truncate();
            \App\Models\Pemutu\PeriodeSpmi::truncate();
            \App\Models\Pemutu\TimMutu::truncate();
            DB::table('pemutu_indikator_label')->truncate();
            DB::table('pemutu_indikator_orgunit')->truncate();
            DB::table('pemutu_indikator_doksub')->truncate();
            DB::table('pemutu_doksub_mapping')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (\Throwable $e) {
            $this->command->error("Truncate Error: " . $e->getMessage());
        }
    }

    private function seedLabels()
    {
        $this->command->info('Seeding Labels...');
        $typeAkreditasi = LabelType::create(['name' => 'Standar Akreditasi', 'description' => 'Standar BAN-PT / LAM']);
        $typeISO        = LabelType::create(['name' => 'Klausul ISO', 'description' => 'ISO 9001:2015']);
        $typeRenstra    = LabelType::create(['name' => 'Kategori Renstra', 'description' => 'Bidang Fokus Renstra']);

        $labels = [
            $typeAkreditasi->labeltype_id => ['Kriteria 1: Visi Misi', 'Kriteria 2: Tata Pamong', 'Kriteria 3: Mahasiswa', 'Kriteria 4: SDM', 'Kriteria 9: Luaran'],
            $typeISO->labeltype_id        => ['4. Konteks Organisasi', '5. Kepemimpinan', '6. Perencanaan', '7. Dukungan', '8. Operasional'],
            $typeRenstra->labeltype_id    => ['Bidang Akademik', 'Bidang Keuangan', 'Bidang Kemahasiswaan', 'Bidang Sarpras'],
        ];

        foreach ($labels as $typeId => $names) {
            foreach ($names as $name) {
                Label::create(['type_id' => $typeId, 'name' => $name, 'slug' => \Str::slug($name)]);
            }
        }
    }

    private function seedOrgUnits()
    {
        $this->command->info('Seeding Org Units...');
        $pcr      = OrgUnit::updateOrCreate(['code' => 'PCR'], ['name' => 'Politeknik Caltex Riau', 'type' => 'Institusi', 'level' => 1, 'seq' => 1]);
        $direktur = OrgUnit::updateOrCreate(['code' => 'DIR'], ['name' => 'Direktur', 'type' => 'Pimpinan', 'parent_id' => $pcr->orgunit_id, 'level' => 2, 'seq' => 1]);
        $wadir1   = OrgUnit::updateOrCreate(['code' => 'WDIR1'], ['name' => 'Wadir 1 (Akademik)', 'type' => 'Pimpinan', 'parent_id' => $direktur->orgunit_id, 'level' => 3, 'seq' => 1]);
        $wadir2   = OrgUnit::updateOrCreate(['code' => 'WDIR2'], ['name' => 'Wadir 2 (Keu & Umum)', 'type' => 'Pimpinan', 'parent_id' => $direktur->orgunit_id, 'level' => 3, 'seq' => 2]);
        $wadir3   = OrgUnit::updateOrCreate(['code' => 'WDIR3'], ['name' => 'Wadir 3 (Mhs & Alumni)', 'type' => 'Pimpinan', 'parent_id' => $direktur->orgunit_id, 'level' => 3, 'seq' => 3]);

        // Basic Units needed for structure
        $tik = OrgUnit::updateOrCreate(['code' => 'JTIK'], ['name' => 'Jurusan TIK', 'type' => 'Jurusan', 'parent_id' => $wadir1->orgunit_id, 'level' => 4, 'seq' => 1]);
        $bpm = OrgUnit::updateOrCreate(['code' => 'BPM'], ['name' => 'Badan Penjaminan Mutu', 'type' => 'Unit', 'parent_id' => $direktur->orgunit_id, 'level' => 3, 'seq' => 4]);

                                                             // Add more units from PersonilSeeder logic if needed, but the structure in MainPemutuSeeder seemed minimal.
                                                             // Let's expand with generic structure to support PersonilSeeder
        $this->createGenericUnits($wadir1, 'Jurusan', 2);    // More Jurusans
        $this->createGenericUnits($wadir1, 'Direktorat', 0); // No extra Direktorat under Wadir1, Wadir IS Direktorat level usually? No, Wadir is Pimpinan.

        // PersonilSeeder expects 'Direktorat', 'Bagian', 'Jurusan', 'Prodi', 'Laboratorium'
        // Let's create proper structure for PersonilSeeder
        OrgUnit::updateOrCreate(['code' => 'SEKDIR'], ['name' => 'Sekretariat Direktur', 'type' => 'Sekretariat', 'parent_id' => $direktur->orgunit_id, 'level' => 3, 'seq' => 99]);

        // Additional Jurusans and Prodis
        $te = OrgUnit::updateOrCreate(['code' => 'JTE'], ['name' => 'Jurusan Teknik Elektronika', 'type' => 'Jurusan', 'parent_id' => $wadir1->orgunit_id, 'level' => 4, 'seq' => 2]);
        OrgUnit::updateOrCreate(['code' => 'TI'], ['name' => 'D4 Teknik Informatika', 'type' => 'Prodi', 'parent_id' => $tik->orgunit_id, 'level' => 5, 'seq' => 1]);
        OrgUnit::updateOrCreate(['code' => 'SI'], ['name' => 'D4 Sistem Informasi', 'type' => 'Prodi', 'parent_id' => $tik->orgunit_id, 'level' => 5, 'seq' => 2]);
        OrgUnit::updateOrCreate(['code' => 'MK'], ['name' => 'D4 Teknik Mekatronika', 'type' => 'Prodi', 'parent_id' => $te->orgunit_id, 'level' => 5, 'seq' => 1]);

        // Bagian
        $bagianUmum = OrgUnit::updateOrCreate(['code' => 'BAU'], ['name' => 'Bagian Umum', 'type' => 'Bagian', 'parent_id' => $wadir2->orgunit_id, 'level' => 4, 'seq' => 1]);

        // Labs
        OrgUnit::updateOrCreate(['code' => 'LAB1'], ['name' => 'Lab Komputer 1', 'type' => 'Laboratorium', 'parent_id' => $tik->orgunit_id, 'level' => 5, 'seq' => 1]);
    }

    private function createGenericUnits($parent, $type, $count)
    {
        for ($i = 1; $i <= $count; $i++) {
            OrgUnit::firstOrCreate(
                ['code' => strtoupper(substr($type, 0, 3)) . $i],
                [
                    'name'      => "$type $i",
                    'type'      => $type,
                    'parent_id' => $parent->orgunit_id,
                    'level'     => $parent->level + 1,
                    'seq'       => 10 + $i,
                ]
            );
        }
    }

    private function seedPeriodeKpi()
    {
        $this->command->info('Seeding Periode KPI...');
        PeriodeKpi::updateOrCreate(['tahun_akademik' => '2024/2025', 'semester' => 'Ganjil'], ['nama' => 'Semester Ganjil 2024/2025', 'tahun' => 2024, 'tanggal_mulai' => Carbon::parse('2024-08-01'), 'tanggal_selesai' => Carbon::parse('2025-01-31'), 'is_active' => true]);
        PeriodeKpi::updateOrCreate(['tahun_akademik' => '2023/2024', 'semester' => 'Genap'], ['nama' => 'Semester Genap 2023/2024', 'tahun' => 2024, 'tanggal_mulai' => Carbon::parse('2024-02-01'), 'tanggal_selesai' => Carbon::parse('2024-07-31'), 'is_active' => false]);
    }

    private function seedPeriodeSpmi()
    {
        $this->command->info('Seeding Periode SPMI...');
        $now = now();
        \App\Models\Pemutu\PeriodeSpmi::create([
            'periode'         => 2026,
            'jenis_periode'   => 'Akademik',
            'penetapan_awal'  => $now->copy()->startOfYear(),
            'penetapan_akhir' => $now->copy()->addMonths(2),
            'ed_awal'         => $now->copy()->addMonths(2),
            'ed_akhir'        => $now->copy()->addMonths(4),
            'ami_awal'        => $now->copy()->addMonths(4),
            'ami_akhir'       => $now->copy()->addMonths(6),
        ]);

        \App\Models\Pemutu\PeriodeSpmi::create([
            'periode'         => 2026,
            'jenis_periode'   => 'Non Akademik',
            'penetapan_awal'  => $now->copy()->startOfYear(),
            'penetapan_akhir' => $now->copy()->addMonths(2),
        ]);
    }

    private function seedDokumen()
    {
        $this->command->info('Seeding Dokumen & Indikator (New Architecture)...');
        $periode = 2026;
        $faker   = \Faker\Factory::create('id_ID');
        $indSeq  = 1; // Global sequence for YYXXXX format

        // 1. DOKUMEN KEBIJAKAN (Hierarki & Mapping)
        $jenisKebijakan = [
            'visi'    => 'Visi Politeknik Caltex Riau',
            'misi'    => 'Misi Politeknik Caltex Riau',
            'rjp'     => 'Rencana Pembangunan Jangka Panjang (RPJP)',
            'renstra' => 'Rencana Strategis (Renstra)',
            'renop'   => 'Rencana Operasional (Renop)',
        ];

        $dokumenMap = []; // jenis => Dokumen model
        $poinMap    = []; // jenis => [DokSub models]
        $seq        = 1;
        foreach ($jenisKebijakan as $jenis => $label) {
            $dok = Dokumen::create([
                'judul'      => $label,
                'periode'    => $periode,
                'jenis'      => $jenis,
                'level'      => 1,
                'seq'        => $seq++,
                'created_by' => 1,
            ]);
            $dokumenMap[$jenis] = $dok;
            $poinMap[$jenis]    = [];

            // Create some points for each policy document
            $count = ($jenis === 'visi' ? 3 : ($jenis === 'misi' ? 5 : 8));
            for ($p = 1; $p <= $count; $p++) {
                $sub = DokSub::create([
                    'dok_id'                => $dok->dok_id,
                    'judul'                 => $label . " - Poin $p",
                    'isi'                   => "<p>Konten detail untuk $label poin ke-$p. Dokumen ini merupakan landasan strategis institusi.</p>",
                    'seq'                   => $p,
                    'kode'                  => strtoupper(substr($jenis, 0, 2)) . '-' . str_pad($p, 2, '0', STR_PAD_LEFT),
                    'is_hasilkan_indikator' => ($jenis === 'renop' ? 1 : 0),
                    'created_by'            => 1,
                ]);
                $poinMap[$jenis][] = $sub;

                // RENOP → Hasilkan Indikator Renop
                if ($jenis === 'renop') {
                    $indRenop = Indikator::create([
                        'type'               => 'renop',
                        'kelompok_indikator' => 'Akademik',
                        'no_indikator'       => substr($periode, -2) . str_pad($indSeq++, 4, '0', STR_PAD_LEFT),
                        'indikator'          => "Tercapainya sasaran strategis Renop poin ke-$p",
                        'target'             => rand(80, 100) . '%',
                        'jenis_indikator'    => 'Utama',
                        'created_by'         => 1,
                    ]);
                    $indRenop->dokSubs()->attach($sub->doksub_id, ['is_hasilkan_indikator' => true]);
                }
            }
        }

        // Establish Mappings (Misi -> Visi, Renstra -> RPJP, etc.)
        $mappingChain = [
            'misi'    => 'visi',
            'rjp'     => 'misi',
            'renstra' => 'rjp',
            'renop'   => 'renstra',
        ];
        foreach ($mappingChain as $source => $target) {
            foreach ($poinMap[$source] as $sSub) {
                $tSub = collect($poinMap[$target])->random();
                \DB::table('pemutu_doksub_mapping')->insert([
                    'doksub_id'        => $sSub->doksub_id,
                    'mapped_doksub_id' => $tSub->doksub_id,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }

// 2. DOKUMEN STANDAR (With specific 8 points structure)
        $standarList = [
            'Standar Kompetensi Lulusan',
            'Standar Isi Pembelajaran',
            'Standar Proses Pembelajaran',
            'Standar Penilaian Pembelajaran',
            'Standar Dosen dan Tenaga Kependidikan',
            'Standar Sarana Prasarana',
        ];

        $standarPoints = [
            'Visi, Misi dan Tujuan',
            'Rasional Standar',
            'Definisi Istilah',
            'Subjek/Pihak yang Bertanggungjawab untuk Mencapai/Memenuhi Isi Standar',
            'Pernyataan Isi Standar/Indikator Capaian',
            'Strategi Pelaksanaan Standar',
            'Dokumen Terkait dalam Pelaksanaan Standar',
            'Referensi',
        ];

        $units  = OrgUnit::where('type', 'Prodi')->limit(5)->get();
        $labels = Label::all();

        foreach ($standarList as $index => $judul) {
            $std = Dokumen::create([
                'judul'      => $judul,
                'periode'    => $periode,
                'jenis'      => 'standar',
                'level'      => 1,
                'seq'        => $index + 1,
                'kode'       => 'STD-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'created_by' => 1,
            ]);

            foreach ($standarPoints as $pIndex => $pJudul) {
                $isIndikatorPoint = ($pIndex === 4); // Poin ke-5 (Index 4)
                $sub              = DokSub::create([
                    'dok_id'                => $std->dok_id,
                    'judul'                 => $pJudul,
                    'isi'                   => "<p>Konten untuk $pJudul pada $judul.</p>",
                    'seq'                   => $pIndex + 1,
                    'is_hasilkan_indikator' => $isIndikatorPoint,
                    'created_by'            => 1,
                ]);

                if ($isIndikatorPoint) {
                    // Create Indikator Standar linked to Point 5
                    $ind = Indikator::create([
                        'type'               => 'standar',
                        'kelompok_indikator' => 'Akademik',
                        'no_indikator'       => substr($periode, -2) . str_pad($indSeq++, 4, '0', STR_PAD_LEFT),
                        'indikator'          => "Ketercapaian indikator utama pada " . $judul,
                        'target'             => rand(80, 100) . '%',
                        'jenis_indikator'    => 'Utama',
                        'created_by'         => 1,
                    ]);
                    $ind->dokSubs()->attach($sub->doksub_id, ['is_hasilkan_indikator' => true]);

                    // Attach to Units (Prodis)
                    foreach ($units as $unit) {
                        \App\Models\Pemutu\IndikatorOrgUnit::create([
                            'indikator_id' => $ind->indikator_id,
                            'org_unit_id'  => $unit->orgunit_id,
                            'target'       => $ind->target,
                            'ed_capaian'   => rand(70, 95) . '%',
                            'ed_skala'     => rand(2, 4),
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ]);
                    }

                    if ($labels->isNotEmpty()) {
                        $ind->labels()->attach($labels->random(2)->pluck('label_id'));
                    }
                }
            }

            // 3. FORMULIR & MANUAL PROSEDUR (Linked as children of Standar)
            for ($f = 1; $f <= 2; $f++) {
                Dokumen::create([
                    'parent_id'  => $std->dok_id,
                    'judul'      => "Formulir $judul - $f",
                    'periode'    => $periode,
                    'jenis'      => 'formulir',
                    'level'      => 2,
                    'seq'        => $f,
                    'created_by' => 1,
                ]);
            }

            for ($m = 1; $m <= 2; $m++) {
                Dokumen::create([
                    'parent_id'  => $std->dok_id,
                    'judul'      => "Manual Prosedur $judul - $m",
                    'periode'    => $periode,
                    'jenis'      => 'manual_prosedur',
                    'level'      => 2,
                    'seq'        => $m,
                    'created_by' => 1,
                ]);
            }
        }

// 4. DOKUMEN LAINNYA
        Dokumen::create([
            'judul'      => 'Dokumen SPMI Lainnya',
            'periode'    => $periode,
            'jenis'      => 'dll',
            'level'      => 1,
            'seq'        => 99,
            'created_by' => 1,
        ]);
    }

    private function generateRandomIndikatorText($type)
    {
        $actions  = ['Meningkatkan', 'Mempertahankan', 'Menurunkan', 'Mencapai', 'Menghasilkan'];
        $objects  = ['Kualitas Pembelajaran', 'Jumlah Publikasi', 'Rasio Dosen Mahasiswa', 'Kepuasan Pengguna', 'Serapan Anggaran', 'Prestasi Mahasiswa', 'Kerjasama Industri', 'Sitasi Penelitian'];
        $contexts = ['Tingkat Nasional', 'Tingkat Internasional', 'di Program Studi', 'di Lingkungan Kampus', 'per Semester'];

        $action  = $actions[array_rand($actions)];
        $object  = $objects[array_rand($objects)];
        $context = $contexts[array_rand($contexts)];

        return "$action $object $context ($type)";
    }
}
