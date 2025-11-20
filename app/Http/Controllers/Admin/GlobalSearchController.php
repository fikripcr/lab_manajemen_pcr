<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sys\Role;
use App\Models\Sys\Permission;
use Spatie\Searchable\Search;

class GlobalSearchController extends Controller
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
