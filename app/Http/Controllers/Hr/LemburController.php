<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Lembur;
use App\Models\Hr\Pegawai;
use App\Services\Hr\LemburService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LemburController extends Controller
{
    public function __construct(
        protected LemburService $LemburService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Data Lembur';
        return view('pages.hr.lembur.index', compact('pageTitle'));
    }

    /**
     * Get data for DataTables
     */
    public function data(Request $request)
    {
        $query = Lembur::with(['pengusul.latestDataDiri', 'latestApproval', 'pegawais'])
            ->orderBy('tgl_pelaksanaan', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pengusul_nama', function ($row) {
                return $row->pengusul?->latestDataDiri?->nama ?? '-';
            })
            ->addColumn('tanggal', function ($row) {
                return $row->tgl_pelaksanaan?->format('d/m/Y') ?? '-';
            })
            ->addColumn('waktu', function ($row) {
                return $row->jam_mulai . ' - ' . $row->jam_selesai;
            })
            ->addColumn('durasi', function ($row) {
                $jam   = floor($row->durasi_menit / 60);
                $menit = $row->durasi_menit % 60;
                return "{$jam} jam {$menit} menit";
            })
            ->addColumn('jumlah_pegawai', function ($row) {
                return $row->pegawais->count();
            })
            ->addColumn('status', function ($row) {
                $status = $row->status_approval;
                $badges = [
                    'pending'  => 'bg-warning',
                    'approved' => 'bg-success',
                    'rejected' => 'bg-danger',
                ];
                $badge = $badges[$status] ?? 'bg-secondary';
                return '<span class="badge ' . $badge . '">' . ucfirst($status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-icon btn-info ajax-modal-btn"
                            data-url="' . route('hr.lembur.show', $row->hashid) . '"
                            data-modal-title="Detail Lembur">
                            <i class="ti ti-eye"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-warning ajax-modal-btn"
                            data-url="' . route('hr.lembur.edit', $row->hashid) . '"
                            data-modal-title="Edit Lembur">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-danger ajax-delete-btn"
                            data-url="' . route('hr.lembur.destroy', $row->hashid) . '">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Tambah Lembur';
        $pegawais  = Pegawai::with('latestDataDiri')->get();
        return view('pages.hr.lembur.create', compact('pageTitle', 'pegawais'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengusul_id'      => 'required|exists:pegawai,pegawai_id',
            'judul'            => 'required|string|max:255',
            'uraian_pekerjaan' => 'nullable|string',
            'alasan'           => 'nullable|string',
            'tgl_pelaksanaan'  => 'required|date',
            'jam_mulai'        => 'required',
            'jam_selesai'      => 'required',
            'is_dibayar'       => 'boolean',
            'metode_bayar'     => 'nullable|in:uang,cuti_pengganti,tidak_dibayar',
            'nominal_per_jam'  => 'nullable|numeric|min:0',
            'pegawai_ids'      => 'required|array|min:1',
            'pegawai_ids.*'    => 'exists:pegawai,pegawai_id',
            'override_nominal' => 'nullable|array',
            'catatan_pegawai'  => 'nullable|array',
        ]);

        try {
            $lembur = $this->LemburService->store($validated);

            return response()->json([
                'success'  => true,
                'message'  => 'Lembur berhasil ditambahkan',
                'redirect' => route('hr.lembur.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan lembur: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lembur $lembur)
    {
        $lembur->load(['pengusul.latestDataDiri', 'pegawais.latestDataDiri', 'latestApproval', 'approvals']);
        return view('pages.hr.lembur.show', compact('lembur'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lembur $lembur)
    {
        $pageTitle = 'Edit Lembur';
        $pegawais  = Pegawai::with('latestDataDiri')->get();
        $lembur->load(['pengusul', 'pegawais']);
        return view('pages.hr.lembur.edit', compact('pageTitle', 'lembur', 'pegawais'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lembur $lembur)
    {
        $validated = $request->validate([
            'pengusul_id'      => 'required|exists:pegawai,pegawai_id',
            'judul'            => 'required|string|max:255',
            'uraian_pekerjaan' => 'nullable|string',
            'alasan'           => 'nullable|string',
            'tgl_pelaksanaan'  => 'required|date',
            'jam_mulai'        => 'required',
            'jam_selesai'      => 'required',
            'is_dibayar'       => 'boolean',
            'metode_bayar'     => 'nullable|in:uang,cuti_pengganti,tidak_dibayar',
            'nominal_per_jam'  => 'nullable|numeric|min:0',
            'pegawai_ids'      => 'required|array|min:1',
            'pegawai_ids.*'    => 'exists:pegawai,pegawai_id',
            'override_nominal' => 'nullable|array',
            'catatan_pegawai'  => 'nullable|array',
        ]);

        try {
            $lembur = $this->LemburService->update($lembur, $validated);

            return response()->json([
                'success'  => true,
                'message'  => 'Lembur berhasil diupdate',
                'redirect' => route('hr.lembur.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate lembur: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lembur $lembur)
    {
        try {
            $lembur->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lembur berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus lembur: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Approve lembur
     */
    public function approve(Request $request, Lembur $lembur)
    {
        $validated = $request->validate([
            'status'     => 'required|in:approved,rejected,pending',
            'pejabat'    => 'required|string|max:191',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $this->LemburService->approve($lembur, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Lembur berhasil di-' . ($validated['status'] === 'approved' ? 'setujui' : 'tolak'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses approval: ' . $e->getMessage(),
            ], 500);
        }
    }
}
