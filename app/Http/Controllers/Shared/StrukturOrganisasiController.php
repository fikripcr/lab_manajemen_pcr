<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\StrukturOrganisasiRequest;
use App\Services\Shared\StrukturOrganisasiService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StrukturOrganisasiController extends Controller
{
    protected $strukturOrganisasiService;

    public function __construct(StrukturOrganisasiService $strukturOrganisasiService)
    {
        $this->strukturOrganisasiService = $strukturOrganisasiService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $filters = $request->only(['type', 'status']);
            $query   = $this->strukturOrganisasiService->getFilteredQuery($filters);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('parent_id', function ($row) {
                    return $row->parent->name ?? '-';
                })
                ->editColumn('is_active', function ($row) {
                    return $row->is_active ? '<span class="badge bg-success text-white">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    return view('components.tabler.datatables-actions', [
                        'editUrl'   => route('shared.struktur-organisasi.edit', $row->orgunit_id),
                        'deleteUrl' => route('shared.struktur-organisasi.destroy', $row->orgunit_id),
                        'showUrl'   => route('shared.struktur-organisasi.show', $row->orgunit_id),
                    ])->render();
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }

        $types = $this->strukturOrganisasiService->getTypes();
        return view('pages.shared.struktur-organisasi.index', compact('types'));
    }

    public function tree(Request $request)
    {
        $orgUnits = $this->strukturOrganisasiService->getActiveHierarchicalUnits();
        return view('pages.shared.struktur-organisasi.tree', compact('orgUnits'));
    }

    public function create()
    {
        $parents = $this->strukturOrganisasiService->getHierarchicalList();
        $types   = $this->strukturOrganisasiService->getTypes();
        return view('pages.shared.struktur-organisasi.create', compact('parents', 'types'));
    }

    public function store(StrukturOrganisasiRequest $request)
    {
        try {
            $this->strukturOrganisasiService->createOrgUnit($request->validated());

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Unit Organisasi berhasil ditambahkan.']);
            }

            return redirect()->route('shared.struktur-organisasi.index')
                ->with('success', 'Unit Organisasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $orgUnit = $this->strukturOrganisasiService->getOrgUnitById($id);
        return view('pages.shared.struktur-organisasi.show', compact('orgUnit'));
    }

    public function edit($id)
    {
        $orgUnit = $this->strukturOrganisasiService->getOrgUnitById($id);
        $parents = $this->strukturOrganisasiService->getHierarchicalList();
        $types   = $this->strukturOrganisasiService->getTypes();

        return view('pages.shared.struktur-organisasi.edit', compact('orgUnit', 'parents', 'types'));
    }

    public function update(StrukturOrganisasiRequest $request, $id)
    {
        try {
            $this->strukturOrganisasiService->updateOrgUnit($id, $request->validated());

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Unit Organisasi berhasil diperbarui.']);
            }

            return redirect()->route('shared.struktur-organisasi.index')
                ->with('success', 'Unit Organisasi berhasil diperbarui.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->strukturOrganisasiService->deleteOrgUnit($id);
            return redirect()->back()->with('success', 'Unit Organisasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reorder(Request $request)
    {
        $hierarchy = $request->input('hierarchy');

        if ($this->strukturOrganisasiService->reorderUnits($hierarchy)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 500);
    }

    public function toggleStatus($id)
    {
        try {
            $unit = $this->strukturOrganisasiService->toggleStatus($id);
            return response()->json(['success' => true, 'is_active' => $unit->is_active]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function setAuditee(Request $request, $id)
    {
        $validated = $request->validate([
            'auditee_user_id' => 'required|exists:users,id',
        ]);

        try {
            $this->strukturOrganisasiService->setAuditee($id, $validated['auditee_user_id']);
            return response()->json(['success' => true, 'message' => 'Auditee berhasil diset.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
