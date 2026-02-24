<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\PegawaiRequest;
use App\Http\Requests\Shared\SearchRequest;
use App\Models\Hr\OrgUnit;
use App\Models\Hr\StatusAktifitas;
use App\Models\Hr\StatusPegawai;
use App\Models\Shared\Pegawai;
use App\Services\Hr\PegawaiService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PegawaiController extends Controller
{
    public function __construct(protected PegawaiService $pegawaiService)
    {}

    /**
     * Search pegawai for Select2 AJAX.
     */
    public function select2Search(SearchRequest $request)
    {
        $search = $request->validated('q', '');
        $query  = Pegawai::with('latestDataDiri')
            ->whereHas('latestDataDiri', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get();

        $results = $query->map(function ($p) {
            return [
                'id'   => $p->pegawai_id,
                'text' => $p->nama . ' (' . ($p->nip ?? 'No NIP') . ')',
            ];
        });

        return response()->json(['results' => $results]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->pegawaiService->getFilteredQuery($request);
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('components.tabler.datatables-actions', [
                        'viewUrl'    => route('hr.pegawai.show', $row->hashid),
                        'editUrl'    => route('hr.pegawai.edit', $row->hashid),
                        'deleteUrl'  => route('hr.pegawai.destroy', $row->hashid),
                        'deleteName' => $row->nama,
                        'extraActions' => !$row->user_id ? [
                            [
                                'icon' => 'ti ti-user-plus',
                                'text' => 'Generate Data User',
                                'class' => 'dropdown-item generate-user',
                                'dataUrl' => route('hr.pegawai.generate-user', $row->hashid),
                            ],
                        ] : [],
                    ])->render();
                })
                ->addColumn('nama_lengkap', function ($row) {
                    return $row->nama;
                })
                ->addColumn('status_kepegawaian', function ($row) {
                    return $row->latestStatusPegawai?->statusPegawai?->nama ?? '-';
                })
                ->addColumn('email', function ($row) {
                    return $row->latestDataDiri?->email ?? '-';
                })
                ->addColumn('posisi', function ($row) {
                    return $row->latestDataDiri?->posisi?->name ?? '-';
                })
                ->addColumn('unit', function ($row) {
                    return $row->latestDataDiri?->departemen?->name ?? '-';
                })
            // Prodi column removed as it is redundant/broken
                ->addColumn('penyelia', function ($row) {
                    $atasan1 = $row->atasanSatu?->nama ?? null;
                    $atasan2 = $row->atasanDua?->nama ?? null;

                    if (! $atasan1 && ! $atasan2) {
                        return '-';
                    }

                    $html = '';
                    if ($atasan1) {
                        $html .= '<div><small class="text-muted">1:</small> ' . $atasan1 . '</div>';
                    }

                    if ($atasan2) {
                        $html .= '<div><small class="text-muted">2:</small> ' . $atasan2 . '</div>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'penyelia'])
                ->make(true);
        }

        return view('pages.hr.data-diri.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statusPegawai   = StatusPegawai::where('is_active', 1)->get();
        $statusAktifitas = StatusAktifitas::where('is_active', 1)->get();

        return view('pages.hr.pegawai.create-edit', compact('statusPegawai', 'statusAktifitas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PegawaiRequest $request)
    {
        try {
            $this->pegawaiService->createPegawai($request->validated());
            return jsonSuccess('Pegawai berhasil ditambahkan', route('hr.pegawai.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pegawai $pegawai)
    {

        $pegawai->load([
            'latestDataDiri', 'historyDataDiri.approval',
            'keluarga.approval',
            'riwayatPendidikan.approval',
            'pengembanganDiri.approval',
            'latestStatusPegawai.statusPegawai',
            'latestJabatanFungsional.jabatanFungsional',
            'latestJabatanStruktural.orgUnit',
            'latestInpassing.golonganInpassing',
            'historyStatPegawai.statusPegawai',
            'historyStatPegawai.before',
            'historyStatPegawai.after',
            'historyStatAktifitas.statusAktifitas',
            'historyStatAktifitas.before',
            'historyStatAktifitas.after',
            'historyJabFungsional.jabatanFungsional',
            'historyJabStruktural.orgUnit',
            'historyInpassing.golonganInpassing',
            'historyInpassing.before',
            'historyInpassing.after',
        ]);

        // dd($pegawai)->toArray();

        // Prepare pending changes if any
        $pendingChange = $pegawai->historyDataDiri
            ->where('latest_riwayatapproval_id', '!=', null)
            ->where('approval.status', 'Pending')
            ->first();

        return view('pages.hr.pegawai.show', compact('pegawai', 'pendingChange'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pegawai $pegawai)
    {
        $pegawai->load('latestDataDiri');

        $posisi     = OrgUnit::where('type', 'posisi')->select('orgunit_id', 'name')->get();
        $departemen = OrgUnit::whereIn('type', ['Bagian', 'Jurusan', 'Prodi', 'Unit'])->select('orgunit_id', 'name')->get();
        $prodi      = OrgUnit::where('type', 'Prodi')->select('orgunit_id', 'name')->get();

        return view('pages.hr.pegawai.create-edit', compact('pegawai', 'posisi', 'departemen', 'prodi'));
    }

    /**
     * Update the specified resource in storage.
     * This creates a NEW history record + Pending Approval.
     */
    public function update(PegawaiRequest $request, Pegawai $pegawai)
    {
        try {
            // Request Change Logic
            $this->pegawaiService->requestDataDiriChange($pegawai, $request->validated());
            return jsonSuccess('Permintaan perubahan berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pegawai $pegawai)
    {
        try {
            $this->pegawaiService->delete($pegawai->pegawai_id);
            return jsonSuccess('Pegawai berhasil dihapus');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Generate user account for pegawai without user.
     */
    public function generateUser(Pegawai $pegawai)
    {
        try {
            if ($pegawai->user) {
                return jsonError('Pegawai ini sudah memiliki user.');
            }

            // Get email from latest data diri
            $email = $pegawai->latestDataDiri?->email;
            
            if (!$email) {
                return jsonError('Email tidak ditemukan pada data diri pegawai.');
            }

            // Check if email already exists
            if (\App\Models\User::where('email', $email)->exists()) {
                return jsonError("Email {$email} sudah terdaftar. Silakan gunakan email lain.");
            }

            // Generate password default
            $password = 'password123';
            
            // Create user
            $user = \App\Models\User::create([
                'name'              => $pegawai->nama,
                'email'             => $email,
                'password'          => \Illuminate\Support\Facades\Hash::make($password),
                'email_verified_at' => now(),
                'created_by'        => auth()->id() ?? 'system',
            ]);

            // Link pegawai to user
            $pegawai->update(['user_id' => $user->id]);

            // Assign default role based on posisi
            $role = $this->determineRoleFromPosisi($pegawai->latestDataDiri?->posisi?->name);
            $user->assignRole($role);

            return jsonSuccess(
                "User berhasil dibuat untuk {$pegawai->nama}.<br>Email: {$email}<br>Password: {$password}<br>Role: {$role}",
                route('hr.pegawai.index')
            );
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal membuat user: ' . $e->getMessage());
        }
    }

    /**
     * Determine role from posisi.
     */
    private function determineRoleFromPosisi($posisi)
    {
        $posisi = strtolower($posisi ?? '');
        
        if (str_contains($posisi, 'dosen')) {
            return 'dosen';
        } elseif (str_contains($posisi, 'teknisi')) {
            return 'teknisi';
        } elseif (str_contains($posisi, 'kepala lab')) {
            return 'penanggung_jawab_lab';
        }
        
        return 'admin'; // Default role
    }
}
