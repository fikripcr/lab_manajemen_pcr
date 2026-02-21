<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\ConfirmPaymentRequest;
use App\Http\Requests\Pmb\FileUploadRequest;
use App\Http\Requests\Pmb\StoreRegistrationRequest;
use App\Models\Pmb\JenisDokumen;
use App\Models\Pmb\Pendaftaran;
use App\Services\Pmb\CamabaService;
use App\Services\Pmb\PeriodeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Use OrgUnit

class CamabaController extends Controller
{
    public function __construct(
        protected CamabaService $camabaService,
        protected PeriodeService $periodeService
    ) {}

    /**
     * Camaba Dashboard
     */
    public function dashboard()
    {
        $data = $this->camabaService->getDashboardData(Auth::user());
        return view('pages.pmb.camaba.dashboard', $data);
    }

    /**
     * Start new registration form
     */
    public function create()
    {
        $data = $this->camabaService->getRegistrationFormData(Auth::user());

        if (isset($data['error'])) {
            return redirect()->route('pmb.camaba.dashboard')->with('error', $data['error']);
        }

        if (isset($data['info'])) {
            return redirect()->route('pmb.camaba.dashboard')->with('info', $data['info']);
        }

        return view('pages.pmb.camaba.register', $data);
    }

    /**
     * Store registration data
     */
    public function store(StoreRegistrationRequest $request)
    {
        try {
            $this->camabaService->createRegistration($request->validated());
            return jsonSuccess('Pendaftaran berhasil dibuat. Silakan lengkapi langkah selanjutnya.', route('pmb.camaba.dashboard'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal membuat pendaftaran: ' . $e->getMessage());
        }
    }

    /**
     * Payment Page
     */
    public function payment()
    {
        try {
            $pendaftaran = $this->camabaService->getPendingPaymentRegistration(Auth::user());
            return view('pages.pmb.camaba.payment', compact('pendaftaran'));
        } catch (Exception $e) {
            return redirect()->route('pmb.camaba.dashboard')->with('error', $e->getMessage());
        }
    }

    /**
     * Confirm Payment
     */
    public function confirmPayment(ConfirmPaymentRequest $request, Pendaftaran $pendaftaran)
    {
        try {
            $this->camabaService->confirmPayment($pendaftaran, $request->validated(), $request->file('bukti_bayar'));
            return jsonSuccess('Konfirmasi pembayaran berhasil dikirim.', route('pmb.camaba.dashboard'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal mengirim konfirmasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Document Upload Page
     */
    public function upload()
    {
        try {
            $data = $this->camabaService->getUploadData(Auth::user());
            return view('pages.pmb.camaba.upload', $data);
        } catch (Exception $e) {
            return redirect()->route('pmb.camaba.dashboard')->with('error', $e->getMessage());
        }
    }

    /**
     * Individual Upload Form (Modal)
     */
    public function uploadForm(Request $request)
    {
        $pendaftaran_id   = $request->input('pendaftaran');
        $jenis_dokumen_id = $request->input('jenis');
        $jenis            = JenisDokumen::findOrFail(decryptIdIfEncrypted($jenis_dokumen_id));

        return view('pages.pmb.camaba.upload_form', compact('pendaftaran_id', 'jenis_dokumen_id', 'jenis'));
    }

    /**
     * Do Upload
     */
    public function doUpload(FileUploadRequest $request, Pendaftaran $pendaftaran, JenisDokumen $jenis)
    {
        try {
            $this->camabaService->uploadFile($pendaftaran, $jenis->jenis_dokumen_id, $request->file('file'));
            return jsonSuccess('Dokumen berhasil diunggah.', route('pmb.camaba.upload'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal mengunggah dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Finalize Upload
     */
    public function finalizeFiles(Pendaftaran $pendaftaran)
    {
        try {
            $this->camabaService->finalizeFiles($pendaftaran);
            return jsonSuccess('Berkas pendaftaran telah diajukan untuk verifikasi.', route('pmb.camaba.dashboard'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memfinalisasi berkas: ' . $e->getMessage());
        }
    }

    /**
     * Exam Card View
     */
    public function examCard()
    {
        try {
            $pendaftaran = $this->camabaService->getExamCardData(Auth::user());
            return view('pages.pmb.camaba.exam-card', compact('pendaftaran'));
        } catch (Exception $e) {
            return redirect()->route('pmb.camaba.dashboard')->with('error', $e->getMessage());
        }
    }
}
