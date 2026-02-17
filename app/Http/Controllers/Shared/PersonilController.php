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
    protected $PersonilService;

    public function __construct(PersonilService $PersonilService)
    {
        $this->PersonilService = $PersonilService;
    }

    public function index()
    {
        return view('pages.shared.personil.index');
    }

    public function paginate(Request $request)
    {
        $query = $this->PersonilService->getFilteredQuery($request->all());

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
                    'editUrl'   => route('shared.personil.edit-modal.show', $row->hashid),
                    'editModal' => true,
                    'viewUrl'   => route('shared.personil.show', $row->hashid),
                    'deleteUrl' => route('shared.personil.destroy', $row->hashid),
                ])->render();
            })
            ->rawColumns(['status_aktif', 'user_info', 'action'])
            ->make(true);
    }

    public function show($id)
    {
        $id       = decryptIdIfEncrypted($id);
        $personil = $this->PersonilService->getFilteredQuery()->findOrFail($id);

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
            $this->PersonilService->createPersonil($request->validated());
            return jsonSuccess('Data Personil berhasil ditambahkan.', route('shared.personil.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function editModal($id)
    {
        $id       = decryptIdIfEncrypted($id);
        $personil = $this->PersonilService->getFilteredQuery()->findOrFail($id);
        $units    = StrukturOrganisasi::orderBy('name')->get();
        return view('pages.shared.personil.edit-ajax', compact('personil', 'units'));
    }

    public function edit($id)
    {
        $id       = decryptIdIfEncrypted($id);
        $personil = $this->PersonilService->getFilteredQuery()->findOrFail($id);
        $units    = StrukturOrganisasi::orderBy('name')->get();
        return view('pages.shared.personil.edit', compact('personil', 'units'));
    }

    public function update(PersonilRequest $request, $id)
    {
        $id = decryptIdIfEncrypted($id);
        try {
            $this->PersonilService->updatePersonil($id, $request->validated());
            return jsonSuccess('Data Personil berhasil diperbarui.', route('shared.personil.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        $id = decryptIdIfEncrypted($id);
        try {
            $this->PersonilService->deletePersonil($id);
            return jsonSuccess('Data Personil berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
