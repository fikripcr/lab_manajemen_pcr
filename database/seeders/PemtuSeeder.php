<?php
namespace Database\Seeders;

use App\Models\Pemtu\DokSub;
use App\Models\Pemtu\Dokumen;
use App\Models\Pemtu\Indikator;
use App\Models\Pemtu\Label;
use App\Models\Pemtu\LabelType;
use App\Models\Pemtu\OrgUnit;
use App\Models\Pemtu\Personil;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PemtuSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // 1. Label Types & Labels
        $typeAkreditasi = LabelType::create(['name' => 'Standar Akreditasi', 'description' => 'Standar BAN-PT / LAM']);
        $typeISO        = LabelType::create(['name' => 'Klausul ISO', 'description' => 'ISO 9001:2015']);
        $typeRenstra    = LabelType::create(['name' => 'Kategori Renstra', 'description' => 'Bidang Fokus Renstra']);

        $labels = [
            $typeAkreditasi->id => ['Kriteria 1: Visi Misi', 'Kriteria 2: Tata Pamong', 'Kriteria 3: Mahasiswa', 'Kriteria 4: SDM', 'Kriteria 9: Luaran'],
            $typeISO->id        => ['4. Konteks Organisasi', '5. Kepemimpinan', '6. Perencanaan', '7. Dukungan', '8. Operasional'],
            $typeRenstra->id    => ['Bidang Akademik', 'Bidang Keuangan', 'Bidang Kemahasiswaan', 'Bidang Sarpras'],
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

        // 2. Org Units (Structure PCR - Simplified)
        $pcr      = OrgUnit::create(['name' => 'Politeknik Caltex Riau', 'code' => 'PCR', 'type' => 'Institusi', 'level' => 1, 'seq' => 1]);
        $direktur = OrgUnit::create(['name' => 'Direktur', 'code' => 'DIR', 'type' => 'Pimpinan', 'parent_id' => $pcr->org_unit_id, 'level' => 2, 'seq' => 1]);

        $wadir1 = OrgUnit::create(['name' => 'Wadir 1 (Akademik)', 'code' => 'WDIR1', 'type' => 'Pimpinan', 'parent_id' => $direktur->org_unit_id, 'level' => 3, 'seq' => 1]);
        $wadir2 = OrgUnit::create(['name' => 'Wadir 2 (Keu & Umum)', 'code' => 'WDIR2', 'type' => 'Pimpinan', 'parent_id' => $direktur->org_unit_id, 'level' => 3, 'seq' => 2]);
        $wadir3 = OrgUnit::create(['name' => 'Wadir 3 (Mhs & Alumni)', 'code' => 'WDIR3', 'type' => 'Pimpinan', 'parent_id' => $direktur->org_unit_id, 'level' => 3, 'seq' => 3]);

        $tik = OrgUnit::create(['name' => 'Jurusan TIK', 'code' => 'JTIK', 'type' => 'Jurusan', 'parent_id' => $wadir1->org_unit_id, 'level' => 4, 'seq' => 1]);
        $te  = OrgUnit::create(['name' => 'Jurusan Teknik Elektronika', 'code' => 'JTE', 'type' => 'Jurusan', 'parent_id' => $wadir1->org_unit_id, 'level' => 4, 'seq' => 2]);

        $prodiTi = OrgUnit::create(['name' => 'D4 Teknik Informatika', 'code' => 'TI', 'type' => 'Prodi', 'parent_id' => $tik->org_unit_id, 'level' => 5, 'seq' => 1]);
        $prodiSi = OrgUnit::create(['name' => 'D4 Sistem Informasi', 'code' => 'SI', 'type' => 'Prodi', 'parent_id' => $tik->org_unit_id, 'level' => 5, 'seq' => 2]);

        $bpm = OrgUnit::create(['name' => 'Badan Penjaminan Mutu', 'code' => 'BPM', 'type' => 'Unit', 'parent_id' => $direktur->org_unit_id, 'level' => 3, 'seq' => 4]);

        // 3. Personils
        // Create generic users first if not exist
        $users = User::limit(5)->get();

        Personil::create(['nama' => 'Dr. Dadang Syarif', 'email' => 'dadang@pcr.ac.id', 'jenis' => 'Dosen', 'org_unit_id' => $direktur->org_unit_id]);
        Personil::create(['nama' => 'Maksum Rois', 'email' => 'maksum@pcr.ac.id', 'jenis' => 'Dosen', 'org_unit_id' => $wadir1->org_unit_id]);
        Personil::create(['nama' => 'Yohana Dewi', 'email' => 'yohana@pcr.ac.id', 'jenis' => 'Staf', 'org_unit_id' => $bpm->org_unit_id]);

        // 4. Dokumen: RENSTRA
        $renstra = Dokumen::create([
            'judul'          => 'Rencana Strategis (RENSTRA) PCR 2025-2030',
            'kode'           => 'RENSTRA-2025',
            'jenis'          => 'Rencana Strategis',
            'periode'        => '2025-2030',
            'tgl_berlaku'    => '2025-01-01',
            'std_is_staging' => 0, // 0 = Published
        ]);

        // Sub Docs Renstra (Visi Misi)
        $visi = DokSub::create([
            'dok_id'    => $renstra->dokumen_id,
            'parent_id' => null,
            'judul'     => 'Visi Politeknik Caltex Riau',
            'isi'       => '<p><strong>"Menjadi Perguruan Tinggi Vokasi Unggulan yang Bereputasi Internasional"</strong></p><p>Unggulan berarti memiliki kelebihan kompetitif dalam bidang akademik dan tata kelola...</p>',
            'seq'       => 1,
        ]);

        $misi = DokSub::create([
            'dok_id'    => $renstra->dokumen_id,
            'parent_id' => null,
            'judul'     => 'Misi Politeknik Caltex Riau',
            'isi'       => '<p>1. Menyelenggarakan pendidikan vokasi yang berkualitas, berkarakter, dan relevan dengan kebutuhan industri.</p><p>2. Melaksanakan penelitian terapan yang inovatif.</p><p>3. Melaksanakan pengabdian kepada masyarakat...</p>',
            'seq'       => 2,
        ]);

        // Indikator Visi
        Indikator::create([
            'doksub_id'       => $visi->doksub_id,
            'no_indikator'    => 'V-01',
            'indikator'       => 'Tercapainya akreditasi Unggul untuk seluruh program studi pada tahun 2028',
            'target'          => '100% Prodi Unggul',
            'jenis_indikator' => 'Utama',
        ])->labels()->attach([$createdLabels[0]->label_id]); // Kriteria 1

        Indikator::create([
            'doksub_id'       => $visi->doksub_id,
            'no_indikator'    => 'V-02',
            'indikator'       => 'Peningkatan ranking Webometrics kedalam 5 besar Politeknik Swasta se-Indonesia',
            'target'          => 'Top 5',
            'jenis_indikator' => 'Tambahan',
        ])->orgUnits()->attach([$bpm->org_unit_id, $wadir1->org_unit_id]);

        // 5. Dokumen: MANUAL MUTU
        $manual = Dokumen::create([
            'judul'          => 'Manual Mutu SPMI',
            'kode'           => 'MM-SPMI-01',
            'jenis'          => 'Manual Mutu',
            'periode'        => '2025',
            'tgl_berlaku'    => '2025-01-01',
            'std_is_staging' => 1, // 1 = Draft/Staging
        ]);

        DokSub::create([
            'dok_id' => $manual->dokumen_id,
            'judul'  => 'Kebijakan Mutu',
            'isi'    => '<p>PCR berkomitmen untuk menerapkan Sistem Penjaminan Mutu Internal secara konsisten dan berkelanjutan...</p>',
            'seq'    => 1,
        ]);

        $this->command->info('Seeding Pemtu Data Selesai (Bahasa Indonesia).');
    }
}
