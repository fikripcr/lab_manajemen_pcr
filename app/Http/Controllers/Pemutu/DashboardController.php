<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Event\Rapat;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\IndikatorPegawai;
use App\Models\Pemutu\PeriodeKpi;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Shared\Pegawai;
use App\Models\Shared\Personil;

class DashboardController extends Controller
{
    public function index()
    {
        $pageTitle = 'Dashboard Pemutu';

        // Metrics - Total Counts
        $totalDokumen   = Dokumen::count();
        $totalIndikator = Indikator::count();
        $totalKpi       = IndikatorPegawai::count();
        $totalPersonil  = Personil::count();
        $totalPegawai   = Pegawai::count();
        // Dokumen by Type
        $dokumenByType = Dokumen::selectRaw('jenis, COUNT(*) as total')
            ->whereNotNull('jenis')
            ->groupBy('jenis')
            ->pluck('total', 'jenis');

        $dokumenKebijakan = [
            'visi'    => $dokumenByType->get('visi', 0),
            'misi'    => $dokumenByType->get('misi', 0),
            'rjp'     => $dokumenByType->get('rjp', 0),
            'renstra' => $dokumenByType->get('renstra', 0),
            'renop'   => $dokumenByType->get('renop', 0),
        ];

        $dokumenStandar = [
            'standar'         => $dokumenByType->get('standar', 0),
            'manual_prosedur' => $dokumenByType->get('manual_prosedur', 0),
            'formulir'        => $dokumenByType->get('formulir', 0),
        ];

        // Indikator by Type
        $indikatorByType = Indikator::selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $standarCount  = $indikatorByType->get('standar', 0);
        $renopCount    = $indikatorByType->get('renop', 0);
        $performaCount = $indikatorByType->get('performa', 0);

        // Active Periods (based on current date within range)
        $now               = now();
        $activePeriodeSpmi = PeriodeSpmi::whereDate('penetapan_awal', '<=', $now)
            ->whereDate('peningkatan_akhir', '>=', $now)
            ->first();

        $activePeriodeKpi = PeriodeKpi::whereDate('tanggal_mulai', '<=', $now)
            ->whereDate('tanggal_selesai', '>=', $now)
            ->first();

        // Recent Activity
        $recentDokumen = Dokumen::latest()->take(5)->get();
        $recentKpi     = IndikatorPegawai::with(['indikator', 'pegawai'])
            ->whereIn('status', ['submitted', 'approved'])
            ->latest()
            ->take(5)
            ->get();

        // KPI Achievement Rate (submitted/approved vs total)
        $kpiSubmitted       = IndikatorPegawai::whereIn('status', ['submitted', 'approved'])->count();
        $kpiAchievementRate = $totalKpi > 0 ? round(($kpiSubmitted / $totalKpi) * 100, 1) : 0;

        // KPI by Status
        $kpiByStatus = IndikatorPegawai::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Upcoming Rapat
        $upcomingRapats = Rapat::where('tgl_rapat', '>=', now()->toDateString())
            ->orderBy('tgl_rapat', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->take(5)
            ->get();

        // -------------------------------------------------------------
        // NEW DASHBOARD CHARTS (PHASE 7)
        // -------------------------------------------------------------
        $activeSpmiPeriodeYear = $activePeriodeSpmi ? $activePeriodeSpmi->periode : null;

        $baseChartQuery = function () use ($activeSpmiPeriodeYear) {
            $query = IndikatorOrgUnit::join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
                ->where('pemutu_indikator.type', 'standar')
                ->whereNull('pemutu_indikator.deleted_at');

            // Jika ada periode aktif, ambil yang standar normal tahun ini ATAU dokumen duplikasi (peningkatan_{tahun})
            if ($activeSpmiPeriodeYear) {
                $query->where(function ($q) use ($activeSpmiPeriodeYear) {
                    $q->where('pemutu_indikator.origin_from', 'peningkatan_' . $activeSpmiPeriodeYear)
                        ->orWhere('pemutu_indikator.periode_mulai', $activeSpmiPeriodeYear);
                });
            }
            return $query;
        };

        // 1. ED per Unit (Rata-rata skala ED per Unit Kerja)
        $edPerUnitRaw = (clone $baseChartQuery())
            ->join('struktur_organisasi', 'pemutu_indikator_orgunit.org_unit_id', '=', 'struktur_organisasi.orgunit_id')
            ->whereNotNull('pemutu_indikator_orgunit.ed_skala')
            ->selectRaw('struktur_organisasi.code as unit_code, AVG(pemutu_indikator_orgunit.ed_skala) as avg_skala')
            ->groupBy('struktur_organisasi.code')
            ->orderBy('struktur_organisasi.code')
            ->get();
        $edPerUnit = [
            'categories' => $edPerUnitRaw->pluck('unit_code')->toArray(),
            'data'       => $edPerUnitRaw->pluck('avg_skala')->map(fn($v) => round((float) $v, 2))->toArray(),
        ];

        // 2. AMI per Unit (Count per ami_hasil_akhir)
        $amiPerUnitRaw = (clone $baseChartQuery())
            ->join('struktur_organisasi', 'pemutu_indikator_orgunit.org_unit_id', '=', 'struktur_organisasi.orgunit_id')
            ->whereNotNull('pemutu_indikator_orgunit.ami_hasil_akhir')
            ->selectRaw('struktur_organisasi.code as unit_code, pemutu_indikator_orgunit.ami_hasil_akhir, COUNT(*) as total')
            ->groupBy('struktur_organisasi.code', 'pemutu_indikator_orgunit.ami_hasil_akhir')
            ->get();

        // Grouping into series: 0=KTS, 1=Terpenuhi, 2=Terlampaui
        $amiUnits  = $amiPerUnitRaw->pluck('unit_code')->unique()->sort()->values()->toArray();
        $amiSeries = [
            ['name' => 'KTS', 'data' => []],
            ['name' => 'Terpenuhi', 'data' => []],
            ['name' => 'Terlampaui', 'data' => []],
        ];
        foreach ($amiUnits as $unit) {
            $unitData               = $amiPerUnitRaw->where('unit_code', $unit);
            $amiSeries[0]['data'][] = $unitData->where('ami_hasil_akhir', 0)->first()->total ?? 0;
            $amiSeries[1]['data'][] = $unitData->where('ami_hasil_akhir', 1)->first()->total ?? 0;
            $amiSeries[2]['data'][] = $unitData->where('ami_hasil_akhir', 2)->first()->total ?? 0;
        }
        $amiPerUnit = [
            'categories' => $amiUnits,
            'series'     => $amiSeries,
        ];

        // 3. Eisenhower Matrix (Urgent vs Important) scatter bubble
        $eisenhowerRaw = (clone $baseChartQuery())
            ->whereNotNull('pemutu_indikator_orgunit.pengend_urgent_matrix')
            ->whereNotNull('pemutu_indikator_orgunit.pengend_important_matrix')
            ->selectRaw('CAST(pengend_urgent_matrix AS UNSIGNED) as x_val, CAST(pengend_important_matrix AS UNSIGNED) as y_val, COUNT(*) as z_val')
            ->groupBy('pengend_urgent_matrix', 'pengend_important_matrix')
            ->get();

        // Map to format required by apexcharts scatter/bubble: [[x, y, z]]
        $eisenhowerSeries = $eisenhowerRaw->map(function ($item) {
            return [$item->x_val, $item->y_val, $item->z_val];
        })->toArray();

        // 4. Status Pengendalian (Count per status)
        $pengendalianRaw = (clone $baseChartQuery())
            ->whereNotNull('pemutu_indikator_orgunit.pengend_status')
            ->where('pemutu_indikator_orgunit.pengend_status', '!=', '')
            ->selectRaw('pemutu_indikator_orgunit.pengend_status, COUNT(*) as total')
            ->groupBy('pemutu_indikator_orgunit.pengend_status')
            ->get();
        $pengendStatus = [
            'labels' => $pengendalianRaw->pluck('pengend_status')->map(fn($v) => ucfirst($v))->toArray(),
            'series' => $pengendalianRaw->pluck('total')->toArray(),
        ];

        return view('pages.pemutu.dashboard.index', compact(
            'pageTitle',
            'totalDokumen',
            'totalIndikator',
            'totalKpi',
            'totalPersonil',
            'totalPegawai',
            'standarCount',
            'renopCount',
            'performaCount',
            'activePeriodeSpmi',
            'activePeriodeKpi',
            'recentDokumen',
            'recentKpi',
            'kpiAchievementRate',
            'kpiByStatus',
            'dokumenByType',
            'dokumenKebijakan',
            'dokumenStandar',
            'upcomingRapats',
            'edPerUnit',
            'amiPerUnit',
            'eisenhowerSeries',
            'pengendStatus'
        ));
    }
}
