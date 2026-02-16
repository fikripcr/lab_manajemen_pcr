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
