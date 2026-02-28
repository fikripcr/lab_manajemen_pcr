<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\RiwayatStatAktifitasRequest;
use App\Models\Hr\RiwayatStatAktifitas;
use App\Models\Hr\StatusAktifitas;
use App\Models\Shared\Pegawai;
use App\Services\Hr\PegawaiService;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class RiwayatStatAktifitasController extends Controller
{
    public function __construct(protected PegawaiService $pegawaiService)
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
        $this->pegawaiService->requestChange($pegawai, RiwayatStatAktifitas::class, $request->validated(), 'latest_riwayatstataktifitas_id');
        return jsonSuccess('Riwayat Status Aktifitas berhasil diajukan.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
    }

    public function data()
    {
        $query = RiwayatStatAktifitas::with(['pegawai', 'statusAktifitas'])->select('hr_riwayat_stataktifitas.*');

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
            ->make(true);
    }
}
