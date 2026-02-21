<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Event\Rapat;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorPegawai;
use App\Models\Pemutu\PeriodeKpi;
use App\Models\Pemutu\PeriodeSpmi;
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

        return view('pages.pemutu.dashboard.index', compact(
            'pageTitle',
            'totalDokumen',
            'totalIndikator',
            'totalKpi',
            'totalPersonil',
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
            'dokumenKebijakan',
            'dokumenStandar',
            'upcomingRapats'
        ));
    }
}
