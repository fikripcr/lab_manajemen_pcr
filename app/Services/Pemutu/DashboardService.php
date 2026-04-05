<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\RiwayatApproval;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get KPI Summary (Tercapai, Tidak Tercapai, Tingkatkan, dsb)
     * Optimized: 6 COUNT queries → 1 query with conditional aggregation
     */
    public function getKpiStandar($year)
    {
        $result = DB::table('vw_pemutu_dashboard_indikator')
            ->where('tahun', $year)
            ->selectRaw('
                SUM(CASE WHEN ami_hasil_akhir IN (1, 2) THEN 1 ELSE 0 END) as tercapai,
                SUM(CASE WHEN ami_hasil_akhir = 0 THEN 1 ELSE 0 END) as tidak_tercapai,
                SUM(CASE WHEN pengend_status = "peningkatan" THEN 1 ELSE 0 END) as tingkatkan,
                SUM(CASE WHEN pengend_status = "penyesuaian" THEN 1 ELSE 0 END) as penyesuaian,
                SUM(CASE WHEN pengend_status = "tetap" THEN 1 ELSE 0 END) as tetap,
                SUM(CASE WHEN pengend_status = "nonaktif" THEN 1 ELSE 0 END) as nonaktif
            ')
            ->first();

        return [
            'tercapai'       => (int) $result->tercapai,
            'tidak_tercapai' => (int) $result->tidak_tercapai,
            'tingkatkan'     => (int) $result->tingkatkan,
            'penyesuaian'    => (int) $result->penyesuaian,
            'tetap'          => (int) $result->tetap,
            'nonaktif'       => (int) $result->nonaktif,
        ];
    }

    /**
     * Get trend data for the last 4 years
     * Optimized: 8 queries → 1 query with GROUP BY
     */
    public function getTrendData($currentYear)
    {
        $trendYears = collect(range($currentYear - 3, $currentYear));
        
        $results = DB::table('vw_pemutu_dashboard_indikator')
            ->whereIn('tahun', $trendYears)
            ->selectRaw('
                tahun,
                COUNT(DISTINCT indikator_id) as ind_count,
                COUNT(DISTINCT dok_id) as dok_count
            ')
            ->groupBy('tahun')
            ->get()
            ->keyBy('tahun');

        $trendIndikator = [];
        $trendStandar = [];
        
        foreach ($trendYears as $y) {
            $data = $results->get($y);
            $trendIndikator[] = $data ? (int) $data->ind_count : 0;
            $trendStandar[] = $data ? (int) $data->dok_count : 0;
        }

        return [
            'years'     => $trendYears->toArray(),
            'indikator' => $trendIndikator,
            'standar'   => $trendStandar,
        ];
    }

    public function getTopAndBottomUnits($year)
    {
        $unitRanksRaw = DB::table('vw_pemutu_dashboard_indikator')
            ->where('tahun', $year)
            ->selectRaw('unit_name, AVG(ed_skala) as avg_skala')
            ->groupBy('unit_name')
            ->havingRaw('avg_skala IS NOT NULL')
            ->orderByDesc('avg_skala')
            ->get();

        return [
            'top'    => $unitRanksRaw->take(3),
            'bottom' => $unitRanksRaw->reverse()->take(3)->values(),
        ];
    }

    public function getTopAndBottomStandar($year)
    {
        $standarRanksRaw = DB::table('vw_pemutu_dashboard_indikator')
            ->where('tahun', $year)
            ->selectRaw('dokumen_name, AVG(ed_skala) as avg_skala')
            ->groupBy('dokumen_name')
            ->havingRaw('avg_skala IS NOT NULL')
            ->orderByDesc('avg_skala')
            ->get();

        return [
            'top'    => $standarRanksRaw->take(3),
            'bottom' => $standarRanksRaw->reverse()->take(3)->values(),
        ];
    }

    public function getKriteriaDonut($year)
    {
        return DB::table('vw_pemutu_dashboard_indikator')
            ->where('tahun', $year)
            ->selectRaw('kelompok_indikator as label, COUNT(*) as total')
            ->whereNotNull('kelompok_indikator')
            ->where('kelompok_indikator', '!=', '')
            ->groupBy('kelompok_indikator')
            ->get();
    }

    /**
     * Get Eisenhower Matrix counts
     * Optimized: 4 COUNT queries → 1 query with conditional aggregation
     */
    public function getEisenhowerMatrix($year)
    {
        $result = DB::table('vw_pemutu_dashboard_indikator')
            ->where('tahun', $year)
            ->selectRaw('
                SUM(CASE WHEN pengend_important_matrix = "important" AND pengend_urgent_matrix = "urgent" THEN 1 ELSE 0 END) as important_urgent,
                SUM(CASE WHEN pengend_important_matrix = "important" AND pengend_urgent_matrix = "not_urgent" THEN 1 ELSE 0 END) as important_not_urgent,
                SUM(CASE WHEN pengend_important_matrix = "not_important" AND pengend_urgent_matrix = "urgent" THEN 1 ELSE 0 END) as not_important_urgent,
                SUM(CASE WHEN pengend_important_matrix = "not_important" AND pengend_urgent_matrix = "not_urgent" THEN 1 ELSE 0 END) as not_important_not_urgent
            ')
            ->first();

        return [
            'important_urgent'         => (int) $result->important_urgent,
            'important_not_urgent'     => (int) $result->important_not_urgent,
            'not_important_urgent'     => (int) $result->not_important_urgent,
            'not_important_not_urgent' => (int) $result->not_important_not_urgent,
        ];
    }

    /**
     * Get pending approval count for the current authenticated user.
     */
    public function getPendingApprovalsCount(): int
    {
        if (auth()->check() && auth()->user()->pegawai) {
            return RiwayatApproval::where('status', 'Pending')
                ->where('pegawai_id', auth()->user()->pegawai->pegawai_id)
                ->count();
        }

        return 0;
    }

    /**
     * Get strategic goals (Visi & Misi) summary.
     * Optimized: Uses direct SQL query instead of N+1 service calls
     */
    public function getStrategicGoals(int|string $year, DokumenSpmiService $dokumenSpmiService): array
    {
        // Get Visi documents with their achievement stats in one query
        $visiDocs = Dokumen::where('jenis', 'visi')->where('periode', $year)->get();
        $misiDocs = Dokumen::where('jenis', 'misi')->where('periode', $year)->get();

        // Optimized: Get all stats in single queries using JOINs
        $visiStats = [];
        if ($visiDocs->isNotEmpty()) {
            $visiIds = $visiDocs->pluck('dok_id');
            
            $visiData = DB::table('pemutu_dok_sub as ds')
                ->join('pemutu_indikator_doksub as ids', 'ds.doksub_id', '=', 'ids.doksub_id')
                ->join('pemutu_indikator as i', 'ids.source_id', '=', 'i.indikator_id')
                ->join('pemutu_indikator_orgunit as io', 'i.indikator_id', '=', 'io.indikator_id')
                ->whereIn('ds.dok_id', $visiIds)
                ->where('ids.source_type', 'App\\Models\\Pemutu\\Indikator')
                ->selectRaw('
                    ds.dok_id,
                    COUNT(DISTINCT i.indikator_id) as total_indicators,
                    COUNT(DISTINCT io.indikorgunit_id) as total_assessments,
                    SUM(CASE WHEN io.ami_hasil_akhir IN (1, 2) THEN 1 ELSE 0 END) as achieved
                ')
                ->groupBy('ds.dok_id')
                ->get()
                ->keyBy('dok_id');

            foreach ($visiDocs as $doc) {
                $data = $visiData->get($doc->dok_id);
                $totalAssessments = $data ? (int) $data->total_assessments : 0;
                $achieved = $data ? (int) $data->achieved : 0;
                $rate = $totalAssessments > 0 ? round(($achieved / $totalAssessments) * 100, 1) : 0;

                $visiStats[] = [
                    'id'    => encryptId($doc->dok_id),
                    'judul' => $doc->judul,
                    'stats' => [
                        'rate' => $rate,
                        'total_indicators' => $data ? (int) $data->total_indicators : 0,
                        'total_assessments' => $totalAssessments,
                        'achieved' => $achieved,
                    ],
                ];
            }
        }

        // Get Misi stats
        $misiStatsArr = [];
        $totalMisiRate = 0;
        if ($misiDocs->isNotEmpty()) {
            $misiIds = $misiDocs->pluck('dok_id');
            
            $misiData = DB::table('pemutu_dok_sub as ds')
                ->join('pemutu_indikator_doksub as ids', 'ds.doksub_id', '=', 'ids.doksub_id')
                ->join('pemutu_indikator as i', 'ids.source_id', '=', 'i.indikator_id')
                ->join('pemutu_indikator_orgunit as io', 'i.indikator_id', '=', 'io.indikator_id')
                ->whereIn('ds.dok_id', $misiIds)
                ->where('ids.source_type', 'App\\Models\\Pemutu\\Indikator')
                ->selectRaw('
                    ds.dok_id,
                    COUNT(DISTINCT i.indikator_id) as total_indicators,
                    COUNT(DISTINCT io.indikorgunit_id) as total_assessments,
                    SUM(CASE WHEN io.ami_hasil_akhir IN (1, 2) THEN 1 ELSE 0 END) as achieved
                ')
                ->groupBy('ds.dok_id')
                ->get()
                ->keyBy('dok_id');

            foreach ($misiDocs as $doc) {
                $data = $misiData->get($doc->dok_id);
                $totalAssessments = $data ? (int) $data->total_assessments : 0;
                $achieved = $data ? (int) $data->achieved : 0;
                $rate = $totalAssessments > 0 ? round(($achieved / $totalAssessments) * 100, 1) : 0;

                $misiStatsArr[] = [
                    'rate' => $rate,
                    'total_indicators' => $data ? (int) $data->total_indicators : 0,
                    'total_assessments' => $totalAssessments,
                    'achieved' => $achieved,
                ];
                $totalMisiRate += $rate;
            }
        }

        $avgMisiRate = count($misiStatsArr) > 0 ? round($totalMisiRate / count($misiStatsArr), 1) : 0;

        return [
            'visiStats'    => $visiStats,
            'avgMisiRate'  => $avgMisiRate,
        ];
    }

    public function getUnitAnalysisBarChart($year)
    {
        $unitAnalysisRaw = DB::table('vw_pemutu_dashboard_indikator')
            ->where('tahun', $year)
            ->selectRaw('
                unit_name,
                AVG(ed_skala) as avg_ed,
                AVG(CASE
                    WHEN ami_hasil_akhir = 2 THEN 100
                    WHEN ami_hasil_akhir = 1 THEN 100
                    WHEN ami_hasil_akhir = 0 THEN 0
                    ELSE NULL END) as avg_ami_pct,
                SUM(CASE WHEN ami_hasil_akhir = 0 THEN 1 ELSE 0 END) as count_kts,
                SUM(CASE WHEN ami_hasil_akhir = 1 THEN 1 ELSE 0 END) as count_terpenuhi,
                SUM(CASE WHEN ami_hasil_akhir = 2 THEN 1 ELSE 0 END) as count_terlampaui
            ')
            ->groupBy('unit_name')
            ->orderBy('unit_name')
            ->get();

        return [
            'categories'     => $unitAnalysisRaw->pluck('unit_name')->toArray(),
            'ed_series'      => $unitAnalysisRaw->map(fn($item) => round((float) $item->avg_ed, 2))->toArray(),
            'ami_series'     => $unitAnalysisRaw->map(fn($item) => round((float) $item->avg_ami_pct, 1))->toArray(),
            'ami_kts'        => $unitAnalysisRaw->pluck('count_kts')->toArray(),
            'ami_terpenuhi'  => $unitAnalysisRaw->pluck('count_terpenuhi')->toArray(),
            'ami_terlampaui' => $unitAnalysisRaw->pluck('count_terlampaui')->toArray(),
        ];
    }
}
