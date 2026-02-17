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
    protected $FormBuilderService;

    public function __construct(FormBuilderService $FormBuilderService)
    {
        $this->FormBuilderService = $FormBuilderService;
    }

    public function index(Survei $survei)
    {
        $survei        = $this->FormBuilderService->getSurveyForBuilder($survei);
        $allPertanyaan = $survei->pertanyaan()->orderBy('urutan')->get();

        return view('pages.survei.admin.builder', compact('survei', 'allPertanyaan'));
    }

    /**
     * Preview the survey as it would appear to the user.
     */
    public function preview(Survei $survei)
    {
        $survei = $this->FormBuilderService->getSurveyForBuilder($survei);

        return view('pages.survei.player.show', [
            'survei'    => $survei,
            'isPreview' => true,
        ]);
    }

    // --- Halaman Methods ---

    public function storeHalaman(Request $request, Survei $survei)
    {
        $halaman = $this->FormBuilderService->addHalaman($survei);
        return jsonSuccess('Halaman berhasil ditambahkan', null, ['halaman' => $halaman]);
    }

    public function updateHalaman(Request $request, Halaman $halaman)
    {
        $this->FormBuilderService->updateHalaman($halaman, $request->only('judul_halaman', 'deskripsi_halaman'));
        return jsonSuccess('Halaman diperbarui');
    }

    public function destroyHalaman(Halaman $halaman)
    {
        try {
            $this->FormBuilderService->deleteHalaman($halaman);
            return jsonSuccess('Halaman dihapus');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function reorderHalaman(Request $request)
    {
        if (is_array($request->order)) {
            $this->FormBuilderService->reorderHalaman($request->order);
        }
        return jsonSuccess('Urutan halaman disimpan');
    }

    // --- Pertanyaan Methods ---

    public function storePertanyaan(Request $request, Survei $survei)
    {
        $validated = $request->validate([
            'halaman_id'      => 'required|exists:survei_halaman,id',
            'tipe'            => 'required|in:Teks_Singkat,Esai,Angka,Pilihan_Ganda,Kotak_Centang,Dropdown,Skala_Linear,Tanggal,Upload_File,Rating_Bintang',
            'teks_pertanyaan' => 'required|string',
        ]);

        try {
            $pertanyaan = $this->FormBuilderService->addPertanyaan($survei, $validated);

            $pertanyaan->load('opsi');
            $allPertanyaan = $survei->pertanyaan()->orderBy('urutan')->get();
            $html          = view('pages.survei.admin.partials.question_card', compact('pertanyaan', 'allPertanyaan'))->render();

            return jsonSuccess('Pertanyaan ditambahkan', null, ['html' => $html]);

        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function updatePertanyaan(Request $request, Pertanyaan $pertanyaan)
    {
        try {
            $this->FormBuilderService->updatePertanyaan($pertanyaan, $request->all());

            // Reload and return fresh HTML for the question card
            $pertanyaan->load('opsi');
            $allPertanyaan = $pertanyaan->survei->pertanyaan()->orderBy('urutan')->get();
            $html          = view('pages.survei.admin.partials.question_card', compact('pertanyaan', 'allPertanyaan'))->render();

            return jsonSuccess('Pertanyaan disimpan', null, ['html' => $html]);
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroyPertanyaan(Pertanyaan $pertanyaan)
    {
        $this->FormBuilderService->deletePertanyaan($pertanyaan);
        return jsonSuccess('Pertanyaan dihapus');
    }

    public function reorderPertanyaan(Request $request)
    {
        if (is_array($request->order)) {
            $this->FormBuilderService->reorderPertanyaan($request->order);
        }
        return jsonSuccess('Urutan pertanyaan disimpan');
    }
}
