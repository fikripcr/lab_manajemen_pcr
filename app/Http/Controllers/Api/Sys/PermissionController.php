<?php

namespace App\Http\Controllers\Api\Sys;

use App\Http\Controllers\Controller;
use App\Services\Sys\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Search permissions for autocomplete/select
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
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
                        'category' => $permission->category,
                        'sub_category' => $permission->sub_category,
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $results,
                'total' => $results->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all permissions (for initial load)
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
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

            return response()->json([
                'success' => true,
                'data' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching permissions: ' . $e->getMessage()
            ], 500);
        }
    }
}
