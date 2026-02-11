<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->input('q');

            if (empty($query)) {
                return jsonSuccess('Search results', null, [
                    'users'       => [],
                    'roles'       => [],
                    'permissions' => [],
                ]);
            }

            // Perform search with spatie/laravel-searchable
            $searchResults = (new Search())
                ->registerModel(User::class, 'name', 'email')
                ->registerModel(Role::class, 'name')
                ->registerModel(Permission::class, 'name')
                ->search($query);

            return jsonSuccess('Search results', null, [
                'users'       => $users,
                'roles'       => $roles,
                'permissions' => $permissions,
                'query'       => $query,
            ]);
        } catch (\Exception $e) {
            \Log::error('Global search error: ' . $e->getMessage());
            return jsonError('Search failed', 500, [
                'users'       => [],
                'roles'       => [],
                'permissions' => [],
            ]);
        }
    }
}
