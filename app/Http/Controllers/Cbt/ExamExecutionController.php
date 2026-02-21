<?php
namespace App\Http\Controllers\Cbt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cbt\LogViolationRequest;
use App\Http\Requests\Cbt\SaveAnswerRequest;
use App\Http\Requests\Cbt\StartExamRequest;
use App\Http\Requests\Cbt\SubmitExamRequest;
use App\Http\Requests\Cbt\ValidateTokenRequest;
use App\Models\Cbt\JadwalUjian;
use App\Models\Cbt\JawabanSiswa;
use App\Models\Cbt\LogPelanggaran;
use App\Models\Cbt\RiwayatUjianSiswa;
use App\Services\Cbt\ExamExecutionService;
use Exception;
use Log;

class ExamExecutionController extends Controller
{
    public function __construct(protected ExamExecutionService $examExecutionService)
    {}

    /**
     * Unified Dashboard (Admin & Camaba)
     */
    public function dashboard()
    {
        return view('pages.cbt.dashboard.index');
    }

    /**
     * API: Save answer
     */
    public function saveAnswerApi(SaveAnswerRequest $request)
    {
        try {
            $user    = auth()->user();
            $riwayat = RiwayatUjianSiswa::where('user_id', $user->id)
                ->where('status', 'Sedang_Mengerjakan')
                ->firstOrFail();

            $this->examExecutionService->saveAnswer($riwayat, $request->validated());

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Submit exam
     */
    public function submitExamApi(SubmitExamRequest $request)
    {
        try {
            $user    = auth()->user();
            $riwayat = RiwayatUjianSiswa::where('user_id', $user->id)
                ->where('status', 'Sedang_Mengerjakan')
                ->firstOrFail();

            $this->examExecutionService->submitExam($riwayat);

            return response()->json([
                'success'  => true,
                'redirect' => route('cbt.execute.finished', $riwayat->jadwal->hashid),
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Log violation
     */
    public function logViolationApi(LogViolationRequest $request)
    {
        try {
            $user = auth()->user();

            // Priority 1: Find active exam
            $riwayat = RiwayatUjianSiswa::where('user_id', $user->id)
                ->where('status', 'Sedang_Mengerjakan')
                ->first();

            // Priority 2: Try to find by jadwal_id from request
            if (! $riwayat) {
                $jadwalId = $request->input('jadwal_id');
                if ($jadwalId) {
                    $decryptedJadwalId = decryptIdIfEncrypted($jadwalId);
                    $riwayat           = RiwayatUjianSiswa::where('user_id', $user->id)
                        ->where('jadwal_id', $decryptedJadwalId)
                        ->first();
                }
            }

            // Priority 3: Find any recent riwayat (within last 24 hours)
            if (! $riwayat) {
                $riwayat = RiwayatUjianSiswa::where('user_id', $user->id)
                    ->where('waktu_mulai', '>=', now()->subHours(24))
                    ->orderBy('waktu_mulai', 'desc')
                    ->first();
            }

            if ($riwayat) {
                LogPelanggaran::create([
                    'riwayat_id'        => $riwayat->getKey(),
                    'jenis_pelanggaran' => $request->validated('type'),
                    'keterangan'        => $request->validated('keterangan') ?? $request->validated('type'),
                    'waktu_kejadian'    => now(),
                ]);

                return response()->json(['success' => true]);
            }

            // No riwayat found - log error but return success to avoid client errors
            Log::warning('Violation logged without valid riwayat', [
                'user_id' => $user->id,
                'type'    => $request->validated('type'),
            ]);

            return response()->json(['success' => true, 'warning' => 'No active exam found']);
        } catch (Exception $e) {
            logError($e);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Toggle token
     */
    public function toggleTokenApi(JadwalUjian $jadwal)
    {
        try {
            $jadwal->update(['is_token_aktif' => ! $jadwal->is_token_aktif]);

            return response()->json([
                'success'   => true,
                'is_active' => $jadwal->is_token_aktif,
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show token validation form (Modal)
     */
    public function tokenForm(JadwalUjian $jadwal)
    {
        return view('pages.cbt.execution.token_form', compact('jadwal'));
    }

    /**
     * Validate token and redirect to exam
     */
    public function validateToken(ValidateTokenRequest $request, JadwalUjian $jadwal)
    {
        try {
            if ($jadwal->token_ujian !== strtoupper($request->validated('token_ujian'))) {
                return jsonError('Token yang Anda masukkan salah.');
            }

            if (! $jadwal->is_token_aktif) {
                return jsonError('Token saat ini sedang tidak aktif.');
            }

            // Set session flag to allow access to start exam
            session(['cbt_token_validated_' . $jadwal->jadwal_ujian_id => true]);

            return jsonSuccess('Token valid.', route('cbt.execute.start', $jadwal->hashid));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Welcome / Info page before starting exam
     */
    public function welcome(JadwalUjian $jadwal)
    {
        $jadwal->load(['paket.komposisi']);
        $user = auth()->user();

        // Check if already finished
        $existing = RiwayatUjianSiswa::where('jadwal_id', $jadwal->jadwal_ujian_id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing && $existing->status === 'Selesai') {
            return redirect()->route('cbt.execute.finished', $jadwal->hashid)
                ->with('info', 'Anda telah menyelesaikan ujian ini.');
        }

        $totalSoal = $jadwal->paket->komposisi->count();
        $durasi    = $jadwal->waktu_mulai->diffInMinutes($jadwal->waktu_selesai);

        return view('pages.cbt.execution.welcome', compact('jadwal', 'totalSoal', 'durasi', 'existing'));
    }

    /**
     * Start the exam session (POST after welcome page)
     */
    public function start(StartExamRequest $request, JadwalUjian $jadwal)
    {
        try {

            $user = auth()->user();
            $jadwal->load(['paket.komposisi.soal.opsiJawaban', 'paket.komposisi.soal.mataUji']);
            $riwayat = $this->examExecutionService->startExam($jadwal, $user, [
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($riwayat->status === 'Selesai') {
                return redirect()->route('cbt.execute.finished', $jadwal->hashid)
                    ->with('info', 'Anda telah menyelesaikan ujian ini.');
            }

            // Build flat ordered soal collection from komposisi
            $paketSoal = $jadwal->paket->komposisi
                ->sortBy('urutan_tampil')
                ->map(fn($komp) => $komp->soal)
                ->filter()
                ->values();

            if ($jadwal->paket->is_acak_soal) {
                $paketSoal = $paketSoal->shuffle()->values();
            }

            return view('pages.cbt.execution.index', compact('jadwal', 'riwayat', 'paketSoal'));
        } catch (Exception $e) {
            logError($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Save answer (AJAX) - Hybrid sync logic
     */
    public function saveAnswer(SaveAnswerRequest $request, RiwayatUjianSiswa $riwayat)
    {
        try {
            $this->examExecutionService->saveAnswer($riwayat, $request->validated());
            return response()->json(['status' => 'success', 'message' => 'Jawaban disimpan.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Final submission
     */
    public function submit(RiwayatUjianSiswa $riwayat)
    {
        try {
            $this->examExecutionService->submitExam($riwayat);

            $redirect = auth()->user()->hasRole('admin') ? route('cbt.dashboard') : route('cbt.execute.finished', $riwayat->jadwal->hashid);
            return jsonSuccess('Ujian berhasil diserahkan. Terima kasih.', $redirect);
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Exam finished / success page
     */
    public function finished(JadwalUjian $jadwal)
    {
        $user    = auth()->user();
        $riwayat = RiwayatUjianSiswa::where('jadwal_id', $jadwal->jadwal_ujian_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('pages.cbt.execution.finished', compact('jadwal', 'riwayat'));
    }

    /**
     * Reset admin exam history for testing
     */
    public function resetAdminExam(JadwalUjian $jadwal)
    {
        try {
            if (! auth()->user()->hasRole('admin')) {
                return jsonError('Hanya admin yang dapat mereset data ujian testing.');
            }

            $user    = auth()->user();
            $riwayat = RiwayatUjianSiswa::where('jadwal_id', $jadwal->jadwal_ujian_id)
                ->where('user_id', $user->id)
                ->first();

            if ($riwayat) {
                // Delete answers first
                JawabanSiswa::where('riwayat_id', $riwayat->riwayat_ujian_id)->forceDelete();
                // Delete violations
                LogPelanggaran::where('riwayat_id', $riwayat->riwayat_ujian_id)->forceDelete();
                // Force delete history
                $riwayat->forceDelete();
            }

            return jsonSuccess('Data testing berhasil direset.', route('cbt.execute.start', $jadwal->hashid));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Test exam for admin: reset history and bypass token, then redirect to exam
     */
    public function testExam(JadwalUjian $jadwal)
    {
        try {
            $user    = auth()->user();
            $riwayat = RiwayatUjianSiswa::where('jadwal_id', $jadwal->jadwal_ujian_id)
                ->where('user_id', $user->id)
                ->first();

            if ($riwayat) {
                JawabanSiswa::where('riwayat_id', $riwayat->riwayat_ujian_id)->forceDelete();
                LogPelanggaran::where('riwayat_id', $riwayat->riwayat_ujian_id)->forceDelete();
                $riwayat->forceDelete();
            }

            return redirect()->route('cbt.execute.welcome', $jadwal->hashid)
                ->with('info', 'Riwayat ujian sebelumnya telah direset. Silakan mulai ujian.');
        } catch (Exception $e) {
            logError($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Exam Monitoring for Admin
     */
    public function monitor(JadwalUjian $jadwal)
    {
        $jadwal->load(['riwayatSiswa.user', 'riwayatSiswa.jawaban']);
        return view('pages.cbt.execution.monitor', compact('jadwal'));
    }

    /**
     * Violation Reports for Admin
     */
    public function violations()
    {
        $violations = LogPelanggaran::with(['riwayatUjianSiswa.user', 'riwayatUjianSiswa.jadwal'])
            ->latest('waktu_kejadian')
            ->paginate(20);

        return view('pages.cbt.execution.violations', compact('violations'));
    }
}
