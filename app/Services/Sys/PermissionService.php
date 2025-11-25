<?php

namespace App\Services\Sys;

use App\Models\Sys\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PermissionService
{
    /**
     * Get list of permissions with optional filters (read-only → no transaction)
     */
    public function getPermissionList(array $filters = []): LengthAwarePaginator
    {
        $query = $this->applyFilters(Permission::query(), $filters)
            ->with('roles');

        $perPage = $filters['per_page'] ?? 10;
        return $query->latest()->paginate($perPage);
    }

    /**
     * Get a specific permission by ID (read-only → no transaction)
     */
    public function getPermissionById(int $permissionId): ?Permission
    {
        return Permission::with('roles')->find($permissionId);
    }

    /**
     * Create a new permission
     */
    public function createPermission(array $data): Permission
    {
        return DB::transaction(function () use ($data) {
            $permission = Permission::create([
                'name' => $data['name'],
                'category' => $data['category'] ?? null,
                'sub_category' => $data['sub_category'] ?? null,
            ]);

            logActivity(
                'permission_management',
                "Membuat izin baru: {$permission->name}" .
                ($permission->category ? " (kategori: {$permission->category})" : "")
            );

            return $permission;
        });
    }

    /**
     * Update an existing permission
     */
    public function updatePermission(int $permissionId, array $data): bool
    {
        return DB::transaction(function () use ($permissionId, $data) {
            $permission = $this->findOrFail($permissionId);

            $oldName = $permission->name;
            $oldCategory = $permission->category;

            $permission->update([
                'name' => $data['name'],
                'category' => $data['category'] ?? $permission->category,
                'sub_category' => $data['sub_category'] ?? $permission->sub_category,
            ]);

            // Logging perubahan
            $changes = [];
            if ($oldName !== $permission->name) {
                $changes[] = "nama dari '{$oldName}' menjadi '{$permission->name}'";
            }
            if ($oldCategory !== $permission->category) {
                $changes[] = "kategori dari '{$oldCategory}' menjadi '{$permission->category}'";
            }

            if (!empty($changes)) {
                logActivity(
                    'permission_management',
                    "Memperbarui izin '{$oldName}': " . implode(', ', $changes)
                );
            } else {
                logActivity(
                    'permission_management',
                    "Memperbarui izin: {$permission->name}"
                );
            }

            return true;
        });
    }

    /**
     * Delete a permission
     */
    public function deletePermission(int $permissionId): bool
    {
        return DB::transaction(function () use ($permissionId) {
            $permission = $this->findOrFail($permissionId);

            if ($permission->roles()->count() > 0) {
                throw new \Exception("Tidak bisa menghapus izin '{$permission->name}' karena masih digunakan oleh role.");
            }

            $permissionName = $permission->name;
            $permission->delete();

            logActivity(
                'permission_management',
                "Menghapus izin: {$permissionName}"
            );

            return true;
        });
    }

    /**
     * Get filtered query for DataTables or export
     */
    public function getFilteredQuery(array $filters = [])
    {
        return $this->applyFilters(Permission::query(), $filters)
            ->with('roles')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Count permissions with filters (read-only → no transaction)
     */
    public function countPermissions(array $filters = []): int
    {
        return $this->applyFilters(Permission::query(), $filters)->count();
    }

    /**
     * Get all permissions as collection (read-only → no transaction)
     */
    public function getAllPermissions(): Collection
    {
        return Permission::all();
    }

    /**
     * 🔑 SINGLE SOURCE OF TRUTH for filtering permissions
     */
    protected function applyFilters($query, array $filters): \Illuminate\Database\Eloquent\Builder
    {
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        return $query;
    }

    /**
     * Find model by ID or throw exception
     */
    protected function findOrFail(int $id): \App\Models\Sys\Permission
    {
        $model = $this->getPermissionById($id);
        if (!$model) {
            throw new \Exception("Izin dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
