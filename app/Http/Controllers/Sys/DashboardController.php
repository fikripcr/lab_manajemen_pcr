<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalUsers = User::count();
        $totalRoles = Role::count();
        $totalPermissions = Permission::count();
        $todayActivities = Activity::whereDate('created_at', today())->count();

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
        $appDebug = config('app.debug') ? 'Enabled' : 'Disabled';
        $appUrl = config('app.url');

        // Get recent logs (last 10)
        $recentLogs = Activity::with(['causer:id,name', 'subject'])
            ->latest()
            ->limit(10)
            ->get();

        // Get user role distribution for chart
        $userRoleData = [];
        $roleNames = [];

        $roles = Role::all();
        foreach ($roles as $role) {
            $userCount = $role->users()->count();
            $userRoleData[] = $userCount;
            $roleNames[] = $role->name;
        }

        // Format role data for ApexCharts
        $roleChartData = json_encode([
            'series' => $userRoleData,
            'labels' => $roleNames
        ]);

        // Get activities by date for the last 7 days
        $activityByDate = [];
        $dates = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Activity::whereDate('created_at', $date)->count();

            $activityByDate[] = $count;
            $dates[] = $date->format('M d');
        }

        // Format activity data for ApexCharts
        $activityChartData = json_encode([
            'series' => [[
                'name' => 'Activities',
                'data' => $activityByDate
            ]],
            'categories' => $dates
        ]);

        return view('pages.sys.dashboard', compact(
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
            'activityChartData'
        ));
    }
}
