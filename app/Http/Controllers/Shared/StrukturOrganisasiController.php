<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\ReorderRequest;
use App\Http\Requests\Shared\SetAuditeeRequest;
use App\Http\Requests\Shared\StrukturOrganisasiRequest;
use App\Services\Shared\StrukturOrganisasiService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StrukturOrganisasiController extends Controller
{
    public function __construct(protected StrukturOrganisasiService $strukturOrganisasiService)
    {}

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
                        'editUrl'   => route('shared.struktur-organisasi.edit', $row->encrypted_org_unit_id),
                        'deleteUrl' => route('shared.struktur-organisasi.destroy', $row->encrypted_org_unit_id),
                        'showUrl'   => route('shared.struktur-organisasi.show', $row->encrypted_org_unit_id),
                        'editModal' => true,
                    ])->render();
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }

        $types = $this->strukturOrganisasiService->getTypes();
        return view('pages.shared.struktur-organisasi.index', compact('types'));
    }

    public function tree()
    {
        $orgUnits = $this->strukturOrganisasiService->getActiveHierarchicalUnits();
        return view('pages.shared.struktur-organisasi.tree', compact('orgUnits'));
    }

    public function create()
    {
        $parents = $this->strukturOrganisasiService->getHierarchicalList();
        $types   = $this->strukturOrganisasiService->getTypes();
        return view('pages.shared.struktur-organisasi.create-edit-ajax', compact('parents', 'types'));
    }

    public function store(StrukturOrganisasiRequest $request)
    {
        try {
            $this->strukturOrganisasiService->createOrgUnit($request->validated());
            return jsonSuccess('Unit Organisasi berhasil ditambahkan.', route('shared.struktur-organisasi.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menambahkan unit organisasi: ' . $e->getMessage());
        }
    }

    public function show(StrukturOrganisasi $orgUnit)
    {
        $orgUnit->load(['parent', 'children', 'personils.user', 'successor', 'auditee']);
        return view('pages.shared.struktur-organisasi.show', compact('orgUnit'));
    }

    public function edit(StrukturOrganisasi $orgUnit)
    {
        $parents = $this->strukturOrganisasiService->getHierarchicalList();
        $types   = $this->strukturOrganisasiService->getTypes();

        return view('pages.shared.struktur-organisasi.create-edit-ajax', compact('orgUnit', 'parents', 'types'));
    }

    public function update(StrukturOrganisasiRequest $request, StrukturOrganisasi $orgUnit)
    {
        try {
            $this->strukturOrganisasiService->updateOrgUnit($orgUnit, $request->validated());
            return jsonSuccess('Unit Organisasi berhasil diperbarui.', route('shared.struktur-organisasi.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui unit organisasi: ' . $e->getMessage());
        }
    }

    public function destroy(StrukturOrganisasi $orgUnit)
    {
        try {
            $this->strukturOrganisasiService->deleteOrgUnit($orgUnit);
            return jsonSuccess('Unit Organisasi berhasil dihapus.', route('shared.struktur-organisasi.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus unit organisasi: ' . $e->getMessage());
        }
    }

    public function reorder(ReorderRequest $request)
    {
        try {
            $hierarchy = $request->validated()['hierarchy'] ?? [];
            if ($hierarchy) {
                $this->strukturOrganisasiService->reorderUnits($hierarchy);
                return jsonSuccess('Urutan unit organisasi berhasil diperbarui.');
            }
            return jsonError('Data urutan tidak valid.');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui urutan: ' . $e->getMessage());
        }
    }

    public function toggleStatus(StrukturOrganisasi $orgUnit)
    {
        try {
            $unit = $this->strukturOrganisasiService->toggleStatus($orgUnit);
            return jsonSuccess('Status berhasil diubah.', null, ['is_active' => $unit->is_active]);
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal mengubah status: ' . $e->getMessage());
        }
    }

    public function setAuditee(SetAuditeeRequest $request, StrukturOrganisasi $orgUnit)
    {
        try {
            $validated = $request->validated();
            $this->strukturOrganisasiService->setAuditee($orgUnit, $validated['auditee_user_id']);
            return jsonSuccess('Auditee berhasil diset.');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menyetel auditee: ' . $e->getMessage());
        }
    }
}
