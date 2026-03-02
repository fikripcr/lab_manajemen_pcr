<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\RiwayatStatAktifitasRequest;
use App\Models\Hr\RiwayatStatAktifitas;
use App\Models\Hr\StatusAktifitas;
use App\Models\Shared\Pegawai;
use App\Services\Hr\RiwayatStatAktifitasService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RiwayatStatAktifitasController extends Controller
{
    public function __construct(protected RiwayatStatAktifitasService $statAktifitasService)
    {}

    public function index()
    {
        return view('pages.hr.data-diri.tabs.status-aktifitas');
    }

    public function create(Pegawai $pegawai)
    {
        $statusAktifitas = StatusAktifitas::where('is_active', 1)->get();
        return view('pages.hr.pegawai.status-aktifitas.create-edit-ajax', compact('pegawai', 'statusAktifitas'));
    }

    public function store(RiwayatStatAktifitasRequest $request, Pegawai $pegawai)
    {
        $this->statAktifitasService->requestChange($pegawai, $request->validated());
        return jsonSuccess('Riwayat Status Aktifitas berhasil diajukan.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) . '#section-kepegawaian');
    }

    public function data(Request $request)
    {
        $query = RiwayatStatAktifitas::with(['pegawai', 'statusAktifitas'])->select('hr_riwayat_stataktifitas.*');

        if ($request->has('pegawai_id')) {
            $query->where('pegawai_id', decryptIdIfEncrypted($request->pegawai_id));
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->addColumn('status_nama', function ($row) {
                return $row->statusAktifitas->nama ?? '-';
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
                return '';
            })
            ->rawColumns(['approval_status', 'action'])
            ->make(true);
    }
}
