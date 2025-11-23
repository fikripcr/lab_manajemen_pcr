<?php

namespace App\Services;

use App\Models\Sys\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;

class RoleService
{
    /**
     * Get list of roles with optional filters
     */
    public function getRoleList(array $filters = []): LengthAwarePaginator
    {
        return DB::transaction(function() use ($filters) {
            $query = Role::query();

            // Apply filters
            if (isset($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            $perPage = $filters['per_page'] ?? 10;

            return $query->withCount('users')->latest()->paginate($perPage);
        });
    }

    /**
     * Get a specific role by ID
     */
    public function getRoleById(int $roleId): ?Role
    {
        return DB::transaction(function() use ($roleId) {
            return Role::find($roleId);
        });
    }

    /**
     * Create a new role
     */
    public function createRole(array $data): Role
    {
        return DB::transaction(function() use ($data) {
            $role = Role::create(['name' => $data['name']]);

            // Sync permissions if provided
            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $permissions = array_values(array_filter($data['permissions'], function ($permission) {
                    return !empty($permission);
                }));
                $role->givePermissionTo($permissions);
            }

            return $role;
        });
    }

    /**
     * Update an existing role
     */
    public function updateRole(int $roleId, array $data): bool
    {
        return DB::transaction(function() use ($roleId, $data) {
            $role = Role::find($roleId);
            
            if (!$role) {
                return false;
            }

            $role->update(['name' => $data['name']]);

            // Sync permissions if provided
            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $permissions = array_values(array_filter($data['permissions'], function ($permission) {
                    return !empty($permission);
                }));
                $role->syncPermissions($permissions);
            }

            return true;
        });
    }

    /**
     * Update role permissions only
     */
    public function updateRolePermissions(int $roleId, array $permissions): bool
    {
        return DB::transaction(function() use ($roleId, $permissions) {
            $role = Role::find($roleId);
            
            if (!$role) {
                return false;
            }

            // Ensure we have valid permission names
            $permissions = $permissions ?? [];
            if (is_array($permissions)) {
                $permissions = array_values(array_filter($permissions, function ($permission) {
                    return !empty($permission);
                }));
            }
            $role->syncPermissions($permissions);

            return true;
        });
    }

    /**
     * Delete a role by ID
     */
    public function deleteRole(int $roleId): bool
    {
        return DB::transaction(function() use ($roleId) {
            $role = Role::find($roleId);
            
            if (!$role) {
                return false;
            }

            // Check if role has users assigned
            if ($role->users->count() > 0) {
                throw new \Exception('Cannot delete role with assigned users.');
            }

            return $role->delete();
        });
    }

    /**
     * Count roles with optional filters
     */
    public function countRoles(array $filters = []): int
    {
        return DB::transaction(function() use ($filters) {
            $query = Role::query();

            // Apply filters
            if (isset($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            return $query->count();
        });
    }
}