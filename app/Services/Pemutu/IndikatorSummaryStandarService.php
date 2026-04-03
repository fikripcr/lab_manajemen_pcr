<?php

namespace App\Services\Pemutu;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class IndikatorSummaryStandarService
{
    public function __construct(
        protected PeriodeSpmiService $periodeSpmiService
    ) {}

    public function getStandarStats(string $kelompokLabel, string $tahun)
    {
        $totalIndikator = DB::table('pemutu_indikator')
            ->where('type', 'standar')
            ->where('kelompok_indikator', $kelompokLabel)
            ->whereNull('deleted_at')
            ->count();

        $stats = DB::table('pemutu_indikator_orgunit')
            ->join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->join('pemutu_indikator_doksub as ids', function($join) {
                $join->on('pemutu_indikator.indikator_id', '=', 'ids.source_id')->where('ids.source_type', 'App\Models\Pemutu\Indikator');
            })
            ->join('pemutu_dok_sub as ds', 'ids.doksub_id', '=', 'ds.doksub_id')
            ->join('pemutu_dokumen as d', 'ds.dok_id', '=', 'd.dok_id')
            ->where('pemutu_indikator.type', 'standar')
            ->where('pemutu_indikator.kelompok_indikator', $kelompokLabel)
            ->where('d.periode', $tahun)
            ->where(function ($q) { $q->where('d.std_is_staging', false)->orWhereNull('d.std_is_staging'); })
            ->selectRaw('
                COUNT(pemutu_indikator_orgunit.indikorgunit_id) as edTotalUnits,
                COUNT(DISTINCT pemutu_indikator_orgunit.indikator_id) as uniqueAssignedStandar,
                SUM(CASE WHEN pemutu_indikator_orgunit.ed_capaian IS NOT NULL AND pemutu_indikator_orgunit.ed_capaian != "" THEN 1 ELSE 0 END) as edFilledUnits,
                SUM(CASE WHEN pemutu_indikator_orgunit.ami_hasil_akhir IS NOT NULL THEN 1 ELSE 0 END) as amiAssessed,
                SUM(CASE WHEN pemutu_indikator_orgunit.ami_hasil_akhir = 0 THEN 1 ELSE 0 END) as amiKts,
                SUM(CASE WHEN pemutu_indikator_orgunit.ami_hasil_akhir = 1 THEN 1 ELSE 0 END) as amiTerpenuhi,
                SUM(CASE WHEN pemutu_indikator_orgunit.ami_hasil_akhir = 2 THEN 1 ELSE 0 END) as amiTerlampaui,
                SUM(CASE WHEN pemutu_indikator_orgunit.pengend_status IS NOT NULL AND pemutu_indikator_orgunit.pengend_status != "" THEN 1 ELSE 0 END) as pengendFilled
            ')
            ->first();

        return [
            'totalIndikator' => $totalIndikator,
            'totalIndikatorActive' => $totalIndikator,
            'edTotalUnits' => $stats->edTotalUnits ?? 0,
            'uniqueAssignedStandar' => $stats->uniqueAssignedStandar ?? 0,
            'edFilledUnits' => $stats->edFilledUnits ?? 0,
            'amiAssessed' => $stats->amiAssessed ?? 0,
            'amiKts' => $stats->amiKts ?? 0,
            'amiTerpenuhi' => $stats->amiTerpenuhi ?? 0,
            'amiTerlampaui' => $stats->amiTerlampaui ?? 0,
            'pengendFilled' => $stats->pengendFilled ?? 0,
        ];
    }

    public function getStandarQuery(Request $request)
    {
        $query = DB::table('pemutu_indikator_orgunit as io')
            ->join('vw_pemutu_summary_indikator_standar as v', 'io.indikator_id', '=', 'v.indikator_id')
            ->leftJoin('hr_struktur_organisasi as so', 'io.org_unit_id', '=', 'so.orgunit_id')
            ->select(
                'io.indikorgunit_id', 'io.indikator_id', 'io.org_unit_id', 'io.target', 'io.ed_capaian', 'io.ed_analisis',
                'io.ami_hasil_akhir',
                'io.ami_hasil_temuan',
                'io.ami_hasil_temuan_sebab',
                'io.ami_hasil_temuan_akibat',
                'io.ami_hasil_temuan_rekom',
                'io.ami_rtp_isi', 'io.ed_ptp_isi', 'io.ami_te_isi', 'io.pengend_status',
                'v.*', 'so.name as unit_name', 'so.code as unit_code'
            );

        $this->applyStandarFilters($query, $request);

        return $query;
    }

    protected function applyStandarFilters($query, Request $request)
    {
        // Filter by Kelompok (Context Fallback)
        $kelompok = $request->query('kelompok_indikator');
        if (empty($kelompok) || $kelompok === 'all') {
            $activeKelompok = session('pemutu_active_kelompok', 'akademik');
            $kelompok = $activeKelompok === 'akademik' ? 'Akademik' : 'Non Akademik';
        }
        $query->where('v.kelompok_indikator', $kelompok);

        // Filter by Tahun (Cycle Fallback)
        $tahun = $request->query('tahun');
        if (empty($tahun)) {
            $siklus = $this->periodeSpmiService->getSiklusData();
            $tahun = $siklus['tahun'];
        }

        if ($tahun) {
            $query->whereExists(function ($q) use ($tahun) {
                $q->select(DB::raw(1))
                    ->from('pemutu_indikator_doksub as ids_year')
                    ->join('pemutu_dok_sub as ds_year', 'ids_year.doksub_id', '=', 'ds_year.doksub_id')
                    ->join('pemutu_dokumen as d_year', 'ds_year.dok_id', '=', 'd_year.dok_id')
                    ->whereColumn('ids_year.source_id', 'v.indikator_id')
                    ->where('ids_year.source_type', 'App\Models\Pemutu\Indikator')
                    ->where('d_year.periode', $tahun)
                    ->where(function ($sq) { $sq->where('d_year.std_is_staging', false)->orWhereNull('d_year.std_is_staging'); });
            });
        }

        // Filter by ED Status (skip if 'all')
        if ($request->filled('ed_status') && $request->ed_status !== '') {
            if ($request->ed_status === 'filled') {
                $query->whereNotNull('io.ed_capaian')->where('io.ed_capaian', '!=', '');
            } elseif ($request->ed_status === 'empty') {
                $query->where(function ($q) {
                    $q->whereNull('io.ed_capaian')->orWhere('io.ed_capaian', '');
                });
            }
        }

        // Filter by AMI Hasil (skip if 'all')
        if ($request->filled('ami_hasil') && $request->ami_hasil !== '') {
            if ($request->ami_hasil === 'empty') {
                $query->whereNull('io.ami_hasil_akhir');
            } else {
                $query->where('io.ami_hasil_akhir', $request->ami_hasil);
            }
        }

        // Filter by Pengendalian Status (skip if 'all')
        if ($request->filled('pengend_status') && $request->pengend_status !== '') {
            if ($request->pengend_status === 'filled') {
                $query->whereNotNull('io.pengend_status')->where('io.pengend_status', '!=', '');
            } elseif ($request->pengend_status === 'empty') {
                $query->where(function ($q) {
                    $q->whereNull('io.pengend_status')->orWhere('io.pengend_status', '');
                });
            }
        }

        // Search Filter
        if ($request->filled('search')) {
            $searchValue = $request->input('search.value') ?? $request->input('search');
            $search = is_array($searchValue) ? ($searchValue['value'] ?? '') : (string) $searchValue;
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('v.no_indikator', 'LIKE', "%{$search}%")
                        ->orWhere('v.indikator', 'LIKE', "%{$search}%")
                        ->orWhere('v.parent_no_indikator', 'LIKE', "%{$search}%")
                        ->orWhere('so.name', 'LIKE', "%{$search}%")
                        ->orWhere('so.code', 'LIKE', "%{$search}%");
                });
            }
        }

        return $query;
    }
}
