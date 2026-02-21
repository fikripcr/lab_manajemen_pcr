<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\PengembanganDiriRequest;
use App\Models\Hr\Pegawai;
use App\Models\Hr\PengembanganDiri;
use App\Services\Hr\PegawaiService;
use Carbon\Carbon;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class PengembanganDiriController extends Controller
{
    public function __construct(protected PegawaiService $pegawaiService)
    {}

    public function index(Pegawai $pegawai = null)
    {
        return view('pages.hr.data-diri.tabs.pengembangan', compact('pegawai'));
    }

    public function create(Pegawai $pegawai)
    {
        $pengembangan = new PengembanganDiri();
        return view('pages.hr.pegawai.pengembangan.create-edit-ajax', compact('pegawai', 'pengembangan'));
    }

    public function store(PengembanganDiriRequest $request, Pegawai $pegawai)
    {
        try {
            $this->pegawaiService->requestAddition($pegawai, PengembanganDiri::class, $request->validated());
            return jsonSuccess('Riwayat Pengembangan Diri berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit(Pegawai $pegawai, PengembanganDiri $pengembangan)
    {
        return view('pages.hr.pegawai.pengembangan.create-edit-ajax', compact('pegawai', 'pengembangan'));
    }

    public function update(PengembanganDiriRequest $request, Pegawai $pegawai, PengembanganDiri $pengembangan)
    {
        try {
            $this->pegawaiService->requestChange($pegawai, PengembanganDiri::class, $request->validated(), null, $pengembangan);
            return jsonSuccess('Perubahan Riwayat Pengembangan Diri berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(Pegawai $pegawai, PengembanganDiri $pengembangan)
    {
        try {
            $pengembangan->delete();
            return jsonSuccess('Riwayat Pengembangan Diri berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function data()
    {
        $query = PengembanganDiri::with('pegawai')->select('hr_pengembangan_diri.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->editColumn('tgl_mulai', function ($row) {
                return $row->tgl_mulai ? Carbon::parse($row->tgl_mulai)->format('d-m-Y') : '-';
            })
            ->editColumn('tgl_selesai', function ($row) {
                return $row->tgl_selesai ? Carbon::parse($row->tgl_selesai)->format('d-m-Y') : '-';
            })
            ->addColumn('tahun', function ($row) {
                return $row->tahun ?? ($row->tgl_mulai ? Carbon::parse($row->tgl_mulai)->format('Y') : '-');
            })
            ->make(true);
    }
}
