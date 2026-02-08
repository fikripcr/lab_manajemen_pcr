<?php
namespace App\Services\Hr;

use App\Models\Hr\OrgUnit;
use Illuminate\Support\Facades\DB;

class OrgUnitService
{
    public function getFilteredQuery(array $filters = [])
    {
        $query = OrgUnit::with(['parent'])->select('hr_org_unit.*');

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (array_key_exists('status', $filters) && $filters['status'] !== '') {
            $query->where('is_active', $filters['status'] === 'active');
        }

        return $query;
    }

    public function getActiveHierarchicalUnits()
    {
        return OrgUnit::whereNull('parent_id')
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->with(['children' => function ($q) {
                $q->active()->orderBy('sort_order')->orderBy('name')->with(['children' => function ($qq) {
                    $qq->active()->orderBy('sort_order')->orderBy('name')->with(['children' => function ($qqq) {
                        $qqq->active()->orderBy('sort_order')->orderBy('name');
                    }]);
                }]);
            }])
            ->get();
    }

    public function getAllUnits()
    {
        return OrgUnit::with(['parent'])->orderBy('level')->orderBy('name')->get();
    }

    public function getOrgUnitById(int $id): ?OrgUnit
    {
        return OrgUnit::with(['parent', 'children'])->find($id);
    }

    public function getHierarchicalList($parentId = null, $prefix = '')
    {
        $units = OrgUnit::where('parent_id', $parentId)
            ->orderBy('name')
            ->get();

        $result = [];
        foreach ($units as $unit) {
            $unit->name_display = $prefix . ' ' . $unit->name;
            $result[]           = $unit;
            $result             = array_merge($result, $this->getHierarchicalList($unit->org_unit_id, $prefix . '--'));
        }
        return $result;
    }

    public function createOrgUnit(array $data): OrgUnit
    {
        return DB::transaction(function () use ($data) {
            if (! empty($data['parent_id'])) {
                $parent        = OrgUnit::find($data['parent_id']);
                $data['level'] = $parent ? $parent->level + 1 : 1;
            } else {
                $data['level'] = 1;
            }

            return OrgUnit::create($data);
        });
    }

    public function updateOrgUnit(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $orgUnit = $this->findOrFail($id);

            if (isset($data['parent_id']) && $data['parent_id'] == $id) {
                throw new \Exception('Cannot be parent of itself.');
            }

            if (isset($data['parent_id']) && $data['parent_id'] != $orgUnit->parent_id) {
                if (! empty($data['parent_id'])) {
                    $parent        = OrgUnit::find($data['parent_id']);
                    $data['level'] = $parent ? $parent->level + 1 : 1;
                } else {
                    $data['level'] = 1;
                }
            }

            $orgUnit->update($data);
            return true;
        });
    }

    public function deleteOrgUnit(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $orgUnit = $this->findOrFail($id);

            if ($orgUnit->children()->exists()) {
                throw new \Exception('Unit tidak bisa dihapus karena memiliki sub-unit.');
            }

            $orgUnit->delete();
            return true;
        });
    }

    public function toggleStatus(int $id): OrgUnit
    {
        return DB::transaction(function () use ($id) {
            $orgUnit            = $this->findOrFail($id);
            $orgUnit->is_active = ! $orgUnit->is_active;
            $orgUnit->save();
            return $orgUnit;
        });
    }

    protected function findOrFail(int $id): OrgUnit
    {
        $model = OrgUnit::find($id);
        if (! $model) {
            throw new \Exception("OrgUnit dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }

    public function getTypes(): array
    {
        return [
            'departemen'         => 'Departemen',
            'prodi'              => 'Prodi',
            'unit'               => 'Unit',
            'jabatan_struktural' => 'Jabatan Struktural',
            'posisi'             => 'Posisi',
        ];
    }

    public function reorderUnits(array $hierarchy): bool
    {
        return DB::transaction(function () use ($hierarchy) {
            $this->updateHierarchy($hierarchy);
            return true;
        });
    }

    protected function updateHierarchy(array $items, $parentId = null, int $level = 1): void
    {
        $sortOrder = 0;
        foreach ($items as $item) {
            $orgUnit = OrgUnit::find($item['id']);
            if ($orgUnit) {
                $orgUnit->parent_id  = $parentId;
                $orgUnit->level      = $level;
                $orgUnit->sort_order = $sortOrder;
                $orgUnit->save();
                $sortOrder++;

                if (isset($item['children']) && is_array($item['children'])) {
                    $this->updateHierarchy($item['children'], $orgUnit->org_unit_id, $level + 1);
                }
            }
        }
    }
}
