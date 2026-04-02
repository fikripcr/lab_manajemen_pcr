<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Hr\StrukturOrganisasi;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Dashboard SPMI';

        $pendingApprovalsCount = 0;
        if (auth()->check() && auth()->user()->pegawai) {
            $pendingApprovalsCount = \App\Models\Pemutu\RiwayatApproval::where('status', 'Pending')
                ->where('pegawai_id', auth()->user()->pegawai->pegawai_id)
                ->count();
        }

        // Use global siklus year from session
        $periodeSpmiService = app(\App\Services\Pemutu\PeriodeSpmiService::class);
        $siklusData = $periodeSpmiService->getSiklusData();
        $currentYear = $siklusData['tahun'];
        
        $lastYear = (int) $currentYear - 1;

        $units = StrukturOrganisasi::orderBy('name', 'asc')->get();
        $kriterias = Indikator::whereNotNull('kelompok_indikator')->distinct('kelompok_indikator')->pluck('kelompok_indikator');

        // Base Query Builder for Current Year
        // Filter by year through indikator's relationship to dokumen (via doksub)
        $buildQuery = function ($year) {
            $q = IndikatorOrgUnit::join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
                ->where('pemutu_indikator.type', 'standar')
                ->whereNull('pemutu_indikator.deleted_at')
                ->whereExists(function ($query) use ($year) {
                    $query->select(\DB::raw(1))
                        ->from('pemutu_indikator_doksub')
                        ->join('pemutu_dok_sub', 'pemutu_indikator_doksub.doksub_id', '=', 'pemutu_dok_sub.doksub_id')
                        ->join('pemutu_dokumen', 'pemutu_dok_sub.dok_id', '=', 'pemutu_dokumen.dok_id')
                        ->whereColumn('pemutu_indikator_doksub.source_id', 'pemutu_indikator.indikator_id')
                        ->where('pemutu_indikator_doksub.source_type', 'App\Models\Pemutu\Indikator')
                        ->where('pemutu_dokumen.periode', 'like', '%'.$year.'%');
                });

            return $q;
        };

        $baseCurr = $buildQuery($currentYear);
        $basePrev = $buildQuery($lastYear);

        // --- KPI CARDS: Tercapai vs Tidak Tercapai ---
        $kpiCurr = [
            'tercapai' => (clone $baseCurr)->whereIn('pemutu_indikator_orgunit.ami_hasil_akhir', [1, 2])->count(),
            'tidak_tercapai' => (clone $baseCurr)->where('pemutu_indikator_orgunit.ami_hasil_akhir', 0)->count(),
            'tingkatkan' => (clone $baseCurr)->where('pemutu_indikator_orgunit.pengend_status', 'peningkatan')->count(),
            'penyesuaian' => (clone $baseCurr)->where('pemutu_indikator_orgunit.pengend_status', 'penyesuaian')->count(),
            'tetap' => (clone $baseCurr)->where('pemutu_indikator_orgunit.pengend_status', 'tetap')->count(),
            'nonaktif' => (clone $baseCurr)->where('pemutu_indikator_orgunit.pengend_status', 'nonaktif')->count(),
        ];

        $kpiPrev = [
            'tercapai' => (clone $basePrev)->whereIn('pemutu_indikator_orgunit.ami_hasil_akhir', [1, 2])->count(),
            'tidak_tercapai' => (clone $basePrev)->where('pemutu_indikator_orgunit.ami_hasil_akhir', 0)->count(),
            'tingkatkan' => (clone $basePrev)->where('pemutu_indikator_orgunit.pengend_status', 'peningkatan')->count(),
            'penyesuaian' => (clone $basePrev)->where('pemutu_indikator_orgunit.pengend_status', 'penyesuaian')->count(),
            'tetap' => (clone $basePrev)->where('pemutu_indikator_orgunit.pengend_status', 'tetap')->count(),
            'nonaktif' => (clone $basePrev)->where('pemutu_indikator_orgunit.pengend_status', 'nonaktif')->count(),
        ];

        $yoy = function ($curr, $prev) {
            if ($prev == 0) {
                return $curr > 0 ? 100 : 0;
            }

            return round((($curr - $prev) / $prev) * 100, 1);
        };

        $metrics = [];
        foreach ($kpiCurr as $key => $val) {
            $pct = $yoy($val, $kpiPrev[$key]);
            $metrics[$key] = [
                'val' => $val,
                'pct' => $pct,
                'trend' => $pct > 0 ? 'up' : ($pct < 0 ? 'down' : 'flat'),
                'color' => $pct > 0 ? 'success' : ($pct < 0 ? 'danger' : 'secondary'),
            ];
        }

        // Trends over 4 years
        $trendYears = collect(range($currentYear - 3, $currentYear));
        $trendIndikator = [];
        $trendStandar = [];
        foreach ($trendYears as $y) {
            $q = $buildQuery($y);
            $trendIndikator[] = (clone $q)->count();

            // Total Standar (Dokumen root)
            $trendStandar[] = (clone $q)
                ->join('pemutu_indikator_doksub', 'pemutu_indikator.indikator_id', '=', 'pemutu_indikator_doksub.source_id')
                ->join('pemutu_dok_sub', 'pemutu_indikator_doksub.doksub_id', '=', 'pemutu_dok_sub.doksub_id')
                ->distinct('pemutu_dok_sub.dok_id')
                ->count('pemutu_dok_sub.dok_id');
        }

        $trendData = [
            'years' => $trendYears->toArray(),
            'indikator' => $trendIndikator,
            'standar' => $trendStandar,
        ];

        // Top 3 Unit
        $unitRanksRaw = (clone $baseCurr)
            ->join('hr_struktur_organisasi as so', 'pemutu_indikator_orgunit.org_unit_id', '=', 'so.orgunit_id')
            ->selectRaw('so.code as unit_name, AVG(pemutu_indikator_orgunit.ed_skala) as avg_skala')
            ->groupBy('so.code')
            ->havingRaw('avg_skala IS NOT NULL')
            ->orderByDesc('avg_skala')
            ->get();

        $top3Units = $unitRanksRaw->take(3);
        $bottom3Units = $unitRanksRaw->sortBy('avg_skala')->take(3)->values();

        // Top 3 Standar
        $standarRanksRaw = (clone $baseCurr)
            ->join('pemutu_indikator_doksub as ids', 'pemutu_indikator.indikator_id', '=', 'ids.source_id')
            ->join('pemutu_dok_sub as ds', 'ids.doksub_id', '=', 'ds.doksub_id')
            ->join('pemutu_dokumen as d', 'ds.dok_id', '=', 'd.dok_id')
            ->selectRaw('COALESCE(d.kode, d.judul) as dokumen_name, AVG(pemutu_indikator_orgunit.ed_skala) as avg_skala')
            ->groupBy('dokumen_name')
            ->havingRaw('avg_skala IS NOT NULL')
            ->orderByDesc('avg_skala')
            ->get();

        $top3Standar = $standarRanksRaw->take(3);
        $bottom3Standar = $standarRanksRaw->sortBy('avg_skala')->take(3)->values();

        // Penetapan Jenis Kriteria Donut
        $jenisKriteriaRaw = (clone $baseCurr)
            ->selectRaw('pemutu_indikator.kelompok_indikator as label, COUNT(*) as total')
            ->whereNotNull('pemutu_indikator.kelompok_indikator')
            ->where('pemutu_indikator.kelompok_indikator', '!=', '')
            ->groupBy('pemutu_indikator.kelompok_indikator')
            ->get();

        // Eisenhower Matrix Boxes
        $eisenhowerCount = [
            'important_urgent' => (clone $baseCurr)->where('pengend_important_matrix', '>=', 5)->where('pengend_urgent_matrix', '>=', 5)->count(),
            'important_not_urgent' => (clone $baseCurr)->where('pengend_important_matrix', '>=', 5)->where('pengend_urgent_matrix', '<', 5)->count(),
            'not_important_urgent' => (clone $baseCurr)->where('pengend_important_matrix', '<', 5)->where('pengend_urgent_matrix', '>=', 5)->count(),
            'not_important_not_urgent' => (clone $baseCurr)->where('pengend_important_matrix', '<', 5)->where('pengend_urgent_matrix', '<', 5)->count(),
        ];

        // Analisis Unit Kerja (ED vs AMI Bar Chart)
        $unitAnalysisRaw = (clone $baseCurr)
            ->join('hr_struktur_organisasi as so', 'pemutu_indikator_orgunit.org_unit_id', '=', 'so.orgunit_id')
            ->selectRaw('
                so.code as unit_name, 
                AVG(pemutu_indikator_orgunit.ed_skala) as avg_ed,
                AVG(CASE 
                    WHEN pemutu_indikator_orgunit.ami_hasil_akhir = 2 THEN 100 
                    WHEN pemutu_indikator_orgunit.ami_hasil_akhir = 1 THEN 100 
                    WHEN pemutu_indikator_orgunit.ami_hasil_akhir = 0 THEN 0 
                    ELSE NULL END) as avg_ami_pct,
                SUM(CASE WHEN pemutu_indikator_orgunit.ami_hasil_akhir = 0 THEN 1 ELSE 0 END) as count_kts,
                SUM(CASE WHEN pemutu_indikator_orgunit.ami_hasil_akhir = 1 THEN 1 ELSE 0 END) as count_terpenuhi,
                SUM(CASE WHEN pemutu_indikator_orgunit.ami_hasil_akhir = 2 THEN 1 ELSE 0 END) as count_terlampaui
            ')
            ->groupBy('so.code')
            ->orderBy('so.code')
            ->get();

        $unitChartData = [
            'categories' => $unitAnalysisRaw->pluck('unit_name')->toArray(),
            'ed_series' => $unitAnalysisRaw->map(fn ($item) => round((float) $item->avg_ed, 2))->toArray(),
            'ami_series' => $unitAnalysisRaw->map(fn ($item) => round((float) $item->avg_ami_pct, 1))->toArray(),
            'ami_kts' => $unitAnalysisRaw->pluck('count_kts')->toArray(),
            'ami_terpenuhi' => $unitAnalysisRaw->pluck('count_terpenuhi')->toArray(),
            'ami_terlampaui' => $unitAnalysisRaw->pluck('count_terlampaui')->toArray(),
        ];

        // Hirarki Peta Ketercapaian
        // Ambil hanya Dokumen Root (Visi atau Standar Utama)
        $hierarchyDataRaw = \App\Models\Pemutu\Dokumen::where('periode', $currentYear)
            ->whereNull('parent_id')
            ->orderBy('seq')
            ->get();
            
        // Map Hierarchy Data
        $hierarchyData = [];
        // Kita butuh fungsi helper atau query broad untuk aggregate semua indikator di bawah Dokumen root
        foreach ($hierarchyDataRaw as $doc) {
            // Find all descendant documents if any (e.g. Visi -> Misi -> Renop)
            $descendantIds = [$doc->dok_id];
            $children = \App\Models\Pemutu\Dokumen::where('parent_id', $doc->dok_id)->pluck('dok_id')->toArray();
            if(!empty($children)) {
                $descendantIds = array_merge($descendantIds, $children);
                $grands = \App\Models\Pemutu\Dokumen::whereIn('parent_id', $children)->pluck('dok_id')->toArray();
                if(!empty($grands)) {
                    $descendantIds = array_merge($descendantIds, $grands);
                    $greats = \App\Models\Pemutu\Dokumen::whereIn('parent_id', $grands)->pluck('dok_id')->toArray();
                    if(!empty($greats)) $descendantIds = array_merge($descendantIds, $greats);
                }
            }

            // Aggregate stats for all descendant documents
            $stats = \DB::table('pemutu_indikator_orgunit as pio')
                ->join('pemutu_indikator_doksub as pid', 'pio.indikator_id', '=', 'pid.source_id')
                ->join('pemutu_dok_sub as pds', 'pid.doksub_id', '=', 'pds.doksub_id')
                ->where('pid.source_type', \App\Models\Pemutu\Indikator::class)
                ->whereIn('pds.dok_id', $descendantIds)
                ->selectRaw('
                    COUNT(pio.indikorgunit_id) as total_indikator,
                    AVG(pio.ed_skala) as avg_ed,
                    SUM(CASE WHEN pio.ami_hasil_akhir IN (1,2) THEN 1 ELSE 0 END) as tercapai,
                    SUM(CASE WHEN pio.ami_hasil_akhir = 0 THEN 1 ELSE 0 END) as tidak_tercapai,
                    COUNT(CASE WHEN pio.ami_hasil_akhir IS NOT NULL THEN 1 END) as total_dinilai
                ')
                ->first();

            $ami_pct = $stats->total_dinilai > 0 ? round(($stats->tercapai / $stats->total_dinilai) * 100, 1) : 0;
            
            // Skip roots that have absolutely no indicators (like some manual templates) to keep dashboard clean
            if ($stats->total_indikator == 0) continue;

            $hierarchyData[] = [
                'id' => $doc->dok_id,
                'judul' => $doc->judul,
                'kode' => $doc->kode ?? 'ROOT',
                'stats' => [
                    'total_indikator' => $stats->total_indikator ?? 0,
                    'avg_ed' => round((float) ($stats->avg_ed ?? 0), 2),
                    'ami_pct' => $ami_pct,
                ]
            ];
        }

        // Update page title with year
        $pageTitle = "Dashboard SPMI - {$currentYear}";

        return view('pages.pemutu.dashboard.index', compact(
            'pageTitle', 'currentYear',
            'metrics', 'trendData', 'top3Units', 'bottom3Units', 'top3Standar', 'bottom3Standar',
            'jenisKriteriaRaw', 'eisenhowerCount', 'pendingApprovalsCount',
            'unitChartData', 'hierarchyData', 'currentYear'
        ));
    }

    /**
     * AJAX Endpoint to fetch nested hierarchy for a specific Root Dokumen
     */
    public function hierarchyNode(Request $request, $id)
    {
        $currentYear = session('siklus_spmi_tahun', date('Y'));
        
        // Find root
        $root = \App\Models\Pemutu\Dokumen::findOrFail($id);
        
        // Eager load everything needed for the tree below this root
        // 1. Its direct DokSubs and their Indicators and target Units
        // 2. Its child Dokumens (Standar) and their DokSubs and Indicators and target Units
        $children = \App\Models\Pemutu\Dokumen::with([
            'dokSubs',
            'dokSubs.indikators.orgUnits'
        ])
        ->where('parent_id', $root->dok_id)
        ->where('periode', $currentYear)
        ->orderBy('seq')
        ->get();

        // Plus any direct DokSubs on the root itself
        $rootDokSubs = \App\Models\Pemutu\DokSub::with([
            'indikators.orgUnits'
        ])
        ->where('dok_id', $root->dok_id)
        ->orderBy('seq')
        ->get();

        return view('pages.pemutu.dashboard._hierarchy_tree', compact('root', 'children', 'rootDokSubs'));
    }
}
