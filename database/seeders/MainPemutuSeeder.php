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
        $this->command->info('Seeding Dokumen & Indikator (Hierarki Lengkap)...');
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
        // Buat 2 Visi
        for ($v = 1; $v <= 2; $v++) {
            $visi = Dokumen::create([
                'judul' => "Visi Utama Akademik PCR 2030 - Varian $v", 
                'periode' => $periode, 
                'jenis' => 'visi', 
                'level' => 1, 
                'seq' => $v, 
                'kode' => "VISI-00$v",
                'created_by' => 1
            ]);

            // Setiap Visi punya 5 Poin
            for ($pv = 1; $pv <= 5; $pv++) {
                $teksVisi = $poinTexts[array_rand($poinTexts)] . " (Poin Visi $pv)";
                $poinVisi = DokSub::create(['dok_id' => $visi->dok_id, 'judul' => $teksVisi, 'isi' => "<p>$teksVisi</p>", 'seq' => $pv, 'kode' => "PV-$v-$pv", 'created_by' => 1]);

                // Setiap Poin Visi punya 2 Dokumen Misi
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

                    // Setiap Misi punya 5 Poin Misi
                    for ($pm = 1; $pm <= 5; $pm++) {
                        $teksMisi = $poinTexts[array_rand($poinTexts)] . " (Poin Misi $pm)";
                        $poinMisi = DokSub::create(['dok_id' => $misi->dok_id, 'judul' => $teksMisi, 'isi' => "<p>$teksMisi</p>", 'seq' => $pm, 'kode' => "PM-$v-$pv-$m-$pm", 'created_by' => 1]);

                        // Lanjut ke RPJP, 1 Dokumen RPJP per Poin Misi
                        $rpjp = Dokumen::create(['parent_doksub_id' => $poinMisi->doksub_id, 'parent_id' => $misi->dok_id, 'judul' => "RPJP Tahap $pm", 'periode' => $periode, 'jenis' => 'rjp', 'level' => 3, 'seq' => 1, 'kode' => "RPJP-$v-$m-$pm", 'created_by' => 1]);
                        
                        // 2 Poin RPJP
                        for ($prpjp = 1; $prpjp <= 2; $prpjp++) {
                            $poinRpjp = DokSub::create(['dok_id' => $rpjp->dok_id, 'judul' => "Draft RPJP $prpjp", 'isi' => "<p>Draft</p>", 'seq' => $prpjp, 'created_by' => 1]);
                            
                            // Lanjut Renstra, 1 Dokumen
                            $renstra = Dokumen::create(['parent_doksub_id' => $poinRpjp->doksub_id, 'parent_id' => $rpjp->dok_id, 'judul' => "RENSTRA Strategis $prpjp", 'periode' => $periode, 'jenis' => 'renstra', 'level' => 4, 'seq' => 1, 'created_by' => 1]);
                            $poinRenstra = DokSub::create(['dok_id' => $renstra->dok_id, 'judul' => "Poin Renstra $prpjp", 'isi' => "<p>Poin Renstra</p>", 'seq' => 1, 'created_by' => 1]);
                            
                            // Lanjut Renop, 1 Dokumen
                            $renop = Dokumen::create(['parent_doksub_id' => $poinRenstra->doksub_id, 'parent_id' => $renstra->dok_id, 'judul' => "RENOP Operasional $prpjp", 'periode' => $periode, 'jenis' => 'renop', 'level' => 5, 'seq' => 1, 'created_by' => 1]);
                            $poinRenop = DokSub::create(['dok_id' => $renop->dok_id, 'judul' => "Poin Renop Action $prpjp", 'isi' => "<p>Poin Renop</p>", 'seq' => 1, 'created_by' => 1]);
                        }
                    }
                }
            }
        }

        // 2. HIERARKI STANDAR & INDIKATOR (MAPPING UNIT & PEGAWAI)
        $this->command->info('Seeding Standar dan Indikator Performa...');
        $units = OrgUnit::whereIn('type', ['Institusi', 'Jurusan', 'Prodi', 'Laboratorium'])->get();
        if ($units->isEmpty()) $units = OrgUnit::limit(10)->get();

        $pegawais = Pegawai::limit(20)->get();
        $periodeKpi = PeriodeKpi::where('is_active', true)->first();
        
        // Buat 5 Dokumen Standar
        for ($s = 1; $s <= 5; $s++) {
            $standarNames = ['Standar Kompetensi Lulusan', 'Standar Isi Pembelajaran', 'Standar Proses Pembelajaran', 'Standar Penilaian Pembelajaran', 'Standar Dosen dan Tenaga Kependidikan'];
            $stdDoc = Dokumen::create([
                'judul' => $standarNames[$s-1] ?? "Standar Akademik $s", 
                'periode' => $periode, 
                'jenis' => 'standar', 
                'level' => 1, 
                'seq' => $s, 
                'kode' => "STD-00$s",
                'created_by' => 1
            ]);

            // Setiap Standar punya 5 Poin Standar
            for ($ps = 1; $ps <= 5; $ps++) {
                $isHasilkanIndikator = ($ps === 1); // Poin pertama selalu hasilkan indikator
                $poinStd = DokSub::create([
                    'dok_id' => $stdDoc->dok_id, 
                    'judul' => "Pernyataan Standar ke-$ps", 
                    'isi' => "<p>Detail pernyataan standar $ps untuk {$standarNames[$s-1]}</p>", 
                    'seq' => $ps,
                    'is_hasilkan_indikator' => $isHasilkanIndikator ? 1 : 0,
                    'created_by' => 1
                ]);

                // Buat Indikator Standar JIKA poin ini disetting menghasikan indikator
                if ($isHasilkanIndikator) {
                    $indikatorText = "Tercapainya target evaluasi pembelajaran > 80% pada bagian $s";
                    $indStandar = Indikator::create([
                        'type' => 'standar', 
                        'kelompok_indikator' => 'Akademik',
                        'no_indikator' => "IND-STD-$s-$ps", 
                        'indikator' => $indikatorText, 
                        'target' => '80%', 
                        'jenis_indikator' => 'Utama',
                        'created_by' => 1
                    ]);
                    
                    // Attach ke poin standar
                    $indStandar->dokSubs()->attach($poinStd->doksub_id, ['is_hasilkan_indikator' => true]);
                    
                    // Mapping ke Unit (3 unit random)
                    if ($units->isNotEmpty()) {
                        $targetUnits = $units->random(min(3, $units->count()));
                        foreach ($targetUnits as $unit) {
                            $indStandar->orgUnits()->attach($unit->orgunit_id, [
                                'target' => '80%',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }

                    // Sekaligus buat 4 Indikator Performa turunan Standar ini yang akan dimapping ke Dosen/Pegawai
                    for ($iku = 1; $iku <= 4; $iku++) {
                        $ikuTarget = rand(70, 95);
                        $indPerforma = Indikator::create([
                            'type' => 'performa', 
                            'parent_id' => $indStandar->indikator_id, // Terhubung ke Indikator Standar ini
                            'kelompok_indikator' => 'Akademik',
                            'no_indikator' => "IKU-$s-$ps-$iku", 
                            'indikator' => "[Kinerja] Penyusunan modul dan publikasi ilmiah seri $s-$iku", 
                            'target' => $ikuTarget . ' Dokumen', 
                            'jenis_indikator' => 'IKU',
                            'created_by' => 1
                        ]);
                        
                        // Mapping ke Pegawai (minimal 2 pegawai per performa)
                        if ($pegawais->isNotEmpty() && $periodeKpi) {
                            $targetPegawais = $pegawais->random(min(2, $pegawais->count()));
                            foreach ($targetPegawais as $pegawai) {
                                IndikatorPegawai::create([
                                    'pegawai_id' => $pegawai->pegawai_id,
                                    'indikator_id' => $indPerforma->indikator_id,
                                    'periode_kpi_id' => $periodeKpi->periode_kpi_id,
                                    'year' => $periodeKpi->tahun,
                                    'weight' => rand(2, 5),
                                    'target_value' => $ikuTarget, 
                                    'created_by' => 1,
                                ]);
                            }
                        }
                    }
                } // End if hasikan indikator
            } // End for poin standar
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
