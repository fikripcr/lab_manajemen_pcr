<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\PersonilRequest;
use App\Models\Shared\StrukturOrganisasi;
use App\Services\Shared\PersonilService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PersonilController extends Controller
{
    public function __construct(protected PersonilService $personilService)
    {}

    public function index()
    {
        return view('pages.shared.personil.index');
    }

    public function paginate(Request $request)
    {
        $query = $this->personilService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('status_aktif', function ($row) {
                return $row->status_aktif
                    ? '<span class="badge bg-success text-white">Aktif</span>'
                    : '<span class="badge bg-secondary text-white">Non-Aktif</span>';
            })
            ->addColumn('user_info', function ($row) {
                if ($row->user) {
                    $roles = $row->user->roles->pluck('name')->implode(', ');
                    return "{$row->user->name} <br><small class='text-muted'>({$roles})</small>";
                }
                return '<small class="text-muted italic">Not Linked</small>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('shared.personil.edit-modal.show', $row->encrypted_personil_id),
                    'editModal' => true,
                    'viewUrl'   => route('shared.personil.show', $row->encrypted_personil_id),
                    'deleteUrl' => route('shared.personil.destroy', $row->encrypted_personil_id),
                ])->render();
            })
            ->rawColumns(['status_aktif', 'user_info', 'action'])
            ->make(true);
    }

    public function show(Personil $personil)
    {
        if (request()->ajax()) {
            return view('pages.shared.personil.show-ajax', compact('personil'));
        }

        return view('pages.shared.personil.show', compact('personil'));
    }

    public function create()
    {
        $units = StrukturOrganisasi::orderBy('name')->get();
        return view('pages.shared.personil.create', compact('units'));
    }

    public function store(PersonilRequest $request)
    {
        try {
            $this->personilService->createPersonil($request->validated());
            return jsonSuccess('Data Personil berhasil ditambahkan.', route('shared.personil.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError($e->getMessage(), 500);
        }
    }

    public function editModal(Personil $personil)
    {
        $units = StrukturOrganisasi::orderBy('name')->get();
        return view('pages.shared.personil.edit-ajax', compact('personil', 'units'));
    }

    public function edit(Personil $personil)
    {
        $units = StrukturOrganisasi::orderBy('name')->get();
        return view('pages.shared.personil.edit', compact('personil', 'units'));
    }

    public function update(PersonilRequest $request, Personil $personil)
    {
        try {
            $this->personilService->updatePersonil($personil->personil_id, $request->validated());
            return jsonSuccess('Data Personil berhasil diperbarui.', route('shared.personil.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(Personil $personil)
    {
        try {
            $this->personilService->deletePersonil($personil->personil_id);
            return jsonSuccess('Data Personil berhasil dihapus.');
        } catch (Exception $e) {
            logError($e);
            return jsonError($e->getMessage(), 500);
        }
    }
}
