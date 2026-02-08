<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\OrgUnit;
use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatJabStruktural;
use App\Services\Hr\PegawaiService;

class RiwayatJabStrukturalController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function index()
    {
        return view('pages.hr.data-diri.tabs.struktural');
    }

    public function create(Pegawai $pegawai)
    {
        // Use OrgUnit with type 'jabatan_struktural' instead of legacy JabatanStruktural
        $jabatan = OrgUnit::where('type', 'jabatan_struktural')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.hr.pegawai.jabatan-struktural.create', compact('pegawai', 'jabatan'));
    }

    public function store(\App\Http\Requests\Hr\RiwayatJabStrukturalRequest $request, Pegawai $pegawai)
    {
        try {
            $headerCol = 'latest_riwayatjabstruktural_id';
            $this->pegawaiService->requestChange($pegawai, RiwayatJabStruktural::class, $request->validated(), $headerCol);
            return jsonSuccess('Perubahan Jabatan Struktural berhasil diajukan.', route('hr.pegawai.show', $pegawai->pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function data()
    {
        $query = \App\Models\Hr\RiwayatJabStruktural::with(['pegawai', 'jabatanStruktural'])->select('hr_riwayat_jabstruktural.*');

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->addColumn('jabatan_nama', function ($row) {
                return $row->jabatanStruktural->nama ?? '-';
            })
            ->editColumn('tgl_awal', function ($row) {
                return $row->tgl_awal ? \Carbon\Carbon::parse($row->tgl_awal)->format('d-m-Y') : '-';
            })
            ->editColumn('tgl_akhir', function ($row) {
                return $row->tgl_akhir ? \Carbon\Carbon::parse($row->tgl_akhir)->format('d-m-Y') : '-';
            })
            ->make(true);
    }
}
