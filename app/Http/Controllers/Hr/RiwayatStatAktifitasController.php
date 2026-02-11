<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;

class RiwayatStatAktifitasController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function index()
    {
        return view('pages.hr.data-diri.tabs.status-aktifitas');
    }

    public function create(Pegawai $pegawai)
    {
        $statusAktifitas = \App\Models\Hr\StatusAktifitas::where('is_active', 1)->get();
        return view('pages.hr.pegawai.status-aktifitas.create', compact('pegawai', 'statusAktifitas'));
    }

    public function store(\App\Http\Requests\Hr\RiwayatStatAktifitasRequest $request, Pegawai $pegawai)
    {
        try {
            $this->pegawaiService->requestChange($pegawai, \App\Models\Hr\RiwayatStatAktifitas::class, $request->validated(), 'latest_riwayatstataktifitas_id');
            return jsonSuccess('Riwayat Status Aktifitas berhasil diajukan.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function data()
    {
        $query = \App\Models\Hr\RiwayatStatAktifitas::with(['pegawai', 'statusAktifitas'])->select('hr_riwayat_stataktifitas.*');

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->addColumn('status_nama', function ($row) {
                return $row->statusAktifitas->nama ?? '-';
            })
            ->editColumn('tmt', function ($row) {
                return $row->tmt ? \Carbon\Carbon::parse($row->tmt)->format('d-m-Y') : '-';
            })
            ->make(true);
    }
}
