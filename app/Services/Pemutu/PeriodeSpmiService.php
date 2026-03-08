<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\PeriodeSpmi;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PeriodeSpmiService
{
    /**
     * Ambil semua periode SPMI dengan pagination (untuk halaman index).
     */
    public function getPeriodes(int $perPage = 12): LengthAwarePaginator
    {
        return PeriodeSpmi::orderBy('periode', 'desc')
            ->orderBy('jenis_periode', 'asc')
            ->paginate($perPage);
    }

    /**
     * Ambil semua periode sebagai Collection — untuk dropdown/select.
     */
    public function getAll(?int $year = null)
    {
        $query = PeriodeSpmi::query();

        if ($year) {
            $query->where('periode', $year);
        }

        return $query->orderBy('periode', 'desc')
            ->orderBy('jenis_periode', 'asc')
            ->get();
    }

    /**
     * Ambil daftar tahun yang tersedia di sistem.
     */
    public function getAvailableYears()
    {
        return PeriodeSpmi::select('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode');
    }

    /**
     * Kembalikan base query Builder untuk DataTables.
     */
    public function getBaseQuery()
    {
        return PeriodeSpmi::query()
            ->orderBy('periode', 'desc')
            ->orderBy('jenis_periode', 'asc');
    }

    public function store(array $data): PeriodeSpmi
    {
        return DB::transaction(function () use ($data) {
            $periode = PeriodeSpmi::create($data);
            logActivity('pemutu', "Menambah periode SPMI: {$periode->periode}", $periode);
            return $periode;
        });
    }

    public function update(PeriodeSpmi $periode, array $data): PeriodeSpmi
    {
        return DB::transaction(function () use ($periode, $data) {
            $periode->update($data);
            logActivity('pemutu', "Mengupdate periode SPMI: {$periode->periode}", $periode);
            return $periode;
        });
    }

    public function delete(PeriodeSpmi $periode): void
    {
        DB::transaction(function () use ($periode) {
            logActivity('pemutu', "Menghapus periode SPMI: {$periode->periode}", $periode);
            $periode->delete();
        });
    }
}
