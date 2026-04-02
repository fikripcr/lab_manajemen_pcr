<?php

namespace App\Services\Pemutu;

use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use Illuminate\Support\Facades\DB;

class FiveYearSummaryService
{
    /**
     * Get 5-year historical data for an indicator.
     * Follows the prev_indikator_id chain to get historical data.
     *
     * @param int $indikatorId The current indicator ID
     * @return array Array of historical data with PPEPP information
     */
    public function getIndicatorHistory(int $indikatorId): array
    {
        $history = [];
        $currentId = $indikatorId;
        $years = [];

        // Get all periods to map tahun - use correct column name 'periode'
        try {
            $periods = PeriodeSpmi::orderBy('periode', 'desc')->get();
            $periodMap = $periods->pluck('periodespmi_id', 'periode')->toArray();
        } catch (\Exception $e) {
            $periodMap = [];
        }

        // Traverse back through prev_indikator_id chain
        while ($currentId) {
            $indikator = Indikator::with(['dokSubs.dokumen', 'orgUnits'])
                ->find($currentId);

            if (!$indikator) {
                break;
            }

            // Get tahun from linked dokumen
            $tahun = $this->extractTahun($indikator);

            // Get all org units for this indicator
            $orgUnits = IndikatorOrgUnit::with(['orgUnit'])
                ->where('indikator_id', $indikator->indikator_id)
                ->get();

            $history[] = [
                'indikator' => $indikator,
                'tahun' => $tahun,
                'org_units' => $orgUnits,
                'origin_from' => $indikator->origin_from,
                'no_indikator' => $indikator->no_indikator,
            ];

            $years[] = $tahun;

            // Move to previous year
            $currentId = $indikator->prev_indikator_id;
        }

        // Sort by tahun descending (newest first)
        usort($history, function ($a, $b) {
            return $b['tahun'] <=> $a['tahun'];
        });

        return [
            'history' => $history,
            'years' => array_unique($years),
        ];
    }

    /**
     * Get summary data for 5-year view across all indicators.
     * Groups indicators by their root (oldest) ancestor.
     *
     * @param string|null $kelompok Filter by kelompok (Akademik/Non Akademik)
     * @param int|null $limitYear Limit to last N years
     * @return array
     */
    public function getSummaryData(?string $kelompok = null, ?int $limitYear = 5): array
    {
        $currentYear = (int) date('Y');
        $minYear = $currentYear - ($limitYear - 1);

        // Get all indicators with their chain
        $query = DB::table('pemutu_indikator as i')
            ->leftJoin('pemutu_indikator as parent', 'i.prev_indikator_id', '=', 'parent.indikator_id')
            ->leftJoin('pemutu_indikator_doksub as ids', function ($join) {
                $join->on('i.indikator_id', '=', 'ids.source_id')
                    ->where('ids.source_type', 'App\Models\Pemutu\Indikator');
            })
            ->leftJoin('pemutu_dok_sub as ds', 'ids.doksub_id', '=', 'ds.doksub_id')
            ->leftJoin('pemutu_dokumen as d', 'ds.dok_id', '=', 'd.dok_id')
            ->select(
                'i.*',
                'd.periode as tahun',
                'parent.indikator_id as prev_indikator_id'
            )
            ->where('i.type', 'standar')
            ->whereNotNull('d.periode')
            ->whereBetween('d.periode', [$minYear, $currentYear]);

        if ($kelompok && $kelompok !== 'all') {
            $query->where('i.kelompok_indikator', $kelompok);
        }

        $indicators = $query->orderBy('d.periode', 'desc')
            ->orderBy('i.no_indikator')
            ->get();

        // Group by root indicator (follow chain to oldest)
        $grouped = $this->groupByRoot($indicators);

        return [
            'indicators' => $grouped,
            'years' => range($currentYear, $minYear),
            'current_year' => $currentYear,
            'min_year' => $minYear,
        ];
    }

    /**
     * Get PPEPP data for a specific year and indicator.
     */
    public function getPpeppData(int $indikatorId, int $tahun): array
    {
        $indikator = Indikator::with(['dokSubs.dokumen', 'orgUnits.orgUnit'])
            ->find($indikatorId);

        if (!$indikator) {
            return [];
        }

        $ppeppData = [
            'penetapan' => [
                'indikator' => $indikator->indikator,
                'target' => $indikator->target,
                'unit_ukuran' => $indikator->unit_ukuran,
                'dokumen' => $this->getLinkedDokumen($indikator),
            ],
            'pelaksanaan' => [],
            'evaluasi' => [],
            'pengendalian' => [],
            'peningkatan' => [
                'has_next' => $indikator->nextIndikators()->exists(),
                'origin_from' => $indikator->origin_from,
            ],
        ];

        // Get pelaksanaan/monitoring data
        $pelaksanaan = DB::table('pemutu_rapat_mutu as rm')
            ->leftJoin('pemutu_rapat_mutu_indikator as rmi', 'rm.rapat_mutu_id', '=', 'rmi.rapat_mutu_id')
            ->where('rmi.indikator_id', $indikatorId)
            ->whereYear('rm.tanggal', $tahun)
            ->select('rm.*', 'rmi.catatan')
            ->get();

        $ppeppData['pelaksanaan'] = $pelaksanaan;

        // Get evaluasi data (ED + AMI)
        $evaluasi = DB::table('pemutu_indikator_orgunit as io')
            ->leftJoin('hr_struktur_organisasi as so', 'io.org_unit_id', '=', 'so.orgunit_id')
            ->where('io.indikator_id', $indikatorId)
            ->select(
                'io.*',
                'so.name as unit_name',
                'so.code as unit_code'
            )
            ->get();

        $ppeppData['evaluasi'] = $evaluasi;

        return $ppeppData;
    }

    /**
     * Extract tahun from indicator's linked dokumen.
     */
    private function extractTahun(Indikator $indikator): ?int
    {
        foreach ($indikator->dokSubs as $dokSub) {
            if ($dokSub->dokumen && $dokSub->dokumen->periode) {
                return (int) $dokSub->dokumen->periode;
            }
        }

        // Fallback: try to extract from no_indikator (e.g., 24XXXX -> 2024)
        if (preg_match('/^(\d{2})/', $indikator->no_indikator, $matches)) {
            return 2000 + (int) $matches[1];
        }

        return null;
    }

    /**
     * Get linked dokumen for an indicator.
     */
    private function getLinkedDokumen(Indikator $indikator): array
    {
        $dokumen = [];

        foreach ($indikator->dokSubs as $dokSub) {
            if ($dokSub->dokumen) {
                $dokumen[] = [
                    'jenis' => $dokSub->dokumen->jenis,
                    'judul' => $dokSub->dokumen->judul,
                    'periode' => $dokSub->dokumen->periode,
                    'poin' => $dokSub->poin,
                ];
            }
        }

        return $dokumen;
    }

    /**
     * Group indicators by their root ancestor.
     */
    private function groupByRoot($indicators): array
    {
        $grouped = [];

        foreach ($indicators as $indicator) {
            // Find root by following prev_indikator_id chain
            $rootId = $this->findRootId($indicator);

            if (!isset($grouped[$rootId])) {
                $grouped[$rootId] = [
                    'root' => $indicator,
                    'chain' => [],
                    'years' => [],
                ];
            }

            $tahun = $indicator->tahun;
            $grouped[$rootId]['chain'][$tahun] = $indicator;
            $grouped[$rootId]['years'][] = $tahun;
        }

        // Sort years descending
        foreach ($grouped as &$group) {
            rsort($group['years']);
            $group['years'] = array_unique($group['years']);
        }

        return $grouped;
    }

    /**
     * Find root indicator ID by following the chain.
     */
    private function findRootId($indicator): int
    {
        $current = $indicator;

        while ($current->prev_indikator_id) {
            $prev = DB::table('pemutu_indikator')
                ->where('indikator_id', $current->prev_indikator_id)
                ->first();

            if (!$prev) {
                break;
            }

            $current = $prev;
        }

        return $current->indikator_id;
    }

    /**
     * Get available years from database.
     */
    public function getAvailableYears(): array
    {
        return DB::table('pemutu_dokumen')
            ->where('jenis', 'standar')
            ->whereNotNull('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode')
            ->toArray();
    }

    /**
     * Get statistics for 5-year summary.
     */
    public function getStatistics(?string $kelompok = null, ?int $year = null): array
    {
        $currentYear = $year ?? (int) date('Y');
        $minYear = $currentYear - 4;

        $baseQuery = DB::table('pemutu_indikator as i')
            ->join('pemutu_indikator_doksub as ids', function ($join) {
                $join->on('i.indikator_id', '=', 'ids.source_id')
                    ->where('ids.source_type', 'App\Models\Pemutu\Indikator');
            })
            ->join('pemutu_dok_sub as ds', 'ids.doksub_id', '=', 'ds.doksub_id')
            ->join('pemutu_dokumen as d', 'ds.dok_id', '=', 'd.dok_id')
            ->where('i.type', 'standar')
            ->whereBetween('d.periode', [$minYear, $currentYear]);

        if ($kelompok && $kelompok !== 'all') {
            $baseQuery->where('i.kelompok_indikator', $kelompok);
        }

        // Total unique indicators (count root only)
        $totalIndicators = (clone $baseQuery)
            ->distinct('i.indikator_id')
            ->count('i.indikator_id');

        // Count by year
        $byYear = (clone $baseQuery)
            ->select('d.periode as tahun', DB::raw('COUNT(DISTINCT i.indikator_id) as count'))
            ->groupBy('d.periode')
            ->orderBy('d.periode', 'desc')
            ->get()
            ->pluck('count', 'tahun')
            ->toArray();

        // AMI results distribution (last year only)
        $amiQuery = DB::table('pemutu_indikator_orgunit as io')
            ->join('pemutu_indikator as i', 'io.indikator_id', '=', 'i.indikator_id')
            ->join('pemutu_indikator_doksub as ids', function ($join) {
                $join->on('i.indikator_id', '=', 'ids.source_id')
                    ->where('ids.source_type', 'App\Models\Pemutu\Indikator');
            })
            ->join('pemutu_dok_sub as ds', 'ids.doksub_id', '=', 'ds.doksub_id')
            ->join('pemutu_dokumen as d', 'ds.dok_id', '=', 'd.dok_id')
            ->where('i.type', 'standar')
            ->where('d.periode', $currentYear);

        if ($kelompok && $kelompok !== 'all') {
            $amiQuery->where('i.kelompok_indikator', $kelompok);
        }

        $amiStats = [
            'total' => (clone $amiQuery)->count(),
            'kts' => (clone $amiQuery)->where('io.ami_hasil_akhir', 0)->count(),
            'terpenuhi' => (clone $amiQuery)->where('io.ami_hasil_akhir', 1)->count(),
            'terlampaui' => (clone $amiQuery)->where('io.ami_hasil_akhir', 2)->count(),
        ];

        return [
            'total_indicators' => $totalIndicators,
            'by_year' => $byYear,
            'ami_stats' => $amiStats,
            'years_range' => [$currentYear, $minYear],
        ];
    }
}
