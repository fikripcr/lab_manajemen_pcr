<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\OrgUnitRequest;
use App\Models\Hr\OrgUnit as HrOrgUnit;
use App\Services\Hr\OrgUnitService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrgUnitController extends Controller
{
    protected $OrgUnitService;

    public function __construct(OrgUnitService $OrgUnitService)
    {
        $this->OrgUnitService = $OrgUnitService;
    }

    public function index()
    {
        $rootUnits = $this->OrgUnitService->getActiveHierarchicalUnits();
        $allUnits  = $this->OrgUnitService->getAllUnits();
        $types     = $this->OrgUnitService->getTypes();

        return view('pages.hr.org-units.index', compact('rootUnits', 'allUnits', 'types'));
    }

    public function show($id)
    {
        $orgUnit = $this->OrgUnitService->getOrgUnitById($id);
        if (! $orgUnit) {
            abort(404);
        }

        return view('pages.hr.org-units.detail', compact('orgUnit'));
    }

    public function data(Request $request)
    {
        $query = $this->OrgUnitService->getFilteredQuery($request->all());

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
            $orgUnit = $this->OrgUnitService->toggleStatus($id);
            return jsonSuccess('Status updated.', null, ['is_active' => $orgUnit->is_active]);
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {
        $parentId = $request->query('parent_id');
        $parent   = $parentId ? $this->OrgUnitService->getOrgUnitById($parentId) : null;
        $units    = $this->OrgUnitService->getHierarchicalList();
        $types    = $this->OrgUnitService->getTypes();

        return view('pages.hr.org-units.create', compact('parent', 'units', 'types'));
    }

    public function store(OrgUnitRequest $request)
    {
        try {
            $data              = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);

            $this->OrgUnitService->createOrgUnit($data);
            return jsonSuccess('OrgUnit created.', route('hr.org-units.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(HrOrgUnit $org_unit)
    {
        $orgUnit  = $org_unit;
        $allUnits = $this->OrgUnitService->getHierarchicalList();
        $units    = collect($allUnits)->filter(fn($u) => $u->org_unit_id != $orgUnit->org_unit_id);
        $types    = $this->OrgUnitService->getTypes();

        return view('pages.hr.org-units.edit', compact('orgUnit', 'units', 'types'));
    }

    public function update(OrgUnitRequest $request, HrOrgUnit $org_unit)
    {
        try {
            $data              = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);

            $this->OrgUnitService->updateOrgUnit($org_unit->org_unit_id, $data);
            return jsonSuccess('OrgUnit updated.', route('hr.org-units.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(HrOrgUnit $org_unit)
    {
        try {
            $this->OrgUnitService->deleteOrgUnit($org_unit->org_unit_id);
            return jsonSuccess('OrgUnit deleted.', route('hr.org-units.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function reorder(Request $request)
    {
        try {
            $hierarchy = $request->input('hierarchy', []);
            $this->OrgUnitService->reorderUnits($hierarchy);
            return jsonSuccess('Hierarchy updated.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
