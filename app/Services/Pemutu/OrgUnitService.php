<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\OrgUnit;
use Illuminate\Support\Facades\DB;

class OrgUnitService
{
    public function getFilteredQuery(array $filters = [])
    {
        $query = OrgUnit::with(['parent', 'successor', 'auditee'])->select('pemutu_org_unit.*');

        if (array_key_exists('status', $filters) && $filters['status'] !== '') { // Use array_key_exists to allow '0' or empty string logic if strict
                                                                                     // Controller used: if ($request->has('status') && $request->status !== '')
            if ($filters['status'] !== '') {
                $query->where('is_active', $filters['status'] === 'active');
            }
        }

        return $query;
    }

    public function getActiveHierarchicalUnits()
    {
        return OrgUnit::whereNull('parent_id')
            ->active()
            ->orderBy('seq')
            ->with(['activeChildren' => function ($q) {
                $q->orderBy('seq')->with(['activeChildren' => function ($qq) {
                    $qq->orderBy('seq')->with(['activeChildren' => function ($qqq) {
                        $qqq->orderBy('seq')->with(['activeChildren' => function ($qqqq) {
                            $qqqq->orderBy('seq');
                        }]);
                    }]);
                }]);
            }])
            ->get();
    }

    public function getAllUnits()
    {
        return OrgUnit::with(['parent', 'successor'])->orderBy('level')->orderBy('seq')->get();
    }

    public function getOrgUnitById(int $id): ?OrgUnit
    {
        return OrgUnit::with(['parent', 'personils.user'])->find($id);
    }

    // Helper used in Create/Edit View for flat hierarchical list
    public function getHierarchicalList($parentId = null, $prefix = '')
    {
        $units = OrgUnit::where('parent_id', $parentId)
            ->orderBy('seq')
            ->orderBy('name')
            ->get();

        $result = [];
        foreach ($units as $unit) {
            // We return objects but modify name for display?
            // Better to clone or specific DTO.
            // Eloquent models are objects. Modifying 'name' property affects the instance.
            // Only valid for this request scope. Safe enough for view.
            $unit->name_display = $prefix . ' ' . $unit->name;
            $result[]           = $unit;
            $result             = array_merge($result, $this->getHierarchicalList($unit->orgunit_id, $prefix . '--'));
        }
        return $result;
    }

    public function createOrgUnit(array $data): OrgUnit
    {
        return DB::transaction(function () use ($data) {
            // Calculate Level
            if (! empty($data['parent_id'])) {
                $parent        = OrgUnit::find($data['parent_id']);
                $data['level'] = $parent ? $parent->level + 1 : 1;
            } else {
                $data['level'] = 1;
            }

            // Default Seq
            if (empty($data['seq'])) {
                if (! empty($data['parent_id'])) {
                    $data['seq'] = OrgUnit::where('parent_id', $data['parent_id'])->max('seq') + 1;
                } else {
                    $data['seq'] = OrgUnit::whereNull('parent_id')->max('seq') + 1;
                }
            }

            $orgUnit = OrgUnit::create($data);

            logActivity(
                'org_unit_management',
                "Membuat unit organisasi baru: {$orgUnit->name}"
            );

            return $orgUnit;
        });
    }

    public function updateOrgUnit(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $orgUnit = $this->findOrFail($id);
            $oldName = $orgUnit->name;

            if (isset($data['parent_id']) && $data['parent_id'] == $id) {
                throw new \Exception('Cannot be parent of itself.');
            }

            // Recalculate Level if parent changed
            if (isset($data['parent_id']) && $data['parent_id'] != $orgUnit->parent_id) {
                if (! empty($data['parent_id'])) {
                    $parent        = OrgUnit::find($data['parent_id']);
                    $data['level'] = $parent ? $parent->level + 1 : 1;
                } else {
                    $data['level'] = 1;
                }
                // TODO: Update children levels recursively?
                // Skipped in original controller too.
            }

            $orgUnit->update($data);

            logActivity(
                'org_unit_management',
                "Memperbarui unit organisasi: {$oldName}" . ($oldName !== $orgUnit->name ? " menjadi {$orgUnit->name}" : "")
            );

            return true;
        });
    }

    public function deleteOrgUnit(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $orgUnit = $this->findOrFail($id);
            $name    = $orgUnit->name;

            if ($orgUnit->children()->exists()) {
                throw new \Exception('Unit tidak bisa dihapus karena memiliki sub-unit. Pindahkan atau hapus sub-unit terlebih dahulu.');
            }

            $orgUnit->delete();

            logActivity(
                'org_unit_management',
                "Menghapus unit organisasi: {$name}"
            );

            return true;
        });
    }

    public function toggleStatus(int $id): OrgUnit
    {
        return DB::transaction(function () use ($id) {
            $orgUnit            = $this->findOrFail($id);
            $orgUnit->is_active = ! $orgUnit->is_active;
            $orgUnit->save();

            logActivity(
                'org_unit_management',
                "Mengubah status unit organisasi: {$orgUnit->name} menjadi " . ($orgUnit->is_active ? 'Active' : 'Inactive')
            );

            return $orgUnit;
        });
    }

    public function setAuditee(int $id, ?int $userId): bool
    {
        return DB::transaction(function () use ($id, $userId) {
            $orgUnit                  = $this->findOrFail($id);
            $orgUnit->auditee_user_id = $userId;
            $orgUnit->save();

            logActivity(
                'org_unit_management',
                "Set Auditee untuk unit: {$orgUnit->name}"
            );

            return true;
        });
    }

    public function reorderUnits(array $hierarchy)
    {
        return DB::transaction(function () use ($hierarchy) {
            $this->updateHierarchy($hierarchy);
            return true;
        });
    }

    protected function updateHierarchy($items, $parentId = null, $level = 1)
    {
        foreach ($items as $index => $item) {
            $orgUnit = OrgUnit::find($item['id']);
            if ($orgUnit) {
                $orgUnit->parent_id = $parentId;
                $orgUnit->seq       = $index + 1;
                $orgUnit->level     = $level;
                $orgUnit->save();

                if (isset($item['children']) && is_array($item['children'])) {
                    $this->updateHierarchy($item['children'], $orgUnit->orgunit_id, $level + 1);
                }
            }
        }
    }

    protected function findOrFail(int $id): OrgUnit
    {
        $model = OrgUnit::find($id);
        if (! $model) {
            throw new \Exception("Layout Unit dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }

    public function getNextSeq(?int $parentId = null): int
    {
        if ($parentId) {
            return OrgUnit::where('parent_id', $parentId)->max('seq') + 1;
        } else {
            return OrgUnit::whereNull('parent_id')->max('seq') + 1;
        }
    }
}
