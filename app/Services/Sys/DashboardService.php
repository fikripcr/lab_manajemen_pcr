<?php

namespace App\Services\Sys;

use App\Models\User;
use App\Models\Sys\ErrorLog;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\Sys\SysDashboardView;
use App\Models\Sys\ServerMonitorCheck;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;

class DashboardService
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        $data = SysDashboardView::first();
        return [
            'totalUsers' => $data->total_users,
            'totalRoles' => $data->total_roles,
            'totalPermissions' => $data->total_permissions,
            'todayActivities' => $data->today_activities,
        ];
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return Activity::with(['causer:id,name', 'subject'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent logs
     */
    public function getRecentLogs(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Activity::with(['causer:id,name', 'subject'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get user role distribution
     */
    public function getUserRoleDistribution(): array
    {
        $roleUserCounts = [];
        $roles = Role::all();

        foreach ($roles as $role) {
            $roleUserCounts[] = [
                'name' => $role->name,
                'count' => $role->users()->count()
            ];
        }

        return $roleUserCounts;
    }

    /**
     * Get activities by date for the last 14 days
     */
    public function getActivitiesByDate(): array
    {
        $activityByDate = [];
        $dates = [];

        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Activity::whereDate('created_at', $date)->count();

            $activityByDate[] = $count;
            $dates[] = $date->format('M d');
        }

        return [
            'data' => $activityByDate,
            'categories' => $dates,
        ];
    }

    /**
     * Get error logs by date for the last 14 days
     */
    public function getErrorLogsByDate(): array
    {
        $errorByDate = [];
        $errorDates = [];

        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = ErrorLog::whereDate('created_at', $date)->count();

            $errorByDate[] = $count;
            $errorDates[] = $date->format('M d');
        }

        return [
            'data' => $errorByDate,
            'categories' => $errorDates,
        ];
    }

    /**
     * Get server monitoring data
     */
    public function getServerMonitoringData(): array
    {
        $serverMonitoringData = [];

        $diskSpaceCheck = ServerMonitorCheck::where('type', 'diskspace')->latest()->first();
        if ($diskSpaceCheck) {
            $diskSpaceData = json_decode($diskSpaceCheck->last_run_output, true);
            $serverMonitoringData['disk_space'] = [
                'last_check' => $diskSpaceCheck->last_ran_at,
                'data' => $diskSpaceData,
                'message' => $diskSpaceCheck->last_run_message,
            ];
        }

        $databaseSizeCheck = ServerMonitorCheck::where('type', 'databasesize')->latest()->first();
        if ($databaseSizeCheck) {
            $databaseSizeData = json_decode($databaseSizeCheck->last_run_output, true);
            $serverMonitoringData['database_size'] = [
                'last_check' => $databaseSizeCheck->last_ran_at,
                'data' => $databaseSizeData,
                'message' => $databaseSizeCheck->last_run_message,
            ];
        }

        $projectSizeCheck = ServerMonitorCheck::where('type', 'projectsize')->latest()->first();
        if ($projectSizeCheck) {
            $projectSizeData = json_decode($projectSizeCheck->last_run_output, true);
            $serverMonitoringData['project_size'] = [
                'last_check' => $projectSizeCheck->last_ran_at,
                'data' => $projectSizeData,
                'message' => $projectSizeCheck->last_run_message,
            ];
        }

        return $serverMonitoringData;
    }
}
