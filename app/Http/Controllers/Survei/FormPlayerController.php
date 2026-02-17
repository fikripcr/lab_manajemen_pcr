<?php
namespace App\Http\Controllers\Survei;

use App\Http\Controllers\Controller;
use App\Models\Survei\Survei;
use App\Services\Survei\FormPlayerService;
use Illuminate\Http\Request;

class FormPlayerController extends Controller
{
    protected $FormPlayerService;

    public function __construct(FormPlayerService $FormPlayerService)
    {
        $this->FormPlayerService = $FormPlayerService;
    }

    public function show($slug)
    {
        $survei = Survei::where('slug', $slug)
            ->where('is_aktif', true)
            ->firstOrFail();

        try {
            $this->FormPlayerService->validateAccessibility($survei);
        } catch (\Exception $e) {
            if ($e->getMessage() == 'AUTH_REQUIRED') {
                return redirect()->route('login')->with('error', 'Anda harus login untuk mengisi survei ini.');
            }
            if ($e->getMessage() == 'ALREADY_FILLED') {
                return redirect()->route('dashboard')->with('error', 'Anda sudah mengisi survei ini.');
            }
            abort(403, $e->getMessage());
        }

        $survei = $this->FormPlayerService->getSurveyForPlayer($survei);

        return view('pages.survei.player.show', compact('survei'));
    }

    public function store(Request $request, $slug)
    {
        $survei = Survei::where('slug', $slug)
            ->where('is_aktif', true)
            ->firstOrFail();

        // Validation logic stays in controller if standard, but here it's dynamic
        $rules         = ['jawaban' => 'required|array'];
        $pertanyaanMap = $survei->pertanyaan()->pluck('tipe', 'id');

        foreach ($pertanyaanMap as $id => $tipe) {
            $pertanyaan = $survei->pertanyaan()->find($id);
            if ($pertanyaan && $pertanyaan->wajib_diisi) {
                $rules["jawaban.{$id}"] = 'required';
            }
        }

        $request->validate($rules, [
            'jawaban.required'   => 'Anda harus mengisi minimal satu jawaban.',
            'jawaban.*.required' => 'Pertanyaan wajib harus diisi.',
        ]);

        try {
            $this->FormPlayerService->submitSurvey($survei, $request->jawaban, $request->ip());
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
