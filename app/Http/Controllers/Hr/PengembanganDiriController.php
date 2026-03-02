<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\PengembanganDiriRequest;
use App\Models\Hr\Pegawai;
use App\Models\Hr\PengembanganDiri;
use App\Services\Hr\PengembanganDiriService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PengembanganDiriController extends Controller
{
    public function __construct(protected PengembanganDiriService $pengembanganDiriService)
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
        $this->pengembanganDiriService->requestAddition($pegawai, $request->validated());
        return jsonSuccess('Riwayat Pengembangan Diri berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) . '#section-pengembangan');
    }

    public function edit(Pegawai $pegawai, PengembanganDiri $pengembangan)
    {
        return view('pages.hr.pegawai.pengembangan.create-edit-ajax', compact('pegawai', 'pengembangan'));
    }

    public function update(PengembanganDiriRequest $request, Pegawai $pegawai, PengembanganDiri $pengembangan)
    {
        $this->pengembanganDiriService->requestChange($pegawai, $request->validated(), $pengembangan);
        return jsonSuccess('Perubahan Riwayat Pengembangan Diri berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) . '#section-pengembangan');
    }

    public function destroy(Pegawai $pegawai, PengembanganDiri $pengembangan)
    {
        $pengembangan->delete();
        return jsonSuccess('Riwayat Pengembangan Diri berhasil dihapus.');
    }

    public function data(Request $request)
    {
        $query = PengembanganDiri::with('pegawai')->select('hr_pengembangan_diri.*');

        if ($request->has('pegawai_id')) {
            $query->where('pegawai_id', decryptIdIfEncrypted($request->pegawai_id));
        }

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
            ->addColumn('sertifikat', function ($row) {
                if ($row->file_sertifikat) {
                    return '<a href="' . asset($row->file_sertifikat) . '" target="_blank" class="btn btn-sm btn-icon btn-ghost-info" title="Unduh Sertifikat">
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
                $devId     = $row->encrypted_pengembangandiri_id;

                return '<div class="btn-list justify-content-end">
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-primary ajax-modal-btn"
                                data-url="' . route('hr.pegawai.pengembangan.edit', [$pegawaiId, $devId]) . '"
                                data-modal-title="Edit Pengembangan Diri" title="Edit">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-danger ajax-delete"
                                data-url="' . route('hr.pegawai.pengembangan.destroy', [$pegawaiId, $devId]) . '"
                                title="Hapus">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>';
            })
            ->rawColumns(['sertifikat', 'status', 'action'])
            ->make(true);
    }
}
