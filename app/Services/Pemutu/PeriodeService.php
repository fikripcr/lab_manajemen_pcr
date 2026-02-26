<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\PeriodeKpi;
use Exception;
use Illuminate\Support\Facades\DB;

class PeriodeService
{
    /**
     * Ambil semua periode KPI dengan pagination.
     */
    public function getPeriodes(int $perPage = 20)
    {
        return PeriodeKpi::orderBy('tahun', 'desc')->paginate($perPage);
    }

    /**
     * Ambil semua periode KPI sebagai collection (untuk dropdown/select).
     */
    public function getAll()
    {
        return PeriodeKpi::orderBy('tahun', 'desc')->get();
    }

    /**
     * Kembalikan base query Builder untuk DataTables.
     */
    public function getBaseQuery()
    {
        return PeriodeKpi::query()->orderBy('tahun', 'desc');
    }

    /**
     * Store a new period.
     */
    public function store(array $data): PeriodeKpi
    {
        $periode = PeriodeKpi::create($data);
        logActivity('pemutu', "Menambah periode KPI baru: {$periode->nama}");
        return $periode;
    }

    /**
     * Update an existing period.
     */
    public function update(PeriodeKpi $periode, array $data): bool
    {
        $oldNama = $periode->nama;
        $updated = $periode->update($data);
        if ($updated) {
            logActivity('pemutu', "Memperbarui periode KPI: {$oldNama}" . ($oldNama !== $periode->nama ? " menjadi {$periode->nama}" : ""));
        }
        return $updated;
    }

    /**
     * Delete a period only if it's not active.
     */
    public function destroy(PeriodeKpi $periode): bool
    {
        if ($periode->is_active) {
            throw new Exception('Tidak dapat menghapus periode yang sedang aktif.');
        }
        $nama    = $periode->nama;
        $deleted = $periode->delete();
        if ($deleted) {
            logActivity('pemutu', "Menghapus periode KPI: {$nama}");
        }
        return $deleted;
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
            logActivity('pemutu', "Mengaktifkan periode KPI: {$periode->nama}");
        });
    }
}
