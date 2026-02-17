<?php
namespace App\Services\Survei;

use App\Models\Survei\Halaman;
use App\Models\Survei\Pertanyaan;
use App\Models\Survei\Survei;
use Exception;
use Illuminate\Support\Facades\DB;

class FormBuilderService
{
    /**
     * Get survey structure for builder.
     */
    public function getSurveyForBuilder(Survei $survei): Survei
    {
        return $survei->load([
            'halaman'            => fn($q)            => $q->orderBy('urutan'),
            'halaman.pertanyaan' => fn($q) => $q->orderBy('urutan'),
            'halaman.pertanyaan.opsi',
            'halaman.pertanyaan.logika',
        ]);
    }

    // --- Halaman Logic ---

    public function addHalaman(Survei $survei): Halaman
    {
        $count = $survei->halaman()->count();
        return $survei->halaman()->create([
            'judul_halaman' => 'Halaman ' . ($count + 1),
            'urutan'        => $count + 1,
        ]);
    }

    public function updateHalaman(Halaman $halaman, array $data): bool
    {
        return $halaman->update($data);
    }

    public function deleteHalaman(Halaman $halaman)
    {
        if ($halaman->survei->halaman()->count() <= 1) {
            throw new Exception('Survei harus memiliki minimal satu halaman.');
        }
        return $halaman->delete();
    }

    public function reorderHalaman(array $order)
    {
        return DB::transaction(function () use ($order) {
            foreach ($order as $index => $id) {
                Halaman::where('id', $id)->update(['urutan' => $index + 1]);
            }
        });
    }

    // --- Pertanyaan Logic ---

    public function addPertanyaan(Survei $survei, array $data): Pertanyaan
    {
        return DB::transaction(function () use ($survei, $data) {
            $halaman  = Halaman::findOrFail($data['halaman_id']);
            $maxOrder = $halaman->pertanyaan()->max('urutan') ?? 0;

            $pertanyaan = $survei->pertanyaan()->create([
                'halaman_id'      => $halaman->id,
                'teks_pertanyaan' => $data['teks_pertanyaan'],
                'tipe'            => $data['tipe'],
                'urutan'          => $maxOrder + 1,
                'wajib_diisi'     => true,
                'config_json'     => [],
            ]);

            // Add default options for choice types
            if (in_array($data['tipe'], ['Pilihan_Ganda', 'Kotak_Centang', 'Dropdown'])) {
                $pertanyaan->opsi()->createMany([
                    ['label' => 'Opsi 1', 'urutan' => 1],
                    ['label' => 'Opsi 2', 'urutan' => 2],
                ]);
            }

            // Default config for Scale
            if ($data['tipe'] === 'Skala_Linear') {
                $pertanyaan->update([
                    'config_json' => [
                        'min'       => 1, 'max' => 5,
                        'label_min' => 'Sangat Buruk',
                        'label_max' => 'Sangat Baik',
                    ],
                ]);
            }

            return $pertanyaan;
        });
    }

    public function updatePertanyaan(Pertanyaan $pertanyaan, array $data): Pertanyaan
    {
        return DB::transaction(function () use ($pertanyaan, $data) {
            $updateData = collect($data)->only(['teks_pertanyaan', 'bantuan_teks', 'config_json', 'next_pertanyaan_id'])->toArray();

            if (isset($data['wajib_diisi'])) {
                $updateData['wajib_diisi'] = filter_var($data['wajib_diisi'], FILTER_VALIDATE_BOOLEAN);
            }

            // Handle type change
            if (isset($data['tipe'])) {
                $oldTipe            = $pertanyaan->tipe;
                $newTipe            = $data['tipe'];
                $updateData['tipe'] = $newTipe;

                $choiceTypes = ['Pilihan_Ganda', 'Kotak_Centang', 'Dropdown'];

                if (in_array($oldTipe, $choiceTypes) && ! in_array($newTipe, $choiceTypes)) {
                    $pertanyaan->opsi()->delete();
                }

                if (! in_array($oldTipe, $choiceTypes) && in_array($newTipe, $choiceTypes)) {
                    $pertanyaan->opsi()->createMany([
                        ['label' => 'Opsi 1', 'urutan' => 1],
                        ['label' => 'Opsi 2', 'urutan' => 2],
                    ]);
                }

                if ($newTipe === 'Skala_Linear' && $oldTipe !== 'Skala_Linear') {
                    $updateData['config_json'] = [
                        'min'       => 1, 'max' => 5,
                        'label_min' => 'Sangat Buruk',
                        'label_max' => 'Sangat Baik',
                    ];
                }
            }

            $pertanyaan->update($updateData);

            // Handle Options Update
            if (isset($data['opsi']) && is_array($data['opsi'])) {
                $incomingIds = collect($data['opsi'])->map(fn($o) => is_array($o) ? ($o['id'] ?? null) : null)->filter()->toArray();
                $pertanyaan->opsi()->whereNotIn('id', $incomingIds)->delete();

                foreach ($data['opsi'] as $index => $opsiData) {
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

            return $pertanyaan;
        });
    }

    public function deletePertanyaan(Pertanyaan $pertanyaan): bool
    {
        return $pertanyaan->delete();
    }

    public function reorderPertanyaan(array $order)
    {
        return DB::transaction(function () use ($order) {
            foreach ($order as $index => $id) {
                Pertanyaan::where('id', $id)->update(['urutan' => $index + 1]);
            }
        });
    }
}
