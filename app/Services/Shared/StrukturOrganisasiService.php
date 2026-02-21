<?php
namespace App\Services\Shared;

use App\Models\Shared\StrukturOrganisasi;
use Exception;
use Illuminate\Support\Facades\DB;

class StrukturOrganisasiService
{
    /**
     * Get query for datatables or lists with filters
     */
    public function getFilteredQuery(array $filters = [])
    {
        $query = StrukturOrganisasi::with(['parent', 'successor', 'auditee'])
            ->select('struktur_organisasi.*');

        // Filter by Type
        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Filter by Status (Active/Inactive)
        if (array_key_exists('status', $filters) && $filters['status'] !== '') {
            $query->where('is_active', $filters['status'] === 'active');
        }

        return $query;
    }

    /**
     * Get active units in hierarchical structure (nested relations)
     */
    public function getActiveHierarchicalUnits()
    {
        return StrukturOrganisasi::whereNull('parent_id')
            ->active()
            ->orderBy('sort_order') // Primary sort
            ->orderBy('seq')        // Secondary sort (sync backup)
            ->orderBy('name')
            ->with(['activeChildren' => function ($q) {
                $q->orderBy('sort_order')
                    ->orderBy('seq')
                    ->with(['activeChildren' => function ($qq) {
                        $qq->orderBy('sort_order')
                            ->orderBy('seq')
                            ->with(['activeChildren' => function ($qqq) {
                                $qqq->orderBy('sort_order')
                                    ->orderBy('seq');
                            }]);
                    }]);
            }])
            ->get();
    }

    /**
     * Get all units list
     */
    public function getAllUnits()
    {
        return StrukturOrganisasi::with(['parent', 'successor'])
            ->orderBy('level')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get single unit by ID with relations
     */
    public function getOrgUnitById(int $id): ?StrukturOrganisasi
    {
        return StrukturOrganisasi::with(['parent', 'children', 'personils.user', 'successor', 'auditee'])->find($id);
    }

    /**
     * Get flattened hierarchical list for dropdowns (e.g. parent selection)
     */
    public function getHierarchicalList($parentId = null, $prefix = '')
    {
        $units = StrukturOrganisasi::where('parent_id', $parentId)
            ->orderBy('sort_order')
            ->orderBy('seq')
            ->orderBy('name')
            ->get();

        $result = [];
        foreach ($units as $unit) {
            $unit->name_display = $prefix . ' ' . $unit->name;
            $result[]           = $unit;
            $result             = array_merge($result, $this->getHierarchicalList($unit->orgunit_id, $prefix . '--'));
        }
        return $result;
    }

    /**
     * Create new OrgUnit
     */
    public function createOrgUnit(array $data): StrukturOrganisasi
    {
        return DB::transaction(function () use ($data) {
            // 1. Calculate Level
            if (! empty($data['parent_id'])) {
                $parent        = StrukturOrganisasi::find($data['parent_id']);
                $data['level'] = $parent ? $parent->level + 1 : 1;
            } else {
                $data['level'] = 1;
            }

            // 2. Calculate Order (Sync seq and sort_order)
            // If sort_order or seq is not provided, calculate max + 1
            if (empty($data['sort_order']) || empty($data['seq'])) {
                $query   = StrukturOrganisasi::where('parent_id', $data['parent_id'] ?? null);
                $maxSort = $query->max('sort_order') ?? 0;
                $maxSeq  = $query->max('seq') ?? 0;

                $nextOrder = max($maxSort, $maxSeq) + 1;

                $data['sort_order'] = $nextOrder;
                $data['seq']        = $nextOrder;
            } else {
                // Ensure both are set if one is provided
                $data['sort_order'] = $data['sort_order'] ?? $data['seq'];
                $data['seq']        = $data['seq'] ?? $data['sort_order'];
            }

            $orgUnit = StrukturOrganisasi::create($data);

            logActivity('org_unit_management', "Membuat unit organisasi baru: {$orgUnit->name}");

            return $orgUnit;
        });
    }

    /**
     * Update OrgUnit
     */
    public function updateOrgUnit(StrukturOrganisasi $orgUnit, array $data): bool
    {
        return DB::transaction(function () use ($orgUnit, $data) {
            $oldName = $orgUnit->name;

            // Prevent self-parenting
            if (isset($data['parent_id']) && $data['parent_id'] == $orgUnit->orgunit_id) {
                throw new Exception('Cannot be parent of itself.');
            }

            // Recalculate Level if parent changed
            if (isset($data['parent_id']) && $data['parent_id'] != $orgUnit->parent_id) {
                if (! empty($data['parent_id'])) {
                    $parent        = StrukturOrganisasi::find($data['parent_id']);
                    $data['level'] = $parent ? $parent->level + 1 : 1;
                } else {
                    $data['level'] = 1;
                }
            }

            // Sync sort_order and seq if updated
            if (isset($data['sort_order'])) {
                $data['seq'] = $data['sort_order'];
            } elseif (isset($data['seq'])) {
                $data['sort_order'] = $data['seq'];
            }

            $orgUnit->update($data);

            logActivity(
                'org_unit_management',
                "Memperbarui unit organisasi: {$oldName}" . ($oldName !== $orgUnit->name ? " menjadi {$orgUnit->name}" : "")
            );

            return true;
        });
    }

    /**
     * Delete OrgUnit
     */
    public function deleteOrgUnit(StrukturOrganisasi $orgUnit): bool
    {
        return DB::transaction(function () use ($orgUnit) {
            $name = $orgUnit->name;

            if ($orgUnit->children()->exists()) {
                throw new Exception('Unit tidak bisa dihapus karena memiliki sub-unit. Pindahkan atau hapus sub-unit terlebih dahulu.');
            }

            $orgUnit->delete();

            logActivity('org_unit_management', "Menghapus unit organisasi: {$name}");

            return true;
        });
    }

    /**
     * Toggle Active Status
     */
    public function toggleStatus(StrukturOrganisasi $orgUnit): StrukturOrganisasi
    {
        return DB::transaction(function () use ($orgUnit) {
            $orgUnit->is_active = ! $orgUnit->is_active;
            $orgUnit->save();

            logActivity(
                'org_unit_management',
                "Mengubah status unit organisasi: {$orgUnit->name} menjadi " . ($orgUnit->is_active ? 'Active' : 'Inactive')
            );

            return $orgUnit;
        });
    }

    /**
     * Set Auditee User
     */
    public function setAuditee(StrukturOrganisasi $orgUnit, ?int $userId): bool
    {
        return DB::transaction(function () use ($orgUnit, $userId) {
            $orgUnit->auditee_user_id = $userId;
            $orgUnit->save();

            logActivity('org_unit_management', "Set Auditee untuk unit: {$orgUnit->name}");

            return true;
        });
    }

    /**
     * Reorder Units via Nested Sortable
     */
    public function reorderUnits(array $hierarchy): bool
    {
        return DB::transaction(function () use ($hierarchy) {
            $this->updateHierarchy($hierarchy);
            return true;
        });
    }

    /**
     * Recursive function to update hierarchy and order
     */
    protected function updateHierarchy(array $items, $parentId = null, int $level = 1): void
    {
        foreach ($items as $index => $item) {
            $orgUnit = StrukturOrganisasi::find($item['id']);
            if ($orgUnit) {
                $orgUnit->parent_id = $parentId;
                $orgUnit->level     = $level;
                // Sync both columns
                $orgUnit->sort_order = $index + 1;
                $orgUnit->seq        = $index + 1;
                $orgUnit->save();

                if (isset($item['children']) && is_array($item['children'])) {
                    $this->updateHierarchy($item['children'], $orgUnit->orgunit_id, $level + 1);
                }
            }
        }
    }

    /**
     * Helper to find model or throw exception
     */
    protected function findOrFail(int $id): StrukturOrganisasi
    {
        $model = StrukturOrganisasi::find($id);
        if (! $model) {
            throw new Exception("Unit Organisasi dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }

    /**
     * Get available types for dropdown
     */
    public function getTypes(): array
    {
        return [
            'institusi'          => 'Institusi',
            'direktorat'         => 'Direktorat',
            'fakultas'           => 'Fakultas',
            'bagian'             => 'Bagian',
            'jurusan'            => 'Jurusan',
            'prodi'              => 'Prodi',
            'laboratorium'       => 'Laboratorium',
            'unit'               => 'Unit',
            'senat'              => 'Senat',
            'sekretariat'        => 'Sekretariat',
            'pimpinan'           => 'Pimpinan',
            'jabatan_struktural' => 'Jabatan Struktural',
            'posisi'             => 'Posisi',
        ];
    }
}
