<?php

namespace App\Services\Pemutu;

use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use Illuminate\Support\Collection;
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
     */
    public function getFiveYearSummaryData(?string $kelompokLabel = null, ?int $currentYear = null, ?int $limitYear = 5, ?int $unitId = null): array
    {
        $currentYear = $currentYear ?? (int) date('Y');
        $minYear     = $currentYear - ($limitYear - 1);

        $query = $this->fiveYearBaseQuery($currentYear, $minYear, $kelompokLabel, $unitId)
            ->select('i.*', 'd.periode as tahun', 'io.ami_hasil_akhir', 'io.org_unit_id');

        $indicators = $query->orderBy('d.periode', 'desc')
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

        $units = $this->fiveYearBaseQuery($currentYear, $minYear, $kelompokLabel)
            ->join('hr_struktur_organisasi as u', 'u.orgunit_id', '=', 'io.org_unit_id')
            ->select(
                'u.orgunit_id',
                'u.name',
                'u.code',
                'd.periode as tahun',
                DB::raw('COUNT(DISTINCT i.indikator_id) as total_indikator'),
                DB::raw('SUM(CASE WHEN io.ami_hasil_akhir = 1 THEN 1 ELSE 0 END) as total_terpenuhi'),
                DB::raw('SUM(CASE WHEN io.ami_hasil_akhir = 2 THEN 1 ELSE 0 END) as total_terlampaui'),
                DB::raw('SUM(CASE WHEN io.ami_hasil_akhir = 0 THEN 1 ELSE 0 END) as total_kts')
            );

        $results = $units->groupBy('u.orgunit_id', 'd.periode', 'u.name', 'u.code')
            ->orderBy('u.name')
            ->orderBy('d.periode', 'desc')
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
            ->distinct('i.indikator_id')
            ->count('i.indikator_id');

        $byYear = (clone $baseQuery)
            ->select('d.periode as tahun', DB::raw('COUNT(DISTINCT i.indikator_id) as count'))
            ->groupBy('d.periode')
            ->orderBy('d.periode', 'desc')
            ->get()
            ->pluck('count', 'tahun')
            ->toArray();

        $amiQuery = (clone $baseQuery)->where('d.periode', $currentYear);

        $amiStats = [
            'total'      => (clone $amiQuery)->count(),
            'kts'        => (clone $amiQuery)->where('io.ami_hasil_akhir', 0)->count(),
            'terpenuhi'  => (clone $amiQuery)->where('io.ami_hasil_akhir', 1)->count(),
            'terlampaui' => (clone $amiQuery)->where('io.ami_hasil_akhir', 2)->count(),
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
     * Build base DB query for 5-year indicator data.
     */
    private function fiveYearBaseQuery(int $currentYear, int $minYear, ?string $kelompokLabel = null, ?int $unitId = null)
    {
        $query = DB::table('pemutu_indikator as i')
            ->join('pemutu_indikator_orgunit as io', 'i.indikator_id', '=', 'io.indikator_id')
            ->join('pemutu_indikator_doksub as ids', function ($join) {
                $join->on('i.indikator_id', '=', 'ids.source_id')
                    ->where('ids.source_type', 'App\Models\Pemutu\Indikator');
            })
            ->join('pemutu_dok_sub as ds', 'ids.doksub_id', '=', 'ds.doksub_id')
            ->join('pemutu_dokumen as d', 'ds.dok_id', '=', 'd.dok_id')
            ->where('i.type', 'standar')
            ->whereNotNull('d.periode')
            ->whereRaw('CAST(d.periode AS UNSIGNED) BETWEEN ? AND ?', [$minYear, $currentYear]);

        if ($unitId) {
            $query->where('io.org_unit_id', $unitId);
        }

        if ($kelompokLabel && $kelompokLabel !== 'all' && $kelompokLabel !== 'Semua') {
            $queryKelompok = $kelompokLabel === 'Akademik' ? 'Akademik' : 'Non Akademik';
            $query->where('i.kelompok_indikator', $queryKelompok);
        }

        return $query;
    }
}
