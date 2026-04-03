<?php

namespace App\Services\Pemutu;

use App\Models\Pemutu\IndikatorPegawai;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IndikatorSummaryPerformaService
{
    /**
     * Get the base array of general stats for Performa.
     */
    public function getPerformaStats(string $kelompok): array
    {
        $queryBase = \Illuminate\Support\Facades\DB::table('pemutu_indikator')
            ->where('type', 'performa')
            ->where('kelompok_indikator', $kelompok);

        $totalIndikator = (clone $queryBase)->count();
        $totalIndikatorActive = (clone $queryBase)->whereNull('deleted_at')->count();

        // Summary KPI
        $kpiQueryBase = \Illuminate\Support\Facades\DB::table('pemutu_indikator_pegawai')
            ->join('pemutu_indikator', 'pemutu_indikator_pegawai.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->where('pemutu_indikator.type', 'performa')
            ->where('pemutu_indikator.kelompok_indikator', $kelompok);

        $kpiTotalPegawai = (clone $kpiQueryBase)
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        $kpiAvgScore = (clone $kpiQueryBase)
            ->avg('pemutu_indikator_pegawai.score');

        return [
            'totalIndikator' => $totalIndikator,
            'totalIndikatorActive' => $totalIndikatorActive,
            'kpiTotalPegawai' => $kpiTotalPegawai,
            'kpiAvgScore' => $kpiAvgScore,
        ];
    }

    /**
     * Get Pegawai data for filtering that have DataDiri.
     */
    public function getPegawais()
    {
        return \App\Models\Hr\Pegawai::whereHas('latestDataDiri')->get()->sortBy(function ($pegawai) {
            return $pegawai->nama;
        });
    }

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
            ->whereHas('pegawai', function ($q) {
                $q->whereNull('deleted_at');
            });

        // Apply filters (Context Fallback)
        $kelompok = $request->query('kelompok_indikator');
        if (empty($kelompok) || $kelompok === 'all') {
            $activeKelompok = session('pemutu_active_kelompok', 'akademik');
            $kelompok = $activeKelompok === 'akademik' ? 'Akademik' : 'Non Akademik';
        }

        $query->whereHas('indikator', function ($q) use ($kelompok) {
            $q->where('kelompok_indikator', $kelompok);
        });

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
        $search = is_array($searchValue) ? ($searchValue['value'] ?? '') : (string) $searchValue;

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
        $query = $this->getQuery($request);
        $allData = $query->get();

        return [
            'totalIndikatorActive' => $allData->unique('indikator_id')->count(),
            'kpiTotalPegawai' => $allData->unique('pegawai_id')->count(),
            'kpiAvgScore' => $allData->avg('score') ?? 0,
        ];
    }
}
