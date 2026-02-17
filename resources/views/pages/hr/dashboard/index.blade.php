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
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <!-- KPI Cards -->
            <div class="col-sm-6 col-lg-3">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="subheader text-uppercase fw-bold text-muted">Total Pegawai</div>
                            <div class="ms-auto lh-1">
                                <span class="badge bg-primary-lt">Aktif</span>
                            </div>
                        </div>
                        <div class="h1 mb-2">{{ $stats['total_pegawai'] ?? 0 }}</div>
                        <div class="text-muted small">Personil Terdata</div>
                        <div class="progress progress-sm mt-3">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="subheader text-uppercase fw-bold text-muted">Hadir Hari Ini</div>
                            <div class="ms-auto lh-1">
                                <span class="badge bg-green-lt">{{ $stats['hadir_hari_ini'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="h1 mb-2">{{ $stats['hadir_percentage'] ?? 0 }}%</div>
                        <div class="text-muted small">Tingkat Kehadiran</div>
                        <div class="progress progress-sm mt-3">
                            <div class="progress-bar bg-green" style="width: {{ $stats['hadir_percentage'] ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="subheader text-uppercase fw-bold text-muted">Cuti Aktif</div>
                            <div class="ms-auto lh-1">
                                <span class="badge bg-yellow-lt">{{ $stats['cuti_aktif'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="h1 mb-2">{{ $stats['cuti_percentage'] ?? 0 }}%</div>
                        <div class="text-muted small">Sedang Cuti</div>
                        <div class="progress progress-sm mt-3">
                            <div class="progress-bar bg-yellow" style="width: {{ $stats['cuti_percentage'] ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="subheader text-uppercase fw-bold text-muted">Approval Pending</div>
                            <div class="ms-auto lh-1">
                                <span class="badge bg-red-lt">{{ $stats['pending_approval'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="h1 mb-2">{{ $stats['pending_approval'] ?? 0 }}</div>
                        <div class="text-muted small">Menunggu Persetujuan</div>
                        <div class="progress progress-sm mt-3">
                            <div class="progress-bar bg-red" style="width: {{ $stats['pending_approval'] > 0 ? 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h3 class="card-title fw-bold"><i class="ti ti-chart-area-line me-2 text-primary"></i> Trend Kehadiran 30 Hari Terakhir</h3>
                    </div>
                    <div class="card-body">
                        <div id="chart-attendance-trend" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h3 class="card-title fw-bold"><i class="ti ti-chart-pie me-2 text-primary"></i> Distribusi Pegawai per Unit</h3>
                    </div>
                    <div class="card-body">
                        <div id="chart-department-distribution" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Approvals & Stats -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-transparent border-0 py-3 d-flex align-items-center">
                        <h3 class="card-title fw-bold m-0"><i class="ti ti-clipboard-check me-2 text-primary"></i> Menunggu Persetujuan</h3>
                        <div class="ms-auto">
                            <a href="{{ route('hr.approval.index') }}" class="btn btn-ghost-secondary btn-sm">Lihat Semua</a>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="table-responsive">
                            <table class="table table-vcenter table-nowrap card-table border-top">
                                <thead>
                                    <tr>
                                        <th>Pegawai</th>
                                        <th>Modul</th>
                                        <th>Tanggal Mohon</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingApprovals as $approval)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm rounded-circle me-3 bg-primary-lt">
                                                    {{ substr($approval->pegawai->nama ?? 'P', 0, 1) }}
                                                </span>
                                                <div>
                                                    <div class="fw-bold">{{ $approval->pegawai->nama ?? 'Unknown' }}</div>
                                                    <div class="text-muted small">{{ $approval->pegawai->nip ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-blue-lt fw-bold">{{ class_basename($approval->subject_type ?? $approval->model) }}</span>
                                        </td>
                                        <td class="text-muted small">
                                            {{ $approval->created_at->format('d M Y') }}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('hr.approval.approve', $approval->riwayatapproval_id) }}" class="btn btn-icon btn-ghost-success btn-sm">
                                                    <i class="ti ti-check"></i>
                                                </a>
                                                <a href="{{ route('hr.approval.reject', $approval->riwayatapproval_id) }}" class="btn btn-icon btn-ghost-danger btn-sm">
                                                    <i class="ti ti-x"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Semua persetujuan sudah diproses.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h3 class="card-title fw-bold m-0"><i class="ti ti-list-details me-2 text-primary"></i> Ringkasan Statistik</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <div class="d-flex align-items-center p-3 rounded bg-blue-lt">
                                <div class="avatar avatar-sm rounded bg-blue text-white me-3">
                                    <i class="ti ti-clock"></i>
                                </div>
                                <div class="flex-fill">
                                    <div class="fw-bold">Lembur Bulan Ini</div>
                                    <div class="text-muted small">Total jam lembur approved</div>
                                </div>
                                <div class="h3 m-0 text-blue fw-bold">{{ $stats['lembur_bulan_ini'] ?? 0 }}h</div>
                            </div>

                            <div class="d-flex align-items-center p-3 rounded bg-red-lt">
                                <div class="avatar avatar-sm rounded bg-red text-white me-3">
                                    <i class="ti ti-pill"></i>
                                </div>
                                <div class="flex-fill">
                                    <div class="fw-bold">Izin Sakit</div>
                                    <div class="text-muted small">Jumlah hari izin sakit</div>
                                </div>
                                <div class="h3 m-0 text-red fw-bold">{{ $stats['izin_sakit'] ?? 0 }}d</div>
                            </div>

                            <div class="d-flex align-items-center p-3 rounded bg-yellow-lt">
                                <div class="avatar avatar-sm rounded bg-yellow text-white me-3">
                                    <i class="ti ti-user-x"></i>
                                </div>
                                <div class="flex-fill">
                                    <div class="fw-bold">Izin Pribadi</div>
                                    <div class="text-muted small">Jumlah hari izin pribadi</div>
                                </div>
                                <div class="h3 m-0 text-yellow fw-bold">{{ $stats['izin_pribadi'] ?? 0 }}d</div>
                            </div>

                            <div class="d-flex align-items-center p-3 rounded bg-green-lt">
                                <div class="avatar avatar-sm rounded bg-green text-white me-3">
                                    <i class="ti ti-briefcase"></i>
                                </div>
                                <div class="flex-fill">
                                    <div class="fw-bold">Dinas Luar</div>
                                    <div class="text-muted small">Penugasan luar kantor</div>
                                </div>
                                <div class="h3 m-0 text-green fw-bold">{{ $stats['dinas_luar'] ?? 0 }}x</div>
                            </div>
                        </div>
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
document.addEventListener("DOMContentLoaded", function () {
    const chartOptions = {
        theme: {
            mode: document.body.classList.contains('dark') ? 'dark' : 'light'
        }
    };

    // Attendance Trend Chart
    const attendanceData = @json($chartData['attendance_trend'] ?? []);
    if (attendanceData.labels) {
        const trendOptions = {
            series: [{
                name: 'Hadir',
                data: attendanceData.data.map(d => d.hadir)
            }, {
                name: 'Cuti',
                data: attendanceData.data.map(d => d.cuti)
            }, {
                name: 'Izin',
                data: attendanceData.data.map(d => d.izin)
            }],
            chart: {
                type: 'line',
                height: 300,
                toolbar: { show: false },
                ...chartOptions
            },
            stroke: { curve: 'smooth', width: 3 },
            colors: ['#2fb344', '#f59f00', '#206bc4'],
            dataLabels: { enabled: false },
            xaxis: {
                categories: attendanceData.labels,
                title: { text: 'Hari Dalam Sebulan' }
            },
            markers: { size: 0 }
        };
        const trendChart = new ApexCharts(document.querySelector("#chart-attendance-trend"), trendOptions);
        trendChart.render();
    }

    // Department Distribution Chart
    const deptData = @json($chartData['department_distribution'] ?? []);
    if (deptData.length > 0) {
        const deptOptions = {
            series: deptData.map(d => d.count),
            chart: {
                type: 'donut',
                height: 300,
                ...chartOptions
            },
            labels: deptData.map(d => d.name),
            colors: ['#206bc4', '#2fb344', '#f59f00', '#d63384', '#6610f2'],
            legend: { position: 'bottom' }
        };
        const deptChart = new ApexCharts(document.querySelector("#chart-department-distribution"), deptOptions);
        deptChart.render();
    }
});

function changePeriod(period) {
    window.location.href = `{{ route('hr.dashboard') }}?period=${period}`;
}

function refreshDashboard() {
    window.location.reload();
}
</script>
@endpush
