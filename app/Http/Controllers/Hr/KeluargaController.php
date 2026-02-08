<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;

class KeluargaController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function index(\Illuminate\Http\Request $request, Pegawai $pegawai = null)
    {
        return view('pages.hr.data-diri.tabs.keluarga', compact('pegawai'));
    }

    public function create(Pegawai $pegawai)
    {
        return view('pages.hr.pegawai.keluarga.create', compact('pegawai'));
    }

    public function store(\App\Http\Requests\Hr\KeluargaRequest $request, Pegawai $pegawai)
    {
        try {
            $this->pegawaiService->requestAddition($pegawai, \App\Models\Hr\Keluarga::class, $request->validated());
            return jsonSuccess('Data Keluarga berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->hashid));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
    public function edit(Pegawai $pegawai, \App\Models\Hr\Keluarga $keluarga)
    {
        return view('pages.hr.pegawai.keluarga.edit', compact('pegawai', 'keluarga'));
    }

    public function update(\App\Http\Requests\Hr\KeluargaRequest $request, Pegawai $pegawai, \App\Models\Hr\Keluarga $keluarga)
    {
        try {
            $keluarga->update($request->validated());
            return jsonSuccess('Data Keluarga berhasil diperbarui.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(Pegawai $pegawai, \App\Models\Hr\Keluarga $keluarga)
    {
        try {
            $keluarga->delete();
            return jsonSuccess('Data Keluarga berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function data()
    {
        $query = \App\Models\Hr\Keluarga::with('pegawai')->select('hr_keluarga.*');

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->editColumn('tgl_lahir', function ($row) {
                return $row->tgl_lahir ? \Carbon\Carbon::parse($row->tgl_lahir)->format('d-m-Y') : '-';
            })
            ->addColumn('action', function ($row) {
                // Actions can be added here if needed, or kept read-only for now
                return '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
