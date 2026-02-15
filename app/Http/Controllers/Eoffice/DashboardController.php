<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Models\Eoffice\JenisLayanan;
use App\Models\Eoffice\Layanan;
use App\Models\Eoffice\LayananStatus;
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

        // Top Performers
        $topPerformers = $this->getTopPerformers($period);

        // Chart Data
        $chartData = $this->getChartData($period);

        return view('pages.eoffice.dashboard.index', compact(
            'stats',
            'recentActivities',
            'topPerformers',
            'chartData'
        ));
    }

    private function getMainStats($startDate, $endDate)
    {
        $totalLayanan = Layanan::whereBetween('created_at', [$startDate, $endDate])->count();
        $completed    = Layanan::whereHas('latestStatus', function ($query) {
            $query->where('status_layanan', 'Selesai');
        })->whereBetween('created_at', [$startDate, $endDate])->count();

        $pending = Layanan::whereHas('latestStatus', function ($query) {
            $query->whereIn('status_layanan', ['Pending', 'Proses']);
        })->whereBetween('created_at', [$startDate, $endDate])->count();

        // Calculate average response time (in hours)
        $avgResponseTime = LayananStatus::selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, done_at)) as avg_time')
            ->whereNotNull('done_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->value('avg_time') ?? 0;

        // Previous period for comparison
        $previousPeriod = $this->getPreviousPeriod($startDate, $endDate);
        $previousTotal  = Layanan::whereBetween('created_at', [$previousPeriod['start'], $previousPeriod['end']])->count();

        $changePercentage = $previousTotal > 0 ? (($totalLayanan - $previousTotal) / $previousTotal) * 100 : 0;

        return [
            'total_layanan'        => $totalLayanan,
            'completed'            => $completed,
            'pending'              => $pending,
            'completed_percentage' => $totalLayanan > 0 ? round(($completed / $totalLayanan) * 100, 1) : 0,
            'pending_percentage'   => $totalLayanan > 0 ? round(($pending / $totalLayanan) * 100, 1) : 0,
            'avg_response_time'    => round($avgResponseTime, 1),
            'change_percentage'    => round($changePercentage, 1),
            'pegawai_change'       => '+12', // Placeholder
        ];
    }

    private function getRecentActivities()
    {
        return Layanan::with(['jenisLayanan', 'latestStatus'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    private function getTopPerformers($period)
    {
        $startDate = $this->getStartDate($period);

        // This would need to be implemented based on your user/assignment logic
        // For now, returning mock data structure
        return collect([
            (object) ['name' => 'Ahmad Fauzi', 'processed_count' => 45],
            (object) ['name' => 'Siti Nurhaliza', 'processed_count' => 38],
            (object) ['name' => 'Budi Santoso', 'processed_count' => 32],
            (object) ['name' => 'Dewi Lestari', 'processed_count' => 28],
            (object) ['name' => 'Eko Prasetyo', 'processed_count' => 25],
        ]);
    }

    private function getChartData($period)
    {
        // Monthly trend data for the last 6 months
        $monthlyData = [];
        $labels      = [];

        for ($i = 5; $i >= 0; $i--) {
            $month    = now()->subMonths($i);
            $labels[] = $month->format('M');

            $total = Layanan::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $completed = Layanan::whereHas('latestStatus', function ($query) {
                $query->where('status_layanan', 'Selesai');
            })->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $pending = Layanan::whereHas('latestStatus', function ($query) {
                $query->whereIn('status_layanan', ['Pending', 'Proses']);
            })->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $monthlyData[] = [
                'total'     => $total,
                'completed' => $completed,
                'pending'   => $pending,
            ];
        }

        // Jenis layanan distribution
        $jenisDistribution = JenisLayanan::withCount('layanans')
            ->orderBy('layanans_count', 'desc')
            ->limit(5)
            ->get();

        // Status distribution
        $statusDistribution = Layanan::selectRaw('
            CASE
                WHEN latest_status.status_layanan = "Selesai" THEN "Selesai"
                WHEN latest_status.status_layanan IN ("Pending", "Proses") THEN "Proses"
                ELSE "Pending"
            END as status_group,
            COUNT(*) as count
        ')
            ->leftJoin('eoffice_layanan_status as latest_status', 'eoffice_layanan.latest_layananstatus_id', '=', 'latest_status.layananstatus_id')
            ->groupBy('status_group')
            ->get();

        return [
            'monthly_trend'       => [
                'labels' => $labels,
                'data'   => $monthlyData,
            ],
            'jenis_distribution'  => $jenisDistribution,
            'status_distribution' => $statusDistribution,
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
