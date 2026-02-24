<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\VerifyPaymentRequest;
use App\Models\Pmb\Pembayaran;
use App\Models\Pmb\Pendaftaran;
use App\Services\Pmb\PendaftaranService;
use App\Services\Pmb\VerificationService;
use Exception;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function __construct(
        protected VerificationService $verificationService,
        protected PendaftaranService $pendaftaranService
    ) {}

    /**
     * List of pending payments
     */
    public function payments()
    {
        return view('pages.pmb.verification.payments');
    }

    /**
     * Paginate pending payments
     */
    public function paginatePayments(Request $request)
    {
        return datatables()->of($this->verificationService->getPendingPaymentsQuery())
            ->addIndexColumn()
            ->editColumn('jumlah_bayar', fn($p) => 'Rp ' . number_format($p->jumlah_bayar, 0, ',', '.'))
            ->addColumn('action', function ($row) {
                return view('pages.pmb.verification._payment_action', [
                    'row'       => $row,
                    'verifyUrl' => route('pmb.verification.payment-form', $row->encrypted_pembayaran_id),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show verification form for payment
     */
    public function paymentForm($encryptedId)
    {
        $pembayaran = Pembayaran::findOrFail(decryptIdIfEncrypted($encryptedId));
        $pembayaran = $this->verificationService->getPaymentDetails($pembayaran);
        return view('pages.pmb.verification.payment_form', compact('pembayaran'));
    }

    /**
     * Verify Payment Action
     */
    public function verifyPayment(VerifyPaymentRequest $request, $encryptedId)
    {
        $pembayaran = Pembayaran::findOrFail(decryptIdIfEncrypted($encryptedId));
        
        try {
            $this->verificationService->verifyPayment($pembayaran, $request->validated());
            return jsonSuccess('Proses verifikasi pembayaran selesai.', route('pmb.verification.payments'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * List of pendaftaran for document verification
     */
    public function documents()
    {
        return view('pages.pmb.verification.documents');
    }

    /**
     * Paginate pendaftaran for document verification
     */
    public function paginateDocuments(Request $request)
    {
        $filters           = $request->all();
        $filters['status'] = 'Menunggu_Verifikasi_Berkas';

        return datatables()->of($this->pendaftaranService->getFilteredQuery($filters))
            ->addIndexColumn()
            ->addColumn('action', function ($p) {
                return '<a href="' . route('pmb.pendaftaran.show', $p->encrypted_pendaftaran_id) . '" class="btn btn-sm btn-primary">Verifikasi Berkas</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Load berkas for modal verification
     */
    public function loadBerkas(Request $request)
    {
        $pendaftaranId = $request->pendaftaran_id;
        
        $pendaftaran = Pendaftaran::with(['dokumenUpload.jenisDokumen', 'camaba.user'])
            ->findOrFail($pendaftaranId);
        
        $html = view('pages.pmb.verification._modal_berkas', compact('pendaftaran'))->render();
        
        return response()->json(['html' => $html]);
    }

    /**
     * Verify document batch
     */
    public function verifyDocumentBatch(Request $request)
    {
        $request->validate([
            'dokumen_ids' => 'required|array',
            'status' => 'required|in:Valid,Ditolak',
            'catatan' => 'nullable|string',
        ]);
        
        try {
            foreach ($request->dokumen_ids as $dokumenId) {
                $dokumen = \App\Models\Pmb\DokumenUpload::findOrFail($dokumenId);
                $dokumen->update([
                    'status_verifikasi' => $request->status,
                    'catatan_verifikasi' => $request->catatan,
                ]);
            }
            
            // Update status pendaftaran if all documents verified
            $pendaftaran = $pendaftaran->dokumenUpload->first()?->pendaftaran;
            if ($pendaftaran && $request->status === 'Valid') {
                $allVerified = $pendaftaran->dokumenUpload->where('status_verifikasi', '!=', 'Valid')->isEmpty();
                if ($allVerified) {
                    $pendaftaran->update(['status_terkini' => 'Siap_Ujian']);
                }
            }
            
            logActivity('pmb_verifikasi_berkas', "Verifikasi berkas batch: {$request->status}", $pendaftaran);
            
            return jsonSuccess('Verifikasi berkas berhasil.');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memverifikasi berkas: ' . $e->getMessage());
        }
    }
}
