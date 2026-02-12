<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\PegawaiRequest;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PegawaiController extends Controller
{
    protected $PegawaiService;

    public function __construct(PegawaiService $PegawaiService)
    {
        $this->PegawaiService = $PegawaiService;
    }

    /**
     * Search pegawai for Select2 AJAX.
     */
    public function select2Search(Request $request)
    {
        $search = $request->input('q', '');
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
            $query = $this->PegawaiService->getFilteredQuery($request);
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('components.tabler.datatables-actions', [
                        'viewUrl'    => route('hr.pegawai.show', $row->hashid),
                        'editUrl'    => route('hr.pegawai.edit', $row->hashid),
                        'deleteUrl'  => route('hr.pegawai.destroy', $row->hashid),
                        'deleteName' => $row->nama, // Optional, if component supports it or we need it for JS
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
                ->addColumn('prodi', function ($row) {
                    return $row->latestDataDiri?->prodi?->nama_prodi ?? '-';
                })
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
        $statusPegawai   = \App\Models\Hr\StatusPegawai::where('is_active', 1)->get();
        $statusAktifitas = \App\Models\Hr\StatusAktifitas::where('is_active', 1)->get();

        return view('pages.hr.pegawai.create', compact('statusPegawai', 'statusAktifitas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PegawaiRequest $request)
    {
        try {
            $this->PegawaiService->createPegawai($request->validated());
            return jsonSuccess('Pegawai berhasil ditambahkan', route('hr.pegawai.index'));
        } catch (\Exception $e) {
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

        $posisi     = \App\Models\Hr\OrgUnit::where('type', 'posisi')->select('org_unit_id', 'name')->get();
        $departemen = \App\Models\Hr\OrgUnit::whereIn('type', ['Bagian', 'Jurusan', 'Prodi', 'Unit'])->select('org_unit_id', 'name')->get();
        $prodi      = \App\Models\Hr\OrgUnit::where('type', 'Prodi')->select('org_unit_id', 'name')->get();

        return view('pages.hr.pegawai.edit', compact('pegawai', 'posisi', 'departemen', 'prodi'));
    }

    /**
     * Update the specified resource in storage.
     * This creates a NEW history record + Pending Approval.
     */
    public function update(PegawaiRequest $request, Pegawai $pegawai)
    {
        try {
            // Request Change Logic
            $this->PegawaiService->requestDataDiriChange($pegawai, $request->validated());
            return jsonSuccess('Permintaan perubahan berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pegawai $pegawai)
    {
        try {
            $pegawai->delete();
            return jsonSuccess('Pegawai berhasil dihapus');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
