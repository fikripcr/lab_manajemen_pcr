<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\RiwayatStatPegawaiRequest;
use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatStatPegawai;
use App\Models\Hr\StatusPegawai;
use App\Services\Hr\PegawaiService;
use Carbon\Carbon;
use Exception;
use Yajra\DataTables\DataTables;

class RiwayatStatPegawaiController extends Controller
{
    protected $PegawaiService;

    public function __construct(PegawaiService $PegawaiService)
    {
        $this->PegawaiService = $PegawaiService;
    }

    public function index()
    {
        return view('pages.hr.data-diri.tabs.status-pegawai');
    }

    public function create(Pegawai $pegawai)
    {
        $statusPegawai = StatusPegawai::where('is_active', 1)->get();
        return view('pages.hr.pegawai.status-pegawai.create', compact('pegawai', 'statusPegawai'));
    }

    public function store(RiwayatStatPegawaiRequest $request, Pegawai $pegawai)
    {
        try {
            $this->PegawaiService->requestChange($pegawai, RiwayatStatPegawai::class, $request->validated(), 'latest_riwayatstatpegawai_id');
            return jsonSuccess('Perubahan Status Pegawai berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function data()
    {
        $query = RiwayatStatPegawai::with(['pegawai', 'statusPegawai'])->select('hr_riwayat_statpegawai.*');

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
            ->make(true);
    }
}
