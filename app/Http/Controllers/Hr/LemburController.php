<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\LemburRequest;
use App\Models\Hr\Lembur;
use App\Models\Shared\Pegawai;
use App\Services\Hr\LemburService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LemburController extends Controller
{
    public function __construct(
        protected LemburService $lemburService
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
                return '<span class="badge ' . $badge . ' text-white">' . ucfirst($status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'viewUrl'   => route('hr.lembur.show', $row->encrypted_lembur_id),
                    'editUrl'   => route('hr.lembur.edit', $row->encrypted_lembur_id),
                    'editModal' => true,
                    'editTitle' => 'Edit Lembur',
                    'deleteUrl' => route('hr.lembur.destroy', $row->encrypted_lembur_id),
                ])->render();
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
        $lembur    = new Lembur();
        return view('pages.hr.lembur.create-edit-ajax', compact('pageTitle', 'pegawais', 'lembur'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LemburRequest $request)
    {
        try {
            $this->lemburService->store($request->validated());

            return jsonSuccess('Lembur berhasil ditambahkan', route('hr.lembur.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menambahkan lembur: ' . $e->getMessage());
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
        return view('pages.hr.lembur.create-edit-ajax', compact('pageTitle', 'lembur', 'pegawais'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LemburRequest $request, Lembur $lembur)
    {
        try {
            $this->lemburService->update($lembur, $request->validated());

            return jsonSuccess('Lembur berhasil diupdate', route('hr.lembur.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal mengupdate lembur: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lembur $lembur)
    {
        try {
            $lembur->delete();

            return jsonSuccess('Lembur berhasil dihapus');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus lembur: ' . $e->getMessage());
        }
    }

    /**
     * Approve lembur
     */
    public function approve(LemburRequest $request, Lembur $lembur)
    {
        try {
            $validated = $request->validated();
            $this->lemburService->approve($lembur, $validated);

            return jsonSuccess('Lembur berhasil di-' . ($validated['status'] === 'approved' ? 'setujui' : 'tolak'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memproses approval: ' . $e->getMessage());
        }
    }
}
