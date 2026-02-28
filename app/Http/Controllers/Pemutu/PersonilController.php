<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PersonilImportRequest;
use App\Http\Requests\Pemutu\PersonilRequest;
use App\Models\Pemutu\OrgUnit;
use App\Models\Shared\Personil; // Import Service
use App\Services\Pemutu\PersonilService;
use Yajra\DataTables\DataTables;

class PersonilController extends Controller
{
    public function __construct(protected PersonilService $personilService)
    {}

    public function index()
    {
        return view('pages.pemutu.personils.index');
    }

    public function paginate()
    {
        $query = $this->personilService->getFilteredQuery();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('org_unit_id', function ($row) {
                return $row->orgUnit ? $row->orgUnit->name : '-';
            })
            ->editColumn('user_id', function ($row) {
                return $row->user ? '<span class="badge bg-success-lt">Linked</span>' : '<span class="badge bg-secondary-lt">Unlinked</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('pemutu.personils.edit', $row->encrypted_personil_id),
                    'editModal' => true,
                    'deleteUrl' => route('pemutu.personils.destroy', $row->encrypted_personil_id),
                ])->render();
            })
            ->rawColumns(['user_id', 'action'])
            ->make(true);
    }

    public function create()
    {
        $units = OrgUnit::orderBy('name')->get();
        return view('pages.pemutu.personils.create', compact('units'));
    }

    public function store(PersonilRequest $request)
    {
        $this->personilService->createPersonil($request->validated());

        logActivity('pemutu', "Menambah personil baru: " . ($request->nama ?? ''));

        return jsonSuccess('Personil created successfully.');
    }

    public function edit(Personil $personil)
    {
        $units = OrgUnit::orderBy('name')->get();
        return view('pages.pemutu.personils.edit', compact('personil', 'units'));
    }

    public function update(PersonilRequest $request, Personil $personil)
    {
        $this->personilService->updatePersonil($personil->personil_id, $request->validated());

        logActivity('pemutu', "Memperbarui data personil: {$personil->nama}");

        return jsonSuccess('Personil updated successfully.');
    }

    public function destroy(Personil $personil)
    {
        $personilName = $personil->nama;
        $this->personilService->deletePersonil($personil->personil_id);

        logActivity('pemutu', "Menghapus personil: {$personilName}");

        return jsonSuccess('Personil deleted successfully.');
    }

    public function import(PersonilImportRequest $request)
    {
        if ($request->isMethod('get')) {
            return view('pages.pemutu.personils.import');
        }

        $this->personilService->importPersonils($request->file('file'));

        logActivity('pemutu', "Mengimport data personil via Excel");

        return jsonSuccess('Personils imported successfully.', route('pemutu.personils.index'));
    }
}
