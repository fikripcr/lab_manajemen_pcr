<?php
namespace App\Http\Controllers\Api\Sys;

use App\Http\Controllers\Controller;
use App\Services\Sys\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct(protected PermissionService $permissionService)
    {}

    /**
     * Search permissions for autocomplete/select
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        $limit = $request->input('limit', 20);

        // Use service to get filtered permissions
        $permissions = $this->permissionService
            ->getFilteredQuery(['name' => $query])
            ->limit($limit)
            ->get(['id', 'name', 'category', 'sub_category']);

        // Format for Choices.js
        $results = $permissions->map(function ($permission) {
            return [
                'value' => $permission->id,
                'label' => $permission->name .
                ($permission->category ? " ({$permission->category})" : ''),
                'customProperties' => [
                    'category'     => $permission->category,
                    'sub_category' => $permission->sub_category,
                ],
            ];
        });

        return jsonSuccess('Permissions found', null, $results);
    }

    /**
     * Get all permissions (for initial load)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $permissions = $this->permissionService
            ->getFilteredQuery()
            ->limit(50)
            ->get(['id', 'name', 'category']);

        $results = $permissions->map(function ($permission) {
            return [
                'value' => $permission->id,
                'label' => $permission->name .
                ($permission->category ? " ({$permission->category})" : ''),
            ];
        });

        return jsonSuccess('Permissions found', null, $results);
    }
}
