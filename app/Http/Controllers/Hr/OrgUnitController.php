<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Services\Hr\OrgUnitService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrgUnitController extends Controller
{
    protected $orgUnitService;

    public function __construct(OrgUnitService $orgUnitService)
    {
        $this->orgUnitService = $orgUnitService;
    }

    public function index()
    {
        $rootUnits = $this->orgUnitService->getActiveHierarchicalUnits();
        $allUnits  = $this->orgUnitService->getAllUnits();
        $types     = $this->orgUnitService->getTypes();

        return view('pages.hr.org-units.index', compact('rootUnits', 'allUnits', 'types'));
    }

    public function show($id)
    {
        $orgUnit = $this->orgUnitService->getOrgUnitById($id);
        if (! $orgUnit) {
            abort(404);
        }

        return view('pages.hr.org-units.detail', compact('orgUnit'));
    }

    public function data(Request $request)
    {
        $query = $this->orgUnitService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                $badge = '<span class="badge bg-secondary-lt ms-2">' . e($row->type) . '</span>';
                return e($row->name) . $badge;
            })
            ->editColumn('parent_id', function ($row) {
                return $row->parent ? $row->parent->name : '-';
            })
            ->addColumn('status', function ($row) {
                $checked = $row->is_active ? 'checked' : '';
                return '<label class="form-check form-switch mb-0">
                    <input type="checkbox" class="form-check-input toggle-status" data-id="' . $row->org_unit_id . '" ' . $checked . '>
                </label>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('hr.org-units.edit', ['org_unit' => $row->hashid]),
                    'editModal' => true,
                    'deleteUrl' => route('hr.org-units.destroy', ['org_unit' => $row->hashid]),
                ])->render();
            })
            ->rawColumns(['name', 'status', 'action'])
            ->make(true);
    }

    public function toggleStatus($id)
    {
        try {
            $orgUnit = $this->orgUnitService->toggleStatus($id);
            return jsonSuccess('Status updated.', null, ['is_active' => $orgUnit->is_active]);
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {
        $parentId = $request->query('parent_id');
        $parent   = $parentId ? $this->orgUnitService->getOrgUnitById($parentId) : null;
        $units    = $this->orgUnitService->getHierarchicalList();
        $types    = $this->orgUnitService->getTypes();

        return view('pages.hr.org-units.create', compact('parent', 'units', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'type'      => 'required|string',
            'parent_id' => 'nullable|exists:hr_org_unit,org_unit_id',
            'code'      => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $data              = $request->only(['name', 'type', 'parent_id', 'code', 'description']);
            $data['is_active'] = $request->boolean('is_active', true);

            $this->orgUnitService->createOrgUnit($data);
            return jsonSuccess('OrgUnit created.', route('hr.org-units.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(OrgUnit $org_unit)
    {
        $orgUnit  = $org_unit;
        $allUnits = $this->orgUnitService->getHierarchicalList();
        $units    = collect($allUnits)->filter(fn($u) => $u->org_unit_id != $orgUnit->org_unit_id);
        $types    = $this->orgUnitService->getTypes();

        return view('pages.hr.org-units.edit', compact('orgUnit', 'units', 'types'));
    }

    public function update(Request $request, OrgUnit $org_unit)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'type'      => 'required|string',
            'parent_id' => 'nullable|exists:hr_org_unit,org_unit_id',
            'code'      => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $data              = $request->only(['name', 'type', 'parent_id', 'code', 'description']);
            $data['is_active'] = $request->boolean('is_active', true);

            $this->orgUnitService->updateOrgUnit($org_unit->org_unit_id, $data);
            return jsonSuccess('OrgUnit updated.', route('hr.org-units.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(OrgUnit $org_unit)
    {
        try {
            $this->orgUnitService->deleteOrgUnit($org_unit->org_unit_id);
            return jsonSuccess('OrgUnit deleted.', route('hr.org-units.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function reorder(Request $request)
    {
        try {
            $hierarchy = $request->input('hierarchy', []);
            $this->orgUnitService->reorderUnits($hierarchy);
            return jsonSuccess('Hierarchy updated.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
