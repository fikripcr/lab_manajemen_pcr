<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\VerifyPaymentRequest;
use App\Models\Pmb\Pembayaran;
use App\Models\Pmb\Pendaftaran;
use App\Services\Pmb\VerificationService;
use Exception;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    protected $VerificationService;

    public function __construct(VerificationService $VerificationService)
    {
        $this->VerificationService = $VerificationService;
    }

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
        $query = Pembayaran::with(['pendaftaran.user', 'pendaftaran.jalur'])
            ->where('status_verifikasi', 'Pending');

        return datatables()->of($query)
            ->addIndexColumn()
            ->editColumn('jumlah_bayar', fn($p) => 'Rp ' . number_format($p->jumlah_bayar, 0, ',', '.'))
            ->addColumn('action', function ($p) {
                return '<button class="btn btn-sm btn-primary ajax-modal-btn" data-modal-target="#modalAction"
                        data-modal-title="Verifikasi Pembayaran" data-url="' . route('pmb.verification.payment-form', $p->hashid) . '">
                        Detail</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show verification form for payment
     */
    public function paymentForm(Pembayaran $pembayaran)
    {
        $pembayaran->load(['pendaftaran.user']);
        return view('pages.pmb.verification.payment_form', compact('pembayaran'));
    }

    /**
     * Verify Payment Action
     */
    public function verifyPayment(VerifyPaymentRequest $request, Pembayaran $pembayaran)
    {
        try {
            $this->VerificationService->verifyPayment($pembayaran, $request->validated());
            return jsonSuccess('Proses verifikasi pembayaran selesai.', route('pmb.verification.payments'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
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
        $query = Pendaftaran::with(['user', 'jalur'])
            ->where('status_terkini', 'Menunggu_Verifikasi_Berkas');

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($p) {
                return '<a href="' . route('pmb.pendaftaran.show', $p->hashid) . '" class="btn btn-sm btn-primary">Verifikasi Berkas</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
