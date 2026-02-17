<?php
namespace App\Http\Controllers\Survei;

use App\Http\Controllers\Controller;
use App\Models\Survei\Halaman;
use App\Models\Survei\Pertanyaan;
use App\Models\Survei\Survei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormBuilderController extends Controller
{
    public function index(Survei $survei)
    {
        $survei->load([
            'halaman'            => fn($q)            => $q->orderBy('urutan'),
            'halaman.pertanyaan' => fn($q) => $q->orderBy('urutan'),
            'halaman.pertanyaan.opsi',
            'halaman.pertanyaan.logika',
        ]);
        $allPertanyaan = $survei->pertanyaan()->orderBy('urutan')->get();

        return view('pages.survei.admin.builder', compact('survei', 'allPertanyaan'));
    }

    /**
     * Preview the survey as it would appear to the user.
     */
    public function preview(Survei $survei)
    {
        $survei->load([
            'halaman'            => fn($q)            => $q->orderBy('urutan'),
            'halaman.pertanyaan' => fn($q) => $q->orderBy('urutan'),
            'halaman.pertanyaan.opsi',
        ]);

        return view('pages.survei.player.show', [
            'survei'    => $survei,
            'isPreview' => true,
        ]);
    }

    // --- Halaman Methods ---

    public function storeHalaman(Request $request, Survei $survei)
    {
        $count   = $survei->halaman()->count();
        $halaman = $survei->halaman()->create([
            'judul_halaman' => 'Halaman ' . ($count + 1),
            'urutan'        => $count + 1,
        ]);

        return jsonSuccess('Halaman berhasil ditambahkan', null, ['halaman' => $halaman]);
    }

    public function updateHalaman(Request $request, Halaman $halaman)
    {
        $halaman->update($request->only('judul_halaman', 'deskripsi_halaman'));
        return jsonSuccess('Halaman diperbarui');
    }

    public function destroyHalaman(Halaman $halaman)
    {
        if ($halaman->survei->halaman()->count() <= 1) {
            return jsonError('Survei harus memiliki minimal satu halaman.');
        }
        $halaman->delete();
        return jsonSuccess('Halaman dihapus');
    }

    public function reorderHalaman(Request $request)
    {
        $order = $request->order;
        if (is_array($order)) {
            $cases = [];
            $ids   = [];
            foreach ($order as $index => $id) {
                $id      = (int) $id;
                $ids[]   = $id;
                $cases[] = "WHEN id = {$id} THEN " . ($index + 1);
            }
            if (! empty($ids)) {
                $caseStr = implode(' ', $cases);
                $idsStr  = implode(',', $ids);
                DB::statement("UPDATE survei_halaman SET urutan = CASE {$caseStr} END WHERE id IN ({$idsStr})");
            }
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

        DB::beginTransaction();
        try {
            $halaman  = Halaman::findOrFail($request->halaman_id);
            $maxOrder = $halaman->pertanyaan()->max('urutan') ?? 0;

            $pertanyaan = $survei->pertanyaan()->create([
                'halaman_id'      => $halaman->id,
                'teks_pertanyaan' => $validated['teks_pertanyaan'],
                'tipe'            => $validated['tipe'],
                'urutan'          => $maxOrder + 1,
                'wajib_diisi'     => true,
                'config_json'     => [],
            ]);

            // Add default options for choice types
            if (in_array($validated['tipe'], ['Pilihan_Ganda', 'Kotak_Centang', 'Dropdown'])) {
                $pertanyaan->opsi()->createMany([
                    ['label' => 'Opsi 1', 'urutan' => 1],
                    ['label' => 'Opsi 2', 'urutan' => 2],
                ]);
            }

            // Default config for Scale
            if ($validated['tipe'] === 'Skala_Linear') {
                $pertanyaan->update([
                    'config_json' => [
                        'min'       => 1, 'max' => 5,
                        'label_min' => 'Sangat Buruk',
                        'label_max' => 'Sangat Baik',
                    ],
                ]);
            }

            DB::commit();

            $pertanyaan->load('opsi');
            $allPertanyaan = $survei->pertanyaan()->orderBy('urutan')->get();
            $html          = view('pages.survei.admin.partials.question_card', compact('pertanyaan', 'allPertanyaan'))->render();

            return jsonSuccess('Pertanyaan ditambahkan', null, ['html' => $html]);

        } catch (\Exception $e) {
            DB::rollBack();
            return jsonError($e->getMessage());
        }
    }

    public function updatePertanyaan(Request $request, Pertanyaan $pertanyaan)
    {
        $data = $request->only(['teks_pertanyaan', 'bantuan_teks', 'config_json', 'next_pertanyaan_id']);

        if ($request->has('wajib_diisi')) {
            $data['wajib_diisi'] = filter_var($request->wajib_diisi, FILTER_VALIDATE_BOOLEAN);
        }

        // Handle type change — clean up options if switching away from choice type
        if ($request->has('tipe')) {
            $oldTipe      = $pertanyaan->tipe;
            $newTipe      = $request->tipe;
            $data['tipe'] = $newTipe;

            $choiceTypes = ['Pilihan_Ganda', 'Kotak_Centang', 'Dropdown'];

            // If switching FROM a choice type TO a non-choice type, delete options
            if (in_array($oldTipe, $choiceTypes) && ! in_array($newTipe, $choiceTypes)) {
                $pertanyaan->opsi()->delete();
            }

            // If switching TO a choice type FROM a non-choice type, create defaults
            if (! in_array($oldTipe, $choiceTypes) && in_array($newTipe, $choiceTypes)) {
                $pertanyaan->opsi()->createMany([
                    ['label' => 'Opsi 1', 'urutan' => 1],
                    ['label' => 'Opsi 2', 'urutan' => 2],
                ]);
            }

            // If switching to Skala_Linear, set default config
            if ($newTipe === 'Skala_Linear' && $oldTipe !== 'Skala_Linear') {
                $data['config_json'] = [
                    'min'       => 1, 'max' => 5,
                    'label_min' => 'Sangat Buruk',
                    'label_max' => 'Sangat Baik',
                ];
            }
        }

        $pertanyaan->update($data);

        // Handle Options Update (Improved Sync Strategy)
        if ($request->has('opsi') && is_array($request->opsi)) {
            $incomingIds = collect($request->opsi)->map(fn($o) => is_array($o) ? ($o['id'] ?? null) : null)->filter()->toArray();
            $pertanyaan->opsi()->whereNotIn('id', $incomingIds)->delete();

            foreach ($request->opsi as $index => $opsiData) {
                $label = is_array($opsiData) ? ($opsiData['label'] ?? '') : $opsiData;
                if (! empty($label) || $label === '0') {
                    $pertanyaan->opsi()->updateOrCreate(
                        ['id' => is_array($opsiData) ? ($opsiData['id'] ?? null) : null],
                        [
                            'label'              => $label,
                            'urutan'             => $index + 1,
                            'next_pertanyaan_id' => is_array($opsiData) ? ($opsiData['next_pertanyaan_id'] ?? null) : null,
                        ]
                    );
                }
            }
        }

        // Reload and return fresh HTML for the question card
        $pertanyaan->load('opsi');
        $allPertanyaan = $pertanyaan->survei->pertanyaan()->orderBy('urutan')->get();
        $html          = view('pages.survei.admin.partials.question_card', compact('pertanyaan', 'allPertanyaan'))->render();

        return jsonSuccess('Pertanyaan disimpan', null, ['html' => $html]);
    }

    public function destroyPertanyaan(Pertanyaan $pertanyaan)
    {
        $pertanyaan->delete();
        return jsonSuccess('Pertanyaan dihapus');
    }

    public function reorderPertanyaan(Request $request)
    {
        $order = $request->order;
        if (is_array($order)) {
            $cases = [];
            $ids   = [];
            foreach ($order as $index => $id) {
                $id      = (int) $id;
                $ids[]   = $id;
                $cases[] = "WHEN id = {$id} THEN " . ($index + 1);
            }
            if (! empty($ids)) {
                $caseStr = implode(' ', $cases);
                $idsStr  = implode(',', $ids);
                DB::statement("UPDATE survei_pertanyaan SET urutan = CASE {$caseStr} END WHERE id IN ({$idsStr})");
            }
        }
        return jsonSuccess('Urutan pertanyaan disimpan');
    }
}
