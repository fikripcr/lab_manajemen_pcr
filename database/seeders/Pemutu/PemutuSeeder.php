<?php
namespace Database\Seeders\Pemutu;

use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\Label;
use App\Models\Pemutu\LabelType;
use App\Models\Pemutu\OrgUnit;
use App\Models\Pemutu\Personil;
use App\Models\User;
use Illuminate\Database\Seeder;

class PemutuSeeder extends Seeder
{
    public function run()
    {
        $this->command->info("DB Name: " . \DB::connection()->getDatabaseName());

        try {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            LabelType::truncate();
            Label::truncate();
            OrgUnit::truncate();
            Personil::truncate();
            Dokumen::truncate();
            DokSub::truncate();
            Indikator::truncate();
            \DB::table('pemutu_indikator_label')->truncate();
            \DB::table('pemutu_indikator_orgunit')->truncate();
            \DB::table('pemutu_indikator_doksub')->truncate();
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // 1. Label Types & Labels
            $typeAkreditasi = LabelType::create(['name' => 'Standar Akreditasi', 'description' => 'Standar BAN-PT / LAM']);
            $typeISO        = LabelType::create(['name' => 'Klausul ISO', 'description' => 'ISO 9001:2015']);
            $typeRenstra    = LabelType::create(['name' => 'Kategori Renstra', 'description' => 'Bidang Fokus Renstra']);

            $labels = [
                $typeAkreditasi->labeltype_id => ['Kriteria 1: Visi Misi', 'Kriteria 2: Tata Pamong', 'Kriteria 3: Mahasiswa', 'Kriteria 4: SDM', 'Kriteria 9: Luaran'],
                $typeISO->labeltype_id        => ['4. Konteks Organisasi', '5. Kepemimpinan', '6. Perencanaan', '7. Dukungan', '8. Operasional'],
                $typeRenstra->labeltype_id    => ['Bidang Akademik', 'Bidang Keuangan', 'Bidang Kemahasiswaan', 'Bidang Sarpras'],
            ];

            $createdLabels = [];
            foreach ($labels as $typeId => $names) {
                foreach ($names as $name) {
                    $createdLabels[] = Label::create([
                        'type_id' => $typeId,
                        'name'    => $name,
                        'slug'    => \Str::slug($name),
                    ]);
                }
            }

            // 2. Org Units
            $pcr      = OrgUnit::create(['name' => 'Politeknik Caltex Riau', 'code' => 'PCR', 'type' => 'Institusi', 'level' => 1, 'seq' => 1]);
            $direktur = OrgUnit::create(['name' => 'Direktur', 'code' => 'DIR', 'type' => 'Pimpinan', 'parent_id' => $pcr->orgunit_id, 'level' => 2, 'seq' => 1]);
            $wadir1   = OrgUnit::create(['name' => 'Wadir 1 (Akademik)', 'code' => 'WDIR1', 'type' => 'Pimpinan', 'parent_id' => $direktur->orgunit_id, 'level' => 3, 'seq' => 1]);
            $wadir2   = OrgUnit::create(['name' => 'Wadir 2 (Keu & Umum)', 'code' => 'WDIR2', 'type' => 'Pimpinan', 'parent_id' => $direktur->orgunit_id, 'level' => 3, 'seq' => 2]);
            $wadir3   = OrgUnit::create(['name' => 'Wadir 3 (Mhs & Alumni)', 'code' => 'WDIR3', 'type' => 'Pimpinan', 'parent_id' => $direktur->orgunit_id, 'level' => 3, 'seq' => 3]);
            $tik      = OrgUnit::create(['name' => 'Jurusan TIK', 'code' => 'JTIK', 'type' => 'Jurusan', 'parent_id' => $wadir1->orgunit_id, 'level' => 4, 'seq' => 1]);
            $te       = OrgUnit::create(['name' => 'Jurusan Teknik Elektronika', 'code' => 'JTE', 'type' => 'Jurusan', 'parent_id' => $wadir1->orgunit_id, 'level' => 4, 'seq' => 2]);
            $prodiTi  = OrgUnit::create(['name' => 'D4 Teknik Informatika', 'code' => 'TI', 'type' => 'Prodi', 'parent_id' => $tik->orgunit_id, 'level' => 5, 'seq' => 1]);
            $prodiSi  = OrgUnit::create(['name' => 'D4 Sistem Informasi', 'code' => 'SI', 'type' => 'Prodi', 'parent_id' => $tik->orgunit_id, 'level' => 5, 'seq' => 2]);
            $bpm      = OrgUnit::create(['name' => 'Badan Penjaminan Mutu', 'code' => 'BPM', 'type' => 'Unit', 'parent_id' => $direktur->orgunit_id, 'level' => 3, 'seq' => 4]);

            // 3. Personils
            $users = User::limit(5)->get();
            Personil::create(['nama' => 'Dr. Dadang Syarif', 'email' => 'dadang@pcr.ac.id', 'jenis' => 'Dosen', 'org_unit_id' => $direktur->orgunit_id]);
            Personil::create(['nama' => 'Maksum Rois', 'email' => 'maksum@pcr.ac.id', 'jenis' => 'Dosen', 'org_unit_id' => $wadir1->orgunit_id]);
            Personil::create(['nama' => 'Yohana Dewi', 'email' => 'yohana@pcr.ac.id', 'jenis' => 'Staf', 'org_unit_id' => $bpm->orgunit_id]);

            // 4. Dokumen
            $this->command->info("Creating Dokumen...");
            $renstra = Dokumen::create([
                'judul'          => 'Rencana Strategis (RENSTRA) PCR 2025-2030',
                'kode'           => 'RENSTRA-2025',
                'jenis'          => 'renstra',
                'periode'        => 2025,
                'std_is_staging' => 0,
            ]);

            $visi = DokSub::create([
                'dok_id' => $renstra->dok_id,
                'judul'  => 'Visi Politeknik Caltex Riau',
                'isi'    => '<p>Visi...</p>',
                'seq'    => 1,
            ]);
            $misi = DokSub::create([
                'dok_id' => $renstra->dok_id,
                'judul'  => 'Misi Politeknik Caltex Riau',
                'isi'    => '<p>Misi...</p>',
                'seq'    => 2,
            ]);
            $this->command->info("DokSub Created");

            // Indikator Visi (V-01)
            $this->command->info("Creating Indikator V-01...");
            $indikatorV1 = Indikator::create([
                'type'            => 'renop',
                'no_indikator'    => 'V-01',
                'indikator'       => 'Tercapainya akreditasi Unggul...',
                'target'          => '100% Prodi Unggul',
                'jenis_indikator' => 'Utama',
            ]);

            if ($visi && $indikatorV1) {
                // Attach DokSub V-01 (Using Manual Insert)
                \DB::table('pemutu_indikator_doksub')->insert([
                    'indikator_id'          => $indikatorV1->indikator_id,
                    'doksub_id'             => $visi->doksub_id,
                    'is_hasilkan_indikator' => 0,
                    // Timestamps omitted to prevent Data Truncated error
                ]);
            }

            // Attach label manually
            if ($indikatorV1 && isset($createdLabels[0])) {
                \DB::table('pemutu_indikator_label')->insert([
                    'indikator_id' => $indikatorV1->indikator_id,
                    'label_id'     => $createdLabels[0]->label_id,
                    // Timestamps omitted
                ]);
            }

            // Indikator V2
            $this->command->info("Creating Indikator V-02...");
            if ($visi) {
                $indikatorV2 = Indikator::create([
                    'type'            => 'renop',
                    'no_indikator'    => 'V-02',
                    'indikator'       => 'Peningkatan ranking Webometrics...',
                    'target'          => 'Top 5',
                    'jenis_indikator' => 'Tambahan',
                    'parent_id'       => null,
                ]);

                if ($indikatorV2) {
                    // Attach DokSub V-02
                    \DB::table('pemutu_indikator_doksub')->insert([
                        'indikator_id'          => $indikatorV2->indikator_id,
                        'doksub_id'             => $visi->doksub_id,
                        'is_hasilkan_indikator' => 0,
                    ]);

                    // Attach OrgUnits to V-02
                    $this->command->info("Attaching OrgUnits to V-02...");
                    \DB::table('pemutu_indikator_orgunit')->insert([
                        [
                            'indikator_id' => $indikatorV2->indikator_id,
                            'org_unit_id'  => $bpm->orgunit_id,
                            'target'       => null,
                        ],
                        [
                            'indikator_id' => $indikatorV2->indikator_id,
                            'org_unit_id'  => $wadir1->orgunit_id,
                            'target'       => null,
                        ],
                    ]);
                }
            }

            // 5. Dokumen: MANUAL MUTU
            $manual = Dokumen::create([
                'judul'          => 'Manual Mutu SPMI',
                'kode'           => 'MM-SPMI-01',
                'jenis'          => 'manual_prosedur', // Fixed enum value
                'periode'        => 2025,
                'std_is_staging' => 1,
            ]);

            DokSub::create([
                'dok_id' => $manual->dok_id,
                'judul'  => 'Kebijakan Mutu',
                'isi'    => '<p>PCR berkomitmen...</p>',
                'seq'    => 1,
            ]);

            $this->command->info('Seeding Pemutu Data Selesai (Bahasa Indonesia).');

        } catch (\Throwable $e) {
            $this->command->error("EXCEPTION CAUGHT: " . $e->getMessage());
            $this->command->error("Line: " . $e->getLine());
        }
    }
}
