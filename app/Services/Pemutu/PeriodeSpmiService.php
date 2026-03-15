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
        $startYear = (int) env('SPMI_START_YEAR', 2021);
        $endYear   = (int) date('Y') + 1;
        
        $years = [];
        for ($y = $endYear; $y >= $startYear; $y--) {
            $years[] = $y;
        }

        return collect($years);
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

    /**
     * Resolve the active Siklus SPMI year and return both Akademik & Non Akademik periodes.
     * Reads from session `siklus_spmi_tahun`, falls back to the latest year available.
     *
     * @return array{tahun: int, years: \Illuminate\Support\Collection, akademik: ?PeriodeSpmi, non_akademik: ?PeriodeSpmi}
     */
    public function getSiklusData(): array
    {
        $years = $this->getAvailableYears();

        if ($years->isEmpty()) {
            return [
                'tahun'        => (int) date('Y'),
                'years'        => collect(),
                'akademik'     => null,
                'non_akademik' => null,
            ];
        }

        $tahun = (int) (session('siklus_spmi_tahun') ?? $years->first());

        // Ensure the session year is valid
        if (! $years->contains($tahun)) {
            $tahun = $years->first();
            session(['siklus_spmi_tahun' => $tahun]);
        }

        $periodes = PeriodeSpmi::where('periode', $tahun)->get();

        return [
            'tahun'        => $tahun,
            'years'        => $years,
            'akademik'     => $periodes->firstWhere('jenis_periode', 'Akademik'),
            'non_akademik' => $periodes->firstWhere('jenis_periode', 'Non Akademik'),
        ];
    }
}

