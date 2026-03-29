<?php

namespace App\Services\Sys;

use App\Models\Sys\SysPeriode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Global Periode Service - Period/Milestone Management
 * 
 * Digunakan untuk mengelola periode di berbagai modul:
 * - Pemutu (SPMI, KPI)
 * - PMB (Pendaftaran)
 * - Eoffice (Layanan)
 * - Event (Event periods)
 * - Dan modul lainnya
 * 
 * @package App\Services\Sys
 */
class PeriodeService
{
    /**
     * Get all periodes with optional filters
     * 
     * @param string|null $type
     * @param int|null $year
     * @param bool $activeOnly
     * @return Collection
     */
    public function getAll(?string $type = null, ?int $year = null, bool $activeOnly = false): Collection
    {
        $query = SysPeriode::query();

        if ($type) {
            $query->type($type);
        }

        if ($year) {
            $query->year($year);
        }

        if ($activeOnly) {
            $query->active();
        }

        return $query->orderBy('year', 'desc')
            ->orderBy('start_date', 'desc')
            ->get();
    }

    /**
     * Get active periode for a specific type
     * 
     * @param string $type
     * @return SysPeriode|null
     */
    public function getActivePeriode(string $type): ?SysPeriode
    {
        return SysPeriode::type($type)
            ->active()
            ->latest('year')
            ->first();
    }

    /**
     * Get current periode (tanggal sekarang di antara start dan end)
     * 
     * @param string|null $type
     * @return SysPeriode|null
     */
    public function getCurrentPeriode(?string $type = null): ?SysPeriode
    {
        $query = SysPeriode::current();

        if ($type) {
            $query->type($type);
        }

        return $query->first();
    }

    /**
     * Create new periode
     * 
     * @param array $data
     * @return SysPeriode
     */
    public function create(array $data): SysPeriode
    {
        return DB::transaction(function () use ($data) {
            // Deactivate other periodes of the same type if this one is active
            if (!empty($data['is_active']) && !empty($data['type'])) {
                SysPeriode::type($data['type'])
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $periode = SysPeriode::create($data);

            logActivity('sys_periode', "Membuat periode baru: {$periode->name}", $periode);

            return $periode;
        });
    }

    /**
     * Update periode
     * 
     * @param SysPeriode $periode
     * @param array $data
     * @return SysPeriode
     */
    public function update(SysPeriode $periode, array $data): SysPeriode
    {
        return DB::transaction(function () use ($periode, $data) {
            // Deactivate other periodes of the same type if this one is set active
            if (!empty($data['is_active']) && $periode->type) {
                SysPeriode::type($periode->type)
                    ->where('is_active', true)
                    ->where('sys_periode_id', '!=', $periode->sys_periode_id)
                    ->update(['is_active' => false]);
            }

            $periode->update($data);

            logActivity('sys_periode', "Mengupdate periode: {$periode->name}", $periode);

            return $periode;
        });
    }

    /**
     * Delete periode
     * 
     * @param SysPeriode $periode
     * @return void
     */
    public function delete(SysPeriode $periode): void
    {
        DB::transaction(function () use ($periode) {
            logActivity('sys_periode', "Menghapus periode: {$periode->name}", $periode);
            $periode->delete();
        });
    }

    /**
     * Set active periode (will deactivate others of the same type)
     * 
     * @param SysPeriode $periode
     * @return SysPeriode
     */
    public function setActive(SysPeriode $periode): SysPeriode
    {
        return DB::transaction(function () use ($periode) {
            // Deactivate other periodes of the same type
            SysPeriode::type($periode->type)
                ->where('is_active', true)
                ->where('sys_periode_id', '!=', $periode->sys_periode_id)
                ->update(['is_active' => false]);

            // Activate this periode
            $periode->update(['is_active' => true]);

            logActivity('sys_periode', "Mengaktifkan periode: {$periode->name}", $periode);

            return $periode;
        });
    }

    /**
     * Get available years for periodes
     * 
     * @param int $startYear
     * @param int|null $endYear
     * @return array
     */
    public function getAvailableYears(int $startYear = 2020, ?int $endYear = null): array
    {
        $endYear = $endYear ?? (int) date('Y') + 2;

        $existingYears = SysPeriode::selectRaw('DISTINCT year')
            ->whereNotNull('year')
            ->pluck('year')
            ->toArray();

        $years = [];
        for ($y = $endYear; $y >= $startYear; $y--) {
            $years[] = $y;
        }

        return $years;
    }

    /**
     * Get periode statistics
     * 
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'total' => SysPeriode::count(),
            'active' => SysPeriode::active()->count(),
            'by_type' => SysPeriode::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'by_year' => SysPeriode::selectRaw('year, COUNT(*) as count')
                ->whereNotNull('year')
                ->groupBy('year')
                ->orderByDesc('year')
                ->pluck('count', 'year')
                ->toArray(),
        ];
    }
}
