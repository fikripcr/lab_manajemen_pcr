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
            abort(403, $e->getMessage());
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
            $this->formPlayerService->submitSurvey($survei, $request->jawaban, $request->ip());
            return redirect()->route('survei.public.thankyou', $survei->slug);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan jawaban: ' . $e->getMessage());
        }
    }

    public function thankyou($slug)
    {
        $survei = Survei::where('slug', $slug)->firstOrFail();
        return view('pages.survei.player.thankyou', compact('survei'));
    }
}
