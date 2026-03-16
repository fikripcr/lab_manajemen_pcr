<?php
namespace Database\Seeders;

use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Pemutu\TimMutu;
use App\Services\Pemutu\IndikatorService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainPemutuSeeder extends Seeder
{
    private IndikatorService $indikatorService;
    private array $standarIndicators = []; // Keyed by period → standar index → indicator models

    // For Peningkatan cross-period tracking
    private array $prevIndikators = [];
    private array $prevOrgUnits   = [];
    private array $poinMap        = [];

    public function run()
    {
        $this->indikatorService = app(IndikatorService::class);

        $this->command->info('MainPemutuSeeder started...');
        $this->truncateTables();

        foreach ([2024, 2025, 2026] as $year) {
            $this->command->info("Seeding data for period {$year}...");
            $this->seedPeriodeSpmi($year);
            $this->seedSpmiData($year);
        }

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

    private function seedPeriodeSpmi(int $year)
    {
        $start = now()->setYear($year)->startOfYear();
        PeriodeSpmi::create([
            'periode'            => $year,
            'jenis_periode'      => 'Akademik',
            'penetapan_awal'     => $start->copy(),
            'penetapan_akhir'    => $start->copy()->addMonths(2),
            'ed_awal'            => $start->copy()->addMonths(2),
            'ed_akhir'           => $start->copy()->addMonths(4),
            'ami_awal'           => $start->copy()->addMonths(4),
            'ami_akhir'          => $start->copy()->addMonths(6),
            'pengendalian_awal'  => $start->copy()->addMonths(6),
            'pengendalian_akhir' => $start->copy()->addMonths(8),
            'peningkatan_awal'   => $start->copy()->addMonths(8),
            'peningkatan_akhir'  => $start->copy()->addMonths(10),
        ]);
    }

    private function seedSpmiData(int $year)
    {
        // ─── A. KEBIJAKAN (Visi → Misi → RPJP → Renstra → Renop) ────
        $poinMap = $this->seedKebijakan($year);

        // ─── B. STANDAR (5 Standar × 8 default poin) ────────────
        $this->seedStandar($year);

        // ─── C. RENOP INDICATORS (only certain poin) ────────────
        $this->seedRenopIndicators($year, $poinMap);

        // ─── D. FORMULIR & MANUAL PROSEDUR ──────────────────────
        $this->seedFormulirManualProsedur($year);
    }

    // ═══════════════════════════════════════════════════════════════════
    //  A. KEBIJAKAN
    // ═══════════════════════════════════════════════════════════════════
    private function seedKebijakan(int $year): array
    {
        $items = [
            'visi'    => ['judul' => 'Visi Politeknik Caltex Riau', 'poin' => [
                'Menjadi perguruan tinggi vokasi bereputasi internasional di bidang teknologi dan bisnis',
                'Menghasilkan lulusan yang kompeten, inovatif, dan berkarakter',
                'Pusat pengembangan teknologi terapan unggulan nasional',
                'Mewujudkan tata kelola institusi yang akuntabel dan berkelanjutan',
                'Berkontribusi pada pembangunan daerah melalui pendidikan vokasi berkualitas',
            ]],
            'misi'    => ['judul' => 'Misi Politeknik Caltex Riau', 'poin' => [
                'Menyelenggarakan pendidikan vokasi yang relevan dengan kebutuhan industri global',
                'Mengembangkan kurikulum berbasis kompetensi dan sertifikasi profesi',
                'Membangun pusat riset terapan dan inovasi teknologi',
                'Menerapkan sistem tata kelola yang transparan, efisien, dan modern',
                'Menjalin kemitraan strategis dengan industri dan perguruan tinggi nasional/internasional',
            ]],
            'rjp'     => ['judul' => 'Rencana Pembangunan Jangka Panjang (RPJP) 2020-2040', 'poin' => [
                'Pencapaian akreditasi unggul seluruh program studi',
                'Pengembangan fasilitas pendidikan berstandar industri 4.0',
                'Penguatan kapasitas SDM dosen dan tenaga kependidikan',
                'Transformasi digital layanan akademik dan administrasi',
                'Ekspansi kerjasama internasional dan pengakuan global',
            ]],
            'renstra' => ['judul' => "Rencana Strategis (Renstra) $year-" . ($year + 4), 'poin' => [
                'Peningkatan mutu lulusan melalui kurikulum berbasis OBE',
                'Modernisasi laboratorium dan sarana praktikum',
                'Akselerasi penelitian terapan dan karya inovatif',
                'Penguatan sistem penjaminan mutu internal',
                'Pengembangan jejaring kemitraan industri dan alumni',
            ]],
            'renop'   => ['judul' => "Rencana Operasional (Renop) $year", 'poin' => [
                'Target lulusan tepat waktu dan bersertifikasi profesi',
                'Pelaksanaan sertifikasi kompetensi TIK bagi mahasiswa',
                'Optimalisasi laboratorium Teaching Factory',
                'Evaluasi dan peningkatan efektivitas standar SPMI',
                'Program magang industri terstruktur dan tracking alumni',
            ]],
        ];

        $kebijakanTypes = array_keys($items);
        $this->poinMap  = [];
        $lastDokId      = null;

        foreach ($kebijakanTypes as $idx => $jenis) {
            $data = $items[$jenis];
            $dok  = Dokumen::create([
                'judul'      => $data['judul'],
                'periode'    => $year,
                'jenis'      => $jenis,
                'parent_id'  => $lastDokId,
                'level'      => $idx + 1,
                'seq'        => $idx + 1,
                'created_by' => 1,
            ]);

            $lastDokId = $dok->dok_id;

            $this->poinMap[$jenis] = [];
            foreach ($data['poin'] as $p => $judul) {
                $kodePrefix = strtoupper(substr($jenis, 0, 2));
                if ($jenis === 'renstra') {
                    $kodePrefix = 'RS';
                }

                if ($jenis === 'renop') {
                    $kodePrefix = 'RO';
                }

                if ($jenis === 'rjp') {
                    $kodePrefix = 'RP';
                }

                // Renop: all points generate indicators
                $isHasilkan = false;
                if ($jenis === 'renop') {
                    $isHasilkan = true;
                }

                $sub = DokSub::create([
                    'dok_id'                => $dok->dok_id,
                    'judul'                 => $judul,
                    'kode'                  => $kodePrefix . '-' . str_pad($p + 1, 2, '0', STR_PAD_LEFT),
                    'seq'                   => $p + 1,
                    'is_hasilkan_indikator' => $isHasilkan,
                    'created_by'            => 1,
                ]);
                $this->poinMap[$jenis][$p] = $sub;

                // Link to previous level
                if ($idx > 0) {
                    $prevJenis  = $kebijakanTypes[$idx - 1];
                    $mappedPoin = $this->poinMap[$prevJenis][$p] ?? $this->poinMap[$prevJenis][0] ?? null;
                    if ($mappedPoin) {
                        DB::table('pemutu_doksub_mapping')->insert([
                            'doksub_id'        => $sub->doksub_id,
                            'mapped_doksub_id' => $mappedPoin->doksub_id,
                            'created_at'       => now(), 'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        return $this->poinMap;
    }

    // ═══════════════════════════════════════════════════════════════════
    //  B. STANDAR
    // ═══════════════════════════════════════════════════════════════════
    private function seedStandar(int $year)
    {
        $standarList = [
            'Standar Kompetensi Lulusan',
            'Standar Isi Pembelajaran',
            'Standar Proses Pembelajaran',
            'Standar Penilaian Pembelajaran',
            'Standar Dosen dan Tenaga Kependidikan',
        ];

        $standarPoints = [
            'Visi, Misi dan Tujuan',
            'Rasional Standar',
            'Definisi Istilah',
            'Pihak yang Bertanggungjawab',
            'Pernyataan Isi Standar / Indikator Capaian', // Index 4 → is_hasilkan_indikator
            'Strategi Pelaksanaan',
            'Dokumen Terkait',
            'Referensi',
        ];

        // Realistic Academic & Non-Academic indicators per standar
        $indicatorData = [
            // Standar 0: Kompetensi Lulusan (Academic)
            [
                ['name' => 'Rata-rata IPK lulusan', 'target' => '≥ 3.25', 'unit' => 'Skala 4.0', 'skala' => ['Kurang (<3.0)', 'Cukup (3.0-3.24)', 'Baik (3.25-3.49)', 'Sangat Baik (≥3.50)']],
                ['name' => 'Persentase lulusan tepat waktu', 'target' => '≥ 70%', 'unit' => 'Persen', 'skala' => ['Kurang (<50%)', 'Cukup (50-69%)', 'Baik (70-84%)', 'Sangat Baik (≥85%)']],
                ['name' => 'Persentase lulusan bersertifikasi kompetensi', 'target' => '≥ 60%', 'unit' => 'Persen', 'skala' => ['Kurang (<30%)', 'Cukup (30-59%)', 'Baik (60-79%)', 'Sangat Baik (≥80%)']],
                ['name' => 'Waktu tunggu lulusan mendapat pekerjaan pertama', 'target' => '≤ 3 bulan', 'unit' => 'Bulan', 'skala' => ['Buruk (>6 bln)', 'Cukup (4-6 bln)', 'Baik (2-3 bln)', 'Sangat Baik (≤1 bln)']],
                ['name' => 'Tingkat kepuasan pengguna lulusan', 'target' => '≥ 80%', 'unit' => 'Persen', 'skala' => ['Kurang (<60%)', 'Cukup (60-79%)', 'Baik (80-89%)', 'Sangat Baik (≥90%)']],
            ],
            // Standar 1: Isi Pembelajaran (Academic)
            [
                ['name' => 'Persentase mata kuliah dengan RPS lengkap', 'target' => '100%', 'unit' => 'Persen', 'skala' => ['Kurang (<70%)', 'Cukup (70-89%)', 'Baik (90-99%)', 'Sangat Baik (100%)']],
                ['name' => 'Persentase kurikulum yang di-review bersama industri', 'target' => '≥ 80%', 'unit' => 'Persen', 'skala' => ['Kurang (<50%)', 'Cukup (50-79%)', 'Baik (80-94%)', 'Sangat Baik (≥95%)']],
                ['name' => 'Jumlah MoU aktif dengan industri terkait kurikulum', 'target' => '≥ 10', 'unit' => 'Dokumen', 'skala' => ['Kurang (<3)', 'Cukup (3-6)', 'Baik (7-10)', 'Sangat Baik (>10)']],
            ],
            // Standar 2: Proses Pembelajaran (Academic)
            [
                ['name' => 'Persentase perkuliahan yang terlaksana sesuai jadwal', 'target' => '≥ 95%', 'unit' => 'Persen', 'skala' => ['Kurang (<80%)', 'Cukup (80-89%)', 'Baik (90-94%)', 'Sangat Baik (≥95%)']],
                ['name' => 'Rata-rata jam praktikum per semester per mahasiswa', 'target' => '≥ 200 jam', 'unit' => 'Jam', 'skala' => ['Kurang (<100)', 'Cukup (100-149)', 'Baik (150-199)', 'Sangat Baik (≥200)']],
                ['name' => 'Tingkat kepuasan mahasiswa terhadap proses pembelajaran', 'target' => '≥ 80%', 'unit' => 'Persen', 'skala' => ['Kurang (<60%)', 'Cukup (60-79%)', 'Baik (80-89%)', 'Sangat Baik (≥90%)']],
            ],
            // Standar 3: Penilaian Pembelajaran (Non-Academic focus)
            [
                ['name' => 'Persentase assessment menggunakan rubrik terstandar', 'target' => '≥ 90%', 'unit' => 'Persen', 'skala' => ['Kurang (<60%)', 'Cukup (60-79%)', 'Baik (80-89%)', 'Sangat Baik (≥90%)']],
                ['name' => 'Ketepatan waktu pengumpulan nilai akhir', 'target' => '100%', 'unit' => 'Persen', 'skala' => ['Kurang (<70%)', 'Cukup (70-89%)', 'Baik (90-99%)', 'Sangat Baik (100%)']],
                ['name' => 'Jumlah keberatan mahasiswa terhadap penilaian yang ditindaklanjuti', 'target' => '100%', 'unit' => 'Persen', 'skala' => ['Belum', 'Sebagian', 'Penuh']],
            ],
            // Standar 4: Dosen dan Tenaga Kependidikan (Non-Academic)
            [
                ['name' => 'Rasio dosen terhadap mahasiswa', 'target' => '1:25', 'unit' => 'Rasio', 'skala' => ['Kurang (>1:40)', 'Cukup (1:31-1:40)', 'Baik (1:21-1:30)', 'Sangat Baik (≤1:20)']],
                ['name' => 'Persentase dosen berpendidikan S2/S3', 'target' => '≥ 85%', 'unit' => 'Persen', 'skala' => ['Kurang (<60%)', 'Cukup (60-74%)', 'Baik (75-84%)', 'Sangat Baik (≥85%)']],
                ['name' => 'Persentase dosen bersertifikat pendidik', 'target' => '≥ 70%', 'unit' => 'Persen', 'skala' => ['Kurang (<40%)', 'Cukup (40-59%)', 'Baik (60-69%)', 'Sangat Baik (≥70%)']],
                ['name' => 'Rata-rata keikutsertaan dosen dalam pelatihan per tahun', 'target' => '≥ 2 kali', 'unit' => 'Kali/tahun', 'skala' => ['Kurang (0)', 'Cukup (1)', 'Baik (2)', 'Sangat Baik (≥3)']],
            ],
        ];

                                                     // Org units to assign indicators to
        $unitIds = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11]; // Senat, SPM, Wadir, Bagian, SDM

        $this->standarIndicators[$year] = [];

        foreach ($standarList as $sIdx => $judul) {
            $dok = Dokumen::create([
                'judul'      => $judul,
                'periode'    => $year,
                'jenis'      => 'standar',
                'level'      => 1,
                'seq'        => $sIdx + 1,
                'kode'       => 'STD-' . str_pad($sIdx + 1, 2, '0', STR_PAD_LEFT),
                'created_by' => 1,
            ]);

            $indikatorSub = null;
            foreach ($standarPoints as $pIdx => $pJudul) {
                $isIndikatorPoint = ($pIdx === 4);
                $sub              = DokSub::create([
                    'dok_id'                => $dok->dok_id,
                    'judul'                 => $pJudul,
                    'kode'                  => 'S' . ($sIdx + 1) . '.' . ($pIdx + 1),
                    'seq'                   => $pIdx + 1,
                    'is_hasilkan_indikator' => $isIndikatorPoint,
                    'created_by'            => 1,
                ]);

                if ($isIndikatorPoint) {
                    $indikatorSub = $sub;
                }
            }

            // Create indicators for this standar
            $this->standarIndicators[$year][$sIdx] = [];
            $baseIndicators                        = $indicatorData[$sIdx] ?? [];
            $indicators                            = [];

            // Generate 20 indicators based on base items
            if (count($baseIndicators) > 0) {
                for ($i = 0; $i < 20; $i++) {
                    if (isset($baseIndicators[$i])) {
                        $indicators[] = $baseIndicators[$i];
                    } else {
                        $baseItem      = $baseIndicators[$i % count($baseIndicators)];
                        $variantNumber = floor($i / count($baseIndicators)) + 1;
                        $indicators[]  = [
                            'name'   => $baseItem['name'] . ' (Aspek ' . $variantNumber . ')',
                            'target' => $baseItem['target'],
                            'unit'   => $baseItem['unit'],
                            'skala'  => $baseItem['skala'],
                        ];
                    }
                }
            }

            foreach ($indicators as $iIdx => $indData) {
                // Determine previous period links
                $prevIndId  = null;
                $originFrom = null;
                if ($year > 2024 && isset($this->prevIndikators[$year - 1]['standar'][$sIdx][$iIdx])) {
                    $prevIndId  = $this->prevIndikators[$year - 1]['standar'][$sIdx][$iIdx];
                    $originFrom = 'peningkatan_' . ($year - 1);
                }

                $noIndikator = $this->indikatorService->generateNoIndikator($year);
                $ind         = Indikator::create([
                    'type'               => 'standar',
                    'kelompok_indikator' => 'Akademik',
                    'prev_indikator_id'  => $prevIndId,
                    'origin_from'        => $originFrom,
                    'no_indikator'       => $noIndikator,
                    'indikator'          => $indData['name'],
                    'target'             => $indData['target'],
                    'unit_ukuran'        => $indData['unit'],
                    'skala'              => $indData['skala'],
                    'seq'                => $iIdx + 1,
                    'created_by'         => 1,
                ]);
                $this->prevIndikators[$year]['standar'][$sIdx][$iIdx] = $ind->indikator_id;

                $ind->dokSubs()->attach($indikatorSub->doksub_id, ['is_hasilkan_indikator' => true]);

                // Assign to all org units with target & pre-filled ED data
                $edScales = array_keys($indData['skala']);
                foreach ($unitIds as $uIdx => $unitId) {
                    $edSkalaIndex = rand(0, count($edScales) - 1); // Randomize the ED result

                    $prevOuId = null;
                    if ($year > 2024 && isset($this->prevOrgUnits[$year - 1]['standar'][$sIdx][$iIdx][$unitId])) {
                        $prevOuId = $this->prevOrgUnits[$year - 1]['standar'][$sIdx][$iIdx][$unitId];
                    }

                    $insertData = [
                        'indikator_id'         => $ind->indikator_id,
                        'org_unit_id'          => $unitId,
                        'prev_indikorgunit_id' => $prevOuId,
                        'target'               => $indData['target'],
                        'ed_capaian'           => $this->generateEdCapaian($indData, $uIdx),
                        'ed_skala'             => $edSkalaIndex,
                        'created_at'           => now(), 'updated_at' => now(),
                    ];
                    $amiData = $this->generateAmiPengendalianData($edSkalaIndex, count($edScales) - 1);
                    $ouId    = DB::table('pemutu_indikator_orgunit')->insertGetId(array_merge($insertData, $amiData));

                    $this->prevOrgUnits[$year]['standar'][$sIdx][$iIdx][$unitId] = $ouId;
                }

                $this->standarIndicators[$year][$sIdx][] = $ind;
            }
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  C. RENOP INDICATORS – parent_id → Standar Indicator
    // ═══════════════════════════════════════════════════════════════════
    private function seedRenopIndicators(int $year, array $poinMap)
    {
        $renopPoin = $poinMap['renop'] ?? [];

                                                     // Generate many more indicators for ALL 5 Renop points to ensure variety in Misi/Renstra branches
        $unitIds = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11]; // 10 units

        foreach ($renopPoin as $poinIdx => $sub) {
            // Generate 10-20 indicators per Renop point (Total 50-100 indicators for Renop)
            $count = rand(10, 20);
            for ($i = 0; $i < $count; $i++) {
                $stdIdx      = $poinIdx % 5;
                $parentIdxNo = rand(0, 19);
                $parentInd   = $this->standarIndicators[$year][$stdIdx][$parentIdxNo] ?? null;

                $prevIndId  = null;
                $originFrom = null;
                if ($year > 2024 && isset($this->prevIndikators[$year - 1]['renop'][$poinIdx][$i])) {
                    $prevIndId  = $this->prevIndikators[$year - 1]['renop'][$poinIdx][$i];
                    $originFrom = 'peningkatan_' . ($year - 1);
                }

                $indicatorNames = [
                    'Persentase pencapaian target kerja unit',
                    'Efektivitas pelaksanaan program kerja tahunan',
                    'Kesesuaian penggunaan anggaran operasional',
                    'Jumlah luaran inovasi terapan baru',
                    'Tingkat kepuasan stakeholder terhadap layanan',
                    'Rata-rata kehadiran personil dalam rapat koordinasi',
                    'Kecepatan respon terhadap keluhan pelanggan',
                    'Akurasi pelaporan data kinerja berkala',
                    'Jumlah sertifikasi kompetensi staf pendukung',
                    'Efisiensi penggunaan fasilitas sarana prasarana',
                ];

                $nameBase = $indicatorNames[$i % count($indicatorNames)];
                $name     = $nameBase . ' (Target ' . ($poinIdx + 1) . '.' . ($i + 1) . ')';

                $renstraPoin = $this->poinMap['renstra'][$i % count($this->poinMap['renstra'])] ?? null;

                $noIndikator = $this->indikatorService->generateNoIndikator($year);
                $ind         = Indikator::create([
                    'type'               => 'renop',
                    'kelompok_indikator' => ($i % 2 == 0) ? 'Akademik' : 'Non Akademik',
                    'parent_id'          => $parentInd?->indikator_id,
                    'renstra_id'         => $renstraPoin?->dok_id,
                    'renstra_poin_id'    => $renstraPoin?->doksub_id,
                    'prev_indikator_id'  => $prevIndId,
                    'origin_from'        => $originFrom,
                    'no_indikator'       => $noIndikator,
                    'indikator'          => $name,
                    'target'             => '≥ ' . rand(70, 95) . '%',
                    'unit_ukuran'        => 'Persen',
                    'skala'              => ['Tidak Tercapai', 'Kurang', 'Cukup', 'Baik', 'Sangat Baik'],
                    'seq'                => $i + 1,
                    'created_by'         => 1,
                ]);

                $this->prevIndikators[$year]['renop'][$poinIdx][$i] = $ind->indikator_id;
                $ind->dokSubs()->attach($sub->doksub_id, ['is_hasilkan_indikator' => true]);

                // Map to all 10 units
                foreach ($unitIds as $uIdx => $unitId) {
                    $edSkalaIndex = rand(1, 4); // Try to make it mostly successful but with some 'Cukup'

                    $prevOuId = null;
                    if ($year > 2024 && isset($this->prevOrgUnits[$year - 1]['renop'][$poinIdx][$i][$unitId])) {
                        $prevOuId = $this->prevOrgUnits[$year - 1]['renop'][$poinIdx][$i][$unitId];
                    }

                    $insertData = [
                        'indikator_id'         => $ind->indikator_id,
                        'org_unit_id'          => $unitId,
                        'prev_indikorgunit_id' => $prevOuId,
                        'target'               => $ind->target,
                        'ed_capaian'           => rand(70, 98) . '%',
                        'ed_skala'             => $edSkalaIndex,
                        'created_at'           => now(), 'updated_at' => now(),
                    ];
                    $amiData = $this->generateAmiPengendalianData($edSkalaIndex, 4);
                    $ouId    = DB::table('pemutu_indikator_orgunit')->insertGetId(array_merge($insertData, $amiData));

                    $this->prevOrgUnits[$year]['renop'][$poinIdx][$i][$unitId] = $ouId;
                }
            }
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  D. FORMULIR & MANUAL PROSEDUR
    // ═══════════════════════════════════════════════════════════════════
    private function seedFormulirManualProsedur(int $year)
    {
        $formulir = [
            'Formulir Audit Mutu Internal',
            'Formulir Evaluasi Diri Program Studi',
            'Formulir Rencana Tindak Perbaikan',
        ];
        foreach ($formulir as $idx => $judul) {
            Dokumen::create([
                'judul'      => $judul,
                'periode'    => $year,
                'jenis'      => 'formulir',
                'level'      => 1,
                'seq'        => $idx + 1,
                'kode'       => 'FRM-' . str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
                'created_by' => 1,
            ]);
        }

        $manuals = [
            'Manual Prosedur Pengendalian Dokumen Mutu',
            'Manual Prosedur Pelaksanaan Audit Internal',
            'Manual Prosedur Tinjauan Manajemen',
        ];
        foreach ($manuals as $idx => $judul) {
            Dokumen::create([
                'judul'      => $judul,
                'periode'    => $year,
                'jenis'      => 'manual_prosedur',
                'level'      => 1,
                'seq'        => $idx + 1,
                'kode'       => 'MP-' . str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
                'created_by' => 1,
            ]);
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  HELPERS
    // ═══════════════════════════════════════════════════════════════════
    private function generateEdCapaian(array $indData, int $unitIndex): string
    {
        // Generate varied realistic capaian based on target type
        $target = $indData['target'] ?? '';

        if (str_contains($target, '%')) {
            $base   = (int) filter_var($target, FILTER_SANITIZE_NUMBER_INT);
            $offset = ($unitIndex * 5) - 5;
            $val    = max(40, min(100, $base + $offset + rand(-10, 10)));
            return $val . '%';
        }

        if (str_contains($target, 'bulan')) {
            return rand(1, 5) . ' bulan';
        }

        if (str_contains($target, 'jam')) {
            return rand(120, 250) . ' jam';
        }

        if (str_contains($target, 'kali')) {
            return rand(1, 4) . ' kali';
        }

        if (str_contains($target, '1:')) {
            return '1:' . rand(18, 35);
        }

        return rand(60, 95) . '%';
    }

    private function generateAmiPengendalianData(int $edSkala, int $maxSkala): array
    {
        $data = [];

        if ($maxSkala <= 0) {
            $maxSkala = 1;
        }

        // Determine AMI results
        if ($edSkala >= $maxSkala) {
            $data['ami_hasil_akhir'] = 2; // Terlampaui
        } elseif ($edSkala < ceil($maxSkala / 2)) {
            $data['ami_hasil_akhir'] = 0; // KTS
        } else {
            $data['ami_hasil_akhir'] = 1; // Terpenuhi
        }

        // Default matrix values
        $important = rand(0, 1) ? 'important' : 'not_important';
        $urgent    = rand(0, 1) ? 'urgent' : 'not_urgent';

        // Generate findings and RTP if it's KTS
        if ($data['ami_hasil_akhir'] === 0) {
            $data['ami_hasil_temuan']        = 'Capaian indikator belum mencapai target yang ditetapkan secara signifikan.';
            $data['ami_hasil_temuan_sebab']  = 'Kurangnya sumber daya dan koordinasi pelaksanaan yang kurang optimal.';
            $data['ami_hasil_temuan_akibat'] = 'Target IKU institusi berpotensi tidak tercapai pada akhir tahun.';
            $data['ami_hasil_temuan_rekom']  = 'Perlu dilakukan evaluasi metode pelaksanaan dan penambahan alokasi sumber daya.';

            $data['ami_rtp_isi']             = 'Melakukan koordinasi intensif setiap bulan dan workshop peningkatan kapasitas SDM.';
            $data['ami_rtp_tgl_pelaksanaan'] = now()->addDays(rand(7, 30))->format('Y-m-d');

            // Pengendalian
            $data['pengend_status']           = 'penyesuaian';
            $data['pengend_analisis']         = 'Berdasarkan pemantauan, RTP telah berjalan namun hasil belum maksimal.';
            $data['pengend_important_matrix'] = 'important';
            $data['pengend_urgent_matrix']    = 'urgent';

            $data['ami_te_isi'] = 'Tindakan perbaikan terbukti efektif menyelesaikan masalah akar penyebab.';

        } elseif ($data['ami_hasil_akhir'] === 2) {
            // Peningkatan if Terlampaui
            $data['pengend_status']           = 'ditingkatkan';
            $data['pengend_analisis']         = 'Strategi yang diterapkan sangat efektif melampaui target.';
            $data['pengend_important_matrix'] = 'important';
            $data['pengend_urgent_matrix']    = 'not_urgent';

            $data['ami_te_isi'] = 'Strategi pencapaian sangat memuaskan, efektivitas pencapaian terbukti.';
        } else {
            // Terpenuhi
            $data['pengend_status']           = 'tetap';
            $data['pengend_analisis']         = 'Target tercapai dengan baik sesuai rencana.';
            $data['pengend_important_matrix'] = $important;
            $data['pengend_urgent_matrix']    = $urgent;
        }

        // Superior Sync (Draft Keputusan Atasan)
        $data['pengend_status_atsn']           = $data['pengend_status'];
        $data['pengend_analisis_atsn']         = $data['pengend_analisis'] ?? null;
        $data['pengend_important_matrix_atsn'] = $data['pengend_important_matrix'] ?? null;
        $data['pengend_urgent_matrix_atsn']    = $data['pengend_urgent_matrix'] ?? null;

        return $data;
    }
}
