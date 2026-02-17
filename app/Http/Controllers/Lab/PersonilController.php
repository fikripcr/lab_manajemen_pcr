<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\Personil;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PersonilController extends Controller
{
    public function index()
    {
        return view('pages.lab.personil.index');
    }

    public function paginate(Request $request)
    {
        $query = Personil::with(['user.roles']);

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
                $encryptedId = $row->hashid;
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('lab.personil.edit-modal.show', $encryptedId),
                    'editModal' => true,
                    'viewUrl'   => route('lab.personil.show', $encryptedId),
                    'deleteUrl' => route('lab.personil.destroy', $encryptedId),
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
        return view('pages.lab.personil.create');
    }

    public function edit(Personil $personil)
    {
        return view('pages.lab.personil.edit', compact('personil'));
    }

    public function editModal(Personil $personil)
    {
        return view('pages.lab.personil.edit-ajax', compact('personil'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip'    => 'required|string|max:50|unique:personil,nip',
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:personil,email',
            'posisi' => 'required|string|max:255',
        ]);

        $data               = $request->all();
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        Personil::create($data);

        return jsonSuccess('Data Personil berhasil ditambahkan.', route('lab.personil.index'));
    }

    public function update(Request $request, Personil $personil)
    {
        $request->validate([
            'nip'    => 'required|string|max:50|unique:personil,nip,' . $personil->personil_id . ',personil_id',
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:personil,email,' . $personil->personil_id . ',personil_id',
            'posisi' => 'required|string|max:255',
        ]);

        $data               = $request->all();
        $data['updated_by'] = auth()->id();

        $personil->update($data);

        return jsonSuccess('Data Personil berhasil diperbarui.', route('lab.personil.index'));
    }

    public function destroy(Personil $personil)
    {
        try {
            $personil->delete();
            return jsonSuccess('Data Personil berhasil dihapus.', route('lab.personil.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
