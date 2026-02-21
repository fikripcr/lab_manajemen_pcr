<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\RiwayatJabFungsionalRequest;
use App\Models\Hr\JabatanFungsional;
use App\Models\Hr\RiwayatJabFungsional;
use App\Models\Shared\Pegawai;
use App\Services\Hr\PegawaiService;
use Carbon\Carbon;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class RiwayatJabFungsionalController extends Controller
{
    public function __construct(protected PegawaiService $pegawaiService)
    {}

    public function index()
    {
        return view('pages.hr.data-diri.tabs.fungsional');
    }

    public function create(Pegawai $pegawai)
    {
        $jabatan = JabatanFungsional::where('is_active', 1)->get();
        $riwayat = new RiwayatJabFungsional();
        return view('pages.hr.pegawai.jabatan-fungsional.create-edit-ajax', compact('pegawai', 'jabatan', 'riwayat'));
    }

    public function store(RiwayatJabFungsionalRequest $request, Pegawai $pegawai)
    {
        try {
            $this->pegawaiService->requestChange($pegawai, RiwayatJabFungsional::class, $request->validated(), 'latest_riwayatjabfungsional_id');
            return jsonSuccess('Perubahan Jabatan Fungsional berhasil diajukan.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function data()
    {
        $query = RiwayatJabFungsional::with(['pegawai', 'jabatanFungsional'])->select('hr_riwayat_jabfungsional.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->addColumn('jabatan_nama', function ($row) {
                return $row->jabatanFungsional->nama ?? '-';
            })
            ->editColumn('tmt', function ($row) {
                return $row->tmt ? Carbon::parse($row->tmt)->format('d-m-Y') : '-';
            })
            ->make(true);
    }
}
