<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\KeluargaRequest;
use App\Models\Hr\Keluarga;
use App\Models\Shared\Pegawai;
use App\Services\Hr\PegawaiService;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class KeluargaController extends Controller
{
    public function __construct(protected PegawaiService $pegawaiService)
    {}

    public function index(Pegawai $pegawai = null)
    {
        return view('pages.hr.data-diri.tabs.keluarga', compact('pegawai'));
    }

    public function create(Pegawai $pegawai)
    {
        $keluarga = new Keluarga();
        return view('pages.hr.pegawai.keluarga.create-edit-ajax', compact('pegawai', 'keluarga'));
    }

    public function store(KeluargaRequest $request, Pegawai $pegawai)
    {
        $this->pegawaiService->requestAddition($pegawai, Keluarga::class, $request->validated());
        return jsonSuccess('Data Keluarga berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
    }
    public function edit(Pegawai $pegawai, Keluarga $keluarga)
    {
        return view('pages.hr.pegawai.keluarga.create-edit-ajax', compact('pegawai', 'keluarga'));
    }

    public function update(KeluargaRequest $request, Pegawai $pegawai, Keluarga $keluarga)
    {
        $this->pegawaiService->requestChange($pegawai, Keluarga::class, $request->validated(), null, $keluarga);
        return jsonSuccess('Perubahan Data Keluarga berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
    }

    public function destroy(Pegawai $pegawai, Keluarga $keluarga)
    {
        $keluarga->delete();
        return jsonSuccess('Data Keluarga berhasil dihapus.');
    }

    public function data()
    {
        $query = Keluarga::with('pegawai')->select('hr_keluarga.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->editColumn('tgl_lahir', function ($row) {
                return $row->tgl_lahir ? Carbon::parse($row->tgl_lahir)->format('d-m-Y') : '-';
            })
            ->addColumn('action', function ($row) {
                // Actions can be added here if needed, or kept read-only for now
                return '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
