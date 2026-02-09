<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\OrgUnitRequest;
use App\Models\Pemutu\OrgUnit;
use App\Services\Pemutu\OrgUnitService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrgUnitController extends Controller
{
    protected $orgUnitService;

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

    public function __construct(OrgUnitService $orgUnitService)
    {
        $this->orgUnitService = $orgUnitService;
    }

    public function index()
    {
        // Eager load ONLY ACTIVE units up to 5 levels deep for Hierarchy List via Service
        $rootUnits = $this->orgUnitService->getActiveHierarchicalUnits();

        // Get ALL units for Manage tab (including inactive)
        $allUnits = $this->orgUnitService->getAllUnits();

        return view('pages.pemutu.org-units.index', compact('rootUnits', 'allUnits'));
    }

    public function show($id)
    {
        $orgUnit = $this->orgUnitService->getOrgUnitById($id);
        if (! $orgUnit) {
            abort(404);
        }

        return view('pages.pemutu.org-units.detail', compact('orgUnit'));
    }

    public function paginate(Request $request)
    {
        $query = $this->orgUnitService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                $name = e($row->name);
                if ($row->successor) {
                    $name .= '<br><small class="text-muted">→ ' . e($row->successor->name) . '</small>';
                }
                return $name;
            })
            ->editColumn('parent_id', function ($row) {
                return $row->parent ? $row->parent->name : '-';
            })
            ->addColumn('status', function ($row) {
                $checked = $row->is_active ? 'checked' : '';
                return '<label class="form-check form-switch mb-0">
                    <input type="checkbox" class="form-check-input toggle-status" data-id="' . $row->orgunit_id . '" ' . $checked . '>
                </label>';
            })
            ->addColumn('auditee', function ($row) {
                if ($row->auditee) {
                    return '<span class="badge bg-primary-lt">' . e($row->auditee->name) . '</span>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'       => route('pemutu.org-units.edit', $row->orgunit_id),
                    'editModal'     => true,
                    'deleteUrl'     => route('pemutu.org-units.destroy', $row->orgunit_id),
                    'customActions' => [
                        [
                            'url'        => '#',
                            'label'      => 'Set Auditee',
                            'icon'       => 'user-check',
                            'class'      => 'set-auditee-btn',
                            'attributes' => 'data-id="' . $row->orgunit_id . '" data-name="' . e($row->name) . '"',
                        ],
                    ],
                ])->render();
            })
            ->rawColumns(['name', 'status', 'auditee', 'action'])
            ->make(true);
    }

    public function toggleStatus($id)
    {
        try {
            $orgUnit = $this->orgUnitService->toggleStatus($id);

            return jsonSuccess('Status updated successfully.', null, ['is_active' => $orgUnit->is_active]);
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function setAuditee(Request $request, $id)
    {
        // Simple internal method validation, kept here or could be Separate Request
        // Given simplicity, inline is acceptable, but let's stick to standard response.
        $request->validate([
            'auditee_user_id' => 'nullable|exists:users,id',
        ]);

        try {
            $this->orgUnitService->setAuditee($id, $request->auditee_user_id);

            return jsonSuccess('Auditee berhasil diset.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {
        $parentId = $request->query('parent_id');
        $parent   = $parentId ? $this->orgUnitService->getOrgUnitById($parentId) : null;

        $units = $this->orgUnitService->getHierarchicalList();

        // Auto-suggest seq via service (helper or directly in logic?)
        // Controller logic handles suggestion for view.
        // Let's add helper to Service? `getNextSeq($parentId)`.
        $suggestedSeq = 1;

        // Assuming I added this helper to service. If not, I can query via model directly here OR add to service now.
        // I'll add `getNextSeq` to service if I forgot. But let's check my service code.
        // Ah, in write_to_file 367 I didn't verify if I added `getNextSeq`.
        // Let's assume I did or I will fix it.
        // Re-reading Step 367 output... I see `getNextSeq` is NOT there explicitly?
        // Wait, Step 367 output content check...
        // Ah, I missed adding `getNextSeq` in step 367 output display?
        // Let's assume I missed it.
        // I will use direct Model query here or add it.
        // Better to use Model query for View Prep to save time OR update Service.
        // I'll update Service quickly or just query.
        // Let's Query Model here directly as it's View Prep.
        // Or cleaner: `$this->orgUnitService->getNextSeq($parentId?->orgunit_id);`
        // I'll assume I update service.

        // Actually, looking at Step 367 again...
        // No `getNextSeq`.
        // I will add it via replace_file later if needed.
        // For now, I'll use Model directly here.
        if ($parent) {
            $suggestedSeq = OrgUnit::where('parent_id', $parent->orgunit_id)->max('seq') + 1;
        } else {
            $suggestedSeq = OrgUnit::whereNull('parent_id')->max('seq') + 1;
        }

        $types = self::$UNIT_TYPES;
        return view('pages.pemutu.org-units.create', compact('parent', 'units', 'suggestedSeq', 'types'));
    }

    public function store(OrgUnitRequest $request)
    {
        try {
            $this->orgUnitService->createOrgUnit($request->validated());

            return jsonSuccess('OrgUnit created successfully.', route('pemutu.org-units.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $orgUnit = $this->orgUnitService->getOrgUnitById($id);
        if (! $orgUnit) {
            abort(404);
        }

        $allUnits = $this->orgUnitService->getHierarchicalList();
        $units    = collect($allUnits)->filter(function ($u) use ($id) {
            return $u->orgunit_id != $id;
        });

        $types = self::$UNIT_TYPES;
        return view('pages.pemutu.org-units.edit', compact('orgUnit', 'units', 'types'));
    }

    public function update(OrgUnitRequest $request, $id)
    {
        try {
            $this->orgUnitService->updateOrgUnit($id, $request->validated());

            return jsonSuccess('OrgUnit updated successfully.', route('pemutu.org-units.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->orgUnitService->deleteOrgUnit($id);

            return jsonSuccess('OrgUnit deleted successfully.', route('pemutu.org-units.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function reorder(Request $request)
    {
        try {
            $hierarchy = $request->input('hierarchy');
            $this->orgUnitService->reorderUnits($hierarchy);

            return jsonSuccess('Hierarchy updated successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
