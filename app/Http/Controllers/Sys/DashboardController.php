<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Models\Sys\ServerMonitorCheck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalUsers       = User::count();
        $totalRoles       = Role::count();
        $totalPermissions = Permission::count();
        $todayActivities  = Activity::whereDate('created_at', today())->count();

        // Get application name from config
        $appName = config('app.name', 'Laravel');

        // Get the current server time
        $serverTime = now()->format('Y-m-d H:i:s');

        // Get recent activities for the dashboard
        $recentActivities = Activity::with(['causer:id,name', 'subject'])
            ->latest()
            ->limit(5)
            ->get();

        // Get application environment
        $appEnvironment = app()->environment();
        $appDebug       = config('app.debug') ? 'Enabled' : 'Disabled';
        $appUrl         = config('app.url');

        // Get recent logs (last 10)
        $recentLogs = Activity::with(['causer:id,name', 'subject'])
            ->latest()
            ->limit(10)
            ->get();

        // Get user role distribution for chart
        $userRoleData = [];
        $roleNames    = [];

        $roles = Role::all();
        foreach ($roles as $role) {
            $userCount      = $role->users()->count();
            $userRoleData[] = $userCount;
            $roleNames[]    = $role->name;
        }

        // Format role data for ApexCharts
        $roleChartData = json_encode([
            'series' => $userRoleData,
            'labels' => $roleNames,
        ]);

        // Get activities by date for the last 7 days
        $activityByDate = [];
        $dates          = [];

        for ($i = 6; $i >= 0; $i--) {
            $date  = now()->subDays($i);
            $count = Activity::whereDate('created_at', $date)->count();

            $activityByDate[] = $count;
            $dates[]          = $date->format('M d');
        }

        // Format activity data for ApexCharts
        $activityChartData = json_encode([
            'series'     => [[
                'name' => 'Activities',
                'data' => $activityByDate,
            ]],
            'categories' => $dates,
        ]);

        // Check if we need to run server monitoring
        if (request()->has('refresh_monitoring')) {
            Artisan::call('server:monitor');
            LogActivity('sys_dashboard', 'Server monitoring refreshed manually', auth()->user());
        }

        // Retrieve server monitoring data
        $serverMonitoringData = [];

        $diskSpaceCheck = ServerMonitorCheck::where('type', 'diskspace')->latest()->first();
        if ($diskSpaceCheck) {
            $diskSpaceData                      = json_decode($diskSpaceCheck->last_run_output, true);
            $serverMonitoringData['disk_space'] = [
                'last_check' => $diskSpaceCheck->last_ran_at,
                'data'       => $diskSpaceData,
                'message'    => $diskSpaceCheck->last_run_message,
            ];
        }

        $databaseSizeCheck = ServerMonitorCheck::where('type', 'databasesize')->latest()->first();
        if ($databaseSizeCheck) {
            $databaseSizeData                      = json_decode($databaseSizeCheck->last_run_output, true);
            $serverMonitoringData['database_size'] = [
                'last_check' => $databaseSizeCheck->last_ran_at,
                'data'       => $databaseSizeData,
                'message'    => $databaseSizeCheck->last_run_message,
            ];
        }

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
            'roleChartData',
            'activityChartData',
            'serverMonitoringData'
        ));
    }
}
