<?php
namespace App\Http\Controllers\Cbt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cbt\SaveAnswerRequest;
use App\Models\Cbt\JadwalUjian;
use App\Models\Cbt\RiwayatUjianSiswa;
use App\Services\Cbt\ExamExecutionService;
use Exception;
use Illuminate\Http\Request;

class ExamExecutionController extends Controller
{
    protected $ExamExecutionService;

    public function __construct(ExamExecutionService $ExamExecutionService)
    {
        $this->ExamExecutionService = $ExamExecutionService;
    }

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
    public function saveAnswerApi(Request $request)
    {
        try {
            $user = auth()->user();
            $riwayat = RiwayatUjianSiswa::where('user_id', $user->id)
                ->where('status', 'Sedang_Mengerjakan')
                ->firstOrFail();

            $this->ExamExecutionService->saveAnswer($riwayat, $request->all());
            
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Submit exam
     */
    public function submitExamApi(Request $request)
    {
        try {
            $user = auth()->user();
            $riwayat = RiwayatUjianSiswa::where('user_id', $user->id)
                ->where('status', 'Sedang_Mengerjakan')
                ->firstOrFail();

            $this->ExamExecutionService->submitExam($riwayat);
            
            return response()->json([
                'success' => true, 
                'redirect' => route('cbt.exam.complete', $riwayat->hashid)
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Log violation
     */
    public function logViolationApi(Request $request)
    {
        try {
            $user = auth()->user();
            $riwayat = RiwayatUjianSiswa::where('user_id', $user->id)
                ->where('status', 'Sedang_Mengerjakan')
                ->first();

            if ($riwayat) {
                \App\Models\Cbt\LogPelanggaran::create([
                    'riwayat_id' => $riwayat->id,
                    'jenis_pelanggaran' => $request->type,
                    'keterangan' => $request->keterangan ?? null,
                    'waktu_kejadian' => now()
                ]);
            }
            
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Toggle token
     */
    public function toggleTokenApi(JadwalUjian $jadwal)
    {
        try {
            $jadwal->update(['is_token_aktif' => !$jadwal->is_token_aktif]);
            
            return response()->json([
                'success' => true,
                'is_active' => $jadwal->is_token_aktif
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
    public function validateToken(Request $request, JadwalUjian $jadwal)
    {
        try {
            if ($jadwal->token_ujian !== strtoupper($request->token_ujian)) {
                return jsonError('Token yang Anda masukkan salah.');
            }

            if (! $jadwal->is_token_aktif) {
                return jsonError('Token saat ini sedang tidak aktif.');
            }

            return jsonSuccess('Token valid.', route('cbt.execute.start', $jadwal->hashid));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Start the exam session
     */
    public function start(Request $request, JadwalUjian $jadwal)
    {
        try {
            $user = auth()->user();
            $jadwal->load(['paket.komposisi.soal.opsiJawaban']);

            $riwayat = $this->ExamExecutionService->startExam($jadwal, $user, [
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($riwayat->status !== 'Sedang_Mengerjakan') {
                return redirect()->route('pmb.camaba.dashboard')->with('error', 'Ujian telah selesai atau tidak dapat diakses.');
            }

            $paketSoal = $jadwal->paket->komposisi->map(fn($komp) => $komp->soal);
            if ($jadwal->paket->is_acak_soal) {
                $paketSoal = $paketSoal->shuffle();
            }

            return view('pages.cbt.execution.index', compact('jadwal', 'riwayat', 'paketSoal'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Save answer (AJAX) - Hybrid sync logic
     */
    public function saveAnswer(SaveAnswerRequest $request, RiwayatUjianSiswa $riwayat)
    {
        try {
            $this->ExamExecutionService->saveAnswer($riwayat, $request->validated());
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
            $this->ExamExecutionService->submitExam($riwayat);
            return jsonSuccess('Ujian berhasil diserahkan. Terima kasih.', route('pmb.camaba.dashboard'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
