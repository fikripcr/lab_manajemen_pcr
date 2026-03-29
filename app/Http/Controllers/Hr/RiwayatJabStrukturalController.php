<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\RiwayatJabStrukturalRequest;
use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatJabStruktural;
use App\Services\Hr\PegawaiService;
use App\Services\Hr\RiwayatStrukturalService;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class RiwayatJabStrukturalController extends Controller
{
    public function __construct(
        protected PegawaiService $pegawaiService,
        protected RiwayatStrukturalService $strukturalService
    ) {}

    public function index()
    {
        return view('pages.hr.jabatan-struktural.index');
    }

    public function create(Pegawai $pegawai)
    {
        $jabatan = $this->strukturalService->getStrukturalUnits('jabatan_struktural');

        $riwayat = new RiwayatJabStruktural;

        return view('pages.hr.jabatan-struktural.create-edit-ajax', compact('pegawai', 'jabatan', 'riwayat'));
    }

    public function store(RiwayatJabStrukturalRequest $request, Pegawai $pegawai)
    {
        $headerCol = 'latest_riwayatjabstruktural_id';
        $this->pegawaiService->requestChange($pegawai, RiwayatJabStruktural::class, $request->validated(), $headerCol);

        return jsonSuccess('Perubahan Jabatan Struktural berhasil diajukan.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
    }

    public function data()
    {
        $query = $this->strukturalService->getDataQuery();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->addColumn('jabatan_nama', function ($row) {
                return $row->orgUnit->name ?? '-';
            })
            ->editColumn('tgl_awal', function ($row) {
                return $row->tmt ? Carbon::parse($row->tmt)->format('d-m-Y') : '-';
            })
            ->editColumn('tgl_akhir', function ($row) {
                return $row->tgl_akhir ? \Carbon\Carbon::parse($row->tgl_akhir)->format('d-m-Y') : '-';
            })
            ->make(true);
    }
}
