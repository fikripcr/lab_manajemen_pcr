<?php
namespace App\Services\Sys;

use App\Models\Sys\Activity;
use App\Models\Sys\ErrorLog;
use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use App\Models\Sys\ServerMonitorCheck;
use App\Models\Sys\ServerMonitorHost;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Searchable\Search;

class SysGlobalSearchService
{
    /**
     * Perform global search across multiple models
     */
    public function globalSearch(string $query): array
    {
        if (empty($query)) {
            return [
                'users'         => [],
                'roles'         => [],
                'permissions'   => [],
                'activities'    => [],
                'error_logs'    => [],
                'server_hosts'  => [],
                'server_checks' => [],
            ];
        }

        // Perform search with spatie/laravel-searchable
        $searchResults = (new Search())
            ->registerModel(User::class, 'name', 'email')
            ->registerModel(Role::class, 'name')
            ->registerModel(Permission::class, 'name')
            ->registerModel(Activity::class, 'description', 'log_name', 'event')
            ->registerModel(ErrorLog::class, 'message', 'exception_class', 'url', 'level')
            ->registerModel(ServerMonitorHost::class, 'name', 'ip')
            ->registerModel(ServerMonitorCheck::class, 'type', 'status')
            ->search($query);

        // Additional search for users by role name
        $usersByRole = collect();
        if (strlen($query) >= 2) { // Only search for roles if query is at least 2 chars
            $usersWithRole = User::whereHas('roles', function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })->with('roles')->limit(5)->get();

            foreach ($usersWithRole as $user) {
                // Make sure this user isn't already in the main search results
                $alreadyExists = $searchResults->contains(function ($result) use ($user) {
                    return $result->searchable instanceof User && $result->searchable->id === $user->id;
                });

                if (! $alreadyExists) {
                    $usersByRole->push($user);
                }
            }
        }

        // Group results by type and limit to 5 per type for performance
        $users         = collect();
        $roles         = collect();
        $permissions   = collect();
        $activities    = collect();
        $error_logs    = collect();
        $server_hosts  = collect();
        $server_checks = collect();

        // Process the main search results
        foreach ($searchResults as $result) {
            $model = $result->searchable;

            if ($model instanceof User && $users->count() < 5) {
                $users->push([
                    'id'    => $model->encrypted_id ?? $model->id,
                    'name'  => $model->name,
                    'email' => $model->email,
                    'type'  => 'user',
                    'url'   => route('users.show', $model->encrypted_id ?? $model->id),
                ]);
            } elseif ($model instanceof Role && $roles->count() < 5) {
                $roles->push([
                    'id'   => $model->encrypted_id ?? $model->id,
                    'name' => $model->name,
                    'type' => 'role',
                    'url'  => route('sys.roles.index') . '?search=' . urlencode($query),
                ]);
            } elseif ($model instanceof Permission && $permissions->count() < 5) {
                $permissions->push([
                    'id'   => $model->encrypted_id ?? $model->id,
                    'name' => $model->name,
                    'type' => 'permission',
                    'url'  => route('sys.permissions.index') . '?search=' . urlencode($query),
                ]);
            } elseif ($model instanceof Activity && $activities->count() < 5) {
                $activities->push([
                    'id'          => $model->id,
                    'name'        => $model->description,
                    'description' => $model->log_name . ' | ' . $model->event,
                    'type'        => 'activity',
                    'url'         => route('activity-log.show', $model->id),
                ]);
            } elseif ($model instanceof ErrorLog && $error_logs->count() < 5) {
                $error_logs->push([
                    'id'          => $model->id,
                    'name'        => $model->level . ' - ' . Str::limit(strip_tags($model->message), 50),
                    'description' => Str::limit(strip_tags($model->message), 100),
                    'type'        => 'error_log',
                    'url'         => route('sys.error-log.show', $model->id),
                ]);
            } elseif ($model instanceof ServerMonitorHost && $server_hosts->count() < 5) {
                $server_hosts->push([
                    'id'          => $model->id,
                    'name'        => $model->name,
                    'description' => $model->ip ? 'IP: ' . $model->ip : 'No IP assigned',
                    'type'        => 'server_host',
                    'url'         => '#', // Add specific route if available
                ]);
            } elseif ($model instanceof ServerMonitorCheck && $server_checks->count() < 5) {
                $server_checks->push([
                    'id'          => $model->id,
                    'name'        => $model->type . ' - ' . $model->status,
                    'description' => 'Status: ' . $model->status,
                    'type'        => 'server_check',
                    'url'         => '#', // Add specific route if available
                ]);
            }
        }

        // Add users found by role if we haven't reached the limit
        foreach ($usersByRole as $user) {
            if ($users->count() < 5) {
                $users->push([
                    'id'    => $user->encrypted_id ?? $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'type'  => 'user',
                    'url'   => route('users.show', $user->encrypted_id ?? $user->id),
                ]);
            }
        }

        return [
            'users'         => $users,
            'roles'         => $roles,
            'permissions'   => $permissions,
            'activities'    => $activities,
            'error_logs'    => $error_logs,
            'server_hosts'  => $server_hosts,
            'server_checks' => $server_checks,
            'query'         => $query,
        ];
    }
}
