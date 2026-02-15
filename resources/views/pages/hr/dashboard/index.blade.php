@extends('layouts.admin.app')
@section('title', 'Dashboard HR')

@section('header')
<x-tabler.page-header title="Dashboard HR" pretitle="Human Resources Management">
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
<!-- KPI Cards -->
<div class="row row-deck row-cards">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Pegawai</div>
                    <div class="ms-auto lh-1">
                        <div class="dropdown">
                            <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Active</a>
                        </div>
                    </div>
                </div>
                <div class="h1 mb-3" id="total-pegawai">{{ $stats['total_pegawai'] ?? 0 }}</div>
                <div class="d-flex mb-2">
                    <div>Perubahan dari bulan lalu</div>
                    <div class="ms-auto">
                        <span class="text-green" id="pegawai-change">
                            <i class="ti ti-trending-up"></i> +{{ $stats['pegawai_change'] ?? 0 }}
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-primary" style="width: 85%" id="pegawai-progress"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Hadir Hari Ini</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-green text-green-fg" id="hadir-count">{{ $stats['hadir_hari_ini'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="h1 mb-3" id="hadir-percentage">{{ $stats['hadir_percentage'] ?? 0 }}%</div>
                <div class="d-flex mb-2">
                    <div>Kehadiran rate</div>
                    <div class="ms-auto">
                        <span class="text-green" id="hadir-trend">
                            <i class="ti ti-trending-up"></i> +2.3%
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-green" style="width: {{ $stats['hadir_percentage'] ?? 0 }}%" id="hadir-progress"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Cuti Aktif</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-yellow text-yellow-fg" id="cuti-count">{{ $stats['cuti_aktif'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="h1 mb-3" id="cuti-percentage">{{ $stats['cuti_percentage'] ?? 0 }}%</div>
                <div class="d-flex mb-2">
                    <div>Dari total pegawai</div>
                    <div class="ms-auto">
                        <span class="text-yellow" id="cuti-trend">
                            <i class="ti ti-trending-down"></i> -1.2%
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-yellow" style="width: {{ $stats['cuti_percentage'] ?? 0 }}%" id="cuti-progress"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Pending Approval</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-red text-red-fg" id="pending-count">{{ $stats['pending_approval'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="h1 mb-3" id="pending-number">{{ $stats['pending_approval'] ?? 0 }}</div>
                <div class="d-flex mb-2">
                    <div>Menunggu persetujuan</div>
                    <div class="ms-auto">
                        <span class="text-red" id="pending-trend">
                            <i class="ti ti-alert-triangle"></i> High
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-red" style="width: 75%" id="pending-progress"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Attendance Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Trend Kehadiran 30 Hari Terakhir</h3>
                <div class="card-actions">
                    <div class="dropdown">
                        <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Semua Unit</a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item active" href="#">Semua Unit</a>
                            <a class="dropdown-item" href="#">Fakultas Teknik</a>
                            <a class="dropdown-item" href="#">Fakultas Ekonomi</a>
                            <a class="dropdown-item" href="#">Administrasi Umum</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="chart-attendance-trend" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <!-- Department Distribution -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Distribusi Pegawai per Unit</h3>
            </div>
            <div class="card-body">
                <div id="chart-department-distribution" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activities -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aktivitas Terbaru</h3>
                <div class="card-actions">
                    <a href="#" class="btn btn-sm disabled">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                        <div class="timeline-item">
                            <div class="timeline-point timeline-point-{{ $activity->type_color ?? 'primary' }}"></div>
                            <div class="timeline-content">
                                <div class="timeline-time">{{ $activity->created_at->diffForHumans() }}</div>
                                <div class="timeline-title">{{ $activity->action }}</div>
                                <div class="timeline-body text-muted">
                                    {{ $activity->user_name }} - {{ $activity->description }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty">
                            <div class="empty-img"><img src="{{ asset('images/illustrations/undraw_empty_xct9.svg') }}" height="128" alt=""></div>
                            <p class="empty-title">Tidak ada aktivitas</p>
                            <p class="empty-subtitle text-muted">Belum ada aktivitas HR terbaru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Menunggu Persetujuan</h3>
                <div class="card-actions">
                    <a href="{{ route('hr.approval.index') }}" class="btn btn-sm">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body">
                @if(isset($pendingApprovals) && count($pendingApprovals) > 0)
                    @foreach($pendingApprovals as $approval)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm me-3">
                            <img src="{{ $approval->pegawai->avatar ?? asset('images/avatars/001m.jpg') }}" alt="Avatar">
                        </div>
                        <div class="flex-fill">
                            <div class="font-weight-medium">{{ $approval->pegawai->nama_lengkap ?? $approval->pegawai->nama ?? 'Unknown' }}</div>
                            <div class="text-muted">{{ $approval->model }} - {{ $approval->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="ms-auto">
                            <div class="btn-group">
                                <a href="{{ route('hr.approval.approve', $approval->riwayatapproval_id) }}" class="btn btn-sm btn-success">
                                    <i class="ti ti-check"></i>
                                </a>
                                <a href="{{ route('hr.approval.reject', $approval->riwayatapproval_id) }}" class="btn btn-sm btn-danger">
                                    <i class="ti ti-x"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty">
                        <div class="empty-img"><img src="{{ asset('images/illustrations/undraw_done_re_oak4.svg') }}" height="128" alt=""></div>
                        <p class="empty-title">Tidak ada pending</p>
                        <p class="empty-subtitle text-muted">Semua persetujuan sudah diproses.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Leave Calendar -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kalender Cuti Bulan Ini</h3>
                <div class="card-actions">
                    <div class="dropdown">
                        <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ now()->format('F Y') }}</a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Bulan Depan</a>
                            <a class="dropdown-item" href="#">Bulan Lalu</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="leave-calendar" style="height: 400px;"></div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-lg-4">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Stats</h3>
                    </div>
                    <div class="card-body">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Lembur Bulan Ini</div>
                                <div class="datagrid-content">
                                    <span class="badge bg-blue">{{ $stats['lembur_bulan_ini'] ?? 0 }} Jam</span>
                                </div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Izin Sakit</div>
                                <div class="datagrid-content">
                                    <span class="badge bg-red">{{ $stats['izin_sakit'] ?? 0 }} Hari</span>
                                </div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Izin Pribadi</div>
                                <div class="datagrid-content">
                                    <span class="badge bg-yellow">{{ $stats['izin_pribadi'] ?? 0 }} Hari</span>
                                </div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Dinas Luar</div>
                                <div class="datagrid-content">
                                    <span class="badge bg-green">{{ $stats['dinas_luar'] ?? 0 }} Kali</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('hr.pegawai.create') }}" class="btn btn-primary">
                                <i class="ti ti-user-plus me-2"></i>
                                Tambah Pegawai
                            </a>
                            <a href="{{ route('hr.perizinan.create') }}" class="btn btn-secondary">
                                <i class="ti ti-calendar me-2"></i>
                                Ajukan Izin
                            </a>
                            <a href="{{ route('hr.lembur.create') }}" class="btn btn-secondary">
                                <i class="ti ti-clock me-2"></i>
                                Ajukan Lembur
                            </a>
                            <a href="" class="btn btn-outline-secondary">
                                <i class="ti ti-chart-bar me-2"></i>
                                Laporan HR
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Performance Metrics -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Performance Metrics</h3>
            </div>
            <div class="card-body">
                <div id="chart-performance-metrics" style="height: 250px;"></div>
            </div>
        </div>
    </div>

    <!-- Employee Status -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Status Pegawai</h3>
            </div>
            <div class="card-body">
                <div id="chart-employee-status" style="height: 250px;"></div>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">System Health</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-green me-2"></span>
                        <span>HR System</span>
                        <span class="ms-auto text-green">Online</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-green me-2"></span>
                        <span>Attendance Device</span>
                        <span class="ms-auto text-green">Connected</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-green me-2"></span>
                        <span>Payroll System</span>
                        <span class="ms-auto text-green">Active</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-yellow me-2"></span>
                        <span>Backup</span>
                        <span class="ms-auto text-yellow">2 days ago</span>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-green" style="width: 95%">
                        <span>95% System Health</span>
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

// Attendance Trend Chart
const attendanceTrendOptions = {
    series: [{
        name: 'Hadir',
        data: [85, 88, 82, 90, 87, 92, 89, 86, 91, 88, 85, 90, 87, 89, 92, 88, 86, 90, 87, 89, 91, 88, 85, 87, 90, 89, 86, 88, 92, 87]
    }, {
        name: 'Cuti',
        data: [5, 4, 6, 3, 5, 2, 4, 5, 3, 4, 6, 3, 5, 4, 2, 5, 6, 4, 5, 4, 3, 5, 6, 5, 3, 4, 6, 5, 2, 5]
    }, {
        name: 'Izin',
        data: [3, 2, 4, 2, 3, 2, 3, 4, 2, 3, 4, 2, 3, 2, 3, 2, 3, 2, 3, 2, 4, 3, 4, 3, 2, 3, 2, 3, 3, 3]
    }],
    chart: {
        type: 'line',
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
    xaxis: {
        categories: Array.from({length: 30}, (_, i) => i + 1)
    },
    tooltip: {
        shared: true,
        intersect: false
    }
};

const attendanceTrendChart = new ApexCharts(document.querySelector("#chart-attendance-trend"), attendanceTrendOptions);
attendanceTrendChart.render();

// Department Distribution Chart
const departmentDistributionOptions = {
    series: [45, 25, 15, 10, 5],
    chart: {
        type: 'donut',
        height: 300,
        ...chartOptions
    },
    labels: ['Fakultas Teknik', 'Fakultas Ekonomi', 'Administrasi', 'Fakultas Hukum', 'Lainnya'],
    colors: ['#206bc4', '#2f9e44', '#f59f00', '#fa5252', '#868e96'],
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

const departmentDistributionChart = new ApexCharts(document.querySelector("#chart-department-distribution"), departmentDistributionOptions);
departmentDistributionChart.render();

// Performance Metrics Chart
const performanceMetricsOptions = {
    series: [{
        name: 'Productivity',
        data: [75, 82, 78, 85, 88, 92, 87, 90, 86, 89]
    }],
    chart: {
        type: 'radar',
        height: 250,
        ...chartOptions
    },
    xaxis: {
        categories: ['Quality', 'Speed', 'Accuracy', 'Teamwork', 'Innovation', 'Leadership', 'Communication', 'Problem Solving', 'Adaptability', 'Reliability']
    },
    yaxis: {
        show: false
    }
};

const performanceMetricsChart = new ApexCharts(document.querySelector("#chart-performance-metrics"), performanceMetricsOptions);
performanceMetricsChart.render();

// Employee Status Chart
const employeeStatusOptions = {
    series: [120, 15, 8, 5, 2],
    chart: {
        type: 'pie',
        height: 250,
        ...chartOptions
    },
    labels: ['Active', 'On Leave', 'Remote', 'Training', 'Suspended'],
    colors: ['#2f9e44', '#f59f00', '#206bc4', '#868e96', '#fa5252'],
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

const employeeStatusChart = new ApexCharts(document.querySelector("#chart-employee-status"), employeeStatusOptions);
employeeStatusChart.render();

// Leave Calendar (Simple implementation)
function initLeaveCalendar() {
    const calendarEl = document.getElementById('leave-calendar');
    if (!calendarEl) return;
    
    // Simple calendar implementation
    const today = new Date();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    
    let calendarHTML = '<div class="table-responsive"><table class="table table-vcenter table-bordered"><thead><tr>';
    const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    
    days.forEach(day => {
        calendarHTML += `<th class="text-center">${day}</th>`;
    });
    calendarHTML += '</tr></thead><tbody>';
    
    let date = 1;
    for (let week = 0; week < 6; week++) {
        calendarHTML += '<tr>';
        for (let day = 0; day < 7; day++) {
            if (week === 0 && day < firstDay) {
                calendarHTML += '<td></td>';
            } else if (date > daysInMonth) {
                calendarHTML += '<td></td>';
            } else {
                const isToday = date === today.getDate();
                const hasLeave = Math.random() > 0.8; // Random leave simulation
                calendarHTML += `<td class="text-center ${isToday ? 'bg-primary-lt' : ''} ${hasLeave ? 'bg-yellow-lt' : ''}">${date}</td>`;
                date++;
            }
        }
        calendarHTML += '</tr>';
        if (date > daysInMonth) break;
    }
    
    calendarHTML += '</tbody></table></div>';
    calendarEl.innerHTML = calendarHTML;
}

// Initialize calendar
initLeaveCalendar();

// Functions
function changePeriod(period) {
    console.log('Changing period to:', period);
    refreshDashboard();
}

function refreshDashboard() {
    console.log('Refreshing dashboard...');
    // Add AJAX calls here to refresh data
}
</script>
@endpush
