<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\Mahasiswa;
use App\Services\Shared\StrukturOrganisasiService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MahasiswaController extends Controller
{
    protected $StrukturOrganisasiService;

    public function __construct(StrukturOrganisasiService $StrukturOrganisasiService)
    {
        $this->StrukturOrganisasiService = $StrukturOrganisasiService;
    }
    public function index()
    {
        return view('pages.lab.mahasiswa.index');
    }

    public function paginate(Request $request)
    {
        $query = Mahasiswa::with(['user.roles', 'prodi']);

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
            ->addColumn('prodi_nama', function ($row) {
                return $row->prodi->name ?? '-';
            })
            ->addColumn('action', function ($row) {
                $encryptedId = $row->hashid;
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('lab.mahasiswa.edit-modal.show', $encryptedId),
                    'editModal' => true,
                    'viewUrl'   => route('lab.mahasiswa.show', $encryptedId),
                    'deleteUrl' => route('lab.mahasiswa.destroy', $encryptedId),
                ])->render();
            })
            ->rawColumns(['action', 'user_info'])
            ->make(true);
    }

    public function show(Mahasiswa $mahasiswa)
    {
        return view('pages.lab.mahasiswa.show', compact('mahasiswa'));
    }

    public function create()
    {
        $prodiList = $this->StrukturOrganisasiService
            ->getFilteredQuery(['type' => 'Prodi'])
            ->orderBy('name')
            ->get();
        return view('pages.lab.mahasiswa.create', compact('prodiList'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $prodiList = $this->StrukturOrganisasiService
            ->getFilteredQuery(['type' => 'Prodi'])
            ->orderBy('name')
            ->get();
        return view('pages.lab.mahasiswa.edit', compact('mahasiswa', 'prodiList'));
    }

    public function editModal(Mahasiswa $mahasiswa)
    {
        $prodiList = $this->StrukturOrganisasiService
            ->getFilteredQuery(['type' => 'Prodi'])
            ->orderBy('name')
            ->get();
        return view('pages.lab.mahasiswa.edit-ajax', compact('mahasiswa', 'prodiList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim'        => 'required|string|max:50|unique:mahasiswa,nim',
            'nama'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:mahasiswa,email',
            'orgunit_id' => 'required|exists:struktur_organisasi,orgunit_id',
        ]);

        $data               = $request->all();
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        Mahasiswa::create($data);

        return jsonSuccess('Data Mahasiswa berhasil ditambahkan.', route('lab.mahasiswa.index'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nim'        => 'required|string|max:50|unique:mahasiswa,nim,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
            'nama'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:mahasiswa,email,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
            'orgunit_id' => 'required|exists:struktur_organisasi,orgunit_id',
        ]);

        $data               = $request->all();
        $data['updated_by'] = auth()->id();

        $mahasiswa->update($data);

        return jsonSuccess('Data Mahasiswa berhasil diperbarui.', route('lab.mahasiswa.index'));
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        try {
            $mahasiswa->delete();
            return jsonSuccess('Data Mahasiswa berhasil dihapus.', route('lab.mahasiswa.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
