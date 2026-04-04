<?php

namespace App\Services\Pemutu;

use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use Illuminate\Support\Facades\DB;

class IndikatorHistoryService
{
    /**
     * Get 5-year historical data for a specific indicator.
     */
    public function getIndicatorFiveYearHistory(string|int $indikatorId): array
    {
        $indikatorId = decryptIdIfEncrypted($indikatorId);
        $history     = [];
        $currentId   = $indikatorId;
        $years       = [];

        while ($currentId) {
            $indikator = Indikator::with(['dokSubs.dokumen', 'orgUnits'])
                ->find($currentId);

            if (! $indikator) {
                break;
            }

            $tahun = $this->extractTahunForHistory($indikator);

            $orgUnits = IndikatorOrgUnit::with(['orgUnit'])
                ->where('indikator_id', $indikator->indikator_id)
                ->get();

            $history[] = [
                'indikator'    => $indikator,
                'tahun'        => $tahun,
                'org_units'    => $orgUnits,
                'origin_from'  => $indikator->origin_from,
                'no_indikator' => $indikator->no_indikator,
            ];

            $years[]   = $tahun;
            $currentId = $indikator->prev_indikator_id;
        }

        usort($history, fn ($a, $b) => $b['tahun'] <=> $a['tahun']);

        return [
            'history' => $history,
            'years'   => array_unique($years),
        ];
    }

    /**
     * Get summary data for 5-year view across all indicators.
     *
     * Joins back to pemutu_indikator for full column access (no_indikator, indikator text, etc.)
     * while using the view as the authoritative source for year/AMI/unit mapping.
     */
    public function getFiveYearSummaryData(?string $kelompokLabel = null, ?int $currentYear = null, ?int $limitYear = 5, ?int $unitId = null): array
    {
        $currentYear = $currentYear ?? (int) date('Y');
        $minYear     = $currentYear - ($limitYear - 1);

        $query = DB::table('vw_pemutu_dashboard_indikator as v')
            ->join('pemutu_indikator as i', 'v.indikator_id', '=', 'i.indikator_id')
            ->whereRaw('CAST(v.tahun AS UNSIGNED) BETWEEN ? AND ?', [$minYear, $currentYear])
            ->select('i.*', 'v.tahun', 'v.ami_hasil_akhir', 'v.org_unit_id');

        if ($unitId) {
            $query->where('v.org_unit_id', $unitId);
        }

        if ($kelompokLabel && $kelompokLabel !== 'all' && $kelompokLabel !== 'Semua') {
            $queryKelompok = $kelompokLabel === 'Akademik' ? 'Akademik' : 'Non Akademik';
            $query->where('v.kelompok_indikator', $queryKelompok);
        }

        $indicators = $query->orderBy('v.tahun', 'desc')
            ->orderBy('i.no_indikator')
            ->get();

        $grouped = $this->groupIndicatorsByRoot($indicators);

        return [
            'indicators'   => $grouped,
            'years'        => range($currentYear, $minYear),
            'current_year' => $currentYear,
            'min_year'     => $minYear,
        ];
    }

    /**
     * Get summary data for 5-year view across all units.
     */
    public function getFiveYearUnitSummaryData(?string $kelompokLabel = null, ?int $currentYear = null, ?int $limitYear = 5): array
    {
        $currentYear = $currentYear ?? (int) date('Y');
        $minYear     = $currentYear - ($limitYear - 1);

        $query = $this->fiveYearBaseQuery($currentYear, $minYear, $kelompokLabel)
            ->join('hr_struktur_organisasi as u', 'u.orgunit_id', '=', DB::raw('org_unit_id'))
            ->select(
                'u.orgunit_id',
                'u.name',
                'u.code',
                'tahun',
                DB::raw('COUNT(DISTINCT indikator_id) as total_indikator'),
                DB::raw('SUM(CASE WHEN ami_hasil_akhir = 1 THEN 1 ELSE 0 END) as total_terpenuhi'),
                DB::raw('SUM(CASE WHEN ami_hasil_akhir = 2 THEN 1 ELSE 0 END) as total_terlampaui'),
                DB::raw('SUM(CASE WHEN ami_hasil_akhir = 0 THEN 1 ELSE 0 END) as total_kts')
            );

        $results = $query->groupBy('u.orgunit_id', 'tahun', 'u.name', 'u.code')
            ->orderBy('u.name')
            ->orderBy('tahun', 'desc')
            ->get();

        // Group by Unit
        $unitData = [];
        foreach ($results as $r) {
            if (! isset($unitData[$r->orgunit_id])) {
                $unitData[$r->orgunit_id] = [
                    'orgunit_id' => $r->orgunit_id,
                    'name'       => $r->name,
                    'code'       => $r->code,
                    'timeline'   => [],
                    'years'      => [],
                ];
            }

            $maxPossible = $r->total_indikator * 2;
            $score       = $maxPossible > 0
                ? (($r->total_terpenuhi + ($r->total_terlampaui * 2)) / $maxPossible) * 100
                : 0;

            $unitData[$r->orgunit_id]['timeline'][$r->tahun] = (object) [
                'score'           => round($score, 1),
                'total_indikator' => $r->total_indikator,
                'terpenuhi'       => $r->total_terpenuhi,
                'terlampaui'      => $r->total_terlampaui,
                'kts'             => $r->total_kts,
                'ami_hasil_akhir' => $score >= 80 ? 2 : ($score >= 50 ? 1 : 0),
            ];
            $unitData[$r->orgunit_id]['years'][] = (int) $r->tahun;
        }

        return [
            'units'        => $unitData,
            'years'        => range($currentYear, $minYear),
            'current_year' => $currentYear,
            'min_year'     => $minYear,
        ];
    }

    /**
     * Get statistics for 5-year summary.
     */
    public function getFiveYearStatistics(?string $kelompokLabel = null, ?int $year = null, ?int $unitId = null): array
    {
        $currentYear = $year ?? (int) date('Y');
        $minYear     = $currentYear - 4;

        $baseQuery = $this->fiveYearBaseQuery($currentYear, $minYear, $kelompokLabel, $unitId);

        $totalIndicators = (clone $baseQuery)
            ->distinct('indikator_id')
            ->count('indikator_id');

        $byYear = (clone $baseQuery)
            ->select('tahun', DB::raw('COUNT(DISTINCT indikator_id) as count'))
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->get()
            ->pluck('count', 'tahun')
            ->toArray();

        $amiQuery = (clone $baseQuery)->where('tahun', $currentYear);

        $amiStats = [
            'total'      => (clone $amiQuery)->count(),
            'kts'        => (clone $amiQuery)->where('ami_hasil_akhir', 0)->count(),
            'terpenuhi'  => (clone $amiQuery)->where('ami_hasil_akhir', 1)->count(),
            'terlampaui' => (clone $amiQuery)->where('ami_hasil_akhir', 2)->count(),
        ];

        return [
            'total_indicators' => $totalIndicators,
            'by_year'          => $byYear,
            'ami_stats'        => $amiStats,
            'years_range'      => [$currentYear, $minYear],
        ];
    }

    /**
     * Group indicators by their root ancestor using base indicator number.
     */
    private function groupIndicatorsByRoot($indicators): array
    {
        $grouped = [];

        foreach ($indicators as $indicator) {
            $baseNumber = substr($indicator->no_indikator, -4);
            $groupKey   = 'N-' . $baseNumber;

            if (! isset($grouped[$groupKey])) {
                $grouped[$groupKey] = [
                    'root'              => $indicator,
                    'representative_id' => $indicator->indikator_id,
                    'chain'             => [],
                    'years'             => [],
                ];
            }

            $tahun = (int) $indicator->tahun;
            $grouped[$groupKey]['chain'][$tahun] = $indicator;
            $grouped[$groupKey]['years'][]       = $tahun;
        }

        foreach ($grouped as &$group) {
            rsort($group['years']);
            $group['years'] = array_unique($group['years']);
        }

        return $grouped;
    }

    /**
     * Extract tahun from indicator's linked dokumen.
     */
    private function extractTahunForHistory(Indikator $indikator): ?int
    {
        foreach ($indikator->dokSubs as $dokSub) {
            if ($dokSub->dokumen && $dokSub->dokumen->periode) {
                return (int) $dokSub->dokumen->periode;
            }
        }

        if (preg_match('/^(\d{2})/', $indikator->no_indikator, $matches)) {
            return 2000 + (int) $matches[1];
        }

        return null;
    }

    /**
     * Build base query using the reusable database view.
     *
     * Uses `vw_pemutu_dashboard_indikator` as the foundation,
     * making it consistent with DashboardService and reusable for future APIs.
     */
    private function fiveYearBaseQuery(int $currentYear, int $minYear, ?string $kelompokLabel = null, ?int $unitId = null)
    {
        $query = DB::table('vw_pemutu_dashboard_indikator')
            ->whereRaw('CAST(tahun AS UNSIGNED) BETWEEN ? AND ?', [$minYear, $currentYear]);

        if ($unitId) {
            $query->where('org_unit_id', $unitId);
        }

        if ($kelompokLabel && $kelompokLabel !== 'all' && $kelompokLabel !== 'Semua') {
            $queryKelompok = $kelompokLabel === 'Akademik' ? 'Akademik' : 'Non Akademik';
            $query->where('kelompok_indikator', $queryKelompok);
        }

        return $query;
    }
}
