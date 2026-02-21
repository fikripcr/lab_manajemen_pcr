<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\RiwayatPendidikanRequest;
use App\Models\Hr\RiwayatPendidikan;
use App\Models\Shared\Pegawai;
use App\Services\Hr\PegawaiService;
use Carbon\Carbon;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class RiwayatPendidikanController extends Controller
{
    public function __construct(protected PegawaiService $pegawaiService)
    {}

    public function index(Pegawai $pegawai = null)
    {
        return view('pages.hr.data-diri.tabs.pendidikan', compact('pegawai'));
    }

    public function create(Pegawai $pegawai)
    {
        $pendidikan = new RiwayatPendidikan();
        return view('pages.hr.pegawai.pendidikan.create-edit-ajax', compact('pegawai', 'pendidikan'));
    }

    public function store(RiwayatPendidikanRequest $request, Pegawai $pegawai)
    {
        try {
            $this->pegawaiService->requestAddition($pegawai, RiwayatPendidikan::class, $request->validated());
            return jsonSuccess('Riwayat Pendidikan berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
    public function edit(Pegawai $pegawai, RiwayatPendidikan $pendidikan)
    {
        return view('pages.hr.pegawai.pendidikan.create-edit-ajax', compact('pegawai', 'pendidikan'));
    }

    public function update(RiwayatPendidikanRequest $request, Pegawai $pegawai, RiwayatPendidikan $pendidikan)
    {
        try {
            $this->pegawaiService->requestChange($pegawai, RiwayatPendidikan::class, $request->validated(), null, $pendidikan);
            return jsonSuccess('Perubahan Riwayat Pendidikan berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(Pegawai $pegawai, RiwayatPendidikan $pendidikan)
    {
        try {
            $pendidikan->delete();
            return jsonSuccess('Riwayat Pendidikan berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function data()
    {
        $query = RiwayatPendidikan::with('pegawai')->select('hr_riwayat_pendidikan.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->editColumn('tgl_ijazah', function ($row) {
                return $row->tgl_ijazah ? Carbon::parse($row->tgl_ijazah)->format('d-m-Y') : '-';
            })
            ->addColumn('action', function ($row) {
                return '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
