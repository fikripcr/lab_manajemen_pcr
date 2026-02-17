<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Lembur;
use App\Models\Hr\Perizinan;
use App\Models\Hr\Presensi;
use App\Models\Hr\RiwayatApproval;
use App\Models\Shared\Pegawai;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period    = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate   = now();

        // Main Statistics
        $stats = $this->getMainStats($startDate, $endDate);

        // Recent Activities
        $recentActivities = $this->getRecentActivities();

        // Pending Approvals
        $pendingApprovals = $this->getPendingApprovals();

        // Chart Data
        $chartData = $this->getChartData($period);

        return view('pages.hr.dashboard.index', compact(
            'stats',
            'recentActivities',
            'pendingApprovals',
            'chartData'
        ));
    }

    private function getMainStats($startDate, $endDate)
    {
        // Total pegawai aktif
        $totalPegawai = Pegawai::whereHas('latestStatusPegawai.statusPegawai', function ($query) {
            $query->where('is_active', true);
        })->count();

        // Pegawai hadir hari ini
        $hadirHariIni = $this->getTodayAttendanceCount();

        // Pegawai cuti aktif
        $cutiAktif = Perizinan::where('jenisizin_id', function ($query) {
            $query->select('jenisizin_id')->from('hr_jenis_izin')->where('nama', 'like', '%cuti%');
        })
            ->where('tgl_awal', '<=', now())
            ->where('tgl_akhir', '>=', now())
            ->whereHas('riwayatApproval', function ($query) {
                $query->where('status', 'Approved');
            })
            ->count();

        // Pending approvals
        $pendingApproval = RiwayatApproval::where('status', 'Pending')->count();

        // Previous period for comparison
        $previousPeriod = $this->getPreviousPeriod($startDate, $endDate);
        $previousTotal  = Pegawai::whereHas('latestStatusPegawai.statusPegawai', function ($query) {
            $query->where('is_active', true);
        })->whereBetween('created_at', [$previousPeriod['start'], $previousPeriod['end']])->count();

        $changePercentage = $previousTotal > 0 ? (($totalPegawai - $previousTotal) / $previousTotal) * 100 : 0;

        // Additional stats
        $lemburBulanIni = $this->getLemburHoursThisMonth();
        $izinSakit      = $this->getIzinCount('sakit');
        $izinPribadi    = $this->getIzinCount('pribadi');
        $dinasLuar      = $this->getDinasLuarCount();

        return [
            'total_pegawai'    => $totalPegawai,
            'hadir_hari_ini'   => $hadirHariIni,
            'cuti_aktif'       => $cutiAktif,
            'pending_approval' => $pendingApproval,
            'hadir_percentage' => $totalPegawai > 0 ? round(($hadirHariIni / $totalPegawai) * 100, 1) : 0,
            'cuti_percentage'  => $totalPegawai > 0 ? round(($cutiAktif / $totalPegawai) * 100, 1) : 0,
            'pegawai_change'   => round($changePercentage, 1) > 0 ? '+' . round($changePercentage, 1) : round($changePercentage, 1),
            'lembur_bulan_ini' => $lemburBulanIni,
            'izin_sakit'       => $izinSakit,
            'izin_pribadi'     => $izinPribadi,
            'dinas_luar'       => $dinasLuar,
        ];
    }

    private function getTodayAttendanceCount()
    {
        return Presensi::whereDate('tanggal', now())->count();
    }

    private function getLemburHoursThisMonth()
    {
        // Calculate total overtime hours this month
        $lemburHours = Lembur::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereHas('riwayatApproval', function ($query) {
                $query->where('status', 'Approved');
            })
            ->with('lemburWaktu')
            ->get()
            ->sum(function ($lembur) {
                return $lembur->lemburWaktu->sum('durasi') / 60; // Convert minutes to hours
            });

        return round($lemburHours, 1);
    }

    private function getIzinCount($type)
    {
        return Perizinan::where('jenisizin_id', function ($query) use ($type) {
            $query->select('jenisizin_id')->from('hr_jenis_izin')->where('nama', 'like', "%{$type}%");
        })
            ->whereMonth('tgl_awal', now()->month)
            ->whereYear('tgl_awal', now()->year)
            ->whereHas('riwayatApproval', function ($query) {
                $query->where('status', 'Approved');
            })
            ->count();
    }

    private function getDinasLuarCount()
    {
        return Perizinan::where('jenisizin_id', function ($query) {
            $query->select('jenisizin_id')->from('hr_jenis_izin')->where('nama', 'like', '%dinas%');
        })
            ->whereMonth('tgl_awal', now()->month)
            ->whereYear('tgl_awal', now()->year)
            ->whereHas('riwayatApproval', function ($query) {
                $query->where('status', 'Approved');
            })
            ->count();
    }

    private function getRecentActivities()
    {
        return collect([]);
        /*
        return ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($activity) {
                $activity->type_color = $this->getActivityTypeColor($activity->action);
                return $activity;
            });
        */
    }

    private function getActivityTypeColor($action)
    {
        $colors = [
            'create'  => 'success',
            'update'  => 'primary',
            'delete'  => 'danger',
            'approve' => 'success',
            'reject'  => 'danger',
            'login'   => 'blue',
            'logout'  => 'yellow',
        ];

        foreach ($colors as $key => $color) {
            if (stripos($action, $key) !== false) {
                return $color;
            }
        }

        return 'secondary';
    }

    private function getPendingApprovals()
    {
        return RiwayatApproval::with(['subject'])
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    private function getChartData($period)
    {
        // Attendance trend for last 30 days
        $attendanceTrend = [];
        $labels          = [];

        for ($i = 29; $i >= 0; $i--) {
            $date     = now()->subDays($i);
            $labels[] = $date->format('d');

            // Simulate attendance data
            $totalPegawai = Pegawai::whereHas('latestStatusPegawai.statusPegawai', function ($query) {
                $query->where('is_active', true);
            })->count();

            $hadir = round($totalPegawai * (rand(80, 95) / 100));
            $cuti  = round($totalPegawai * (rand(2, 8) / 100));
            $izin  = round($totalPegawai * (rand(1, 5) / 100));

            $attendanceTrend[] = [
                'hadir' => $hadir,
                'cuti'  => $cuti,
                'izin'  => $izin,
            ];
        }

        // Department distribution
        $departmentDistribution = $this->getDepartmentDistribution();

        // Performance metrics (mock data for now)
        $performanceMetrics = $this->getPerformanceMetrics();

        return [
            'attendance_trend'        => [
                'labels' => $labels,
                'data'   => $attendanceTrend,
            ],
            'department_distribution' => $departmentDistribution,
            'performance_metrics'     => $performanceMetrics,
        ];
    }

    private function getDepartmentDistribution()
    {
        // Get units with employee counts
        // We link Pegawai to their latest RiwayatJabStruktural
        return Pegawai::whereHas('latestJabatanStruktural')
            ->get()
            ->groupBy('latestJabatanStruktural.org_unit_id')
            ->map(function ($group, $orgUnitId) {
                $unit = \App\Models\Shared\StrukturOrganisasi::find($orgUnitId);
                return (object) [
                    'name'  => $unit->name ?? 'Unknown',
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('count')
            ->values()
            ->take(5);
    }

    private function getPerformanceMetrics()
    {
        // Mock performance data
        return [
            'productivity' => [75, 82, 78, 85, 88, 92, 87, 90, 86, 89],
            'quality'      => [80, 85, 82, 88, 90, 85, 88, 92, 87, 91],
            'efficiency'   => [70, 75, 80, 82, 85, 88, 86, 89, 90, 92],
        ];
    }

    private function getStartDate($period)
    {
        switch ($period) {
            case 'today':
                return now()->startOfDay();
            case 'week':
                return now()->startOfWeek();
            case 'month':
                return now()->startOfMonth();
            case 'year':
                return now()->startOfYear();
            default:
                return now()->startOfMonth();
        }
    }

    private function getPreviousPeriod($startDate, $endDate)
    {
        $duration = $startDate->diffInDays($endDate);

        return [
            'start' => $startDate->copy()->subDays($duration),
            'end'   => $startDate->copy()->subDay(),
        ];
    }

    public function refresh(Request $request)
    {
        $period    = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate   = now();

        $stats     = $this->getMainStats($startDate, $endDate);
        $chartData = $this->getChartData($period);

        return response()->json([
            'stats'     => $stats,
            'chartData' => $chartData,
        ]);
    }
}
