<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\RiwayatApproval;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get KPI Summary (Tercapai, Tidak Tercapai, Tingkatkan, dsb)
     */
    public function getKpiStandar($year)
    {
        $base = DB::table('vw_pemutu_dashboard_indikator')->where('tahun', 'like', '%' . $year . '%');
        
        return [
            'tercapai'       => (clone $base)->whereIn('ami_hasil_akhir', [1, 2])->count(),
            'tidak_tercapai' => (clone $base)->where('ami_hasil_akhir', 0)->count(),
            'tingkatkan'     => (clone $base)->where('pengend_status', 'peningkatan')->count(),
            'penyesuaian'    => (clone $base)->where('pengend_status', 'penyesuaian')->count(),
            'tetap'          => (clone $base)->where('pengend_status', 'tetap')->count(),
            'nonaktif'       => (clone $base)->where('pengend_status', 'nonaktif')->count(),
        ];
    }

    public function getTrendData($currentYear)
    {
        $trendYears     = collect(range($currentYear - 3, $currentYear));
        $trendIndikator = [];
        $trendStandar   = [];

        foreach ($trendYears as $y) {
            $base = DB::table('vw_pemutu_dashboard_indikator')->where('tahun', 'like', '%' . $y . '%');
            $trendIndikator[] = (clone $base)->distinct('indikator_id')->count('indikator_id');
            $trendStandar[]   = (clone $base)->distinct('dok_id')->count('dok_id');
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
            ->where('tahun', 'like', '%' . $year . '%')
            ->selectRaw('unit_name, AVG(ed_skala) as avg_skala')
            ->groupBy('unit_name')
            ->havingRaw('avg_skala IS NOT NULL')
            ->orderByDesc('avg_skala')
            ->get();

        return [
            'top'    => $unitRanksRaw->take(3),
            'bottom' => $unitRanksRaw->sortBy('avg_skala')->take(3)->values(),
        ];
    }

    public function getTopAndBottomStandar($year)
    {
        $standarRanksRaw = DB::table('vw_pemutu_dashboard_indikator')
            ->where('tahun', 'like', '%' . $year . '%')
            ->selectRaw('dokumen_name, AVG(ed_skala) as avg_skala')
            ->groupBy('dokumen_name')
            ->havingRaw('avg_skala IS NOT NULL')
            ->orderByDesc('avg_skala')
            ->get();

        return [
            'top'    => $standarRanksRaw->take(3),
            'bottom' => $standarRanksRaw->sortBy('avg_skala')->take(3)->values(),
        ];
    }

    public function getKriteriaDonut($year)
    {
        return DB::table('vw_pemutu_dashboard_indikator')
            ->where('tahun', 'like', '%' . $year . '%')
            ->selectRaw('kelompok_indikator as label, COUNT(*) as total')
            ->whereNotNull('kelompok_indikator')
            ->where('kelompok_indikator', '!=', '')
            ->groupBy('kelompok_indikator')
            ->get();
    }

    public function getEisenhowerMatrix($year)
    {
        $base = DB::table('vw_pemutu_dashboard_indikator')->where('tahun', 'like', '%' . $year . '%');
        
        return [
            'important_urgent'         => (clone $base)->where('pengend_important_matrix', 'important')->where('pengend_urgent_matrix', 'urgent')->count(),
            'important_not_urgent'     => (clone $base)->where('pengend_important_matrix', 'important')->where('pengend_urgent_matrix', 'not_urgent')->count(),
            'not_important_urgent'     => (clone $base)->where('pengend_important_matrix', 'not_important')->where('pengend_urgent_matrix', 'urgent')->count(),
            'not_important_not_urgent' => (clone $base)->where('pengend_important_matrix', 'not_important')->where('pengend_urgent_matrix', 'not_urgent')->count(),
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
     */
    public function getStrategicGoals(int|string $year, DokumenSpmiService $dokumenSpmiService): array
    {
        $visiDocs = Dokumen::where('jenis', 'visi')->where('periode', $year)->get();
        $misiDocs = Dokumen::where('jenis', 'misi')->where('periode', $year)->get();

        $visiStats = [];
        foreach ($visiDocs as $doc) {
            $visiStats[] = [
                'id'    => encryptId($doc->dok_id),
                'judul' => $doc->judul,
                'stats' => $dokumenSpmiService->getAchievementByDokumen($doc, $year),
            ];
        }

        $misiStatsArr  = [];
        $totalMisiRate = 0;
        foreach ($misiDocs as $doc) {
            $res             = $dokumenSpmiService->getAchievementByDokumen($doc, $year);
            $totalMisiRate  += $res['rate'];
            $misiStatsArr[]  = $res;
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
            ->where('tahun', 'like', '%' . $year . '%')
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
