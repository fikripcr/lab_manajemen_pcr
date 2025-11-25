<?php
namespace App\Services\Sys;

use App\Models\Sys\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RoleService
{

    protected function findRoleOrFail(int $roleId): Role
    {
        $role = Role::find($roleId);
        if (! $role) {
            throw new \Exception("Role dengan ID {$roleId} tidak ditemukan.");
        }
        return $role;
    }

    /**
     * Get list of roles with optional filters (read-only)
     */
    public function getRoleList(array $filters = [])
    {
        $query = $this->applyFilters(Role::query(), $filters)
            ->with('permissions')
            ->withCount('users');

        $perPage = $filters['per_page'] ?? 10;
        return $query->latest()->paginate($perPage);
    }

    /**
     * Get a specific role by ID (read-only)
     */
    public function getRoleById(int $roleId)
    {
        return Role::with(['permissions', 'users'])->find($roleId);
    }

    /**
     * Create a new role
     */
    public function createRole(array $data)
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create(['name' => $data['name']]);

            if (! empty($data['permissions']) && is_array($data['permissions'])) {
                $role->givePermissionTo($data['permissions']);
            }

            logActivity('role_management', "Membuat role baru: {$role->name}");
            return $role;
        });
    }

    /**
     * Update an existing role
     */
    public function updateRole(int $roleId, array $data)
    {
        return DB::transaction(function () use ($roleId, $data) {
            $role = $this->findRoleOrFail($roleId);

            $oldName = $role->name;
            $role->update(['name' => $data['name']]);

            if (! empty($data['permissions']) && is_array($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            if ($oldName !== $role->name) {
                logActivity('role_management', "Mengubah nama role dari '{$oldName}' menjadi '{$role->name}'");
            } else {
                logActivity('role_management', "Memperbarui hak akses role: {$role->name}");
            }

            return true;
        });
    }

    /**
     * Delete a role
     */
    public function deleteRole(int $roleId): bool
    {
        return DB::transaction(function () use ($roleId) {
            $role = $this->findRoleOrFail($roleId);

            if ($role->users()->count() > 0) {
                throw new \Exception("Tidak bisa menghapus role '{$role->name}' karena masih digunakan oleh pengguna.");
            }

            $roleName = $role->name;
            $role->delete();

            logActivity('role_management', "Menghapus role: {$roleName}");
            return true;
        });
    }

    /**
     * Update role permissions only
     */
    public function updateRolePermissions(int $roleId, array $permissions): bool
    {
        return DB::transaction(function () use ($roleId, $permissions) {
            $role = $this->findRoleOrFail($roleId);

            $permissions = array_values(array_filter($permissions, fn($p) => ! empty($p)));
            $role->syncPermissions($permissions);

            logActivity('role_management', "Memperbarui hak akses role: {$role->name}");
            return true;
        });
    }

    /**
     * Get filtered query for DataTables or export
     */
    public function getFilteredQuery(array $filters = [])
    {
        return $this->applyFilters(Role::query(), $filters)
            ->with('permissions')
            ->withCount('users')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Count roles with filters
     */
    public function countRoles(array $filters = []): int
    {
        return $this->applyFilters(Role::query(), $filters)->count();
    }

    /**
     * Get all roles as collection (read-only)
     */
    public function getAllRoles(): Collection
    {
        return Role::all();
    }

    /**
     * 🔑 SINGLE SOURCE OF TRUTH for filtering roles
     */
    protected function applyFilters($query, array $filters): \Illuminate\Database\Eloquent\Builder
    {
        if (! empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        // Tambahkan filter lain di sini jika perlu
        return $query;
    }
}
