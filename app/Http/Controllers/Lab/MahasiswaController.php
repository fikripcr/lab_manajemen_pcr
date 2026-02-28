<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\MahasiswaRequest;
use App\Models\Lab\Mahasiswa;
use App\Services\Lab\MahasiswaService;
use App\Services\Shared\StrukturOrganisasiService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MahasiswaController extends Controller
{
    public function __construct(
        protected MahasiswaService $mahasiswaService,
        protected StrukturOrganisasiService $strukturOrganisasiService
    ) {}

    public function index()
    {
        return view('pages.lab.mahasiswa.index');
    }

    public function paginate(Request $request)
    {
        $query = $this->mahasiswaService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return formatTanggalIndo($row->created_at);
            })
            ->addColumn('user_info', function ($row) {
                if ($row->user) {
                    $roles = $row->user->roles->pluck('name')->implode(', ');
                    return "<span title=\"{$roles}\">{$row->user->name}</span>";
                }
                return '<span class="text-muted fst-italic">Belum terkoneksi</span>';
            })
            ->addColumn('prodi_nama', function ($row) {
                return $row->prodi->name ?? '-';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('lab.mahasiswa.edit', $row->encrypted_mahasiswa_id),
                    'editModal' => true,
                    'viewUrl'   => route('lab.mahasiswa.show', $row->encrypted_mahasiswa_id),
                    'deleteUrl' => route('lab.mahasiswa.destroy', $row->encrypted_mahasiswa_id),
                    'extraActions' => !$row->user_id ? [
                        [
                            'icon' => 'ti ti-user-plus',
                            'text' => 'Generate Data User',
                            'class' => 'dropdown-item generate-user',
                            'dataUrl' => route('lab.mahasiswa.generate-user', $row->encrypted_mahasiswa_id),
                        ],
                    ] : [],
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
        $prodiList = $this->strukturOrganisasiService
            ->getFilteredQuery(['type' => 'Prodi'])
            ->orderBy('name')
            ->get();
        $mahasiswa = new Mahasiswa();
        return view('pages.lab.mahasiswa.create-edit-ajax', compact('prodiList', 'mahasiswa'));
    }

    public function store(MahasiswaRequest $request)
    {
        $this->mahasiswaService->createMahasiswa($request->validated());
        return jsonSuccess('Data Mahasiswa berhasil ditambahkan.', route('lab.mahasiswa.index'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $prodiList = $this->strukturOrganisasiService
            ->getFilteredQuery(['type' => 'Prodi'])
            ->orderBy('name')
            ->get();
        return view('pages.lab.mahasiswa.create-edit-ajax', compact('mahasiswa', 'prodiList'));
    }

    public function update(MahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $this->mahasiswaService->updateMahasiswa($mahasiswa, $request->validated());
        return jsonSuccess('Data Mahasiswa berhasil diperbarui.', route('lab.mahasiswa.index'));
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $this->mahasiswaService->deleteMahasiswa($mahasiswa);
        return jsonSuccess('Data Mahasiswa berhasil dihapus.', route('lab.mahasiswa.index'));
    }

    /**
     * Generate user account for mahasiswa without user.
     */
    public function generateUser(Mahasiswa $mahasiswa)
    {
        if ($mahasiswa->user) {
            return jsonError('Mahasiswa ini sudah memiliki user.');
        }

        // Generate password default
        $password = 'password123';

        // Create user
        $user = \App\Models\User::create([
            'name'              => $mahasiswa->nama,
            'email'             => $mahasiswa->email,
            'password'          => \Illuminate\Support\Facades\Hash::make($password),
            'email_verified_at' => now(),
            'created_by'        => auth()->id() ?? 'system',
        ]);

        // Link mahasiswa to user
        $mahasiswa->update(['user_id' => $user->id]);

        // Assign default role
        $user->assignRole('mahasiswa');

        return jsonSuccess(
            "User berhasil dibuat untuk {$mahasiswa->nama}.<br>Email: {$mahasiswa->email}<br>Password: {$password}",
            route('lab.mahasiswa.index')
        );
    }
}
