<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct(protected \App\Services\Eoffice\DashboardService $DashboardService)
    {}

    public function index(\App\Http\Requests\Eoffice\DashboardRequest $request)
    {
        $period = $request->validated('period', 'month');
        $data   = $this->DashboardService->getDashboardData($period);

        return view('pages.eoffice.dashboard.index', $data);
    }

    public function refresh(\App\Http\Requests\Eoffice\DashboardRequest $request)
    {
        $period    = $request->validated('period', 'month');
        $startDate = $this->DashboardService->getStartDate($period);
        $endDate   = now();

        $stats     = $this->DashboardService->getMainStats($startDate, $endDate);
        $chartData = $this->DashboardService->getChartData($period);

        return response()->json([
            'stats'     => $stats,
            'chartData' => $chartData,
        ]);
    }
}
