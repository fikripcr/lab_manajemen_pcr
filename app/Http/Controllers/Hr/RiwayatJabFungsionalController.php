<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;

class RiwayatJabFungsionalController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function index()
    {
        return view('pages.hr.data-diri.tabs.fungsional');
    }

    public function create(Pegawai $pegawai)
    {
        $jabatan = \App\Models\Hr\JabatanFungsional::where('is_active', 1)->get();
        return view('pages.hr.pegawai.jabatan-fungsional.create', compact('pegawai', 'jabatan'));
    }

    public function store(\App\Http\Requests\Hr\RiwayatJabFungsionalRequest $request, Pegawai $pegawai)
    {
        try {
            $this->pegawaiService->requestChange($pegawai, \App\Models\Hr\RiwayatJabFungsional::class, $request->validated(), 'latest_riwayatjabfungsional_id');
            return jsonSuccess('Perubahan Jabatan Fungsional berhasil diajukan.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function data()
    {
        $query = \App\Models\Hr\RiwayatJabFungsional::with(['pegawai', 'jabatanFungsional'])->select('hr_riwayat_jabfungsional.*');

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->addColumn('jabatan_nama', function ($row) {
                return $row->jabatanFungsional->nama ?? '-';
            })
            ->editColumn('tmt', function ($row) {
                return $row->tmt ? \Carbon\Carbon::parse($row->tmt)->format('d-m-Y') : '-';
            })
            ->make(true);
    }
}
