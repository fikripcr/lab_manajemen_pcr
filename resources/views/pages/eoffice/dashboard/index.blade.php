@extends('layouts.tabler.app')
@section('title', 'Dashboard E-Office')

@section('header')
<x-tabler.page-header title="Dashboard E-Office" pretitle="Sistem Layanan Digital">
    <x-slot:actions>
        <div class="btn-group">
            <x-tabler.button class="btn-sm dropdown-toggle" data-bs-toggle="dropdown" icon="ti ti-calendar" text="{{ now()->format('F Y') }}" />
            <div class="dropdown-menu">
                <a href="#" class="dropdown-item" onclick="changePeriod('today')">Hari Ini</a>
                <a href="#" class="dropdown-item" onclick="changePeriod('week')">Minggu Ini</a>
                <a href="#" class="dropdown-item" onclick="changePeriod('month')">Bulan Ini</a>
                <a href="#" class="dropdown-item" onclick="changePeriod('year')">Tahun Ini</a>
            </div>
        </div>
        <x-tabler.button type="button" icon="ti ti-refresh" class="btn-sm" onclick="refreshDashboard()" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <div class="row row-deck row-cards">
            <!-- KPI Cards -->
            <div class="col-sm-6 col-lg-3">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="subheader">Total Layanan</div>
                        </div>
                        <div class="h1 mb-2">{{ $stats['total_layanan'] ?? 0 }}</div>
                        <div class="d-flex mb-2">
                            <div class="text-muted small">Periode Terpilih</div>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="subheader">Menunggu Proses</div>
                            <div class="ms-auto lh-1">
                                <span class="badge bg-yellow-lt fw-bold">{{ $stats['pending'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="h1 mb-2">{{ $stats['pending_percentage'] ?? 0 }}%</div>
                        <div class="d-flex mb-2">
                            <div class="text-muted small">Menunggu Respon PIC</div>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-yellow" style="width: {{ $stats['pending_percentage'] ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="subheader">Selesai Diproses</div>
                            <div class="ms-auto lh-1">
                                <span class="badge bg-green-lt fw-bold">{{ $stats['completed'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="h1 mb-2">{{ $stats['completed_percentage'] ?? 0 }}%</div>
                        <div class="d-flex mb-2">
                            <div class="text-muted small">Layanan Berhasil Selesai</div>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" style="width: {{ $stats['completed_percentage'] ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="subheader">Response Time</div>
                        </div>
                        <div class="h1 mb-2">{{ $stats['avg_response_time'] ?? 0 }}h</div>
                        <div class="d-flex mb-2">
                            <div class="text-muted small">Rata-rata Waktu Proses</div>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-blue" style="width: 70%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h3 class="card-title fw-bold"><i class="ti ti-chart-line me-2 text-primary"></i> Trend Layanan 6 Bulan Terakhir</h3>
                    </div>
                    <div class="card-body">
                        <div id="chart-layanan-trend" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h3 class="card-title fw-bold"><i class="ti ti-chart-pie me-2 text-primary"></i> Distribusi Jenis Layanan</h3>
                    </div>
                    <div class="card-body">
                        <div id="chart-jenis-layanan" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Activities & Top Performers -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-transparent border-0 py-3 d-flex align-items-center">
                        <h3 class="card-title fw-bold m-0"><i class="ti ti-activity me-2 text-primary"></i> Aktivitas Terbaru</h3>
                        <div class="ms-auto">
                            <x-tabler.button href="{{ route('eoffice.layanan.index') }}" class="btn-ghost-secondary btn-sm" text="Lihat Semua" />
                        </div>
                    </div>
                    <div class="card-table">
                        <x-tabler.datatable-client
                            id="table-aktivitas"
                            :columns="[
                                ['name' => 'Layanan'],
                                ['name' => 'Status'],
                                ['name' => 'Waktu'],
                                ['name' => '', 'className' => 'w-1', 'sortable' => false]
                            ]"
                        >
                            @forelse($recentActivities as $activity)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="avatar avatar-sm rounded-circle bg-blue-lt">
                                                {{ substr($activity->jenisLayanan->nama ?? 'L', 0, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $activity->jenisLayanan->nama ?? 'Unknown' }}</div>
                                            <div class="text-muted small">{{ $activity->no_layanan }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $currStatus = $activity->latestStatus->status_layanan ?? 'Pending';
                                        $color = match($currStatus) {
                                            'Selesai' => 'success',
                                            'Proses'  => 'primary',
                                            'Pending' => 'warning',
                                            'Batal'   => 'danger',
                                            default   => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $color }}-lt fw-bold">{{ $currStatus }}</span>
                                </td>
                                <td class="text-muted small">
                                    {{ $activity->created_at->diffForHumans() }}
                                </td>
                                <td>
                                    <x-tabler.button href="{{ route('eoffice.layanan.show', $activity->layanan_id) }}" class="btn-ghost-primary btn-icon btn-sm" icon="ti ti-eye" />
                                </td>
                            </tr>
                            @empty
                                {{-- Handled by component --}}
                            @endforelse
                        </x-tabler.datatable-client>
                        
                        @if($recentActivities->isEmpty())
                            <div class="text-center py-4 text-muted">Tidak ada aktivitas terbaru.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h3 class="card-title fw-bold m-0"><i class="ti ti-award me-2 text-warning"></i> Top Performers (PIC)</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            @forelse($topPerformers as $performer)
                            <div class="d-flex align-items-center mb-4">
                                @if(isset($performer->avatar) && $performer->avatar)
                                <span class="avatar avatar-md rounded-circle me-3" style="background-image: url({{ $performer->avatar }})"></span>
                                @else
                                <span class="avatar avatar-md rounded-circle me-3 bg-primary-lt">
                                    {{ substr($performer->name ?? '?', 0, 1) }}
                                </span>
                                @endif
                                <div class="flex-fill">
                                    <div class="fw-bold text-dark">{{ $performer->name ?? 'Unknown' }}</div>
                                    <div class="text-muted small">{{ $performer->processed_count }} Layanan Selesai</div>
                                </div>
                                <div class="ms-auto text-end">
                                    <div class="badge bg-green-lt fw-bold">#{{ $loop->iteration }}</div>
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-muted py-3">Belum ada data performer.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Chart Options
    const chartOptions = {
        theme: {
            mode: document.body.classList.contains('dark') ? 'dark' : 'light'
        }
    };

    // Layanan Trend Chart
    const trendData = @json($chartData['monthly_trend'] ?? []);
    if (trendData.labels) {
        const trendOptions = {
            series: [{
                name: 'Total Layanan',
                data: trendData.data.map(d => d.total)
            }, {
                name: 'Selesai',
                data: trendData.data.map(d => d.completed)
            }, {
                name: 'Proses/Pending',
                data: trendData.data.map(d => d.pending)
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: { show: false },
                ...chartOptions
            },
            dataLabels: { enabled: false },
            colors: ['#206bc4', '#2fb344', '#f59f00'],
            stroke: { curve: 'smooth', width: 2 },
            xaxis: {
                categories: trendData.labels
            },
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05 }
            }
        };
        const trendChart = new ApexCharts(document.querySelector("#chart-layanan-trend"), trendOptions);
        trendChart.render();
    }

    // Jenis Layanan Chart
    const jenisData = @json($chartData['jenis_distribution'] ?? []);
    if (jenisData.length > 0) {
        const jenisOptions = {
            series: jenisData.map(d => d.layanans_count),
            chart: {
                type: 'donut',
                height: 300,
                ...chartOptions
            },
            labels: jenisData.map(d => d.nama),
            colors: ['#206bc4', '#2fb344', '#f59f00', '#d63384', '#6610f2'],
            legend: { position: 'bottom' }
        };
        const jenisChart = new ApexCharts(document.querySelector("#chart-jenis-layanan"), jenisOptions);
        jenisChart.render();
    }
});

function changePeriod(period) {
    window.location.href = `{{ route('eoffice.dashboard') }}?period=${period}`;
}

function refreshDashboard() {
    window.location.reload();
}
</script>
@endpush
