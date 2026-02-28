<?php
namespace Database\Seeders;

use App\Models\Pemutu\Diskusi;
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
        $faker   = \Faker\Factory::create('id_ID');

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
                'judul'      => "Visi Utama Akademik PCR 2030 - Varian $v",
                'periode'    => $periode,
                'jenis'      => 'visi',
                'level'      => 1,
                'seq'        => $v,
                'kode'       => "VISI-00$v",
                'created_by' => 1,
            ]);

            for ($pv = 1; $pv <= 5; $pv++) {
                $teksVisi = $poinTexts[array_rand($poinTexts)] . " (Poin Visi $pv)";
                $poinVisi = DokSub::create(['dok_id' => $visi->dok_id, 'judul' => $teksVisi, 'isi' => "<p>$teksVisi</p>", 'seq' => $pv, 'kode' => "PV-$v-$pv", 'created_by' => 1]);

                for ($m = 1; $m <= 2; $m++) {
                    $misi = Dokumen::create([
                        'parent_doksub_id' => $poinVisi->doksub_id,
                        'parent_id'        => $visi->dok_id,
                        'judul'            => "Misi Pendidikan Berkelanjutan - Menuju Visi $v Bagian $m",
                        'periode'          => $periode,
                        'jenis'            => 'misi',
                        'level'            => 2,
                        'seq'              => $m,
                        'kode'             => "MISI-$v-$pv-$m",
                        'created_by'       => 1,
                    ]);

                    for ($pm = 1; $pm <= 3; $pm++) {
                        $teksMisi = $poinTexts[array_rand($poinTexts)] . " (Poin Misi $pm)";
                        $poinMisi = DokSub::create(['dok_id' => $misi->dok_id, 'judul' => $teksMisi, 'isi' => "<p>$teksMisi</p>", 'seq' => $pm, 'kode' => "PM-$v-$pv-$m-$pm", 'created_by' => 1]);

                        $rpjp = Dokumen::create(['parent_doksub_id' => $poinMisi->doksub_id, 'parent_id' => $misi->dok_id, 'judul' => "RPJP Tahap $pm", 'periode' => $periode, 'jenis' => 'rjp', 'level' => 3, 'seq' => 1, 'kode' => "RPJP-$v-$m-$pm", 'created_by' => 1]);

                        $poinRpjp = DokSub::create(['dok_id' => $rpjp->dok_id, 'judul' => "Draft RPJP 1", 'isi' => "<p>Draft</p>", 'seq' => 1, 'created_by' => 1]);

                        $renstra     = Dokumen::create(['parent_doksub_id' => $poinRpjp->doksub_id, 'parent_id' => $rpjp->dok_id, 'judul' => "RENSTRA Strategis 1", 'periode' => $periode, 'jenis' => 'renstra', 'level' => 4, 'seq' => 1, 'created_by' => 1]);
                        $poinRenstra = DokSub::create(['dok_id' => $renstra->dok_id, 'judul' => "Poin Renstra 1", 'isi' => "<p>Poin Renstra</p>", 'seq' => 1, 'created_by' => 1]);

                        $renop = Dokumen::create(['parent_doksub_id' => $poinRenstra->doksub_id, 'parent_id' => $renstra->dok_id, 'judul' => "RENOP Operasional 1", 'periode' => $periode, 'jenis' => 'renop', 'level' => 5, 'seq' => 1, 'created_by' => 1]);

                        // Menghasilkan 1 Indikator Renop per Dokumen Renop = 4*5*2*3 = 120 Indikator Renop.
                        // Tambah Poin Renop menjadi 2 agar jadi 240 Indikator Renop.
                        for ($pr = 1; $pr <= 2; $pr++) {
                            $poinRenop = DokSub::create(['dok_id' => $renop->dok_id, 'judul' => "Poin Renop Action $pr", 'isi' => "<p>Poin Renop</p>", 'seq' => $pr, 'created_by' => 1]);
                            $indRenop  = Indikator::create([
                                'type'               => 'renop',
                                'kelompok_indikator' => 'Akademik',
                                'no_indikator'       => "IND-RNP-$v-$m-$pm-$pr",
                                'indikator'          => "Tercapainya sasaran luaran Renop " . rand(70, 100) . "%",
                                'target'             => rand(70, 100) . '%',
                                'jenis_indikator'    => 'Utama',
                                'created_by'         => 1,
                            ]);
                            $indRenop->dokSubs()->attach($poinRenop->doksub_id, ['is_hasilkan_indikator' => true]);
                        }
                    }
                }
            }
        }

        // 2. HIERARKI STANDAR & INDIKATOR (MAPPING UNIT & PEGAWAI)
        $this->command->info('Seeding Standar (500) dan Indikator Pegawai (800+)...');
        // Get exactly 7 Prodi units
        $units = OrgUnit::where('type', 'Prodi')->limit(7)->get();
        if ($units->count() < 7) {
            $genericUnits = OrgUnit::whereNotIn('type', ['Institusi', 'Pimpinan', 'Jurusan'])->limit(7 - $units->count())->get();
            $units        = $units->concat($genericUnits);
        }

        $prodiStats = [];
        foreach ($units as $u) {
            $prodiStats[$u->orgunit_id] = 0;
        }

        $pegawais   = Pegawai::limit(50)->get();
        $periodeKpi = PeriodeKpi::where('is_active', true)->first();
        $labels     = \App\Models\Pemutu\Label::all();

        $adminUserId = \DB::table('users')->value('id');
        if (! $adminUserId) {
            $adminUserId = \DB::table('users')->insertGetId([
                'name'     => 'Admin Seeder',
                'email'    => 'admin_seed@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $skalaDesc = [
            0 => "Pencapaian sangat kurang dan jauh di bawah standar yang ditetapkan. Evaluasi menyeluruh dan perbaikan sistemik segera diperlukan untuk mengidentifikasi hambatan utama dan menyusun strategi pemulihan yang efektif agar kinerja dapat meningkat signifikan.",
            1 => "Pencapaian masih di bawah standar minimal yang diharapkan. Terdapat beberapa kekurangan yang perlu mendapat perhatian khusus dan tindakan korektif secepatnya agar proses operasional dapat kembali berjalan sesuai dengan pedoman dan ketentuan yang berlaku.",
            2 => "Pencapaian sudah memenuhi standar minimal yang ditetapkan. Kinerja berjalan cukup baik namun masih terdapat ruang untuk perbaikan dan optimalisasi lebih lanjut guna memastikan kualitas dan efisiensi yang lebih tinggi di masa mendatang.",
            3 => "Pencapaian melampaui harapan dan menunjukkan hasil yang sangat memuaskan. Strategi yang dijalankan terbukti efektif dan selaras dengan tujuan institusi. Praktik baik ini perlu dipertahankan dan dijadikan contoh bagi unit kerja atau indikator lainnya.",
            4 => "Pencapaian luar biasa dan sangat unggul, menunjukkan inovasi serta efisiensi maksimal dalam pelaksanaan tugas. Kinerja ini tidak sekadar memenuhi target, melainkan menetapkan standar baru yang sangat berharga bagi peningkatan mutu dan reputasi institusi.",
        ];

        $generateLongHtmlText = function ($shortText) use ($faker) {
            return "<p><strong>" . $shortText . "</strong></p><p>" . implode("</p><p>", $faker->paragraphs(3)) . "</p><ul><li>" . implode("</li><li>", $faker->words(6)) . "</li></ul><p>" . implode("</p><p>", $faker->paragraphs(2)) . "</p>";
        };

        $dummyTextShort          = "Implementasi yang dilakukan sudah sesuai dengan SOP institusi.";
        $amiTemuanShort          = "Pembaruan data realisasi belum tepat waktu di triwulan terakhir.";
        $amiSebabShort           = "Kurangnya pemahaman personel baru terkait prosedur entri data.";
        $amiAkibatShort          = "Keterlambatan pengambilan keputusan strategis oleh pimpinan.";
        $amiRekomShort           = "Diadakan pelatihan berkelanjutan untuk seluruh staf administrasi terkait.";
        $pengendAnalisisShort    = "Langkah korektif dipahami, namun tunda implementasi SOP baru.";
        $pengendPenyesuaianShort = "Jadwal operasional disesuaikan agar beban SDM lebih merata.";

        // Buat 10 Dokumen Standar Akademik
        for ($s = 1; $s <= 10; $s++) {
            $standarNames = ['Standar Kompetensi Lulusan', 'Standar Isi Pembelajaran', 'Standar Proses Pembelajaran', 'Standar Penilaian Pembelajaran', 'Standar Dosen dan Tenaga Kependidikan', 'Standar Sarana Prasarana', 'Standar Pengelolaan', 'Standar Pembiayaan', 'Standar Penelitian', 'Standar Pengabdian'];
            $stdDoc       = Dokumen::create([
                'judul'      => $standarNames[$s - 1] ?? "Standar SPMI Seri $s",
                'periode'    => $periode,
                'jenis'      => 'standar',
                'level'      => 1,
                'seq'        => $s,
                'kode'       => "STD-0$s",
                'created_by' => 1,
            ]);

            // 150 poin standar / indikator standar per dokumen (Total 1500)
            // We want at least 70 per Prodi. With 10 Prodis = 700 minimum total.
            // 10 docs * 150 indicators = 1500 indicators. (Roughly 150 per Prodi assigned)
            for ($ps = 1; $ps <= 150; $ps++) {
                $poinStd = DokSub::create([
                    'dok_id'                => $stdDoc->dok_id,
                    'judul'                 => "Pernyataan Standar ke-$ps",
                    'isi'                   => "<p>Detail pernyataan standar $ps untuk " . ($standarNames[$s - 1] ?? '') . "</p>",
                    'seq'                   => $ps,
                    'is_hasilkan_indikator' => 1,
                    'created_by'            => 1,
                ]);

                // Buat Indikator Standar
                $indStandar = Indikator::create([
                    'type'               => 'standar',
                    'kelompok_indikator' => 'Akademik',
                    'no_indikator'       => "IND-STD-$s-$ps",
                    'indikator'          => "Memenuhi target SPMI pada poin $s-$ps minimal > " . rand(60, 95) . "%",
                    'target'             => rand(60, 100) . '%',
                    'jenis_indikator'    => 'Utama',
                    'keterangan'         => "<p>Indikator ini diukur secara berkala untuk mengevaluasi sejauh mana unit menerapkan <strong>" . ($standarNames[$s - 1] ?? "Standar $s") . "</strong> sesuai pedoman akademik yang berlaku.</p>",
                    'skala'              => $skalaDesc,
                    'created_by'         => 1,
                ]);
                $indStandar->dokSubs()->attach($poinStd->doksub_id, ['is_hasilkan_indikator' => true]);

                // Tempelkan Label Random untuk Indikator Standar
                if ($labels->isNotEmpty()) {
                    $indStandar->labels()->attach($labels->random(rand(1, 3))->pluck('label_id')->toArray());
                }

                // Plot Indikator Standar ke OrgUnit Tertentu (random dari 7 Prodi, max 70 per prodi)
                $eligibleUnits = [];
                foreach ($prodiStats as $uid => $count) {
                    if ($count < 70) {
                        $eligibleUnits[] = $uid;
                    }
                }

                if (count($eligibleUnits) > 0) {
                    $unitRandomId = $eligibleUnits[array_rand($eligibleUnits)];
                    $prodiStats[$unitRandomId]++;

                                                                               // Random ED/AMI/Pengendalian logic
                    $isEdFilled         = rand(1, 100) > 10;                   // 90% chance to have ED
                    $isAmiAssessed      = $isEdFilled && rand(1, 100) > 30;    // 70% chance of AMI if ED exists
                    $isPengendalianDone = $isAmiAssessed && rand(1, 100) > 40; // 60% chance of pengendalian if AMI exists

                    $hasilAkhirAmi        = $isAmiAssessed ? rand(0, 2) : null;
                    $pengendStatusOptions = ['Selesai', 'Proses', 'Belum', 'Penyesuaian'];

                    $indOrg = \App\Models\Pemutu\IndikatorOrgUnit::create([
                        'indikator_id'            => $indStandar->indikator_id,
                        'org_unit_id'             => $unitRandomId,
                        'target'                  => $indStandar->target,

                        // ED
                        'ed_capaian'              => $isEdFilled ? rand(50, 100) . '%' : null,
                        'ed_skala'                => $isEdFilled ? rand(1, 4) : null,
                        'ed_analisis'             => $isEdFilled ? $generateLongHtmlText("Analisis Evaluasi Diri: " . $dummyTextShort) : null,

                        // AMI
                        'ami_hasil_akhir'         => $hasilAkhirAmi,
                        'ami_hasil_temuan'        => $isAmiAssessed && $hasilAkhirAmi === 0 ? $generateLongHtmlText("Temuan AMI: " . $amiTemuanShort) : null,
                        'ami_hasil_temuan_sebab'  => $isAmiAssessed && $hasilAkhirAmi === 0 ? $generateLongHtmlText("Sebab Temuan: " . $amiSebabShort) : null,
                        'ami_hasil_temuan_akibat' => $isAmiAssessed && $hasilAkhirAmi === 0 ? $generateLongHtmlText("Akibat Temuan: " . $amiAkibatShort) : null,
                        'ami_hasil_temuan_rekom'  => $isAmiAssessed ? $generateLongHtmlText("Rekomendasi Auditor: " . $amiRekomShort) : null,

                        // Pengendalian
                        'pengend_status'          => $isPengendalianDone ? $pengendStatusOptions[array_rand($pengendStatusOptions)] : null,
                        'pengend_analisis'        => $isPengendalianDone ? $generateLongHtmlText("Analisis Pengendalian: " . $pengendAnalisisShort) : null,
                        'pengend_penyesuaian'     => $isPengendalianDone ? $generateLongHtmlText("Penyesuaian: " . $pengendPenyesuaianShort) : null,

                        'created_at'              => now(),
                        'updated_at'              => now(),
                    ]);

                    // Add Diskusinya
                    if ($isEdFilled) {
                        Diskusi::create([
                            'pengirim_user_id' => $adminUserId,
                            'jenis_pengirim'   => 'PIC',
                            'jenis_diskusi'    => 'ed',
                            'model_type'       => \App\Models\Pemutu\IndikatorOrgUnit::class,
                            'model_id'         => $indOrg->indikorgunit_id,
                            'isi'              => 'Kami sudah melengkapi evaluasi diri sesuai dengan standar yang berlaku. Mohon arahannya lebih lanjut jika ada kekurangan. <br>' . implode(" ", $faker->sentences(3)),
                        ]);
                    }
                    if ($isAmiAssessed) {
                        Diskusi::create([
                            'pengirim_user_id' => $adminUserId,
                            'jenis_pengirim'   => 'Auditor',
                            'jenis_diskusi'    => 'ami',
                            'model_type'       => \App\Models\Pemutu\IndikatorOrgUnit::class,
                            'model_id'         => $indOrg->indikorgunit_id,
                            'isi'              => 'Terdapat beberapa temuan yang harus diperbaiki segera. Harap segera tindak lanjuti temuan yang kami sampaikan. <br>' . implode(" ", $faker->sentences(2)),
                        ]);
                    }
                    if ($isPengendalianDone) {
                        Diskusi::create([
                            'pengirim_user_id' => $adminUserId,
                            'jenis_pengirim'   => 'PIC',
                            'jenis_diskusi'    => 'pengendalian',
                            'model_type'       => \App\Models\Pemutu\IndikatorOrgUnit::class,
                            'model_id'         => $indOrg->indikorgunit_id,
                            'isi'              => 'Kami sudah melakukan penyesuaian sesuai rekomendasi auditor secara keseluruhan. Berikut adalah bukti perbaikan yang sudah kami lampirkan. <br>' . implode(" ", $faker->sentences(3)),
                        ]);
                    }
                }

                // Tambahkan 1 indikator IKU Pegawai dari sini.
                for ($iku = 1; $iku <= 1; $iku++) {
                    $ikuTarget   = rand(70, 95);
                    $indPerforma = Indikator::create([
                        'type'               => 'performa',
                        'parent_id'          => $indStandar->indikator_id,
                        'kelompok_indikator' => 'Akademik',
                        'no_indikator'       => "IKU-$s-$ps-$iku",
                        'indikator'          => "[IKU Kampus] Kinerja $s-$ps-$iku " . $poinTexts[array_rand($poinTexts)],
                        'target'             => $ikuTarget . ' Poin Kinerja',
                        'jenis_indikator'    => 'IKU',
                        'keterangan'         => "<p>Indikator Performa IKU ini menjadi sasaran objektif penugasan pegawai semester ganjil/genap berjalan. Berkorelasi langsung pada <strong>" . ($standarNames[$s - 1] ?? "Standar $s") . "</strong> institusi.</p>",
                        'skala'              => $skalaDesc,
                        'created_by'         => 1,
                    ]);

                    // Tempelkan Label Random ke Indikator Performa
                    if ($labels->isNotEmpty()) {
                        $indPerforma->labels()->attach($labels->random(rand(1, 2))->pluck('label_id')->toArray());
                    }

                    if ($pegawais->isNotEmpty() && $periodeKpi) {
                        $pegawaiRandom = $pegawais->random();
                        $statusOptions = ['draft', 'submitted', 'approved', 'rejected'];
                        IndikatorPegawai::create([
                            'pegawai_id'     => $pegawaiRandom->pegawai_id,
                            'indikator_id'   => $indPerforma->indikator_id,
                            'periode_kpi_id' => $periodeKpi->periode_kpi_id,
                            'year'           => $periodeKpi->tahun,
                            'weight'         => rand(1, 4),
                            'target_value'   => $ikuTarget,
                            'score'          => rand(60, 100),
                            'status'         => $statusOptions[array_rand($statusOptions)],
                            'created_by'     => 1,
                        ]);
                    }
                } // End IKU
            } // End for poin standar

            // Tambahkan 30 Manual Prosedur per Standar
            for ($mp = 1; $mp <= 30; $mp++) {
                $manualProsedur = Dokumen::create([
                    'parent_id'  => $stdDoc->dok_id,
                    'judul'      => "Manual Prosedur - " . ($standarNames[$s - 1] ?? "Standar $s") . " Bagian $mp",
                    'periode'    => $periode,
                    'jenis'      => 'manual_prosedur',
                    'level'      => 2,
                    'seq'        => $mp,
                    'kode'       => "MP-0$s-" . str_pad($mp, 2, '0', STR_PAD_LEFT),
                    'created_by' => 1,
                ]);

                // 1 Poin prosedur
                DokSub::create([
                    'dok_id'     => $manualProsedur->dok_id,
                    'judul'      => "Poin Prosedur $mp",
                    'isi'        => "<p>Detail langkah-langkah pelaksanaan prosedur ke-$mp untuk memastikan " . ($standarNames[$s - 1] ?? '') . " berjalan lancar.</p>",
                    'seq'        => 1,
                    'kode'       => "PM-0$s-$mp",
                    'created_by' => 1,
                ]);
            }

            // Tambahkan 30 Formulir per Standar
            for ($fm = 1; $fm <= 30; $fm++) {
                $formulir = Dokumen::create([
                    'parent_id'  => $stdDoc->dok_id,
                    'judul'      => "Formulir " . ($standarNames[$s - 1] ?? "Standar $s") . " - F$fm",
                    'periode'    => $periode,
                    'jenis'      => 'formulir',
                    'level'      => 2,
                    'seq'        => $fm,
                    'kode'       => "FRM-0$s-" . str_pad($fm, 2, '0', STR_PAD_LEFT),
                    'created_by' => 1,
                ]);

                // 1 Poin isian formulir
                DokSub::create([
                    'dok_id'     => $formulir->dok_id,
                    'judul'      => "Bagian Formulir $fm",
                    'isi'        => "<p>Mohon isi data / bukti yang relevan pada rekaman form bagian $fm terkait kelengkapan standar.</p>",
                    'seq'        => 1,
                    'kode'       => "PF-0$s-$fm",
                    'created_by' => 1,
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
