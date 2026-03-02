<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\RiwayatJabFungsionalRequest;
use App\Models\Hr\JabatanFungsional;
use App\Models\Hr\RiwayatJabFungsional;
use App\Models\Shared\Pegawai;
use App\Services\Hr\RiwayatJabFungsionalService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RiwayatJabFungsionalController extends Controller
{
    public function __construct(protected RiwayatJabFungsionalService $fungsionalService)
    {}

    public function index()
    {
        return view('pages.hr.data-diri.tabs.fungsional');
    }

    public function create(Pegawai $pegawai)
    {
        $jabatan = JabatanFungsional::where('is_active', 1)->get();
        $riwayat = new RiwayatJabFungsional();
        return view('pages.hr.pegawai.jabatan-fungsional.create-edit-ajax', compact('pegawai', 'jabatan', 'riwayat'));
    }

    public function store(RiwayatJabFungsionalRequest $request, Pegawai $pegawai)
    {
        $this->fungsionalService->requestChange($pegawai, $request->validated());
        return jsonSuccess('Perubahan Jabatan Fungsional berhasil diajukan.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) . '#section-fungsional');
    }

    public function data(Request $request)
    {
        $query = RiwayatJabFungsional::with(['pegawai', 'jabatanFungsional'])->select('hr_riwayat_jabfungsional.*');

        if ($request->has('pegawai_id')) {
            $query->where('pegawai_id', decryptIdIfEncrypted($request->pegawai_id));
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->addColumn('jabatan_nama', function ($row) {
                return $row->jabatanFungsional->nama ?? '-';
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
