<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\RiwayatInpassingRequest;
use App\Models\Hr\GolonganInpassing;
use App\Models\Hr\RiwayatInpassing;
use App\Models\Shared\Pegawai;
use App\Services\Hr\PegawaiService;
use Yajra\DataTables\Facades\DataTables;

class RiwayatInpassingController extends Controller
{
    public function __construct(protected PegawaiService $pegawaiService)
    {}

    public function index(Pegawai $pegawai = null)
    {
        if ($pegawai) {
            return view('pages.hr.data-diri.tabs.inpassing', compact('pegawai'));
        }
        return view('pages.hr.data-diri.tabs.inpassing'); // Global view if needed
    }

    public function create(Pegawai $pegawai)
    {
        $golongan  = GolonganInpassing::all();
        $inpassing = new RiwayatInpassing();
        return view('pages.hr.pegawai.inpassing.create-edit-ajax', compact('pegawai', 'golongan', 'inpassing'));
    }

    public function store(RiwayatInpassingRequest $request, Pegawai $pegawai)
    {
        $this->pegawaiService->requestChange($pegawai, RiwayatInpassing::class, $request->validated(), 'latest_riwayatinpassing_id');
        return jsonSuccess('Perubahan Inpassing berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
    }

    public function edit(Pegawai $pegawai, RiwayatInpassing $inpassing)
    {
        $golongan = GolonganInpassing::all();
        return view('pages.hr.pegawai.inpassing.create-edit-ajax', compact('pegawai', 'inpassing', 'golongan'));
    }

    public function update(RiwayatInpassingRequest $request, Pegawai $pegawai, RiwayatInpassing $inpassing)
    {
        $this->pegawaiService->requestChange($pegawai, RiwayatInpassing::class, $request->validated(), 'latest_riwayatinpassing_id', $inpassing);
        return jsonSuccess('Perubahan Inpassing berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
    }

    public function destroy(Pegawai $pegawai, RiwayatInpassing $inpassing)
    {
        $inpassing->delete();
        return jsonSuccess('Riwayat Inpassing berhasil dihapus.');
    }

    public function data()
    {
        // Global data
        $query = RiwayatInpassing::with(['pegawai', 'golonganInpassing'])->select('hr_riwayat_inpassing.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->addColumn('golongan_nama', function ($row) {
                return $row->golonganInpassing->golongan ?? '-';
            })
            ->editColumn('tmt', function ($row) {
                return $row->tmt ? $row->tmt->format('d-m-Y') : '-';
            })
            ->editColumn('tgl_sk', function ($row) {
                return $row->tgl_sk ? $row->tgl_sk->format('d-m-Y') : '-';
            })
            ->make(true);
    }
}
