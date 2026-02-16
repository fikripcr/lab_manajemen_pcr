@extends('layouts.admin.app')
@section('title', 'Dashboard E-Office')

@section('header')
<x-tabler.page-header title="Dashboard E-Office" pretitle="Sistem Layanan Digital">
    <x-slot:actions>
        <div class="btn-group">
            <button type="button" class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                <i class="ti ti-calendar me-1"></i>
                {{ now()->format('F Y') }}
            </button>
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
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Layanan</div>
                    <div class="ms-auto lh-1">
                        <div class="dropdown">
                            <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Last 7 days</a>
                        </div>
                    </div>
                </div>
                <div class="h1 mb-3" id="total-layanan">{{ $stats['total_layanan'] ?? 0 }}</div>
                <div class="d-flex mb-2">
                    <div>Perubahan dari bulan lalu</div>
                    <div class="ms-auto">
                        <span class="text-green" id="layanan-change">
                            <i class="ti ti-trending-up"></i> +12.5%
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-primary" style="width: 75%" id="layanan-progress"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Menunggu Proses</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-yellow text-yellow-fg" id="pending-count">{{ $stats['pending'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="h1 mb-3" id="pending-percentage">{{ $stats['pending_percentage'] ?? 0 }}%</div>
                <div class="d-flex mb-2">
                    <div>Dari total layanan</div>
                    <div class="ms-auto">
                        <span class="text-yellow" id="pending-trend">
                            <i class="ti ti-trending-down"></i> -5.2%
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-yellow" style="width: {{ $stats['pending_percentage'] ?? 0 }}%" id="pending-progress"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Selesai Diproses</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-green text-green-fg" id="completed-count">{{ $stats['completed'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="h1 mb-3" id="completed-percentage">{{ $stats['completed_percentage'] ?? 0 }}%</div>
                <div class="d-flex mb-2">
                    <div>Success rate</div>
                    <div class="ms-auto">
                        <span class="text-green" id="completed-trend">
                            <i class="ti ti-trending-up"></i> +8.7%
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-green" style="width: {{ $stats['completed_percentage'] ?? 0 }}%" id="completed-progress"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Response Time</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-blue text-blue-fg">Avg</span>
                    </div>
                </div>
                <div class="h1 mb-3" id="response-time">{{ $stats['avg_response_time'] ?? 0 }}h</div>
                <div class="d-flex mb-2">
                    <div>Waktu rata-rata</div>
                    <div class="ms-auto">
                        <span class="text-blue" id="response-trend">
                            <i class="ti ti-trending-down"></i> -2.1h
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-blue" style="width: 65%" id="response-progress"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart Layanan per Bulan -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Trend Layanan 6 Bulan Terakhir</h3>
                <div class="card-actions">
                    <div class="dropdown">
                        <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Semua Jenis</a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item active" href="#">Semua Jenis</a>
                            <a class="dropdown-item" href="#">Akademik</a>
                            <a class="dropdown-item" href="#">Administrasi</a>
                            <a class="dropdown-item" href="#">Kemahasiswaan</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="chart-layanan-trend" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <!-- Jenis Layanan Distribution -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Distribusi Jenis Layanan</h3>
            </div>
            <div class="card-body">
                <div id="chart-jenis-layanan" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activities -->
    <div class="col-lg-6 d-flex">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Aktivitas Terbaru</h3>
                <div class="card-actions">
                    <a href="{{ route('eoffice.layanan.index') }}" class="btn btn-sm">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                        <div class="timeline-item">
                            <div class="timeline-point timeline-point-primary"></div>
                            <div class="timeline-content">
                                <div class="timeline-time">{{ $activity->created_at->diffForHumans() }}</div>
                                <div class="timeline-title">{{ $activity->jenisLayanan->name ?? 'Unknown' }}</div>
                                <div class="timeline-body text-muted small">
                                    {{ $activity->pengusul_nama }} - {{ $activity->no_layanan }}
                                </div>
                                <div class="timeline-actions">
                                    <span class="badge bg-{{ $activity->latestStatus->status_color ?? 'blue' }} text-{{ $activity->latestStatus->status_color ?? 'blue' }}-fg">
                                        {{ $activity->latestStatus->status_layanan ?? 'Pending' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty">
                            <div class="empty-img"><img src="{{ asset('images/illustrations/undraw_quitting_time_dm6t.svg') }}" height="128" alt=""></div>
                            <p class="empty-title">Tidak ada aktivitas</p>
                            <p class="empty-subtitle text-muted">Belum ada aktivitas layanan terbaru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="col-lg-6 d-flex">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Top PIC Layanan</h3>
                <div class="card-actions">
                    <div class="dropdown">
                        <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Bulan Ini</a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item active" href="#">Bulan Ini</a>
                            <a class="dropdown-item" href="#">Tahun Ini</a>
                            <a class="dropdown-item" href="#">All Time</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($topPerformers) && count($topPerformers) > 0)
                    @foreach($topPerformers as $index => $performer)
                    <div class="d-flex align-items-center mb-3">
                        <span class="avatar me-3" style="background-color: {{ ['primary', 'secondary', 'success', 'warning', 'danger'][$index] }};">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-fill">
                            <div class="font-weight-medium">{{ $performer->name ?? 'Unknown' }}</div>
                            <div class="text-muted">{{ $performer->processed_count }} layanan diproses</div>
                        </div>
                        <div class="ms-auto">
                            <div class="progress progress-sm" style="width: 100px;">
                                <div class="progress-bar bg-{{ ['primary', 'secondary', 'success', 'warning', 'danger'][$index] }}" 
                                     style="width: {{ ($performer->processed_count / $topPerformers->first()->processed_count) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty">
                        <div class="empty-img"><img src="{{ asset('images/illustrations/undraw_team_up_re_84ok.svg') }}" height="128" alt=""></div>
                        <p class="empty-title">Belum ada data</p>
                        <p class="empty-subtitle text-muted">Belum ada data performer bulan ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Layanan Status Distribution -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Status Layanan</h3>
            </div>
            <div class="card-body">
                <div id="chart-status-layanan" style="height: 250px;"></div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <x-tabler.button href="{{ route('eoffice.layanan.services') }}" class="btn-primary" icon="ti ti-plus" text="Buat Layanan Baru" />
                    <x-tabler.button href="{{ route('eoffice.layanan.index') }}" class="btn-secondary" icon="ti ti-list" text="Daftar Layanan" />
                    <x-tabler.button href="#" class="btn-outline-secondary disabled" icon="ti ti-chart-bar" text="Laporan (Coming Soon)" />
                    <x-tabler.button href="#" class="btn-outline-secondary disabled" icon="ti ti-settings" text="Pengaturan (Coming Soon)" />
                </div>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">System Status</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-green me-2"></span>
                        <span>E-Office Service</span>
                        <span class="ms-auto text-green">Online</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-green me-2"></span>
                        <span>Database</span>
                        <span class="ms-auto text-green">Connected</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-green me-2"></span>
                        <span>Email Service</span>
                        <span class="ms-auto text-green">Active</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-yellow me-2"></span>
                        <span>File Storage</span>
                        <span class="ms-auto text-yellow">85% Used</span>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-yellow" style="width: 85%">
                        <span>85% Storage Used</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Chart Options
const chartOptions = {
    theme: {
        mode: document.body.classList.contains('dark') ? 'dark' : 'light'
    }
};

// Layanan Trend Chart
const layananTrendOptions = {
    series: [{
        name: 'Total Layanan',
        data: [65, 78, 90, 81, 96, 105]
    }, {
        name: 'Selesai',
        data: [55, 68, 75, 71, 86, 95]
    }, {
        name: 'Pending',
        data: [10, 10, 15, 10, 10, 10]
    }],
    chart: {
        type: 'area',
        height: 300,
        ...chartOptions
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth',
        width: 2
    },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.3
        }
    },
    xaxis: {
        categories: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy'
        }
    }
};

const layananTrendChart = new ApexCharts(document.querySelector("#chart-layanan-trend"), layananTrendOptions);
layananTrendChart.render();

// Jenis Layanan Pie Chart
const jenisLayananOptions = {
    series: [44, 33, 23],
    chart: {
        type: 'donut',
        height: 300,
        ...chartOptions
    },
    labels: ['Akademik', 'Administrasi', 'Kemahasiswaan'],
    colors: ['#206bc4', '#2f9e44', '#f59f00'],
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 200
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
};

const jenisLayananChart = new ApexCharts(document.querySelector("#chart-jenis-layanan"), jenisLayananOptions);
jenisLayananChart.render();

// Status Layanan Chart
const statusLayananOptions = {
    series: [65, 20, 15],
    chart: {
        type: 'pie',
        height: 250,
        ...chartOptions
    },
    labels: ['Selesai', 'Proses', 'Pending'],
    colors: ['#2f9e44', '#f59f00', '#fa5252'],
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 200
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
};

const statusLayananChart = new ApexCharts(document.querySelector("#chart-status-layanan"), statusLayananOptions);
statusLayananChart.render();

// Functions
function changePeriod(period) {
    // Implement period change logic
    console.log('Changing period to:', period);
    refreshDashboard();
}

function refreshDashboard() {
    // Implement dashboard refresh logic
    console.log('Refreshing dashboard...');
    // You can add AJAX calls here to refresh data
}
</script>
@endpush
