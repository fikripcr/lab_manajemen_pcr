<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;

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
        $statusPegawai = \App\Models\Hr\StatusPegawai::where('is_active', 1)->get();
        return view('pages.hr.pegawai.status-pegawai.create', compact('pegawai', 'statusPegawai'));
    }

    public function store(\App\Http\Requests\Hr\RiwayatStatPegawaiRequest $request, Pegawai $pegawai)
    {
        try {
            $this->PegawaiService->requestChange($pegawai, \App\Models\Hr\RiwayatStatPegawai::class, $request->validated(), 'latest_riwayatstatpegawai_id');
            return jsonSuccess('Perubahan Status Pegawai berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function data()
    {
        $query = \App\Models\Hr\RiwayatStatPegawai::with(['pegawai', 'statusPegawai'])->select('hr_riwayat_statpegawai.*');

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->addColumn('status_nama', function ($row) {
                return $row->statusPegawai->nama ?? '-';
            })
            ->editColumn('tmt', function ($row) {
                return $row->tmt ? \Carbon\Carbon::parse($row->tmt)->format('d-m-Y') : '-';
            })
            ->make(true);
    }
}
