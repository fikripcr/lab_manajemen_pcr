<?php
namespace Database\Seeders;

use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorPegawai;
use App\Models\Pemutu\Label;
use App\Models\Pemutu\LabelType;
use App\Models\Pemutu\OrgUnit;
use App\Models\Pemutu\PeriodeKpi;
use App\Models\Shared\Pegawai;
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
        $this->command->info('Seeding Dokumen & Indikator (Hierarki Massive)...');
        $periode = 2026;
        $faker = \Faker\Factory::create('id_ID');

        // DAFTAR TEXT AKADEMIK UNTUK POIN-POIN
        $poinTexts = [
            'Meningkatkan Mutu lulusan dan prestasi mahasiswa tingkat Nasional',
            'Penerapan Kurikulum Berbasis Industri dan Merdeka Belajar',
            'Optimalisasi kegiatan tridharma perguruan tinggi berstandar internasional',
            'Meningkatkan kompetensi Dosen dan Tenaga Kependidikan',
            'Pengembangan tata kelola akademik yang transparan',
            'Inovasi riset terapan berkelanjutan',
            'Sinergi dan kolaborasi dengan dunia industri',
            'Meningkatkan kualitas pelayanan administrasi akademik',
            'Penyediaan fasilitas pembelajaran mutakhir',
            'Pemberdayaan alumni dalam pengembangan kurikulum',
        ];

        // 1. HIERARKI KEBIJAKAN (VISI -> MISI -> RPJP -> RENSTRA -> RENOP)
        // Kita butuh sekitar 200+ Dokumen.
        // 4 Visi * 5 Poin Visi = 20 Poin Visi
        // 20 Poin Visi * 2 Misi = 40 Dokumen Misi
        // 40 Misi * 3 Poin Misi = 120 Poin Misi
        // 120 Poin Misi * 1 RPJP = 120 Dokumen RPJP
        // Setidaknya Visi(4) + Misi(40) + RPJP(120) + Renstra(120) + Renop(120) = 404 Dokumen (Sudah melampaui 200)

        for ($v = 1; $v <= 4; $v++) {
            $visi = Dokumen::create([
                'judul' => "Visi Utama Akademik PCR 2030 - Varian $v", 
                'periode' => $periode, 
                'jenis' => 'visi', 
                'level' => 1, 
                'seq' => $v, 
                'kode' => "VISI-00$v",
                'created_by' => 1
            ]);

            for ($pv = 1; $pv <= 5; $pv++) {
                $teksVisi = $poinTexts[array_rand($poinTexts)] . " (Poin Visi $pv)";
                $poinVisi = DokSub::create(['dok_id' => $visi->dok_id, 'judul' => $teksVisi, 'isi' => "<p>$teksVisi</p>", 'seq' => $pv, 'kode' => "PV-$v-$pv", 'created_by' => 1]);

                for ($m = 1; $m <= 2; $m++) {
                    $misi = Dokumen::create([
                        'parent_doksub_id' => $poinVisi->doksub_id, 
                        'parent_id' => $visi->dok_id,
                        'judul' => "Misi Pendidikan Berkelanjutan - Menuju Visi $v Bagian $m", 
                        'periode' => $periode, 
                        'jenis' => 'misi', 
                        'level' => 2, 
                        'seq' => $m, 
                        'kode' => "MISI-$v-$pv-$m",
                        'created_by' => 1
                    ]);

                    for ($pm = 1; $pm <= 3; $pm++) {
                        $teksMisi = $poinTexts[array_rand($poinTexts)] . " (Poin Misi $pm)";
                        $poinMisi = DokSub::create(['dok_id' => $misi->dok_id, 'judul' => $teksMisi, 'isi' => "<p>$teksMisi</p>", 'seq' => $pm, 'kode' => "PM-$v-$pv-$m-$pm", 'created_by' => 1]);

                        $rpjp = Dokumen::create(['parent_doksub_id' => $poinMisi->doksub_id, 'parent_id' => $misi->dok_id, 'judul' => "RPJP Tahap $pm", 'periode' => $periode, 'jenis' => 'rjp', 'level' => 3, 'seq' => 1, 'kode' => "RPJP-$v-$m-$pm", 'created_by' => 1]);
                        
                        $poinRpjp = DokSub::create(['dok_id' => $rpjp->dok_id, 'judul' => "Draft RPJP 1", 'isi' => "<p>Draft</p>", 'seq' => 1, 'created_by' => 1]);
                        
                        $renstra = Dokumen::create(['parent_doksub_id' => $poinRpjp->doksub_id, 'parent_id' => $rpjp->dok_id, 'judul' => "RENSTRA Strategis 1", 'periode' => $periode, 'jenis' => 'renstra', 'level' => 4, 'seq' => 1, 'created_by' => 1]);
                        $poinRenstra = DokSub::create(['dok_id' => $renstra->dok_id, 'judul' => "Poin Renstra 1", 'isi' => "<p>Poin Renstra</p>", 'seq' => 1, 'created_by' => 1]);
                        
                        $renop = Dokumen::create(['parent_doksub_id' => $poinRenstra->doksub_id, 'parent_id' => $renstra->dok_id, 'judul' => "RENOP Operasional 1", 'periode' => $periode, 'jenis' => 'renop', 'level' => 5, 'seq' => 1, 'created_by' => 1]);
                        
                        // Menghasilkan 1 Indikator Renop per Dokumen Renop = 4*5*2*3 = 120 Indikator Renop. 
                        // Tambah Poin Renop menjadi 2 agar jadi 240 Indikator Renop.
                        for ($pr = 1; $pr <= 2; $pr++) {
                            $poinRenop = DokSub::create(['dok_id' => $renop->dok_id, 'judul' => "Poin Renop Action $pr", 'isi' => "<p>Poin Renop</p>", 'seq' => $pr, 'created_by' => 1]);
                            $indRenop = Indikator::create([
                                'type' => 'renop', 
                                'kelompok_indikator' => 'Akademik',
                                'no_indikator' => "IND-RNP-$v-$m-$pm-$pr", 
                                'indikator' => "Tercapainya sasaran luaran Renop " . rand(70,100) . "%", 
                                'target' => rand(70,100).'%', 
                                'jenis_indikator' => 'Utama',
                                'created_by' => 1
                            ]);
                            $indRenop->dokSubs()->attach($poinRenop->doksub_id, ['is_hasilkan_indikator' => true]);
                        }
                    }
                }
            }
        }

        // 2. HIERARKI STANDAR & INDIKATOR (MAPPING UNIT & PEGAWAI)
        $this->command->info('Seeding Standar (500) dan Indikator Pegawai (800+)...');
        $units = OrgUnit::whereIn('type', ['Institusi', 'Jurusan', 'Prodi', 'Laboratorium'])->get();
        if ($units->isEmpty()) $units = OrgUnit::limit(10)->get();

        $pegawais = Pegawai::limit(50)->get(); // Ambil lebih banyak pegawai jika ada
        if($pegawais->count() < 10) {
            // Jika pegawai sedikit di database, tetap jalankan saja yang ada, nanti diputar loopnya
        }
        $periodeKpi = PeriodeKpi::where('is_active', true)->first();
        
        // Buat 10 Dokumen Standar Akademik (masing-masing punya 50 poin standar = 500 Poin Standar)
        // Tiap poin standar = 1 Indikator Standar. Total = 500 Indikator Standar.
        for ($s = 1; $s <= 10; $s++) {
            $standarNames = ['Standar Kompetensi Lulusan', 'Standar Isi Pembelajaran', 'Standar Proses Pembelajaran', 'Standar Penilaian Pembelajaran', 'Standar Dosen dan Tenaga Kependidikan', 'Standar Sarana Prasarana', 'Standar Pengelolaan', 'Standar Pembiayaan', 'Standar Penelitian', 'Standar Pengabdian'];
            $stdDoc = Dokumen::create([
                'judul' => $standarNames[$s-1] ?? "Standar SPMI Seri $s", 
                'periode' => $periode, 
                'jenis' => 'standar', 
                'level' => 1, 
                'seq' => $s, 
                'kode' => "STD-0$s",
                'created_by' => 1
            ]);

            // 50 poin standar / indikator standar per dokumen
            for ($ps = 1; $ps <= 50; $ps++) {
                $poinStd = DokSub::create([
                    'dok_id' => $stdDoc->dok_id, 
                    'judul' => "Pernyataan Standar ke-$ps", 
                    'isi' => "<p>Detail pernyataan standar $ps untuk " . ($standarNames[$s-1] ?? '') . "</p>", 
                    'seq' => $ps,
                    'is_hasilkan_indikator' => 1,
                    'created_by' => 1
                ]);

                // Buat Indikator Standar 
                $indStandar = Indikator::create([
                    'type' => 'standar', 
                    'kelompok_indikator' => 'Akademik',
                    'no_indikator' => "IND-STD-$s-$ps", 
                    'indikator' => "Memenuhi target SPMI pada poin $s-$ps minimal > " . rand(60,95) . "%", 
                    'target' => rand(60,100).'%', 
                    'jenis_indikator' => 'Utama',
                    'created_by' => 1
                ]);
                $indStandar->dokSubs()->attach($poinStd->doksub_id, ['is_hasilkan_indikator' => true]);
                
                // Tambahkan 1 atau 2 indikator IKU Pegawai dari sini.
                for ($iku = 1; $iku <= 2; $iku++) {
                    $ikuTarget = rand(70, 95);
                    $indPerforma = Indikator::create([
                        'type' => 'performa', 
                        'parent_id' => $indStandar->indikator_id, 
                        'kelompok_indikator' => 'Akademik',
                        'no_indikator' => "IKU-$s-$ps-$iku", 
                        'indikator' => "[IKU Kampus] Kinerja $s-$ps-$iku " . $poinTexts[array_rand($poinTexts)], 
                        'target' => $ikuTarget . ' Poin Kinerja', 
                        'jenis_indikator' => 'IKU',
                        'created_by' => 1
                    ]);
                    
                    if ($pegawais->isNotEmpty() && $periodeKpi) {
                        $pegawaiRandom = $pegawais->random();
                        IndikatorPegawai::create([
                            'pegawai_id' => $pegawaiRandom->pegawai_id,
                            'indikator_id' => $indPerforma->indikator_id,
                            'periode_kpi_id' => $periodeKpi->periode_kpi_id,
                            'year' => $periodeKpi->tahun,
                            'weight' => rand(1, 4),
                            'target_value' => $ikuTarget, 
                            'created_by' => 1,
                        ]);
                    }
                } // End IKU
            } // End for poin standar

            // Tambahkan 30 Manual Prosedur per Standar
            for ($mp = 1; $mp <= 30; $mp++) {
                $manualProsedur = Dokumen::create([
                    'parent_id' => $stdDoc->dok_id,
                    'judul' => "Manual Prosedur - " . ($standarNames[$s-1] ?? "Standar $s") . " Bagian $mp",
                    'periode' => $periode,
                    'jenis' => 'manual_prosedur',
                    'level' => 2,
                    'seq' => $mp,
                    'kode' => "MP-0$s-" . str_pad($mp, 2, '0', STR_PAD_LEFT),
                    'created_by' => 1
                ]);

                // 1 Poin prosedur
                DokSub::create([
                    'dok_id' => $manualProsedur->dok_id,
                    'judul' => "Poin Prosedur $mp",
                    'isi' => "<p>Detail langkah-langkah pelaksanaan prosedur ke-$mp untuk memastikan " . ($standarNames[$s-1] ?? '') . " berjalan lancar.</p>",
                    'seq' => 1,
                    'kode' => "PM-0$s-$mp",
                    'created_by' => 1
                ]);
            }

            // Tambahkan 30 Formulir per Standar
            for ($fm = 1; $fm <= 30; $fm++) {
                $formulir = Dokumen::create([
                    'parent_id' => $stdDoc->dok_id,
                    'judul' => "Formulir " . ($standarNames[$s-1] ?? "Standar $s") . " - F$fm",
                    'periode' => $periode,
                    'jenis' => 'formulir',
                    'level' => 2,
                    'seq' => $fm,
                    'kode' => "FRM-0$s-" . str_pad($fm, 2, '0', STR_PAD_LEFT),
                    'created_by' => 1
                ]);

                // 1 Poin isian formulir
                DokSub::create([
                    'dok_id' => $formulir->dok_id,
                    'judul' => "Bagian Formulir $fm",
                    'isi' => "<p>Mohon isi data / bukti yang relevan pada rekaman form bagian $fm terkait kelengkapan standar.</p>",
                    'seq' => 1,
                    'kode' => "PF-0$s-$fm",
                    'created_by' => 1
                ]);
            }
        } // End for standar
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
