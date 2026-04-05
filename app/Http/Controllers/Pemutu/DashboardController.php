<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Services\Pemutu\DashboardService;
use App\Services\Pemutu\DokumenSpmiService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService,
        protected DokumenSpmiService $dokumenSpmiService,
        protected PeriodeSpmiService $periodeSpmiService,
    ) {
        $this->middleware('permission:pemutu.dashboard.view');
    }

    public function index(Request $request): \Illuminate\View\View
    {
        // Use global siklus year from session
        $siklusData  = $this->periodeSpmiService->getSiklusData();
        $currentYear = $siklusData['tahun'];
        $lastYear    = (int) $currentYear - 1;

        $pendingApprovalsCount = $this->dashboardService->getPendingApprovalsCount();

        // --- KPI CARDS ---
        $kpiCurr = $this->dashboardService->getKpiStandar($currentYear);
        $kpiPrev = $this->dashboardService->getKpiStandar($lastYear);

        $yoy = function ($curr, $prev) {
            if ($prev == 0) {
                return $curr > 0 ? 100 : 0;
            }

            return round((($curr - $prev) / $prev) * 100, 1);
        };

        $metrics = [];
        foreach ($kpiCurr as $key => $val) {
            $pct           = $yoy($val, $kpiPrev[$key]);
            $metrics[$key] = [
                'val'   => $val,
                'pct'   => $pct,
                'diff'  => $val - $kpiPrev[$key],
                'trend' => $pct > 0 ? 'up' : ($pct < 0 ? 'down' : 'flat'),
                'color' => $pct > 0 ? 'success' : ($pct < 0 ? 'danger' : 'secondary'),
            ];
        }

        // --- CHARTS & RANKINGS ---
        $trendData       = $this->dashboardService->getTrendData($currentYear);
        $units           = $this->dashboardService->getTopAndBottomUnits($currentYear);
        $top3Units       = $units['top'];
        $bottom3Units    = $units['bottom'];
        $standars        = $this->dashboardService->getTopAndBottomStandar($currentYear);
        $top3Standar     = $standars['top'];
        $bottom3Standar  = $standars['bottom'];
        $jenisKriteriaRaw = $this->dashboardService->getKriteriaDonut($currentYear);
        $eisenhowerCount  = $this->dashboardService->getEisenhowerMatrix($currentYear);
        $unitChartData    = $this->dashboardService->getUnitAnalysisBarChart($currentYear);

        // --- Strategic Goals (Visi & Misi) ---
        $strategicGoals  = $this->dashboardService->getStrategicGoals($currentYear, $this->dokumenSpmiService);
        $visiStats       = $strategicGoals['visiStats'];
        $avgMisiRate     = $strategicGoals['avgMisiRate'];

        $pageTitle = "Dashboard SPMI - {$currentYear}";

        return view('pages.pemutu.dashboard.index', compact(
            'pageTitle', 'currentYear',
            'metrics', 'trendData', 'top3Units', 'bottom3Units', 'top3Standar', 'bottom3Standar',
            'jenisKriteriaRaw', 'eisenhowerCount', 'pendingApprovalsCount',
            'unitChartData', 'visiStats', 'avgMisiRate'
        ));
    }
}
