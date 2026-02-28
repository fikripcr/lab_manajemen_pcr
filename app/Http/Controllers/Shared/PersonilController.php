<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\PersonilRequest;
use App\Models\Shared\Personil;
use App\Models\Shared\StrukturOrganisasi;
use App\Services\Shared\PersonilService;
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
                    return "<span title=\"{$roles}\">{$row->user->name}</span>";
                }
                return '<span class="text-muted fst-italic">Belum terkoneksi</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('shared.personil.edit-modal.show', $row->encrypted_personil_id),
                    'editModal' => true,
                    'viewUrl'   => route('shared.personil.show', $row->encrypted_personil_id),
                    'deleteUrl' => route('shared.personil.destroy', $row->encrypted_personil_id),
                    'extraActions' => !$row->user_id ? [
                        [
                            'icon' => 'ti ti-user-plus',
                            'text' => 'Generate Data User',
                            'class' => 'dropdown-item generate-user',
                            'dataUrl' => route('shared.personil.generate-user', $row->encrypted_personil_id),
                        ],
                    ] : [],
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
        return view('pages.shared.personil.create-edit-ajax', compact('units'));
    }

    public function store(PersonilRequest $request)
    {
        $this->personilService->createPersonil($request->validated());
        return jsonSuccess('Data Personil berhasil ditambahkan.', route('shared.personil.index'));
    }

    public function editModal(Personil $personil)
    {
        $units = StrukturOrganisasi::orderBy('name')->get();
        return view('pages.shared.personil.edit-ajax', compact('personil', 'units'));
    }

    public function edit(Personil $personil)
    {
        $units = StrukturOrganisasi::orderBy('name')->get();
        return view('pages.shared.personil.create-edit-ajax', compact('personil', 'units'));
    }

    public function update(PersonilRequest $request, Personil $personil)
    {
        $this->personilService->updatePersonil($personil->personil_id, $request->validated());
        return jsonSuccess('Data Personil berhasil diperbarui.', route('shared.personil.index'));
    }

    public function destroy(Personil $personil)
    {
        $this->personilService->deletePersonil($personil->personil_id);
        return jsonSuccess('Data Personil berhasil dihapus.');
    }

    /**
     * Generate user account for personil without user.
     */
    public function generateUser(Personil $personil)
    {
        if ($personil->user) {
            return jsonError('Personil ini sudah memiliki user.');
        }

        // Generate email from nama if not exists
        $email = $personil->email ?? $this->generateEmailFromNama($personil->nama);

        // Check if email already exists
        if (\App\Models\User::where('email', $email)->exists()) {
            return jsonError("Email {$email} sudah terdaftar. Silakan gunakan email lain.");
        }

        // Generate password default
        $password = 'password123';

        // Create user
        $user = \App\Models\User::create([
            'name'              => $personil->nama,
            'email'             => $email,
            'password'          => \Illuminate\Support\Facades\Hash::make($password),
            'email_verified_at' => now(),
            'created_by'        => auth()->id() ?? 'system',
        ]);

        // Link personil to user
        $personil->update(['user_id' => $user->id]);

        // Assign default role based on posisi
        $role = $this->determineRoleFromPosisi($personil->posisi);
        $user->assignRole($role);

        return jsonSuccess(
            "User berhasil dibuat untuk {$personil->nama}.<br>Email: {$email}<br>Password: {$password}<br>Role: {$role}",
            route('shared.personil.index')
        );
    }

    /**
     * Generate email from nama.
     */
    private function generateEmailFromNama($nama)
    {
        $base = \Str::slug($nama);
        $email = "{$base}@pcr.ac.id";
        $counter = 1;
        
        while (\App\Models\User::where('email', $email)->exists()) {
            $email = "{$base}{$counter}@pcr.ac.id";
            $counter++;
        }
        
        return $email;
    }

    /**
     * Determine role from posisi.
     */
    private function determineRoleFromPosisi($posisi)
    {
        $posisi = strtolower($posisi ?? '');
        
        if (str_contains($posisi, 'security')) {
            return 'security';
        } elseif (str_contains($posisi, 'cleaning')) {
            return 'cleaning_service';
        } elseif (str_contains($posisi, 'driver')) {
            return 'driver';
        }
        
        return 'admin'; // Default role
    }
}
