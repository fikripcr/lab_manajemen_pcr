<?php
namespace App\Services\Survei;

use App\Models\Survei\Survei;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SurveiService
{
    /**
     * Create a new survey and its initial page.
     */
    public function createSurvei(array $data): Survei
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = Str::slug($data['judul']) . '-' . Str::random(5);
            $survei       = Survei::create($data);

            // Auto-create first page
            $survei->halaman()->create([
                'judul_halaman' => 'Halaman 1',
                'urutan'        => 1,
            ]);

            return $survei;
        });
    }

    /**
     * Update an existing survey.
     */
    public function updateSurvei(Survei $survei, array $data): bool
    {
        return $survei->update($data);
    }

    /**
     * Toggle survey publication status.
     */
    public function toggleStatus(Survei $survei): bool
    {
        return $survei->update(['is_aktif' => ! $survei->is_aktif]);
    }

    /**
     * Duplicate a survey with all its pages, questions, and options.
     */
    public function duplicateSurvei(Survei $survei): Survei
    {
        return DB::transaction(function () use ($survei) {
            $newSurvei           = $survei->replicate();
            $newSurvei->judul    = $survei->judul . ' (Copy)';
            $newSurvei->slug     = Str::slug($newSurvei->judul) . '-' . Str::random(5);
            $newSurvei->is_aktif = false;
            $newSurvei->save();

            // Load relations to avoid N+1 during replication
            $survei->load(['halaman.pertanyaan.opsi']);

            foreach ($survei->halaman as $halaman) {
                $newHalaman            = $halaman->replicate();
                $newHalaman->survei_id = $newSurvei->id;
                $newHalaman->save();

                foreach ($halaman->pertanyaan as $pertanyaan) {
                    $newPertanyaan             = $pertanyaan->replicate();
                    $newPertanyaan->survei_id  = $newSurvei->id;
                    $newPertanyaan->halaman_id = $newHalaman->id;
                    $newPertanyaan->save();

                    foreach ($pertanyaan->opsi as $opsi) {
                        $newOpsi                = $opsi->replicate();
                        $newOpsi->pertanyaan_id = $newPertanyaan->id;
                        $newOpsi->save();
                    }
                }
            }

            return $newSurvei;
        });
    }

    /**
     * Get survey responses with eager loading for export.
     */
    public function getResponsesForExport(Survei $survei)
    {
        return $survei->load([
            'pengisian.jawaban.pertanyaan',
            'pengisian.user',
            'pertanyaan' => fn($q) => $q->orderBy('urutan'),
        ]);
    }

    /**
     * Delete a survey.
     */
    public function deleteSurvei(Survei $survei): bool
    {
        return $survei->delete();
    }
}
