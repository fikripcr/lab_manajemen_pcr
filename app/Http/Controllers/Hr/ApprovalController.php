<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\RiwayatApproval;
use App\Services\Hr\PegawaiService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApprovalController extends Controller
{
    protected $PegawaiService;

    public function __construct(PegawaiService $PegawaiService)
    {
        $this->PegawaiService = $PegawaiService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Fetch pending approvals
            $query = RiwayatApproval::with(['pegawai'])
                ->where('status', 'Pending')
                ->latest();

            return DataTables::of($query)
                ->addColumn('pegawai_nama', function ($row) {
                    return $row->pegawai->nama ?? '-';
                })
                ->addColumn('tipe_request', function ($row) {
                    // Extract readable name from model class
                    // e.g., "App\Models\Hr\RiwayatPendidikan" -> "Riwayat Pendidikan"
                    $modelClass = $row->model;
                    if ($modelClass) {
                        return (new \ReflectionClass($modelClass))->getShortName();
                    }
                    return '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y H:i') : '-';
                })
                ->addColumn('action', function ($row) {
                    return view('pages.hr.approval._action', compact('row'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.hr.approval.index');
    }

    public function approve($id)
    {
        try {
            $this->PegawaiService->approveRequest($id);
            return jsonSuccess('Pengajuan berhasil didsetujui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $reason = $request->input('reason', 'Ditolak tanpa keterangan');
            $this->PegawaiService->rejectRequest($id, $reason);

            return jsonSuccess('Pengajuan berhasil ditolak.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
