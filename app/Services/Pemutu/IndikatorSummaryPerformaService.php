<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\IndikatorPegawai;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IndikatorSummaryPerformaService
{
    /**
     * Get the base query for Performa Summary DataTable and count.
     */
    public function getQuery(Request $request): Builder
    {
        $query = IndikatorPegawai::with([
            'indikator.labels.label',
            'pegawai.latestDataDiri',
            'pegawai.orgUnit',
        ])
            ->whereHas('indikator', function ($q) {
                $q->where('type', 'performa')
                    ->whereNull('deleted_at');
            })
            ->whereHas('hr_pegawai', function ($q) {
                $q->whereNull('deleted_at');
            });

        // Apply filters
        if ($request->filled('kelompok_indikator')) {
            $query->whereHas('indikator', function ($q) use ($request) {
                $q->where('kelompok_indikator', $request->kelompok_indikator);
            });
        }

        if ($request->filled('year')) {
            $query->whereHas('indikator', function ($q) use ($request) {
                $q->whereYear('periode_mulai', $request->year);
            });
        }

        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        if ($request->filled('unit_id')) {
            $query->whereHas('pegawai.orgUnit', function ($q) use ($request) {
                $q->where('orgunit_id', $request->unit_id);
            });
        }

        // Search
        $searchValue = $request->input('search.value') ?? $request->input('search');
        $search      = is_array($searchValue) ? ($searchValue['value'] ?? '') : (string) $searchValue;

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('indikator', function ($iq) use ($search) {
                    $iq->where('no_indikator', 'LIKE', "%{$search}%")
                        ->orWhere('indikator', 'LIKE', "%{$search}%");
                })
                    ->orWhereHas('pegawai.latestDataDiri', function ($pq) use ($search) {
                        $pq->where('nama', 'LIKE', "%{$search}%")
                            ->orWhere('nip', 'LIKE', "%{$search}%");
                    });
            });
        }

        return $query;
    }

    /**
     * Get summary counts for exactly the filtered data
     */
    public function getSummaryCounts(Request $request): array
    {
        $query   = $this->getQuery($request);
        $allData = $query->get();

        return [
            'totalIndikatorActive' => $allData->unique('indikator_id')->count(),
            'kpiTotalPegawai'      => $allData->unique('pegawai_id')->count(),
            'kpiAvgScore'          => $allData->avg('score') ?? 0,
        ];
    }
}
