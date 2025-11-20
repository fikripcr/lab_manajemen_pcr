<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sys\Role;
use App\Models\Sys\Permission;
use Spatie\Searchable\Search;

class SysGlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->input('q');

            if (empty($query)) {
                return response()->json([
                    'users' => [],
                    'roles' => [],
                    'permissions' => [],
                ]);
            }

            // Perform search with spatie/laravel-searchable
            $searchResults = (new Search())
                ->registerModel(User::class, 'name', 'email')
                ->registerModel(Role::class, 'name')
                ->registerModel(Permission::class, 'name')
                ->search($query);

            // Additional search for users by role name
            $usersByRole = collect();
            if (strlen($query) >= 2) { // Only search for roles if query is at least 2 chars
                $usersWithRole = User::whereHas('roles', function($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%');
                })->with('roles')->limit(5)->get();

                foreach ($usersWithRole as $user) {
                    // Make sure this user isn't already in the main search results
                    $alreadyExists = $searchResults->contains(function($result) use ($user) {
                        return $result->searchable instanceof User && $result->searchable->id === $user->id;
                    });

                    if (!$alreadyExists) {
                        $usersByRole->push($user);
                    }
                }
            }

            // Group results by type and limit to 5 per type for performance
            $users = collect();
            $roles = collect();
            $permissions = collect();

            // Process the main search results
            foreach ($searchResults as $result) {
                $model = $result->searchable;

                if ($model instanceof User && $users->count() < 5) {
                    $users->push([
                        'id' => $model->encrypted_id ?? $model->id,
                        'name' => $model->name,
                        'email' => $model->email,
                        'type' => 'user',
                        'url' => route('users.show', $model->encrypted_id ?? $model->id),
                    ]);
                } elseif ($model instanceof Role && $roles->count() < 5) {
                    $roles->push([
                        'id' => $model->encrypted_id ?? $model->id,
                        'name' => $model->name,
                        'type' => 'role',
                        'url' => route('roles.index') . '?search=' . urlencode($query),
                    ]);
                } elseif ($model instanceof Permission && $permissions->count() < 5) {
                    $permissions->push([
                        'id' => $model->encrypted_id ?? $model->id,
                        'name' => $model->name,
                        'type' => 'permission',
                        'url' => route('permissions.index') . '?search=' . urlencode($query),
                    ]);
                }
            }

            // Add users found by role if we haven't reached the limit
            foreach ($usersByRole as $user) {
                if ($users->count() < 5) {
                    $users->push([
                        'id' => $user->encrypted_id ?? $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'type' => 'user',
                        'url' => route('users.show', $user->encrypted_id ?? $user->id),
                    ]);
                }
            }

            return response()->json([
                'users' => $users,
                'roles' => $roles,
                'permissions' => $permissions,
                'query' => $query,
            ]);
        } catch (\Exception $e) {
            \Log::error('Global search error: ' . $e->getMessage());
            return response()->json([
                'users' => [],
                'roles' => [],
                'permissions' => [],
                'error' => 'Search error occurred'
            ]);
        }
    }
}
