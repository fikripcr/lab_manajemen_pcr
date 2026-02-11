<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;

class PengembanganDiriController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function index(\Illuminate\Http\Request $request, Pegawai $pegawai = null)
    {
        return view('pages.hr.data-diri.tabs.pengembangan', compact('pegawai'));
    }

    public function create(Pegawai $pegawai)
    {
        return view('pages.hr.pegawai.pengembangan.create', compact('pegawai'));
    }

    public function store(\App\Http\Requests\Hr\PengembanganDiriRequest $request, Pegawai $pegawai)
    {
        try {
            $this->pegawaiService->requestAddition($pegawai, \App\Models\Hr\PengembanganDiri::class, $request->validated());
            return jsonSuccess('Riwayat Pengembangan Diri berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit(Pegawai $pegawai, \App\Models\Hr\PengembanganDiri $pengembangan)
    {
        return view('pages.hr.pegawai.pengembangan.edit', compact('pegawai', 'pengembangan'));
    }

    public function update(\App\Http\Requests\Hr\PengembanganDiriRequest $request, Pegawai $pegawai, \App\Models\Hr\PengembanganDiri $pengembangan)
    {
        try {
            $pengembangan->update($request->validated());
            return jsonSuccess('Riwayat Pengembangan Diri berhasil diperbarui.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(Pegawai $pegawai, \App\Models\Hr\PengembanganDiri $pengembangan)
    {
        try {
            $pengembangan->delete();
            return jsonSuccess('Riwayat Pengembangan Diri berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function data()
    {
        $query = \App\Models\Hr\PengembanganDiri::with('pegawai')->select('hr_pengembangan_diri.*');

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->editColumn('tgl_mulai', function ($row) {
                return $row->tgl_mulai ? \Carbon\Carbon::parse($row->tgl_mulai)->format('d-m-Y') : '-';
            })
            ->editColumn('tgl_selesai', function ($row) {
                return $row->tgl_selesai ? \Carbon\Carbon::parse($row->tgl_selesai)->format('d-m-Y') : '-';
            })
            ->addColumn('tahun', function ($row) {
                return $row->tahun ?? ($row->tgl_mulai ? \Carbon\Carbon::parse($row->tgl_mulai)->format('Y') : '-');
            })
            ->make(true);
    }
}
