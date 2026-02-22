<?php
namespace App\Http\Controllers\Survei;

use App\Http\Controllers\Controller;
use App\Http\Requests\Survei\FormPlayerRequest;
use App\Models\Survei\Survei;
use App\Services\Survei\FormPlayerService;
use Exception;

class FormPlayerController extends Controller
{
    public function __construct(protected FormPlayerService $formPlayerService)
    {}

    /**
     * Welcome page before starting survey
     */
    public function welcome($slug)
    {
        $survei = Survei::where('slug', $slug)
            ->where('is_aktif', true)
            ->firstOrFail();

        try {
            $this->formPlayerService->validateAccessibility($survei);
        } catch (\Exception $e) {
            if ($e->getMessage() == 'AUTH_REQUIRED') {
                return redirect()->route('login')->with('error', 'Anda harus login untuk mengisi survei ini.');
            }
            if ($e->getMessage() == 'ALREADY_FILLED') {
                return redirect()->route('dashboard')->with('error', 'Anda sudah mengisi survei ini.');
            }
            abort(403, $e->getMessage());
        }

        // Load survey structure
        $survei = $this->formPlayerService->getSurveyForPlayer($survei);
        
        // Calculate stats
        $totalPertanyaan = $survei->pertanyaan->count();
        $estimatedTime = $totalPertanyaan * 2; // 2 minutes per question estimate
        $tanggalMulai = $survei->tanggal_mulai;
        $tanggalSelesai = $survei->tanggal_selesai;

        return view('pages.survei.player.welcome', compact('survei', 'totalPertanyaan', 'estimatedTime', 'tanggalMulai', 'tanggalSelesai'));
    }

    /**
     * Start survey session
     */
    public function start($slug)
    {
        $survei = Survei::where('slug', $slug)
            ->where('is_aktif', true)
            ->firstOrFail();

        try {
            $this->formPlayerService->validateAccessibility($survei);
            
            // Create session flag for survey in progress
            session(['survei_in_progress_' . $survei->id => true]);
            
            return redirect()->route('survei.public.show', $survei->slug);
        } catch (Exception $e) {
            return redirect()->route('survei.public.welcome', $survei->slug)
                ->with('error', $e->getMessage());
        }
    }

    public function show($slug)
    {
        $survei = Survei::where('slug', $slug)
            ->where('is_aktif', true)
            ->firstOrFail();

        try {
            $this->formPlayerService->validateAccessibility($survei);
        } catch (\Exception $e) {
            if ($e->getMessage() == 'AUTH_REQUIRED') {
                return redirect()->route('login')->with('error', 'Anda harus login untuk mengisi survei ini.');
            }
            if ($e->getMessage() == 'ALREADY_FILLED') {
                return redirect()->route('dashboard')->with('error', 'Anda sudah mengisi survei ini.');
            }
            // Redirect to welcome if not started
            return redirect()->route('survei.public.welcome', $survei->slug);
        }

        $survei = $this->formPlayerService->getSurveyForPlayer($survei);

        return view('pages.survei.player.show', compact('survei'));
    }

    public function store(FormPlayerRequest $request, $slug)
    {
        $survei = Survei::where('slug', $slug)
            ->where('is_aktif', true)
            ->firstOrFail();

        try {
            $jawaban = $request->input('jawaban', []);
            $this->formPlayerService->submitSurvey($survei, $jawaban, $request->ip());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jawaban berhasil disimpan',
                    'redirect' => route('survei.public.thankyou', $survei->slug),
                ]);
            }
            
            return redirect()->route('survei.public.thankyou', $survei->slug)
                ->with('success', 'Terima kasih! Jawaban Anda berhasil disimpan.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan jawaban: ' . $e->getMessage(),
                ], 500);
            }
            
            return back()->with('error', 'Gagal menyimpan jawaban: ' . $e->getMessage());
        }
    }

    public function thankyou($slug)
    {
        $survei = Survei::where('slug', $slug)->firstOrFail();
        return view('pages.survei.player.thankyou', compact('survei'));
    }
}
