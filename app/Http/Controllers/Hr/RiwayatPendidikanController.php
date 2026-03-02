<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\RiwayatPendidikanRequest;
use App\Models\Hr\RiwayatPendidikan;
use App\Models\Shared\Pegawai;
use App\Services\Hr\RiwayatPendidikanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RiwayatPendidikanController extends Controller
{
    public function __construct(protected RiwayatPendidikanService $pendidikanService)
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
        $this->pendidikanService->requestAddition($pegawai, $request->validated());
        return jsonSuccess('Riwayat Pendidikan berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) . '#section-pendidikan');
    }
    public function edit(Pegawai $pegawai, RiwayatPendidikan $pendidikan)
    {
        return view('pages.hr.pegawai.pendidikan.create-edit-ajax', compact('pegawai', 'pendidikan'));
    }

    public function update(RiwayatPendidikanRequest $request, Pegawai $pegawai, RiwayatPendidikan $pendidikan)
    {
        $this->pendidikanService->requestChange($pegawai, $request->validated(), $pendidikan);
        return jsonSuccess('Perubahan Riwayat Pendidikan berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) . '#section-pendidikan');
    }

    public function destroy(Pegawai $pegawai, RiwayatPendidikan $pendidikan)
    {
        $pendidikan->delete();
        return jsonSuccess('Riwayat Pendidikan berhasil dihapus.');
    }

    public function data(Request $request)
    {
        $query = RiwayatPendidikan::with('pegawai')->select('hr_riwayat_pendidikan.*');

        if ($request->has('pegawai_id')) {
            $query->where('pegawai_id', decryptIdIfEncrypted($request->pegawai_id));
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->editColumn('tgl_ijazah', function ($row) {
                return $row->tgl_ijazah ? Carbon::parse($row->tgl_ijazah)->format('Y') : '-';
            })
            ->addColumn('ijazah', function ($row) {
                if ($row->file_ijazah) {
                    return '<a href="' . asset($row->file_ijazah) . '" target="_blank" class="btn btn-sm btn-icon btn-ghost-info" title="Unduh Ijazah">
                                <i class="ti ti-download"></i>
                            </a>';
                }
                return '-';
            })
            ->addColumn('status', function ($row) {
                if ($row->approval) {
                    return getApprovalStatus($row->approval->status);
                }
                return '<span class="status status-success"><span class="status-dot"></span> Sistem</span>';
            })
            ->addColumn('action', function ($row) {
                $pegawaiId = encryptId($row->pegawai_id);
                $eduId     = $row->encrypted_riwayatpendidikan_id;

                return '<div class="btn-list justify-content-end">
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-primary ajax-modal-btn"
                                data-url="' . route('hr.pegawai.pendidikan.edit', [$pegawaiId, $eduId]) . '"
                                data-modal-title="Edit Pendidikan" title="Edit">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-danger ajax-delete"
                                data-url="' . route('hr.pegawai.pendidikan.destroy', [$pegawaiId, $eduId]) . '"
                                title="Hapus">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>';
            })
            ->rawColumns(['ijazah', 'status', 'action'])
            ->make(true);
    }
}
