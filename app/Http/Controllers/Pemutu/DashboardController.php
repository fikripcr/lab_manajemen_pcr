<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Hr\StrukturOrganisasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Dashboard SPMI - Overview';

        $pendingApprovalsCount = 0;
        if (auth()->check() && auth()->user()->pegawai) {
            $pendingApprovalsCount = \App\Models\Pemutu\RiwayatApproval::where('status', 'Pending')
                ->where('pegawai_id', auth()->user()->pegawai->pegawai_id)
                ->count();
        }

        $years = PeriodeSpmi::orderBy('periode', 'desc')->pluck('periode')->unique()->toArray();
        if (empty($years)) {
            $years[] = date('Y');
        }
        $units     = StrukturOrganisasi::orderBy('name', 'asc')->get();
        $kriterias = Indikator::whereNotNull('kelompok_indikator')->distinct('kelompok_indikator')->pluck('kelompok_indikator');

        // Current Filters
        $currentYear = $request->get('year');
        if (! $currentYear) {
            $activePeriodeSpmi = PeriodeSpmi::whereDate('penetapan_awal', '<<=', now())
                ->whereDate('peningkatan_akhir', '>=', now())
                ->first();
            $currentYear = $activePeriodeSpmi ? $activePeriodeSpmi->periode : $years[0];
        }
        $lastYear = (int) $currentYear - 1;

        $currentUnit     = $request->get('unit_id');
        $currentKriteria = $request->get('kriteria');

        // Base Query Builder for Current Year
        $buildQuery = function ($year) use ($currentUnit, $currentKriteria) {
            $q = IndikatorOrgUnit::join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
                ->where('pemutu_indikator.type', 'standar')
                ->whereNull('pemutu_indikator.deleted_at');

            // Year logic (check parent document periode)
            // Commented out temporarily: Users expect to see ALL indicators assigned
            // $q->whereExists(function ($query) use ($year) {
            //     $query->select(\DB::raw(1))
            //         ->from('pemutu_indikator_doksub')
            //         ->join('pemutu_dok_sub', 'pemutu_indikator_doksub.doksub_id', '=', 'pemutu_dok_sub.doksub_id')
            //         ->join('pemutu_dokumen', 'pemutu_dok_sub.dok_id', '=', 'pemutu_dokumen.dok_id')
            //         ->whereColumn('pemutu_indikator_doksub.indikator_id', 'pemutu_indikator.indikator_id')
            //         ->where('pemutu_dokumen.periode', $year);
            // });

            if ($currentUnit) {
                $q->where('pemutu_indikator_orgunit.org_unit_id', $currentUnit);
            }
            if ($currentKriteria) {
                $q->where('pemutu_indikator.kelompok_indikator', $currentKriteria);
            }

            return $q;
        };

        $baseCurr = $buildQuery($currentYear);
        $basePrev = $buildQuery($lastYear);

        // --- KPI CARDS: Tercapai vs Tidak Tercapai ---
        $kpiCurr = [
            'tercapai'       => (clone $baseCurr)->whereIn('pemutu_indikator_orgunit.ami_hasil_akhir', [1, 2])->count(),
            'tidak_tercapai' => (clone $baseCurr)->where('pemutu_indikator_orgunit.ami_hasil_akhir', 0)->count(),
            'tingkatkan'     => (clone $baseCurr)->where('pemutu_indikator_orgunit.pengend_status', 'peningkatan')->count(),
            'penyesuaian'    => (clone $baseCurr)->where('pemutu_indikator_orgunit.pengend_status', 'penyesuaian')->count(),
            'tetap'          => (clone $baseCurr)->where('pemutu_indikator_orgunit.pengend_status', 'tetap')->count(),
            'nonaktif'       => (clone $baseCurr)->where('pemutu_indikator_orgunit.pengend_status', 'nonaktif')->count(),
        ];

        $kpiPrev = [
            'tercapai'       => (clone $basePrev)->whereIn('pemutu_indikator_orgunit.ami_hasil_akhir', [1, 2])->count(),
            'tidak_tercapai' => (clone $basePrev)->where('pemutu_indikator_orgunit.ami_hasil_akhir', 0)->count(),
            'tingkatkan'     => (clone $basePrev)->where('pemutu_indikator_orgunit.pengend_status', 'peningkatan')->count(),
            'penyesuaian'    => (clone $basePrev)->where('pemutu_indikator_orgunit.pengend_status', 'penyesuaian')->count(),
            'tetap'          => (clone $basePrev)->where('pemutu_indikator_orgunit.pengend_status', 'tetap')->count(),
            'nonaktif'       => (clone $basePrev)->where('pemutu_indikator_orgunit.pengend_status', 'nonaktif')->count(),
        ];

        $yoy = function ($curr, $prev) {
            if ($prev == 0) {
                return $curr > 0 ? 100 : 0;
            }

            return round((($curr - $prev) / $prev) * 100, 1);
        };

        $metrics = [];
        foreach ($kpiCurr as $key => $val) {
            $pct           = $yoy($val, $kpiPrev[$key]);
            $metrics[$key] = [
                'val'   => $val,
                'pct'   => $pct,
                'trend' => $pct > 0 ? 'up' : ($pct < 0 ? 'down' : 'flat'),
                'color' => $pct > 0 ? 'success' : ($pct < 0 ? 'danger' : 'secondary'),
            ];
        }

        // Trends over 4 years
        $trendYears     = collect(range($currentYear - 3, $currentYear));
        $trendIndikator = [];
        $trendStandar   = [];
        foreach ($trendYears as $y) {
            $q                = $buildQuery($y);
            $trendIndikator[] = (clone $q)->count();

            // Total Standar (Dokumen root)
            $trendStandar[] = (clone $q)
                ->join('pemutu_indikator_doksub', 'pemutu_indikator.indikator_id', '=', 'pemutu_indikator_doksub.indikator_id')
                ->join('pemutu_dok_sub', 'pemutu_indikator_doksub.doksub_id', '=', 'pemutu_dok_sub.doksub_id')
                ->distinct('pemutu_dok_sub.dok_id')
                ->count('pemutu_dok_sub.dok_id');
        }

        $trendData = [
            'years'     => $trendYears->toArray(),
            'indikator' => $trendIndikator,
            'standar'   => $trendStandar,
        ];

        // Top 3 Unit
        $unitRanksRaw = (clone $baseCurr)
            ->join('hr_struktur_organisasi as so', 'pemutu_indikator_orgunit.org_unit_id', '=', 'so.orgunit_id')
            ->selectRaw('so.code as unit_name, AVG(pemutu_indikator_orgunit.ed_skala) as avg_skala')
            ->groupBy('so.code')
            ->havingRaw('avg_skala IS NOT NULL')
            ->orderByDesc('avg_skala')
            ->get();

        $top3Units    = $unitRanksRaw->take(3);
        $bottom3Units = $unitRanksRaw->sortBy('avg_skala')->take(3)->values();

        // Top 3 Standar
        $standarRanksRaw = (clone $baseCurr)
            ->join('pemutu_indikator_doksub as ids', 'pemutu_indikator.indikator_id', '=', 'ids.indikator_id')
            ->join('pemutu_dok_sub as ds', 'ids.doksub_id', '=', 'ds.doksub_id')
            ->join('pemutu_dokumen as d', 'ds.dok_id', '=', 'd.dok_id')
            ->selectRaw('d.judul as dokumen_name, AVG(pemutu_indikator_orgunit.ed_skala) as avg_skala')
            ->groupBy('d.judul')
            ->havingRaw('avg_skala IS NOT NULL')
            ->orderByDesc('avg_skala')
            ->get();

        $top3Standar    = $standarRanksRaw->take(3);
        $bottom3Standar = $standarRanksRaw->sortBy('avg_skala')->take(3)->values();

        // Penetapan Jenis Kriteria Donut
        $jenisKriteriaRaw = (clone $baseCurr)
            ->selectRaw('pemutu_indikator.kelompok_indikator as label, COUNT(*) as total')
            ->whereNotNull('pemutu_indikator.kelompok_indikator')
            ->where('pemutu_indikator.kelompok_indikator', '!=', '')
            ->groupBy('pemutu_indikator.kelompok_indikator')
            ->get();

        // Eisenhower Matrix Boxes
        $eisenhowerCount = [
            'important_urgent'         => (clone $baseCurr)->where('pengend_important_matrix', '>=', 5)->where('pengend_urgent_matrix', '>=', 5)->count(),
            'important_not_urgent'     => (clone $baseCurr)->where('pengend_important_matrix', '>=', 5)->where('pengend_urgent_matrix', '<', 5)->count(),
            'not_important_urgent'     => (clone $baseCurr)->where('pengend_important_matrix', '<', 5)->where('pengend_urgent_matrix', '>=', 5)->count(),
            'not_important_not_urgent' => (clone $baseCurr)->where('pengend_important_matrix', '<', 5)->where('pengend_urgent_matrix', '<', 5)->count(),
        ];

        return view('pages.pemutu.dashboard.index', compact(
            'pageTitle', 'years', 'units', 'kriterias', 'currentYear', 'currentUnit', 'currentKriteria',
            'metrics', 'trendData', 'top3Units', 'bottom3Units', 'top3Standar', 'bottom3Standar',
            'jenisKriteriaRaw', 'eisenhowerCount', 'pendingApprovalsCount'
        ));
    }
}
