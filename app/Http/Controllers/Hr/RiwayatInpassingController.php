<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\RiwayatInpassingRequest;
use App\Models\Hr\GolonganInpassing;
use App\Models\Hr\RiwayatInpassing;
use App\Models\Shared\Pegawai;
use App\Services\Hr\RiwayatInpassingService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RiwayatInpassingController extends Controller
{
    public function __construct(protected RiwayatInpassingService $inpassingService)
    {}

    public function index(Pegawai $pegawai = null)
    {
        if ($pegawai) {
            return view('pages.hr.data-diri.tabs.inpassing', compact('pegawai'));
        }
        return view('pages.hr.data-diri.tabs.inpassing'); // Global view if needed
    }

    public function create(Pegawai $pegawai)
    {
        $golongan  = GolonganInpassing::all();
        $inpassing = new RiwayatInpassing();
        return view('pages.hr.pegawai.inpassing.create-edit-ajax', compact('pegawai', 'golongan', 'inpassing'));
    }

    public function store(RiwayatInpassingRequest $request, Pegawai $pegawai)
    {
        $this->inpassingService->requestChange($pegawai, $request->validated());
        return jsonSuccess('Perubahan Inpassing berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) . '#section-inpassing');
    }

    public function edit(Pegawai $pegawai, RiwayatInpassing $inpassing)
    {
        $golongan = GolonganInpassing::all();
        return view('pages.hr.pegawai.inpassing.create-edit-ajax', compact('pegawai', 'inpassing', 'golongan'));
    }

    public function update(RiwayatInpassingRequest $request, Pegawai $pegawai, RiwayatInpassing $inpassing)
    {
        $this->inpassingService->requestChange($pegawai, $request->validated(), $inpassing);
        return jsonSuccess('Perubahan Inpassing berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) . '#section-inpassing');
    }

    public function destroy(Pegawai $pegawai, RiwayatInpassing $inpassing)
    {
        $inpassing->delete();
        return jsonSuccess('Riwayat Inpassing berhasil dihapus.');
    }

    public function data(Request $request)
    {
        $query = RiwayatInpassing::with(['pegawai', 'golonganInpassing'])->select('hr_riwayat_inpassing.*');

        if ($request->has('pegawai_id')) {
            $query->where('pegawai_id', decryptIdIfEncrypted($request->pegawai_id));
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->addColumn('golongan_nama', function ($row) {
                return $row->golonganInpassing->golongan ?? '-';
            })
            ->editColumn('tmt', function ($row) {
                return $row->tmt ? $row->tmt->format('d-m-Y') : '-';
            })
            ->editColumn('tgl_sk', function ($row) {
                return $row->tgl_sk ? $row->tgl_sk->format('d-m-Y') : '-';
            })
            ->addColumn('approval_status', function ($row) {
                if ($row->approval) {
                    return getApprovalStatus($row->approval->status);
                }
                return '<span class="status status-success"><span class="status-dot"></span> Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                $pegawaiId   = encryptId($row->pegawai_id);
                $inpassingId = $row->encrypted_riwayatinpassing_id;

                return '<div class="btn-list justify-content-end">
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-primary ajax-modal-btn"
                                data-url="' . route('hr.pegawai.inpassing.edit', [$pegawaiId, $inpassingId]) . '"
                                data-modal-title="Edit Inpassing" title="Edit">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-danger ajax-delete"
                                data-url="' . route('hr.pegawai.inpassing.destroy', [$pegawaiId, $inpassingId]) . '"
                                title="Hapus">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>';
            })
            ->rawColumns(['approval_status', 'action'])
            ->make(true);
    }
}
