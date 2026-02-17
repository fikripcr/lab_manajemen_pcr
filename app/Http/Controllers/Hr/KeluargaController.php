<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\KeluargaRequest;
use App\Models\Hr\Keluarga;
use App\Models\Shared\Pegawai;
use App\Services\Hr\PegawaiService;
use Exception;
use Illuminate\Http\Request;

class KeluargaController extends Controller
{
    protected $PegawaiService;

    public function __construct(PegawaiService $PegawaiService)
    {
        $this->PegawaiService = $PegawaiService;
    }

    public function index(Request $request, Pegawai $pegawai = null)
    {
        return view('pages.hr.data-diri.tabs.keluarga', compact('pegawai'));
    }

    public function create(Pegawai $pegawai)
    {
        return view('pages.hr.pegawai.keluarga.create', compact('pegawai'));
    }

    public function store(KeluargaRequest $request, Pegawai $pegawai)
    {
        try {
            $this->PegawaiService->requestAddition($pegawai, Keluarga::class, $request->validated());
            return jsonSuccess('Data Keluarga berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
    public function edit(Pegawai $pegawai, Keluarga $keluarga)
    {
        return view('pages.hr.pegawai.keluarga.edit', compact('pegawai', 'keluarga'));
    }

    public function update(KeluargaRequest $request, Pegawai $pegawai, Keluarga $keluarga)
    {
        try {
            $keluarga->update($request->validated());
            return jsonSuccess('Data Keluarga berhasil diperbarui.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(Pegawai $pegawai, Keluarga $keluarga)
    {
        try {
            $keluarga->delete();
            return jsonSuccess('Data Keluarga berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function data()
    {
        $query = Keluarga::with('pegawai')->select('hr_keluarga.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->editColumn('tgl_lahir', function ($row) {
                return $row->tgl_lahir ? Carbon::parse($row->tgl_lahir)->format('d-m-Y') : '-';
            })
            ->addColumn('action', function ($row) {
                // Actions can be added here if needed, or kept read-only for now
                return '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
