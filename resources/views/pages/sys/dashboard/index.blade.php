@extends('layouts.sys.app')

@section('title', 'System Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Page Title and Search -->
            <div class="col-12 mb-4">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">System Management Dashboard</h4>
                    <p class="text-muted">Manage system configurations, user roles, and application settings</p>
                </div>

                <!-- Prominent Search Bar -->
                <div class="d-flex justify-content-center mb-4">
                    <div class="col-12 col-lg-8">
                        <div class="position-relative">
                            <i class="bx bx-search fs-4 position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); z-index: 10;"></i>
                            <input type="text" class="form-control ps-5 fs-5 py-3 border-2 search-input" placeholder="Search across all system functions..." aria-label="Search across all system functions..." onclick="openGlobalSearchModal('{{ route('sys-search') }}')" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Environment & Server Stats -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <div class="avatar-initial rounded-circle bg-transparent text-primary">
                                            <i class="bx bx-layout bx-lg"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $appName }}</h5>
                                        <p class="mb-0 text-muted">Server Time: <span id="serverTime">{{ \Carbon\Carbon::parse($serverTime)->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm:ss') }}</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <div class="avatar-initial rounded-circle bg-transparent text-info">
                                            <i class="bx bx-server bx-lg"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap gap-3">
                                        <div>
                                            <p class="mb-1">Environment</p>
                                            <span class="badge bg-label-{{ $appEnvironment === 'production' ? 'danger' : ($appEnvironment === 'local' ? 'success' : 'warning') }}">
                                                {{ ucfirst($appEnvironment) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-1">Debug</p>
                                            <span class="badge bg-label-{{ $appDebug === 'Enabled' ? 'danger' : 'success' }}">
                                                {{ $appDebug }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-1">URL</p>
                                            <span class="badge bg-label-info">{{ $appUrl }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disk Space and Database Size -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 ">
                                <h6 class="mb-2">Disk Space</h6>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-3" style="height: 1.2rem;">
                                        @if (isset($serverMonitoringData['disk_space']))
                                            @php
                                                $usagePercentage = $serverMonitoringData['disk_space']['data']['usage_percentage'] ?? 0;
                                            @endphp
                                            <div class="progress-bar {{ $usagePercentage > 80 ? 'bg-danger' : ($usagePercentage > 60 ? 'bg-warning' : 'bg-success') }}" role="progressbar" style="width: {{ $usagePercentage }}%" aria-valuenow="{{ $usagePercentage }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ $usagePercentage }}%
                                            </div>
                                        @else
                                            <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                0%
                                            </div>
                                        @endif
                                    </div>
                                    @if (isset($serverMonitoringData['disk_space']) && isset($serverMonitoringData['disk_space']['data']))
                                        <span class="fw-bold">
                                            {{ $serverMonitoringData['disk_space']['data']['used_space_formatted'] }} /
                                            {{ $serverMonitoringData['disk_space']['data']['total_space_formatted'] }}
                                        </span>
                                    @else
                                        <span class="text-muted">No data</span>
                                    @endif
                                </div>
                            </div>
                            <!-- Refresh Button Row -->
                            <div class="col-md-4 d-flex justify-content-end align-items-end">
                                <div class="d-flex text-end">
                                    <div class="me-2">
                                        @if (isset($serverMonitoringData['project_size']))
                                            <small class="text-muted d-block text-center">Last checked: {{ \Carbon\Carbon::parse($serverMonitoringData['project_size']['last_check'])->diffForHumans() }}</small>
                                        @else
                                            <small class="text-muted d-block text-center">Never checked</small>
                                        @endif
                                    </div>
                                    <a href="{{ route('sys.dashboard', ['refresh_monitoring' => true]) }}" class="btn btn-xs btn-outline-primary" title="Refresh Server Monitoring">
                                        <i class="bx bx-refresh"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row  mt-3">
                            <div class="col-md-6  border-end">
                                <h6 class="mb-2">Project Size <span class="text-muted">({{ $serverMonitoringData['project_size']['data']['size_formatted'] ?? 'N/A' }})</span></h6>
                                @if (isset($serverMonitoringData['project_size']))
                                    <div class="row text-start">
                                        <div class="col-3">
                                            <small class="text-muted">Apps</small>
                                            <p class="mb-0 fw-bold">{{ $serverMonitoringData['project_size']['data']['apps_size']['size_formatted'] ?? '0B' }}</p>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted">Uploads</small>
                                            <p class="mb-0 fw-bold">{{ $serverMonitoringData['project_size']['data']['uploads_size']['size_formatted'] ?? '0B' }}</p>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted">Backups</small>
                                            <p class="mb-0 fw-bold">{{ $serverMonitoringData['project_size']['data']['storage_size']['size_formatted'] ?? '0B' }}</p>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted">Logs</small>
                                            <p class="mb-0 fw-bold">{{ $serverMonitoringData['project_size']['data']['log_size']['size_formatted'] ?? '0B' }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <h5 class="mb-0 text-muted">No data</h5>
                                    </div>
                                @endif

                            </div>

                            <div class="col-md-6">
                                <h6 class="mb-2">Database Size</h6>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        @if (isset($serverMonitoringData['database_size']))
                                            <h5 class="mb-0"></h5>
                                            <div class="row text-start">
                                                <div class="">
                                                    <small class="text-muted">{{ $serverMonitoringData['database_size']['data']['database_name'] ?? 'N/A' }}</small>
                                                    <p class="mb-0 fw-bold">{{ $serverMonitoringData['database_size']['data']['size_formatted'] ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <h5 class="mb-0 text-muted">No data</h5>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="col-xl-3 col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-between">
                        <div class="me-4">
                            <div class="text-muted">Total Users</div>
                            <div class="d-flex align-items-end mt-1">
                                <div class="fs-3 text-primary fw-bold me-2">{{ $totalUsers }}</div>
                            </div>
                        </div>
                        <div class="avatar flex-shrink-0">
                            <div class="avatar-initial rounded-circle bg-transparent text-primary">
                                <i class="bx bx-user bx-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-between">
                        <div class="me-4">
                            <div class="text-muted">Total Roles</div>
                            <div class="d-flex align-items-end mt-1">
                                <div class="fs-3 text-success fw-bold me-2">{{ $totalRoles }}</div>
                            </div>
                        </div>
                        <div class="avatar flex-shrink-0">
                            <div class="avatar-initial rounded-circle bg-transparent text-success">
                                <i class="bx bx-shield-alt bx-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-between">
                        <div class="me-4">
                            <div class="text-muted">Total Permissions</div>
                            <div class="d-flex align-items-end mt-1">
                                <div class="fs-3 text-warning fw-bold me-2">{{ $totalPermissions }}</div>
                            </div>
                        </div>
                        <div class="avatar flex-shrink-0">
                            <div class="avatar-initial rounded-circle bg-transparent text-warning">
                                <i class="bx bx-key bx-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-between">
                        <div class="me-4">
                            <div class="text-muted">Today's Activities</div>
                            <div class="d-flex align-items-end mt-1">
                                <div class="fs-3 text-info fw-bold me-2">{{ $todayActivities }}</div>
                            </div>
                        </div>
                        <div class="avatar flex-shrink-0">
                            <div class="avatar-initial rounded-circle bg-transparent text-info">
                                <i class="bx bx-history bx-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Management Menu -->
            <div class="col-12 mb-4">
                <h5 class="mb-4 text-center">System Management Functions</h5>
                <div class="row g-4">
                    <!-- Activity Log -->
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded hover-pointer" onclick="window.location='{{ route('activity-log.index') }}'">
                            <div class="avatar me-3">
                                <div class="avatar-initial rounded-circle bg-transparent text-primary">
                                    <i class="bx bx-history bx-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Activity Log</h6>
                                <small class="text-muted">Track all system activities and user actions</small>
                            </div>
                            <i class="bx bx-chevron-right text-muted"></i>
                        </div>
                    </div>

                    <!-- Roles -->
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded hover-pointer" onclick="window.location='{{ route('roles.index') }}'">
                            <div class="avatar me-3">
                                <div class="avatar-initial rounded-circle bg-transparent text-success">
                                    <i class="bx bx-shield-alt bx-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">User Roles</h6>
                                <small class="text-muted">Manage user roles and permissions</small>
                            </div>
                            <i class="bx bx-chevron-right text-muted"></i>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded hover-pointer" onclick="window.location='{{ route('permissions.index') }}'">
                            <div class="avatar me-3">
                                <div class="avatar-initial rounded-circle bg-transparent text-success">
                                    <i class="bx bx-key bx-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Permissions</h6>
                                <small class="text-muted">Manage system permissions</small>
                            </div>
                            <i class="bx bx-chevron-right text-muted"></i>
                        </div>
                    </div>

                    <!-- App Configuration -->
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded hover-pointer" onclick="window.location='{{ route('app-config') }}'">
                            <div class="avatar me-3">
                                <div class="avatar-initial rounded-circle bg-transparent text-warning">
                                    <i class="bx bx-cog bx-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">App Configuration</h6>
                                <small class="text-muted">Configure application settings</small>
                            </div>
                            <i class="bx bx-chevron-right text-muted"></i>
                        </div>
                    </div>

                    <!-- Backup Management -->
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded hover-pointer" onclick="window.location='{{ route('admin.backup.index') }}'">
                            <div class="avatar me-3">
                                <div class="avatar-initial rounded-circle bg-transparent text-info">
                                    <i class="bx bx-data bx-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Backup Management</h6>
                                <small class="text-muted">Create and manage system backups</small>
                            </div>
                            <i class="bx bx-chevron-right text-muted"></i>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded hover-pointer" onclick="window.location='{{ route('notifications.index') }}'">
                            <div class="avatar me-3">
                                <div class="avatar-initial rounded-circle bg-transparent text-danger">
                                    <i class="bx bx-bell bx-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Notifications</h6>
                                <small class="text-muted">Manage system notifications</small>
                            </div>
                            <i class="bx bx-chevron-right text-muted"></i>
                        </div>
                    </div>

                    <!-- Error Log -->
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded hover-pointer" onclick="window.location='{{ route('sys.error-log.index') }}'">
                            <div class="avatar me-3">
                                <div class="avatar-initial rounded-circle bg-transparent text-danger">
                                    <i class="bx bx-error bx-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Error Log</h6>
                                <small class="text-muted">View and manage system errors</small>
                            </div>
                            <i class="bx bx-chevron-right text-muted"></i>
                        </div>
                    </div>

                    <!-- Testing -->
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded hover-pointer" onclick="window.location='{{ route('test.index') }}'">
                            <div class="avatar me-3">
                                <div class="avatar-initial rounded-circle bg-transparent text-primary">
                                    <i class="bx bx-wrench bx-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Testing</h6>
                                <small class="text-muted">Test system functionality</small>
                            </div>
                            <i class="bx bx-chevron-right text-muted"></i>
                        </div>
                    </div>

                    <!-- Documentation -->
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center p-3 bg-white rounded hover-pointer" onclick="window.location='{{ route('sys.documentation.index') }}'">
                            <div class="avatar me-3">
                                <div class="avatar-initial rounded-circle bg-transparent text-secondary">
                                    <i class="bx bx-book bx-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Documentation</h6>
                                <small class="text-muted">System documentation and guides</small>
                            </div>
                            <i class="bx bx-chevron-right text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>




            <!-- Charts Section -->
            <div class="col-12 mb-4">
                <div class="row">
                    <!-- User Role Distribution Chart -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">User Distribution by Role</h5>
                                <div id="userRoleChart"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Trend Chart -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Activity Trend (Last 7 Days)</h5>
                                <div id="activityTrendChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Recent Logs -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Logs (Last 10 Entries)</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Activity</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse($recentLogs as $log)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-3">
                                                        <span class="avatar-title rounded-circle bg-label-primary text-primary">
                                                            {{ $log->causer ? strtoupper($log->causer->name[0]) : 'S' }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-medium">{{ $log->causer ? $log->causer->name : 'System' }}</span>
                                                        <small class="text-muted">{{ $log->causer ? $log->causer->email : '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-2">
                                                        <span class="avatar-title rounded-circle bg-label-info text-info">
                                                            @if (str_contains(strtolower($log->description), 'create'))
                                                                <i class="bx bx-plus bx-xs"></i>
                                                            @elseif(str_contains(strtolower($log->description), 'update'))
                                                                <i class="bx bx-edit bx-xs"></i>
                                                            @elseif(str_contains(strtolower($log->description), 'delete') || str_contains(strtolower($log->description), 'destroy'))
                                                                <i class="bx bx-trash bx-xs"></i>
                                                            @elseif(str_contains(strtolower($log->description), 'login'))
                                                                <i class="bx bx-log-in bx-xs"></i>
                                                            @elseif(str_contains(strtolower($log->description), 'logout'))
                                                                <i class="bx bx-log-out bx-xs"></i>
                                                            @else
                                                                <i class="bx bx-cog bx-xs"></i>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <span>{{ $log->description }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $log->created_at->diffForHumans() }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                No recent logs
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .hover-pointer {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .hover-pointer:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            border-color: #d9dee3 !important;
        }

        .menu-icon {
            font-size: 1.125rem;
            line-height: 1.4;
            min-width: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bx {
            font-size: 1.4rem;
        }
    </style>

@endsection

@push('scripts')
    <script src="/js/dashboard.js"></script>
    <!-- Apex Charts JS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        // Ensure the DOM is fully loaded before initializing charts
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit more for the layout to be fully rendered
            setTimeout(function() {
                // Parse chart data from JSON
                const roleChartData = {!! $roleChartData !!};
                const activityChartData = {!! $activityChartData !!};

                // User Role Distribution Chart (Donut)
                const userRoleChart = {
                    chart: {
                        height: 350,
                        type: 'donut',
                    },
                    series: roleChartData.series,
                    labels: roleChartData.labels,
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
                }

                const userRoleChartEl = document.querySelector('#userRoleChart');
                if (userRoleChartEl) {
                    const userRoleChartObj = new ApexCharts(userRoleChartEl, userRoleChart);
                    userRoleChartObj.render();
                }

                // Activity Trend Chart (Line)
                const activityTrendChart = {
                    chart: {
                        height: 350,
                        type: 'line',
                        zoom: {
                            enabled: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'straight'
                    },
                    series: activityChartData.series,
                    grid: {
                        row: {
                            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                            opacity: 0.5
                        },
                    },
                    xaxis: {
                        categories: activityChartData.categories,
                    }
                }

                const activityTrendChartEl = document.querySelector('#activityTrendChart');
                if (activityTrendChartEl) {
                    const activityTrendChartObj = new ApexCharts(activityTrendChartEl, activityTrendChart);
                    activityTrendChartObj.render();
                }
            }, 300); // Delay initialization to ensure DOM is ready
        });
    </script>

@endpush
