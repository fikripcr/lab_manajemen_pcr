<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Models\Pmb\Pendaftaran;
use App\Services\Pmb\PendaftaranService;
use Exception;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    protected $PendaftaranService;

    public function __construct(PendaftaranService $PendaftaranService)
    {
        $this->PendaftaranService = $PendaftaranService;
    }

    /**
     * Display a listing of registrations (Admin)
     */
    public function index()
    {
        return view('pages.pmb.pendaftaran.index');
    }

    /**
     * DataTables pagination for registrations
     */
    public function paginate(Request $request)
    {
        return datatables()->of($this->PendaftaranService->getFilteredQuery($request->all()))
            ->addIndexColumn()
            ->editColumn('no_pendaftaran', function ($pendaftaran) {
                return '<span class="badge bg-blue-lt">' . $pendaftaran->no_pendaftaran . '</span>';
            })
            ->editColumn('status_terkini', function ($pendaftaran) {
                $class = match ($pendaftaran->status_terkini) {
                    'Draft'       => 'bg-secondary',
                    'Menunggu_Verifikasi_Bayar', 'Menunggu_Verifikasi_Berkas' => 'bg-warning',
                    'Siap_Ujian'  => 'bg-info',
                    'Lulus'       => 'bg-success',
                    'Tidak_Lulus' => 'bg-danger',
                    default       => 'bg-primary'
                };
                return '<span class="badge ' . $class . '">' . str_replace('_', ' ', $pendaftaran->status_terkini) . '</span>';
            })
            ->editColumn('waktu_daftar', function ($pendaftaran) {
                return formatTanggalIndo($pendaftaran->waktu_daftar);
            })
            ->addColumn('action', function ($pendaftaran) {
                return view('pages.pmb.pendaftaran._actions', compact('pendaftaran'));
            })
            ->rawColumns(['no_pendaftaran', 'status_terkini', 'action'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load(['user', 'periode', 'jalur', 'pilihanProdi.prodi', 'dokumenUpload.jenisDokumen', 'riwayat.pelaku']);
        return view('pages.pmb.pendaftaran.show', compact('pendaftaran'));
    }

    /**
     * Show update status form
     */
    public function updateStatusForm(Pendaftaran $pendaftaran)
    {
        return view('pages.pmb.pendaftaran.update_status_form', compact('pendaftaran'));
    }

    /**
     * Update pendaftaran status (Admin)
     */
    public function updateStatus(Request $request, Pendaftaran $pendaftaran)
    {
        try {
            $this->PendaftaranService->updateStatus($pendaftaran->id, $request->status, $request->keterangan);
            return jsonSuccess('Status pendaftaran berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Show verification form for single document
     */
    public function verifyDocumentForm($documentId)
    {
        $document = \App\Models\Pmb\DokumenUpload::findOrFail(decryptId($documentId));
        return view('pages.pmb.pendaftaran.verify_doc_form', compact('document'));
    }

    /**
     * Verify single document
     */
    public function verifyDocument(Request $request, $documentId)
    {
        try {
            $this->PendaftaranService->verifyUploadedDocument(decryptId($documentId), $request->status, $request->keterangan);
            return jsonSuccess('Status dokumen berhasil diperbarui!');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
