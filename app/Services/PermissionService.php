<?php

namespace App\Services;

use App\Models\Sys\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PermissionService
{
    /**
     * Get list of permissions with optional filters
     */
    public function getPermissionList(array $filters = []): LengthAwarePaginator
    {
        return DB::transaction(function() use ($filters) {
            $query = Permission::query();

            // Apply filters
            if (isset($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (isset($filters['category'])) {
                $query->where('category', $filters['category']);
            }

            if (isset($filters['sub_category'])) {
                $query->where('sub_category', $filters['sub_category']);
            }

            $perPage = $filters['per_page'] ?? 10;

            return $query->latest()->paginate($perPage);
        });
    }

    /**
     * Get a specific permission by ID
     */
    public function getPermissionById(int $permissionId): ?Permission
    {
        return DB::transaction(function() use ($permissionId) {
            return Permission::find($permissionId);
        });
    }

    /**
     * Create a new permission
     */
    public function createPermission(array $data): Permission
    {
        return DB::transaction(function() use ($data) {
            return Permission::create([
                'name' => $data['name'] ?? '',
                'category' => $data['category'] ?? null,
                'sub_category' => $data['sub_category'] ?? null,
            ]);
        });
    }

    /**
     * Update an existing permission
     */
    public function updatePermission(int $permissionId, array $data): bool
    {
        return DB::transaction(function() use ($permissionId, $data) {
            $permission = Permission::find($permissionId);
            
            if (!$permission) {
                return false;
            }

            $permission->update([
                'name' => $data['name'] ?? $permission->name,
                'category' => $data['category'] ?? $permission->category,
                'sub_category' => $data['sub_category'] ?? $permission->sub_category,
            ]);

            return true;
        });
    }

    /**
     * Delete a permission by ID
     */
    public function deletePermission(int $permissionId): bool
    {
        return DB::transaction(function() use ($permissionId) {
            $permission = Permission::find($permissionId);
            
            if (!$permission) {
                return false;
            }

            // Check if permission is assigned to any roles
            if ($permission->roles()->count() > 0) {
                throw new \Exception('Cannot delete permission that is assigned to roles.');
            }

            return $permission->delete();
        });
    }

    /**
     * Count permissions with optional filters
     */
    public function countPermissions(array $filters = []): int
    {
        return DB::transaction(function() use ($filters) {
            $query = Permission::query();

            // Apply filters
            if (isset($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (isset($filters['category'])) {
                $query->where('category', $filters['category']);
            }

            if (isset($filters['sub_category'])) {
                $query->where('sub_category', $filters['sub_category']);
            }

            return $query->count();
        });
    }
}