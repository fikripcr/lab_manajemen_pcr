<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\RiwayatJabStrukturalRequest;
use App\Models\Hr\StrukturOrganisasi;
use App\Models\Hr\RiwayatJabStruktural;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class RiwayatJabStrukturalController extends Controller
{
    public function __construct(protected PegawaiService $pegawaiService)
    {}

    public function index()
    {
        return view('pages.hr.jabatan-struktural.index');
    }

    public function create(Pegawai $pegawai)
    {
        // Use StrukturOrganisasi with type 'jabatan_struktural' instead of legacy JabatanStruktural
        $jabatan = StrukturOrganisasi::where('type', 'jabatan_struktural')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $riwayat = new RiwayatJabStruktural();
        return view('pages.hr.jabatan-struktural.create-edit-ajax', compact('hr_pegawai', 'jabatan', 'riwayat'));
    }

    public function store(RiwayatJabStrukturalRequest $request, Pegawai $pegawai)
    {
        $headerCol = 'latest_riwayatjabstruktural_id';
        $this->pegawaiService->requestChange($pegawai, RiwayatJabStruktural::class, $request->validated(), $headerCol);
        return jsonSuccess('Perubahan Jabatan Struktural berhasil diajukan.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
    }

    public function data()
    {
        $query = RiwayatJabStruktural::with(['hr_pegawai', 'orgUnit'])->select('hr_riwayat_jabstruktural.*');

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
