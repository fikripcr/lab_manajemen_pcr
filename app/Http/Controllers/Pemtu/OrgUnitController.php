<?php
namespace App\Http\Controllers\Pemtu;

use App\Http\Controllers\Controller;
use App\Models\Pemtu\OrgUnit;
use Illuminate\Http\Request;

class OrgUnitController extends Controller
{
    private static $UNIT_TYPES = [
        'Institusi',
        'Direktorat',
        'Bagian',
        'Jurusan',
        'Prodi',
        'Laboratorium',
        'Unit',
        'Senat',
        'Sekretariat',
        'Pimpinan',
    ];
    public function index()
    {
        // Eager load up to 5 levels deep
        $rootUnits = OrgUnit::whereNull('parent_id')
            ->orderBy('seq')
            ->with(['children' => function ($q) {
                $q->orderBy('seq')->with(['children' => function ($qq) {
                    $qq->orderBy('seq')->with(['children' => function ($qqq) {
                        $qqq->orderBy('seq')->with(['children' => function ($qqqq) {
                            $qqqq->orderBy('seq');
                        }]);
                    }]);
                }]);
            }])
            ->get();
        return view('pages.pemtu.org-units.index', compact('rootUnits'));
    }

    public function show($id)
    {
        $orgUnit = OrgUnit::with(['parent', 'personils.user'])->findOrFail($id);
        return view('pages.pemtu.org-units.detail', compact('orgUnit'));
    }

    private function getHierarchicalUnits($parentId = null, $prefix = '')
    {
        $units = OrgUnit::where('parent_id', $parentId)
            ->orderBy('seq')
            ->orderBy('name')
            ->get();

        $result = [];
        foreach ($units as $unit) {
            $unit->name = $prefix . ' ' . $unit->name;
            $result[]   = $unit;
            $result     = array_merge($result, $this->getHierarchicalUnits($unit->orgunit_id, $prefix . '--'));
        }
        return $result;
    }

    public function create(Request $request)
    {
        $parentId = $request->query('parent_id');
        $parent   = $parentId ? OrgUnit::find($parentId) : null;
        // Use hierarchical order
        $units = $this->getHierarchicalUnits();

        // Auto-suggest seq
        $suggestedSeq = 1;
        if ($parent) {
            $suggestedSeq = OrgUnit::where('parent_id', $parent->orgunit_id)->max('seq') + 1;
        } else {
            $suggestedSeq = OrgUnit::whereNull('parent_id')->max('seq') + 1;
        }

        $types = self::$UNIT_TYPES;
        return view('pages.pemtu.org-units.create', compact('parent', 'units', 'suggestedSeq', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:org_unit,orgunit_id',
            'type'      => 'nullable|string|max:100',
            'code'      => 'nullable|string|max:50',
            'seq'       => 'nullable|integer',
        ]);

        $data = $request->all();

        // Calculate Level
        if (! empty($data['parent_id'])) {
            $parent        = OrgUnit::find($data['parent_id']);
            $data['level'] = $parent ? $parent->level + 1 : 1;
        } else {
            $data['level'] = 1;
        }

        // Default Seq if empty
        if (empty($data['seq'])) {
            if (! empty($data['parent_id'])) {
                $data['seq'] = OrgUnit::where('parent_id', $data['parent_id'])->max('seq') + 1;
            } else {
                $data['seq'] = OrgUnit::whereNull('parent_id')->max('seq') + 1;
            }
        }

        OrgUnit::create($data);

        return response()->json([
            'message'  => 'OrgUnit created successfully.',
            'redirect' => route('pemtu.org-units.index'),
        ]);
    }

    public function edit($id)
    {
        $orgUnit = OrgUnit::findOrFail($id);
        // Exclude self and children from parent options to avoid cycles (simple exclusion of self for now)
        // ideally we filter detailed check, but hierarchical list makes it visual.
        $allUnits = $this->getHierarchicalUnits();
        $units    = collect($allUnits)->filter(function ($u) use ($id) {
            return $u->orgunit_id != $id;
        });

        $types = self::$UNIT_TYPES;
        return view('pages.pemtu.org-units.edit', compact('orgUnit', 'units', 'types'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:org_unit,orgunit_id',
            'type'      => 'nullable|string|max:100',
            'code'      => 'nullable|string|max:50',
            'seq'       => 'nullable|integer',
        ]);

        $orgUnit = OrgUnit::findOrFail($id);

        if ($request->parent_id == $id) {
            return response()->json(['message' => 'Cannot be parent of itself.'], 422);
        }

        $data = $request->all();

        // Recalculate Level if parent changed
        if ($data['parent_id'] != $orgUnit->parent_id) {
            if (! empty($data['parent_id'])) {
                $parent        = OrgUnit::find($data['parent_id']);
                $data['level'] = $parent ? $parent->level + 1 : 1;
            } else {
                $data['level'] = 1;
            }
            // Note: Children levels should technically be updated recursively too.
            // For now, let's assume one-level update.
        }

        $orgUnit->update($data);

        return response()->json([
            'message'  => 'OrgUnit updated successfully.',
            'redirect' => route('pemtu.org-units.index'),
        ]);
    }

    public function destroy($id)
    {
        $orgUnit = OrgUnit::findOrFail($id);

        if ($orgUnit->children()->exists()) {
            return response()->json([
                'message' => 'Cannot delete unit with children. Move or delete children first.',
            ], 422);
        }

        $orgUnit->delete();

        return response()->json([
            'success'  => true,
            'message'  => 'OrgUnit deleted successfully.',
            'redirect' => route('pemtu.org-units.index'),
        ]);
    }
    public function reorder(Request $request)
    {
        $hierarchy = $request->input('hierarchy');

        \DB::transaction(function () use ($hierarchy) {
            $this->updateHierarchy($hierarchy);
        });

        return response()->json(['message' => 'Hierarchy updated successfully.']);
    }

    private function updateHierarchy($items, $parentId = null, $level = 1)
    {
        foreach ($items as $index => $item) {
            $orgUnit = OrgUnit::find($item['id']);
            if ($orgUnit) {
                // Update Parent, Seq, Level
                $orgUnit->parent_id = $parentId;
                $orgUnit->seq       = $index + 1;
                $orgUnit->level     = $level;
                $orgUnit->save();

                // Recursively update children
                if (isset($item['children']) && is_array($item['children'])) {
                    $this->updateHierarchy($item['children'], $orgUnit->orgunit_id, $level + 1);
                }
            }
        }
    }
}
