<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\PeriodeKpi;
use App\Services\Pemutu\IndikatorService;
use Illuminate\Support\Facades\DB;

class KpiService
{
    protected $IndikatorService;

    public function __construct(IndikatorService $IndikatorService)
    {
        $this->IndikatorService = $IndikatorService;
    }

    /**
     * Bulk create KPI indicators from raw data.
     */
    public function bulkCreateKpi(int $parentId, array $items): void
    {
        DB::transaction(function () use ($parentId, $items) {
            $parent    = Indikator::with('dokSubs')->findOrFail($parentId);
            $dokSubIds = $parent->dokSubs->pluck('doksub_id')->toArray();

            foreach ($items as $item) {
                if (empty($item['indikator'])) {
                    continue;
                }

                $data = [
                    'type'         => 'performa',
                    'parent_id'    => $parentId,
                    'indikator'    => $item['indikator'],
                    'target'       => $item['target'] ?? null,
                    'unit_ukuran'  => $item['unit_ukuran'] ?? null,
                    'keterangan'   => $item['keterangan'] ?? null,
                    'no_indikator' => $item['no_indikator'] ?? $this->generateNoIndikator($parentId),
                    'org_units'    => $item['org_unit_ids'] ?? [],
                    'doksub_ids'   => $dokSubIds,
                ];

                $this->IndikatorService->createIndikator($data);
            }
        });
    }

    /**
     * Generate sequential number for child indicator.
     */
    public function generateNoIndikator(int $parentId): string
    {
        $parent = Indikator::findOrFail($parentId);
        $prefix = $parent->no_indikator;
        $count  = Indikator::where('parent_id', $parentId)->count();
        return $prefix . '.' . ($count + 1);
    }

    /**
     * Store pegawai assignments for a KPI.
     */
    public function storeAssignments(Indikator $indikator, array $assignments): void
    {
        DB::transaction(function () use ($indikator, $assignments) {
            $indikator->pegawai()->delete();
            foreach ($assignments as $assign) {
                $indikator->pegawai()->create($assign);
            }
        });
    }

    /**
     * Get active period for KPI.
     */
    public function getActivePeriode(): ?PeriodeKpi
    {
        return PeriodeKpi::where('is_active', true)->first();
    }
}
