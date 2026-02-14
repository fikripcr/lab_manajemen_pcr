<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Services\Sys\DashboardService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    public function index()
    {
        try {
            // Get statistics
            $stats            = $this->dashboardService->getDashboardStats();
            $totalUsers       = $stats['totalUsers'];
            $totalRoles       = $stats['totalRoles'];
            $totalPermissions = $stats['totalPermissions'];
            $todayActivities  = $stats['todayActivities'];

            // Get application name from config
            $appName = config('app.name', 'Laravel');

            // Get the current server time
            $serverTime = now()->format('Y-m-d H:i:s');

            // Get recent activities for the dashboard
            $recentActivities = $this->dashboardService->getRecentActivities(5);

            // Get application environment
            $appEnvironment = app()->environment();
            $appDebug       = config('app.debug') ? 'Enabled' : 'Disabled';
            $appUrl         = config('app.url');

            // Get recent activities (last 10)
            $recentLogs = $this->dashboardService->getRecentLogs(10);

            // Get user role distribution data for list
            $roleUserCounts = $this->dashboardService->getUserRoleDistribution();

            $activityData = $this->dashboardService->getActivitiesByDate();
            $errorData    = $this->dashboardService->getErrorLogsByDate();

            // Format activity data for ApexCharts
            $activityChartData = json_encode([
                'series'     => [[
                    'name' => 'Activities',
                    'data' => $activityData['data'],
                ]],
                'categories' => $activityData['categories'],
            ]);

            // Format error data for ApexCharts
            $errorChartData = json_encode([
                'series'     => [[
                    'name' => 'Errors',
                    'data' => $errorData['data'],
                ]],
                'categories' => $errorData['categories'],
            ]);

            // Check if we need to run server monitoring
            if (request()->has('refresh_monitoring')) {
                Artisan::call('server:monitor');
                logActivity('sys_dashboard', 'Server monitoring refreshed manually', auth()->user());
            }

            // Retrieve server monitoring data
            $serverMonitoringData = $this->dashboardService->getServerMonitoringData();

            return view('pages.sys.dashboard.index', compact(
                'totalUsers',
                'totalRoles',
                'totalPermissions',
                'todayActivities',
                'appName',
                'serverTime',
                'recentActivities',
                'appEnvironment',
                'appDebug',
                'appUrl',
                'recentLogs',
                'roleUserCounts',
                'activityChartData',
                'errorChartData',
                'serverMonitoringData'
            ));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
