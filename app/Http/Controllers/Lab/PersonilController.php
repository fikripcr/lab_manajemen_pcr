<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\PersonilRequest;
use App\Models\Lab\Personil;
use App\Services\Lab\PersonilService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PersonilController extends Controller
{
    public function __construct(protected PersonilService $personilService)
    {}

    public function index()
    {
        return view('pages.lab.personil.index');
    }

    public function data(Request $request)
    {
        $query = $this->personilService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return formatTanggalIndo($row->created_at);
            })
            ->addColumn('user_info', function ($row) {
                if ($row->user) {
                    $roles = $row->user->roles->pluck('name')->implode(', ');
                    return "{$row->user->name} ({$roles})";
                }
                return 'Belum terkoneksi';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('lab.personil.edit', $row->encrypted_personil_id),
                    'editModal' => true,
                    'viewUrl'   => route('lab.personil.show', $row->encrypted_personil_id),
                    'deleteUrl' => route('lab.personil.destroy', $row->encrypted_personil_id),
                ])->render();
            })
            ->rawColumns(['action', 'user_info'])
            ->make(true);
    }

    public function show(Personil $personil)
    {
        return view('pages.lab.personil.show', compact('personil'));
    }

    public function create()
    {
        $personil = new Personil();
        return view('pages.lab.personil.create-edit-ajax', compact('personil'));
    }

    public function store(PersonilRequest $request)
    {
        $data = $request->validated();

        $this->personilService->createPersonil($data);
        return jsonSuccess('Data Personil berhasil ditambahkan.', route('lab.personil.index'));
    }

    public function edit(Personil $personil)
    {
        return view('pages.lab.personil.create-edit-ajax', compact('personil'));
    }

    public function update(PersonilRequest $request, Personil $personil)
    {
        $data = $request->validated();

        $this->personilService->updatePersonil($personil, $data);
        return jsonSuccess('Data Personil berhasil diperbarui.', route('lab.personil.index'));
    }

    public function destroy(Personil $personil)
    {
        $this->personilService->deletePersonil($personil);
        return jsonSuccess('Data Personil berhasil dihapus.', route('lab.personil.index'));
    }
}
