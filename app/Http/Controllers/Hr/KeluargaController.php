<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\KeluargaRequest;
use App\Models\Hr\Keluarga;
use App\Models\Shared\Pegawai;
use App\Services\Hr\KeluargaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KeluargaController extends Controller
{
    public function __construct(protected KeluargaService $keluargaService)
    {}

    public function index(Pegawai $pegawai = null)
    {
        return view('pages.hr.data-diri.tabs.keluarga', compact('pegawai'));
    }

    public function create(Pegawai $pegawai)
    {
        $keluarga = new Keluarga();
        return view('pages.hr.pegawai.keluarga.create-edit-ajax', compact('pegawai', 'keluarga'));
    }

    public function store(KeluargaRequest $request, Pegawai $pegawai)
    {
        $this->keluargaService->requestAddition($pegawai, $request->validated());
        return jsonSuccess('Data Keluarga berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) . '#section-keluarga');
    }
    public function edit(Pegawai $pegawai, Keluarga $keluarga)
    {
        return view('pages.hr.pegawai.keluarga.create-edit-ajax', compact('pegawai', 'keluarga'));
    }

    public function update(KeluargaRequest $request, Pegawai $pegawai, Keluarga $keluarga)
    {
        $this->keluargaService->requestChange($pegawai, $request->validated(), $keluarga);
        return jsonSuccess('Perubahan Data Keluarga berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) . '#section-keluarga');
    }

    public function destroy(Pegawai $pegawai, Keluarga $keluarga)
    {
        $keluarga->delete();
        return jsonSuccess('Data Keluarga berhasil dihapus.');
    }

    public function data(Request $request)
    {
        $query = Keluarga::with('pegawai')->select('hr_keluarga.*');

        if ($request->has('pegawai_id')) {
            $query->where('pegawai_id', decryptIdIfEncrypted($request->pegawai_id));
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->editColumn('tgl_lahir', function ($row) {
                return $row->tgl_lahir ? Carbon::parse($row->tgl_lahir)->format('d-m-Y') : '-';
            })
            ->addColumn('status', function ($row) {
                if ($row->approval) {
                    return getApprovalStatus($row->approval->status);
                }
                return '<span class="status status-success"><span class="status-dot"></span> Sistem</span>';
            })
            ->addColumn('action', function ($row) {
                $pegawaiId  = encryptId($row->pegawai_id);
                $keluargaId = $row->encrypted_keluarga_id;

                return '<div class="btn-list justify-content-end">
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-primary ajax-modal-btn"
                                data-url="' . route('hr.pegawai.keluarga.edit', [$pegawaiId, $keluargaId]) . '"
                                data-modal-title="Edit Keluarga" title="Edit">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-danger ajax-delete"
                                data-url="' . route('hr.pegawai.keluarga.destroy', [$pegawaiId, $keluargaId]) . '"
                                title="Hapus">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
