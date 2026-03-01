<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\VerifySingleDocumentRequest;
use App\Models\Pmb\DokumenUpload;
use App\Models\Pmb\Pendaftaran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PendaftarController extends Controller
{
    /**
     * Display list of Pendaftar
     */
    public function index()
    {
        return view('pages.pmb.pendaftar.index');
    }

    /**
     * Paginate pendaftar data
     */
    public function data(Request $request)
    {
        $query = Pendaftaran::with(['camaba.user', 'jalur', 'dokumenUpload']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('no_pendaftaran', function ($row) {
                return $row->no_pendaftaran;
            })
            ->addColumn('nama', function ($row) {
                return $row->camaba->user->name ?? '-';
            })
            ->addColumn('tanggal_daftar', function ($row) {
                return $row->waktu_daftar ? formatTanggalIndo($row->waktu_daftar) : '-';
            })
            ->addColumn('status_upload', function ($row) {
                $total = $row->dokumenUpload->count();
                if ($total == 0) {
                    return '<span class="badge bg-secondary text-white">Belum Upload</span>';
                }
                return '<span class="badge bg-info text-white">' . $total . ' Berkas</span>';
            })
            ->addColumn('total_verif', function ($row) {
                $total    = $row->dokumenUpload->count();
                $verified = $row->dokumenUpload->where('status_verifikasi', 'Valid')->count();

                if ($total == 0) {
                    return '-';
                }

                $verifiedIcon = $verified > 0
                    ? '<i class="ti ti-circle-check text-success fs-4" title="Terverifikasi: ' . $verified . '"></i>'
                    : '';
                $pendingIcon = ($total - $verified) > 0
                    ? '<i class="ti ti-circle-x text-danger fs-4" title="Belum Verifikasi: ' . ($total - $verified) . '"></i>'
                    : '';

                return "<span class='ms-1'>{$verifiedIcon} {$pendingIcon}</span>";
            })
            ->addColumn('action', function ($row) {
                $buttons = [];

                // Detail button
                $buttons[] = '<a href="' . route('pmb.pendaftaran.show', $row->encrypted_pendaftaran_id) . '" class="btn btn-sm btn-primary" title="Lihat Detail">
                    <i class="ti ti-eye"></i> Detail
                </a>';

                // Verifikasi Berkas button (only if has documents and not all verified)
                $total    = $row->dokumenUpload->count();
                $verified = $row->dokumenUpload->where('status_verifikasi', 'Valid')->count();

                if ($total > 0 && $verified < $total) {
                    $buttons[] = '<button type="button" class="btn btn-sm btn-success btn-verify-docs"
                        data-pendaftaran-id="' . $row->encrypted_pendaftaran_id . '"
                        title="Verifikasi Berkas">
                        <i class="ti ti-file-check"></i> Verif Berkas
                    </button>';
                }

                return implode(' ', $buttons);
            })
            ->rawColumns(['no_pendaftaran', 'nama', 'tanggal_daftar', 'status_upload', 'total_verif', 'action'])
            ->make(true);
    }

    /**
     * Load berkas for modal verification
     */
    public function loadBerkas(Request $request)
    {
        $pendaftaranId = decryptIdIfEncrypted($request->pendaftaran_id);

        $pendaftaran = Pendaftaran::with(['dokumenUpload.jenisDokumen', 'camaba.user'])
            ->findOrFail($pendaftaranId);

        $html = view('pages.pmb.pendaftar._modal_verifikasi', compact('pendaftaran'))->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Verify document with toggle
     */
    public function verifyDocument(VerifySingleDocumentRequest $request)
    {

        $dokumen = DokumenUpload::findOrFail($request->dokumen_id);
        $dokumen->update([
            'status_verifikasi' => $request->status,
            'verifikator_id'    => auth()->id(),
        ]);

        // Check if all documents verified
        $pendaftaran = $dokumen->pendaftaran;
        $allVerified = $pendaftaran->dokumenUpload->where('status_verifikasi', '!=', 'Valid')->isEmpty();

        if ($allVerified && $request->status === 'Valid') {
            $pendaftaran->update(['status_terkini' => 'Siap_Ujian']);
        }

        logActivity('pmb_verifikasi_berkas', "Verifikasi berkas: {$request->status}", $dokumen);

        return jsonSuccess('Verifikasi berkas berhasil.');
    }
}
