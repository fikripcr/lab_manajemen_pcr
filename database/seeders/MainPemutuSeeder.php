<?php
namespace Database\Seeders;

use App\Models\Hr\RiwayatDataDiri;
use App\Models\Hr\RiwayatPenugasan;
use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorPegawai;
use App\Models\Pemutu\Label;
use App\Models\Pemutu\LabelType;
use App\Models\Pemutu\OrgUnit;
use App\Models\Pemutu\PeriodeKpi;
use App\Models\Shared\Pegawai;
use App\Models\User;
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

        // 4. Dokumen
        $this->seedDokumen();

        // 5. Pegawai
        $this->seedPegawai();

        // 6. Indikator
        $this->seedIndikator();

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
        $this->command->info('Seeding Dokumen...');
        $periode = date('Y');

        // 1. Visi Misi Hierarchy (from DokumenSeeder)
        $visi = Dokumen::create(['judul' => 'Visi Politeknik Caltex Riau 2030', 'periode' => $periode, 'jenis' => 'Visi', 'level' => 1, 'seq' => 1, 'kode' => 'VISI-001']);
        DokSub::create(['dok_id' => $visi->dok_id, 'judul' => 'Visi Utama', 'isi' => '<p>Diakui sebagai Politeknik Unggul...</p>', 'seq' => 1]);

        $misi = Dokumen::create(['parent_id' => $visi->dok_id, 'judul' => 'Misi Pendidikan', 'periode' => $periode, 'jenis' => 'Misi', 'level' => 2, 'seq' => 1, 'kode' => 'MISI-001']);
        DokSub::create(['dok_id' => $misi->dok_id, 'judul' => 'Poin Misi 1', 'isi' => '<p>Menyelenggarakan kegiatan pendidikan...</p>', 'seq' => 1]);

        // Manual Mutu (from MainPemutuSeeder)
        $manual = Dokumen::create(['judul' => 'Manual Mutu SPMI', 'kode' => 'MM-SPMI-01', 'jenis' => 'manual_prosedur', 'periode' => 2025, 'std_is_staging' => 1]);
        DokSub::create(['dok_id' => $manual->dok_id, 'judul' => 'Kebijakan Mutu', 'isi' => '<p>PCR berkomitmen...</p>', 'seq' => 1]);

        // Golden Path Standar (from IndikatorSeeder/DokumenSeeder)
        $stdPendidikan = Dokumen::create(['judul' => 'Standar Pendidikan', 'periode' => $periode, 'jenis' => 'standar', 'level' => 1, 'seq' => 10, 'kode' => 'STD-DIK-001']);
        DokSub::create(['dok_id' => $stdPendidikan->dok_id, 'judul' => 'Standar Isi Pembelajaran', 'isi' => '<p>Kedalaman dan keluasan materi...</p>', 'seq' => 1]);
    }

    private function seedPegawai()
    {
        $this->command->info("Seeding Pegawai...");
        $faker = \Faker\Factory::create('id_ID');

        // MainPemutuSeeder specific pegawais
        $direktur = OrgUnit::where('type', 'Pimpinan')->where('code', 'DIR')->first();
        if (! $direktur) {
            $direktur = OrgUnit::where('type', 'Institusi')->first();
        }

        if ($direktur) {
            $this->createPegawai($direktur, 'Dr. Dadang Syarif', 'dadang@pcr.ac.id', 'Dosen');
        }

        // Run Pegawai seeding logic
        $units = OrgUnit::all();
        foreach ($units as $unit) {
            if ($unit->type == 'Institusi' || $unit->type == 'Jurusan') {
                $this->createPegawai($unit, $faker->name, $faker->unique()->safeEmail, 'Pimpinan');
            }

            if ($unit->type == 'Prodi') {
                $this->createPegawai($unit, $faker->name, $faker->unique()->safeEmail, 'Dosen');
            }
        }

        // Golden Path User
        $user1Email = 'user1@contoh-lab.ac.id';
        $user1      = User::where('email', $user1Email)->first();
        if ($user1) {
            $this->createPegawai(OrgUnit::first(), 'Dosen Sertifikasi (User 1)', $user1Email, 'Dosen');
        }
    }

    private function createPegawai($unit, $nama, $email, $jenis)
    {
        // 1. Check existing Data Diri
        $existingData = RiwayatDataDiri::where('email', $email)->latest()->first();
        $pegawaiId    = null;
        $user         = User::where('email', $email)->first();

        if ($existingData) {
            $pegawaiId = $existingData->pegawai_id;
        } else {
            // Create User first if not exists
            if (!$user) {
                $user = User::create([
                    'name'              => $nama,
                    'email'             => $email,
                    'password'          => \Illuminate\Support\Facades\Hash::make('password'),
                    'email_verified_at' => now(),
                    'created_by'        => 1,
                ]);
            }

            // Create Pegawai with user_id
            $pegawai   = Pegawai::create([
                'user_id'     => $user->id,
                'created_by'  => 1,
            ]);
            $pegawaiId = $pegawai->pegawai_id;

            // Create Data Diri
            $dataDiri = RiwayatDataDiri::create([
                'pegawai_id'        => $pegawaiId,
                'nama'              => $nama,
                'email'             => $email,
                'jenis_kelamin'     => 'L',
                'tempat_lahir'      => 'Pekanbaru',
                'tgl_lahir'         => '1990-01-01',
                'agama'             => 'Islam',
                'status_nikah'      => 'Belum Menikah',
                'orgunit_posisi_id' => $unit->orgunit_id,
                'created_by'        => 1,
            ]);

            $pegawai->update(['latest_riwayatdatadiri_id' => $dataDiri->riwayatdatadiri_id]);
        }

        // Ensure Penugasan exists
        $pegawai = Pegawai::find($pegawaiId);
        if (! $pegawai->latest_riwayatpenugasan_id) {
            $penugasan = RiwayatPenugasan::create([
                'pegawai_id'  => $pegawaiId,
                'org_unit_id' => $unit->orgunit_id,
                'jabatan'     => $jenis,
                'tgl_mulai'   => now(),
                'created_by'  => 1,
            ]);
            $pegawai->update(['latest_riwayatpenugasan_id' => $penugasan->riwayatpenugasan_id]);
        }
    }

    private function seedIndikator()
    {
        $this->command->info("Seeding Indikator...");
        $dokSubs = DokSub::limit(30)->get();
        if ($dokSubs->isEmpty()) {
            return;
        }

        // From IndikatorSeeder (Realistic data)
        $indicators = [
            ['indikator' => 'Persentase lulusan yang bekerja sesuai bidang', 'target' => '≥ 80%'],
            ['indikator' => 'Rata-rata IPK lulusan', 'target' => '≥ 3.25'],
            // Add more if needed
        ];

        $seq = 1;
        foreach ($indicators as $index => $data) {
            $indikator = Indikator::create([
                'no_indikator'    => 'IND-' . str_pad($seq, 3, '0', STR_PAD_LEFT),
                'indikator'       => $data['indikator'],
                'target'          => $data['target'],
                'jenis_indikator' => 'IKU',
            ]);
            $indikator->dokSubs()->attach($dokSubs->first()->doksub_id, ['is_hasilkan_indikator' => false]);
        }

        // From MainPemutuSeeder (V-01)
        $indikatorV1 = Indikator::create(['type' => 'renop', 'no_indikator' => 'V-01', 'indikator' => 'Tercapainya akreditasi Unggul...', 'target' => '100% Prodi Unggul', 'jenis_indikator' => 'Utama']);
        if ($dokSubs->isNotEmpty()) {
            $indikatorV1->dokSubs()->attach($dokSubs->first()->doksub_id, ['is_hasilkan_indikator' => 0]);
        }

        // Golden Path
        $stdDok = Dokumen::where('kode', 'STD-DIK-001')->first();
        if ($stdDok) {
            $indStandar = Indikator::create(['type' => 'standar', 'no_indikator' => 'IND-STD-GOLD-01', 'indikator' => 'Minimal 80% Mata Kuliah memiliki RPS', 'target' => '80%', 'jenis_indikator' => 'Utama']);
            $indStandar->dokSubs()->attach($stdDok->dokSubs->first()->doksub_id ?? 1, ['is_hasilkan_indikator' => false]);
            // Attach to PCR
            $pcr = OrgUnit::where('code', 'PCR')->first();
            if ($pcr) {
                $indStandar->orgUnits()->attach($pcr->orgunit_id, ['target' => '80%']);
            }
        }

        $this->seedMoreIndicators();
    }

    private function seedMoreIndicators()
    {
        $this->command->info("Seeding 30 Standard & 30 KPI Indicators...");

        $dokSubs = DokSub::all();
        $units   = OrgUnit::whereIn('type', ['Jurusan', 'Prodi', 'Laboratorium'])->get();
        if ($units->isEmpty()) {
            $units = OrgUnit::limit(5)->get();
        }

        $pegawais   = Pegawai::limit(10)->get();
        $periodeKpi = PeriodeKpi::where('is_active', true)->first();

        $standarIds = [];

        // 1. Seed 30 Standard Indicators
        // Attached to random DokSub, assigned to random Units
        for ($i = 1; $i <= 30; $i++) {
            $dokSub    = $dokSubs->random();
            $kelompok  = rand(0, 1) ? 'Akademik' : 'Non Akademik';
            $indikator = Indikator::create([
                'type'               => 'standar',
                'kelompok_indikator' => $kelompok,
                'no_indikator'       => 'STD-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'indikator'          => "Indikator Standar $i: " . $this->generateRandomIndikatorText('Standar'),
                'target'             => rand(70, 100) . '%',
                'jenis_indikator'    => 'Utama',
                'seq'                => $i,
            ]);

            $standarIds[] = $indikator->indikator_id;

            if ($dokSub) {
                $indikator->dokSubs()->syncWithoutDetaching([$dokSub->doksub_id => ['is_hasilkan_indikator' => false]]);
            }

            // Map to 1-3 Random Units
            $targetUnits = $units->random(min(3, $units->count()));
            foreach ($targetUnits as $unit) {
                $indikator->orgUnits()->attach($unit->orgunit_id, [
                    'target'     => $indikator->target,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 2. Seed 30 KPI Indicators (IKU)
        // Attached to potentially different DokSub (Renstra?), assigned to Personils ONLY, referencing Standar as Parent
        for ($i = 1; $i <= 30; $i++) {
            if (empty($standarIds)) {
                break;
            }

            $parentId     = $standarIds[array_rand($standarIds)];
            $dokSub       = $dokSubs->random();
            $targetNum    = rand(1, 10);
            $targetString = $targetNum . ' Artikel';
            $kelompok     = rand(0, 1) ? 'Akademik' : 'Non Akademik';

            // Changed type from 'iku' to 'performa' to match ENUM
            $indikator = Indikator::create([
                'type'               => 'performa',
                'kelompok_indikator' => $kelompok,
                'parent_id'          => $parentId,
                'no_indikator'       => 'IKU-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'indikator'          => "Indikator Kinerja $i: " . $this->generateRandomIndikatorText('KPI'),
                'target'             => $targetString,
                'jenis_indikator'    => 'IKU',
                'seq'                => $i,
            ]);

            if ($dokSub) {
                $indikator->dokSubs()->syncWithoutDetaching([$dokSub->doksub_id => ['is_hasilkan_indikator' => false]]);
            }

            // Map to 1-2 Random Personils (Dosen/Tendik)
            if ($pegawais->isNotEmpty() && $periodeKpi) {
                $targetPegawais = $pegawais->random(min(2, $pegawais->count()));
                foreach ($targetPegawais as $pegawai) {
                    IndikatorPegawai::create([
                        'pegawai_id'     => $pegawai->pegawai_id,
                        'indikator_id'   => $indikator->indikator_id,
                        'periode_kpi_id' => $periodeKpi->periode_kpi_id,
                        'year'           => $periodeKpi->tahun,
                        'weight'         => rand(1, 5),
                        'target_value'   => $targetNum, // Use numeric value
                        'created_by'     => 1,
                    ]);
                }
            }
        }
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
