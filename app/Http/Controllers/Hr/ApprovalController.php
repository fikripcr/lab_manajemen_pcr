<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\RiwayatApproval;
use App\Models\Shared\Pegawai;
use App\Services\Hr\PegawaiService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApprovalController extends Controller
{
    public function __construct(protected PegawaiService $pegawaiService)
    {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $status = $request->input('status', 'Pending'); // Default showing pending

            // Fetch pending approvals
            $query = RiwayatApproval::with(['subject'])
                ->when($status !== 'all', function ($q) use ($status) {
                    return $q->where('status', $status);
                })
                ->latest();

            return DataTables::of($query)
                ->addColumn('pegawai_nama', function ($row) {
                    return $row->pegawai->nama ?? '-';
                })
                ->addColumn('tipe_request', function ($row) {
                    return hrModelLabel($row->model);
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
        $this->pegawaiService->approveRequest($id);
        return jsonSuccess('Pengajuan berhasil didsetujui.');
    }

    public function reject(Request $request, $id)
    {
        $reason = $request->input('reason', 'Ditolak tanpa keterangan');
        $this->pegawaiService->rejectRequest($id, $reason);

        return jsonSuccess('Pengajuan berhasil ditolak.');
    }

    public function employeeHistory(Pegawai $pegawai)
    {
        $approvals = $pegawai->allApprovals()->paginate(10);
        return view('pages.hr.data-diri.tabs.pengajuan', compact('pegawai', 'approvals'));
    }
}
