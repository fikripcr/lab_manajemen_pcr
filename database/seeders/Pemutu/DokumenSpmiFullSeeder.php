<?php

namespace Database\Seeders\Pemutu;

use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\Label;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Pemutu\IndikatorService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder Lengkap untuk Dokumen SPMI - Siklus 2024-2026
 *
 * Includes:
 * - 3 Periode (2024, 2025, 2026)
 * - Kebijakan (Visi, Misi, RJP, Renstra, Renop) dengan poin
 * - Standar (5 standar × 8 poin) dengan indikator
 * - Renop indicators dengan label
 */
class DokumenSpmiFullSeeder extends Seeder
{
    private array $standarIndicators = [];

    private array $prevIndikators = [];

    private IndikatorService $indikatorService;

    public function run(): void
    {
        $this->command->info('📊 Starting Dokumen SPMI Full Seeder...');

        // Initialize IndikatorService for helper methods
        $this->indikatorService = app(IndikatorService::class);

        // Create Label first
        $renopLabel = Label::firstOrCreate(
            ['name' => 'renop'],
            ['color' => 'purple', 'parent_id' => null]
        );

        // Seed for 3 periods
        foreach ([2024, 2025, 2026] as $year) {
            $this->command->info("📅 Seeding period {$year}...");
            $this->seedPeriode($year);
            $this->seedKebijakan($year);
            $this->seedStandar($year);
            $this->seedRenopIndicators($year, $renopLabel->label_id);
        }

        $this->command->info('✅ Dokumen SPMI Full Seeder completed!');
    }

    private function seedPeriode(int $year): void
    {
        PeriodeSpmi::firstOrCreate(['periode' => $year], [
            'jenis_periode' => 'Akademik',
            'penetapan_awal' => now()->setYear($year)->startOfYear(),
            'ed_awal' => now()->setYear($year)->startOfYear()->addMonths(2),
            'ami_awal' => now()->setYear($year)->startOfYear()->addMonths(4),
        ]);
    }

    private function seedKebijakan(int $year): void
    {
        $items = [
            'visi' => [
                'judul' => 'Visi Politeknik Caltex Riau',
                'poin' => [
                    'Menjadi perguruan tinggi vokasi bereputasi internasional di bidang teknologi dan bisnis',
                    'Menghasilkan lulusan yang kompeten, inovatif, dan berkarakter',
                ],
            ],
            'misi' => [
                'judul' => 'Misi Politeknik Caltex Riau',
                'poin' => [
                    'Menyelenggarakan pendidikan vokasi yang relevan dengan kebutuhan industri global',
                    'Mengembangkan kurikulum berbasis kompetensi dan sertifikasi profesi',
                    'Membangun pusat riset terapan dan inovasi teknologi',
                    'Menerapkan sistem tata kelola yang transparan, efisien, dan modern',
                    'Menjalin kemitraan strategis dengan industri dan perguruan tinggi nasional/internasional',
                ],
            ],
            'rjp' => [
                'judul' => 'Rencana Pembangunan Jangka Panjang (RPJP) 2020-2040',
                'poin' => [
                    'Pencapaian akreditasi unggul seluruh program studi',
                    'Pengembangan fasilitas pendidikan berstandar industri 4.0',
                    'Penguatan kapasitas SDM dosen dan tenaga kependidikan',
                    'Transformasi digital layanan akademik dan administrasi',
                ],
            ],
            'renstra' => [
                'judul' => 'Rencana Strategis (Renstra) '.($year).'-'.($year + 4),
                'poin' => [
                    'Peningkatan mutu lulusan melalui kurikulum berbasis OBE',
                    'Modernisasi laboratorium dan sarana praktikum',
                    'Akselerasi penelitian terapan dan karya inovatif',
                    'Penguatan sistem penjaminan mutu internal',
                    'Pengembangan jejaring kemitraan industri dan alumni',
                    'Digitalisasi kampus dan sistem informasi terintegrasi',
                ],
            ],
            'renop' => [
                'judul' => "Rencana Operasional (Renop) $year",
                'poin' => [], // RENOP TIDAK PUNYA POIN!
            ],
        ];

        $kebijakanTypes = array_keys($items);
        $poinMap = [];
        $lastDokId = null;

        foreach ($kebijakanTypes as $idx => $jenis) {
            $data = $items[$jenis];
            $dok = Dokumen::create([
                'judul' => $data['judul'],
                'periode' => $year,
                'jenis' => $jenis,
                'parent_id' => $lastDokId,
                'level' => $idx + 1,
                'seq' => $idx + 1,
                'created_by' => 1,
            ]);

            $lastDokId = $dok->dok_id;
            $poinMap[$jenis] = [];

            // Skip poin creation for renop
            if ($jenis === 'renop') {
                continue;
            }

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

                $sub = DokSub::create([
                    'dok_id' => $dok->dok_id,
                    'jenis' => 'poin_'.$jenis,
                    'judul' => $judul,
                    'kode' => $kodePrefix.'-'.str_pad($p + 1, 2, '0', STR_PAD_LEFT),
                    'seq' => $p + 1,
                    'is_hasilkan_indikator' => false,
                    'created_by' => 1,
                ]);

                $poinMap[$jenis][$p] = $sub;

                // Mapping to previous level
                if ($idx > 0 && $kebijakanTypes[$idx - 1] !== 'renop') {
                    $prevJenis = $kebijakanTypes[$idx - 1];
                    $mappedPoin = $poinMap[$prevJenis][$p] ?? $poinMap[$prevJenis][0] ?? null;
                    if ($mappedPoin) {
                        DB::table('pemutu_doksub_mapping')->insert([
                            'doksub_id' => $sub->doksub_id,
                            'mapped_doksub_id' => $mappedPoin->doksub_id,
                            'created_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    private function seedStandar(int $year): void
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
            'Pernyataan Isi Standar / Indikator Capaian', // Index 4 → generates indicators
            'Strategi Pelaksanaan',
            'Dokumen Terkait',
            'Referensi',
        ];

        $indicatorData = [
            // Standar 0: Kompetensi Lulusan
            [
                ['name' => 'Rata-rata IPK lulusan', 'target' => '≥ 3.25', 'unit' => 'Skala 4.0'],
                ['name' => 'Persentase lulusan tepat waktu', 'target' => '≥ 70%', 'unit' => 'Persen'],
                ['name' => 'Persentase lulusan bersertifikasi kompetensi', 'target' => '≥ 60%', 'unit' => 'Persen'],
            ],
            // Standar 1: Isi Pembelajaran
            [
                ['name' => 'Persentase mata kuliah dengan RPS lengkap', 'target' => '100%', 'unit' => 'Persen'],
                ['name' => 'Persentase kurikulum yang di-review bersama industri', 'target' => '≥ 80%', 'unit' => 'Persen'],
            ],
            // Standar 2: Proses Pembelajaran
            [
                ['name' => 'Persentase perkuliahan yang terlaksana sesuai jadwal', 'target' => '≥ 95%', 'unit' => 'Persen'],
                ['name' => 'Tingkat kepuasan mahasiswa terhadap proses pembelajaran', 'target' => '≥ 80%', 'unit' => 'Persen'],
            ],
            // Standar 3: Penilaian Pembelajaran
            [
                ['name' => 'Persentase assessment menggunakan rubrik terstandar', 'target' => '≥ 90%', 'unit' => 'Persen'],
                ['name' => 'Ketepatan waktu pengumpulan nilai akhir', 'target' => '100%', 'unit' => 'Persen'],
            ],
            // Standar 4: Dosen dan Tenaga Kependidikan
            [
                ['name' => 'Rasio dosen terhadap mahasiswa', 'target' => '1:25', 'unit' => 'Rasio'],
                ['name' => 'Persentase dosen berpendidikan S2/S3', 'target' => '≥ 85%', 'unit' => 'Persen'],
                ['name' => 'Persentase dosen bersertifikat pendidik', 'target' => '≥ 70%', 'unit' => 'Persen'],
            ],
        ];

        // Get org units (assuming they exist from MainSysSeeder)
        $unitIds = DB::table('hr_struktur_organisasi')
            ->where('level', 1) // Unit level 1 (Bagian/Departemen)
            ->limit(10)
            ->pluck('orgunit_id')
            ->toArray();

        if (empty($unitIds)) {
            $this->command->warn('   ⚠ No organizational units found. Skipping unit assignments.');
            $this->command->warn('   Run MainSysSeeder first to create org units.');
        } else {
            $this->command->info('   Using '.count($unitIds).' organizational units');
        }

        $this->standarIndicators[$year] = [];

        foreach ($standarList as $sIdx => $judul) {
            $dok = Dokumen::create([
                'judul' => $judul,
                'periode' => $year,
                'jenis' => 'standar',
                'level' => 1,
                'seq' => $sIdx + 1,
                'kode' => 'STD-'.str_pad($sIdx + 1, 2, '0', STR_PAD_LEFT),
                'created_by' => 1,
            ]);

            $indikatorSub = null;
            foreach ($standarPoints as $pIdx => $pJudul) {
                $isIndikatorPoint = ($pIdx === 4);
                $sub = DokSub::create([
                    'dok_id' => $dok->dok_id,
                    'jenis' => 'poin_standar',
                    'judul' => $pJudul,
                    'kode' => 'S'.($sIdx + 1).'.'.($pIdx + 1),
                    'seq' => $pIdx + 1,
                    'is_hasilkan_indikator' => $isIndikatorPoint,
                    'created_by' => 1,
                ]);

                if ($isIndikatorPoint) {
                    $indikatorSub = $sub;
                }
            }

            // Create indicators for this standar
            $this->standarIndicators[$year][$sIdx] = [];
            $baseIndicators = $indicatorData[$sIdx] ?? [];

            foreach ($baseIndicators as $iIdx => $indData) {
                $ind = Indikator::create([
                    'type' => 'standar',
                    'kelompok_indikator' => 'Akademik',
                    'no_indikator' => 'STD-'.$year.'-'.str_pad(($sIdx + 1) * 100 + $iIdx + 1, 3, '0', STR_PAD_LEFT),
                    'indikator' => $indData['name'],
                    'target' => $indData['target'],
                    'unit_ukuran' => $indData['unit'],
                    'jenis_data' => 'angka',
                    'seq' => $iIdx + 1,
                    'created_by' => 1,
                ]);

                // Link to indikator sub (poin 5)
                $ind->dokSubs()->attach($indikatorSub->doksub_id, ['is_hasilkan_indikator' => true]);

                // Assign to organizational units with targets (if units exist)
                if (! empty($unitIds)) {
                    foreach ($unitIds as $unitId) {
                        DB::table('pemutu_indikator_orgunit')->insert([
                            'indikator_id' => $ind->indikator_id,
                            'org_unit_id' => $unitId,
                            'target' => $indData['target'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                $this->standarIndicators[$year][$sIdx][] = $ind;
            }
        }

        $this->command->info('   ✓ Created indicators for '.count($standarList).' standar'.(empty($unitIds) ? '' : ' with unit assignments'));
    }

    private function seedRenopIndicators(int $year, int $renopLabelId): void
    {
        $renop = Dokumen::where('jenis', 'renop')->where('periode', $year)->first();
        if (! $renop) {
            return;
        }

        // RENOP TIDAK PUNYA POIN - langsung indikator dengan label 'renop'
        $renopIndicators = [
            ['name' => 'Persentase Prodi Terakreditasi Unggul', 'target' => '25', 'unit' => '%'],
            ['name' => 'IPK Lulusan Rata-rata', 'target' => '3.50', 'unit' => 'IPK'],
            ['name' => 'Persentase Dosen Bergelar Doktor', 'target' => '45', 'unit' => '%'],
            ['name' => 'Jumlah Publikasi Internasional', 'target' => '50', 'unit' => 'Paper'],
            ['name' => 'Jumlah Kerjasama Internasional Baru', 'target' => '3', 'unit' => 'MoU'],
            ['name' => 'Persentase Lulusan dengan Sertifikasi Internasional', 'target' => '30', 'unit' => '%'],
        ];

        foreach ($renopIndicators as $iIdx => $indData) {
            // Use helper to generate proper nomor indikator (YYXXXX format)
            $noIndikator = $this->indikatorService->generateNoIndikator($year);

            $ind = Indikator::create([
                'type' => 'standar', // Renop indicators are standar type with 'renop' label
                'kelompok_indikator' => 'Akademik',
                'no_indikator' => $noIndikator,
                'indikator' => $indData['name'],
                'target' => $indData['target'],
                'unit_ukuran' => $indData['unit'],
                'jenis_data' => 'angka',
                'seq' => $iIdx + 1,
                'created_by' => 1,
            ]);

            // Attach to Renop dokumen via dokSubs pivot
            $renopDokSub = DokSub::where('dok_id', $renop->dok_id)->first();
            if ($renopDokSub) {
                $ind->dokSubs()->attach($renopDokSub->doksub_id, ['is_hasilkan_indikator' => true]);
            }

            // Attach 'renop' label (lowercase)
            $ind->labels()->attach($renopLabelId);
        }

        $this->command->info('   ✓ Created '.count($renopIndicators)." Renop indicators for {$year}");
    }
}
