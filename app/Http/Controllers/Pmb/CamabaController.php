<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\ConfirmPaymentRequest;
use App\Http\Requests\Pmb\FileUploadRequest;
use App\Http\Requests\Pmb\StoreRegistrationRequest;
use App\Models\Cbt\JadwalUjian;
use App\Models\Pmb\Jalur;
use App\Models\Pmb\JenisDokumen;
use App\Models\Pmb\Pendaftaran;
use App\Models\Pmb\Prodi;
use App\Models\Pmb\SyaratDokumenJalur;
use App\Services\Pmb\CamabaService;
use App\Services\Pmb\PeriodeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CamabaController extends Controller
{
    protected $CamabaService;
    protected $PeriodeService;

    public function __construct(CamabaService $CamabaService, PeriodeService $PeriodeService)
    {
        $this->CamabaService  = $CamabaService;
        $this->PeriodeService = $PeriodeService;
    }

    /**
     * Camaba Dashboard
     */
    public function dashboard()
    {
        $user        = Auth::user();
        $pendaftaran = Pendaftaran::with(['periode', 'jalur', 'pilihanProdi.prodi', 'prodiDiterima'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $periodeAktif = $this->PeriodeService->getActivePeriode();

        $activeJadwal = null;
        if ($pendaftaran && in_array($pendaftaran->status_terkini, ['Siap_Ujian', 'Sedang_Ujian'])) {
            $activeJadwal = JadwalUjian::whereIn('id', function ($query) use ($user) {
                $query->select('jadwal_id')->from('cbt_peserta_berhak')->where('user_id', $user->id);
            })
                ->where('waktu_mulai', '<=', now())
                ->where('waktu_selesai', '>=', now())
                ->first();
        }

        return view('pages.pmb.camaba.dashboard', compact('pendaftaran', 'periodeAktif', 'activeJadwal'));
    }

    /**
     * Start new registration form
     */
    public function create()
    {
        $user = Auth::user();

        $periodeAktif = $this->PeriodeService->getActivePeriode();
        if (! $periodeAktif) {
            return redirect()->route('pmb.camaba.dashboard')->with('error', 'Tidak ada periode pendaftaran yang aktif saat ini.');
        }

        $existing = Pendaftaran::where('user_id', $user->id)
            ->where('periode_id', $periodeAktif->id)
            ->first();

        if ($existing) {
            return redirect()->route('pmb.camaba.dashboard')->with('info', 'Anda sudah memiliki pendaftaran di periode ini.');
        }

        $jalur  = Jalur::where('is_aktif', true)->get();
        $prodi  = Prodi::all();
        $profil = $user->profilPmb;

        return view('pages.pmb.camaba.register', compact('periodeAktif', 'jalur', 'prodi', 'profil'));
    }

    /**
     * Store registration data
     */
    public function store(StoreRegistrationRequest $request)
    {
        try {
            $this->CamabaService->createRegistration($request->validated());
            return jsonSuccess('Pendaftaran berhasil dibuat. Silakan lengkapi langkah selanjutnya.', route('pmb.camaba.dashboard'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Payment Page
     */
    public function payment()
    {
        $user        = Auth::user();
        $pendaftaran = Pendaftaran::with('jalur')
            ->where('user_id', $user->id)
            ->where('status_terkini', 'Draft')
            ->latest()
            ->firstOrFail();

        return view('pages.pmb.camaba.payment', compact('pendaftaran'));
    }

    /**
     * Confirm Payment
     */
    public function confirmPayment(ConfirmPaymentRequest $request)
    {
        try {
            $pendaftaran = Pendaftaran::findOrFail($request->pendaftaran_id);
            $this->CamabaService->confirmPayment($pendaftaran, $request->validated(), $request->file('bukti_bayar'));
            return jsonSuccess('Konfirmasi pembayaran berhasil dikirim.', route('pmb.camaba.dashboard'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Document Upload Page
     */
    public function upload()
    {
        $user        = Auth::user();
        $pendaftaran = Pendaftaran::with(['jalur', 'dokumenUpload.jenisDokumen'])
            ->where('user_id', $user->id)
            ->whereIn('status_terkini', ['Menunggu_Verifikasi_Berkas', 'Draft_Berkas', 'Revisi_Berkas'])
            ->latest()
            ->firstOrFail();

        $syarat = SyaratDokumenJalur::with('jenisDokumen')
            ->where('jalur_id', $pendaftaran->jalur_id)
            ->get();

        return view('pages.pmb.camaba.upload', compact('pendaftaran', 'syarat'));
    }

    /**
     * Individual Upload Form (Modal)
     */
    public function uploadForm(Request $request)
    {
        $pendaftaran_id   = $request->pendaftaran;
        $jenis_dokumen_id = $request->jenis;
        $jenis            = JenisDokumen::findOrFail(decryptId($jenis_dokumen_id));

        return view('pages.pmb.camaba.upload_form', compact('pendaftaran_id', 'jenis_dokumen_id', 'jenis'));
    }

    /**
     * Do Upload
     */
    public function doUpload(FileUploadRequest $request)
    {
        try {
            $pendaftaran = Pendaftaran::findOrFail($request->pendaftaran_id);
            $this->CamabaService->uploadFile($pendaftaran, $request->jenis_dokumen_id, $request->file('file'));
            return jsonSuccess('Dokumen berhasil diunggah.', route('pmb.camaba.upload'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Finalize Upload
     */
    public function finalizeFiles(Request $request)
    {
        try {
            $pendaftaran = Pendaftaran::findOrFail(decryptId($request->pendaftaran_id));
            $this->CamabaService->finalizeFiles($pendaftaran);
            return jsonSuccess('Berkas pendaftaran telah diajukan untuk verifikasi.', route('pmb.camaba.dashboard'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Exam Card View
     */
    public function examCard()
    {
        $user        = Auth::user();
        $pendaftaran = Pendaftaran::with(['periode', 'jalur', 'pilihanProdi.prodi', 'pesertaUjian.sesiUjian'])
            ->where('user_id', $user->id)
            ->latest()
            ->firstOrFail();

        return view('pages.pmb.camaba.exam-card', compact('pendaftaran'));
    }
}
