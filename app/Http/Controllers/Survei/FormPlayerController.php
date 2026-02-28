<?php
namespace App\Http\Controllers\Survei;

use App\Http\Controllers\Controller;
use App\Http\Requests\Survei\FormPlayerRequest;
use App\Models\Survei\Survei;
use App\Services\Survei\FormPlayerService;

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

        $this->formPlayerService->validateAccessibility($survei);

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

        $this->formPlayerService->validateAccessibility($survei);
        
        // Create session flag for survey in progress
        session(['survei_in_progress_' . $survei->id => true]);
        
        return redirect()->route('survei.public.show', $survei->slug);
    }

    public function show($slug)
    {
        $survei = Survei::where('slug', $slug)
            ->where('is_aktif', true)
            ->firstOrFail();

        $this->formPlayerService->validateAccessibility($survei);

        $survei = $this->formPlayerService->getSurveyForPlayer($survei);

        return view('pages.survei.player.show', compact('survei'));
    }

    public function store(FormPlayerRequest $request, $slug)
    {
        $survei = Survei::where('slug', $slug)
            ->where('is_aktif', true)
            ->firstOrFail();

        $jawaban = $request->input('jawaban', []);
        $this->formPlayerService->submitSurvey($survei, $jawaban, $request->ip());
        
        return jsonSuccess('Jawaban berhasil disimpan', route('survei.public.thankyou', $survei->slug));
    }

    public function thankyou($slug)
    {
        $survei = Survei::where('slug', $slug)->firstOrFail();
        return view('pages.survei.player.thankyou', compact('survei'));
    }
}
