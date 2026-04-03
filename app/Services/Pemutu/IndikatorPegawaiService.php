<?php

namespace App\Services\Pemutu;

use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorPegawai;
use App\Models\Pemutu\PeriodeKpi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class IndikatorPegawaiService
{
    /**
     * Query IndikatorPegawai for DataTable — only including valid indicators and employees.
     */
    public function getKpiDataTableQuery(PeriodeKpi $periode): Builder
    {
        return IndikatorPegawai::with(['indikator', 'hr_pegawai', 'media'])
            ->where('periode_kpi_id', $periode->periode_kpi_id)
            ->whereHas('indikator')
            ->whereHas('hr_pegawai');
    }

    /**
     * Get PeriodeKpi list with completion statistics.
     */
    public function getKpiPeriodes(int $perPage = 12): array
    {
        $periodes = PeriodeKpi::orderBy('tahun', 'desc')->paginate($perPage);

        $periodeIds  = $periodes->pluck('periode_kpi_id');
        $totalCounts = IndikatorPegawai::whereIn('periode_kpi_id', $periodeIds)
            ->selectRaw('periode_kpi_id, COUNT(*) as total')
            ->groupBy('periode_kpi_id')
            ->pluck('total', 'periode_kpi_id');
        $filledCounts = IndikatorPegawai::whereIn('periode_kpi_id', $periodeIds)
            ->whereNotNull('realization')->where('realization', '!=', '')
            ->selectRaw('periode_kpi_id, COUNT(*) as filled')
            ->groupBy('periode_kpi_id')
            ->pluck('filled', 'periode_kpi_id');

        $periodes->getCollection()->transform(function ($p) use ($totalCounts, $filledCounts) {
            $p->total_assignments = $totalCounts->get($p->periode_kpi_id, 0);
            $p->filled_assignments = $filledCounts->get($p->periode_kpi_id, 0);

            return $p;
        });

        return compact('periodes', 'totalCounts', 'filledCounts');
    }

    /**
     * Build extra data for KPI evaluation edit form.
     */
    public function getKpiEditData(IndikatorPegawai $indikatorPegawai, IndikatorService $indikatorService): array
    {
        $indikator = $indikatorPegawai->indikator;
        $breadcrumbs = $indikatorService->buildBreadcrumbs($indikator);
        $indukDokumenTree = $indikatorService->buildIndukDokumenTree($indikator);

        $edLinks = [];
        if (! empty($indikatorPegawai->kpi_links)) {
            $edLinks = json_decode($indikatorPegawai->kpi_links, true) ?? [];
        }

        return compact('indikator', 'indikatorPegawai', 'breadcrumbs', 'edLinks', 'indukDokumenTree');
    }

    /**
     * Update KPI evaluation data: realizations, analysis, attachments, and links.
     */
    public function updateKpi(IndikatorPegawai $indikatorPegawai, array $data, IndikatorService $indikatorService): IndikatorPegawai
    {
        $updatePayload = [
            'realization'  => $data['realization'] ?? null,
            'kpi_analisis' => $data['kpi_analisis'] ?? null,
            'kpi_links'    => $this->processLinks($data),
            'status'       => 'submitted',
        ];

        $indikatorPegawai->update($updatePayload);

        logActivity('pemutu', 'Memperbarui evaluasi KPI: ' . ($indikatorPegawai->indikator->no_indikator ?? '-'));

        return $indikatorPegawai;
    }

    /**
     * Process links array to JSON string.
     */
    protected function processLinks(array $data, string $nameKey = 'kpi_links_name', string $urlKey = 'kpi_links_url'): ?string
    {
        $linksArray = [];
        if (isset($data[$nameKey]) && is_array($data[$nameKey])) {
            $names = $data[$nameKey];
            $urls  = $data[$urlKey] ?? [];
            foreach ($names as $index => $name) {
                $url = $urls[$index] ?? null;
                if (! empty($name) && ! empty($url)) {
                    $linksArray[] = [
                        'name' => $name,
                        'url'  => $url,
                    ];
                }
            }
        }

        return ! empty($linksArray) ? json_encode($linksArray) : null;
    }

    /**
     * Get KPI period info based on Active period or the standard doc.
     */
    public function getKpiPeriodeData(Indikator $indikator): array
    {
        $activePeriode = PeriodeKpi::where('is_active', 1)->first();
        $kpiYear       = $activePeriode ? $activePeriode->tahun : null;
        $periodeId     = $activePeriode ? $activePeriode->periode_kpi_id : null;

        if (! $kpiYear) {
            try {
                // Determine year from Standar's Dokumen Periode (for 'performa' indicators, the parent should be 'standar')
                $parentStandar = $indikator->parent;
                if ($parentStandar) {
                    $firstDokSub = $parentStandar->dokSubs()->first();
                    if ($firstDokSub && $firstDokSub->dokumen) {
                        $periodeStr = $firstDokSub->dokumen->periode;
                        // Extract a year-like format (e.g. 2024 from '2024-2029')
                        if (preg_match('/\b(20\d{2})\b/', $periodeStr, $matches)) {
                            $kpiYear = $matches[1];
                        }
                    }
                }
            } catch (\Exception $e) {
                // Ignore exception, fallback downstream
            }
        }

        return [
            'periode_kpi_id' => $periodeId,
            'year'           => $kpiYear ?: date('Y'),
        ];
    }
}
