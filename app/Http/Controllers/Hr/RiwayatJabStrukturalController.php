<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\RiwayatJabStrukturalRequest;
use App\Models\Hr\OrgUnit;
use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatJabStruktural;
use App\Services\Hr\PegawaiService;
use Carbon\Carbon;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class RiwayatJabStrukturalController extends Controller
{
    protected $PegawaiService;

    public function __construct(PegawaiService $PegawaiService)
    {
        $this->PegawaiService = $PegawaiService;
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

    public function store(RiwayatJabStrukturalRequest $request, Pegawai $pegawai)
    {
        try {
            $headerCol = 'latest_riwayatjabstruktural_id';
            $this->PegawaiService->requestChange($pegawai, RiwayatJabStruktural::class, $request->validated(), $headerCol);
            return jsonSuccess('Perubahan Jabatan Struktural berhasil diajukan.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function data()
    {
        $query = RiwayatJabStruktural::with(['pegawai', 'orgUnit'])->select('hr_riwayat_jabstruktural.*');

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
