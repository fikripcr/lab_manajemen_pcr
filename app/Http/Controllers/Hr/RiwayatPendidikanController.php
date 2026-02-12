<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;

class RiwayatPendidikanController extends Controller
{
    protected $PegawaiService;

    public function __construct(PegawaiService $PegawaiService)
    {
        $this->PegawaiService = $PegawaiService;
    }

    public function index(\Illuminate\Http\Request $request, Pegawai $pegawai = null)
    {
        return view('pages.hr.data-diri.tabs.pendidikan', compact('pegawai'));
    }

    public function create(Pegawai $pegawai)
    {
        return view('pages.hr.pegawai.pendidikan.create', compact('pegawai'));
    }

    public function store(\App\Http\Requests\Hr\RiwayatPendidikanRequest $request, Pegawai $pegawai)
    {
        try {
            $this->PegawaiService->requestAddition($pegawai, \App\Models\Hr\RiwayatPendidikan::class, $request->validated());
            return jsonSuccess('Riwayat Pendidikan berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
    public function edit(Pegawai $pegawai, \App\Models\Hr\RiwayatPendidikan $pendidikan)
    {
        return view('pages.hr.pegawai.pendidikan.edit', compact('pegawai', 'pendidikan'));
    }

    public function update(\App\Http\Requests\Hr\RiwayatPendidikanRequest $request, Pegawai $pegawai, \App\Models\Hr\RiwayatPendidikan $pendidikan)
    {
        try {
            $pendidikan->update($request->validated());
            return jsonSuccess('Riwayat Pendidikan berhasil diperbarui.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(Pegawai $pegawai, \App\Models\Hr\RiwayatPendidikan $pendidikan)
    {
        try {
            $pendidikan->delete();
            return jsonSuccess('Riwayat Pendidikan berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function data()
    {
        $query = \App\Models\Hr\RiwayatPendidikan::with('pegawai')->select('hr_riwayat_pendidikan.*');

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->editColumn('tgl_ijazah', function ($row) {
                return $row->tgl_ijazah ? \Carbon\Carbon::parse($row->tgl_ijazah)->format('d-m-Y') : '-';
            })
            ->addColumn('action', function ($row) {
                return '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
