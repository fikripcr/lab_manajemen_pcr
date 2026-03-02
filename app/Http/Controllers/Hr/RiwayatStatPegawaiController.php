<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\RiwayatStatPegawaiRequest;
use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatStatPegawai;
use App\Models\Hr\StatusPegawai;
use App\Services\Hr\RiwayatStatPegawaiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RiwayatStatPegawaiController extends Controller
{
    public function __construct(protected RiwayatStatPegawaiService $statPegawaiService)
    {}

    public function index()
    {
        return view('pages.hr.data-diri.tabs.status-pegawai');
    }

    public function create(Pegawai $pegawai)
    {
        $statusPegawai = StatusPegawai::where('is_active', 1)->get();
        return view('pages.hr.pegawai.status-pegawai.create-edit-ajax', compact('pegawai', 'statusPegawai'));
    }

    public function store(RiwayatStatPegawaiRequest $request, Pegawai $pegawai)
    {
        $this->statPegawaiService->requestChange($pegawai, $request->validated());
        return jsonSuccess('Perubahan Status Pegawai berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) . '#section-kepegawaian');
    }

    public function data(Request $request)
    {
        $query = RiwayatStatPegawai::with(['pegawai', 'statusPegawai'])->select('hr_riwayat_statpegawai.*');

        if ($request->has('pegawai_id')) {
            $query->where('pegawai_id', decryptIdIfEncrypted($request->pegawai_id));
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->addColumn('status_nama', function ($row) {
                return $row->statusPegawai->nama ?? '-';
            })
            ->editColumn('tmt', function ($row) {
                return $row->tmt ? Carbon::parse($row->tmt)->format('d-m-Y') : '-';
            })
            ->addColumn('approval_status', function ($row) {
                if ($row->approval) {
                    return getApprovalStatus($row->approval->status);
                }
                return '<span class="status status-success"><span class="status-dot"></span> Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                // Since this is history, we typically only show/edit if pending or just as record
                return '';
            })
            ->rawColumns(['approval_status', 'action'])
            ->make(true);
    }
}
