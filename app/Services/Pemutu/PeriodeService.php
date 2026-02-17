<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\PeriodeKpi;
use Exception;
use Illuminate\Support\Facades\DB;

class PeriodeService
{
    /**
     * Store a new period.
     */
    public function store(array $data): PeriodeKpi
    {
        return PeriodeKpi::create($data);
    }

    /**
     * Update an existing period.
     */
    public function update(PeriodeKpi $periode, array $data): bool
    {
        return $periode->update($data);
    }

    /**
     * Delete a period only if it's not active.
     */
    public function destroy(PeriodeKpi $periode): bool
    {
        if ($periode->is_active) {
            throw new Exception('Tidak dapat menghapus periode yang sedang aktif.');
        }
        return $periode->delete();
    }

    /**
     * Activate a period and deactivate others.
     */
    public function activate(PeriodeKpi $periode): void
    {
        DB::transaction(function () use ($periode) {
            // Deactivate all other periods
            PeriodeKpi::where('is_active', true)->update(['is_active' => false]);
            // Activate this period
            $periode->update(['is_active' => true]);
        });
    }
}
