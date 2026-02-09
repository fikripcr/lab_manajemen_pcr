<?php
namespace App\Services\Hr;

use App\Models\Hr\TanggalLibur;
use Illuminate\Support\Facades\DB;

class TanggalLiburService
{
    /**
     * Store multiple Tanggal Libur records from batch input.
     *
     * @param array $data
     * @return int Count of created records
     */
    public function createBatch(array $data): int
    {
        return DB::transaction(function () use ($data) {
            $tahun = $data['tahun'];
            $count = 0;

            foreach ($data['entries'] as $entry) {
                // Explode dates (from Flatpickr multiple mode)
                $dates = explode(', ', $entry['dates']);

                foreach ($dates as $dateStr) {
                    // Check duplicate? For now, just create.
                    // Ideally we might want to updateOrInsert or check existence.

                    TanggalLibur::create([
                        'tgl_libur'  => trim($dateStr),
                        'tahun'      => $tahun,
                        'keterangan' => $entry['keterangan'],
                    ]);
                    $count++;
                }
            }

            return $count;
        });
    }

    /**
     * Delete a record by ID.
     */
    public function delete($id): void
    {
        $item = TanggalLibur::findOrFail($id);
        $item->delete();
    }
}
