<?php
namespace App\Http\Controllers\Survei;

use App\Http\Controllers\Controller;
use App\Models\Survei\Halaman;
use App\Models\Survei\Pertanyaan;
use App\Models\Survei\Survei;
use App\Services\Survei\FormBuilderService;
use Illuminate\Http\Request;

class FormBuilderController extends Controller
{
    public function __construct(protected FormBuilderService $formBuilderService)
    {}

    public function index(Survei $survei)
    {
        $survei        = $this->formBuilderService->getSurveyForBuilder($survei);
        $allPertanyaan = $survei->pertanyaan()->orderBy('urutan')->get();

        return view('pages.survei.admin.builder', compact('survei', 'allPertanyaan'));
    }

    /**
     * Preview the survey as it would appear to the user.
     */
    public function preview(Survei $survei)
    {
        $survei = $this->formBuilderService->getSurveyForBuilder($survei);

        return view('pages.survei.player.show', [
            'survei'    => $survei,
            'isPreview' => true,
        ]);
    }

    // --- Halaman Methods ---

    public function storeHalaman(Request $request, Survei $survei)
    {
        $halaman = $this->formBuilderService->addHalaman($survei);
        return jsonSuccess('Halaman berhasil ditambahkan', null, ['halaman' => $halaman]);
    }

    public function editHalaman(Halaman $halaman)
    {
        return view('pages.survei.admin.ajax.form-halaman', compact('halaman'));
    }

    public function updateHalaman(\App\Http\Requests\Survei\HalamanRequest $request, Halaman $halaman)
    {
        $this->formBuilderService->updateHalaman($halaman, $request->validated());
        return jsonSuccess('Halaman diperbarui');
    }

    public function destroyHalaman(Halaman $halaman)
    {
        $this->formBuilderService->deleteHalaman($halaman);
        return jsonSuccess('Halaman dihapus');
    }

    public function reorderHalaman(\App\Http\Requests\Survei\ReorderHalamanRequest $request)
    {
        $this->formBuilderService->reorderHalaman($request->validated()['order']);
        return jsonSuccess('Urutan halaman disimpan');
    }

    // --- Pertanyaan Methods ---

    public function storePertanyaan(\App\Http\Requests\Survei\PertanyaanRequest $request, Survei $survei)
    {
        $pertanyaan = $this->formBuilderService->addPertanyaan($survei, $request->validated());

        $pertanyaan->load('opsi');
        $allPertanyaan = $survei->pertanyaan()->orderBy('urutan')->get();
        $html          = view('pages.survei.admin.partials.question_card', compact('pertanyaan', 'allPertanyaan'))->render();

        return jsonSuccess('Pertanyaan ditambahkan', null, ['html' => $html]);
    }

    public function updatePertanyaan(\App\Http\Requests\Survei\PertanyaanRequest $request, Pertanyaan $pertanyaan)
    {
        $this->formBuilderService->updatePertanyaan($pertanyaan, $request->validated());

        // Reload and return fresh HTML for the question card
        $pertanyaan->load('opsi');
        $allPertanyaan = $pertanyaan->survei->pertanyaan()->orderBy('urutan')->get();
        $html          = view('pages.survei.admin.partials.question_card', compact('pertanyaan', 'allPertanyaan'))->render();

        return jsonSuccess('Pertanyaan disimpan', null, ['html' => $html]);
    }

    public function destroyPertanyaan(Pertanyaan $pertanyaan)
    {
        $this->formBuilderService->deletePertanyaan($pertanyaan);
        return jsonSuccess('Pertanyaan dihapus');
    }

    public function reorderPertanyaan(\App\Http\Requests\Survei\ReorderPertanyaanRequest $request)
    {
        $this->formBuilderService->reorderPertanyaan($request->validated()['order']);
        return jsonSuccess('Urutan pertanyaan disimpan');
    }
}
