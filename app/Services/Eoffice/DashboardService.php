<?php
namespace App\Services\Eoffice;

use App\Models\Eoffice\JenisLayanan;
use App\Models\Eoffice\Layanan;
use App\Models\Eoffice\LayananStatus;

class DashboardService
{
    public function getDashboardData($period)
    {
        $startDate = $this->getStartDate($period);
        $endDate   = now();

        return [
            'stats'            => $this->getMainStats($startDate, $endDate),
            'recentActivities' => $this->getRecentActivities(),
            'topPerformers'    => $this->getTopPerformers($startDate),
            'chartData'        => $this->getChartData($period),
        ];
    }

    public function getMainStats($startDate, $endDate)
    {
        $totalLayanan = Layanan::whereBetween('created_at', [$startDate, $endDate])->count();
        $completed    = Layanan::whereHas('latestStatus', function ($query) {
            $query->where('status_layanan', 'Selesai');
        })->whereBetween('created_at', [$startDate, $endDate])->count();

        $pending = Layanan::whereHas('latestStatus', function ($query) {
            $query->whereIn('status_layanan', ['Pending', 'Proses']);
        })->whereBetween('created_at', [$startDate, $endDate])->count();

        $avgResponseTime = LayananStatus::selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, done_at)) as avg_time')
            ->whereNotNull('done_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->value('avg_time') ?? 0;

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

    public function getRecentActivities()
    {
        return Layanan::with(['jenisLayanan', 'latestStatus'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function getTopPerformers($startDate)
    {
        return LayananStatus::where('status_layanan', 'Selesai')
            ->whereBetween('created_at', [$startDate, now()])
            ->with('user')
            ->selectRaw('created_by, COUNT(*) as processed_count')
            ->groupBy('created_by')
            ->orderBy('processed_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'name'            => $item->user->name ?? 'Unknown',
                    'processed_count' => $item->processed_count,
                    'avatar'          => $item->user->profile_photo_url ?? null,
                ];
            });
    }

    public function getChartData($period)
    {
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

        $jenisDistribution = JenisLayanan::withCount('layanans')
            ->orderBy('layanans_count', 'desc')
            ->limit(5)
            ->get();

        $statusDistribution = Layanan::selectRaw("
            CASE
                WHEN latest_status.status_layanan = 'Selesai' THEN 'Selesai'
                WHEN latest_status.status_layanan IN ('Pending', 'Proses') THEN 'Proses'
                ELSE 'Pending'
            END as status_group,
            COUNT(*) as count
        ")
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

    public function getStartDate($period)
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
}
