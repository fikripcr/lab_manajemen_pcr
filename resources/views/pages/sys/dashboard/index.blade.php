@extends('layouts.tabler.app')

@section('title', 'Dashboard')

@section('header')
<x-tabler.page-header title="Dashboard" pretitle="Overview">
    <x-slot:actions>
        <div class="d-flex align-items-center gap-2">
            <div class="input-icon">
                <span class="input-icon-addon">
                    <i class="ti ti-search bg-transparent"></i>
                </span>
                <input type="text" class="form-control" name="search" placeholder="Search..." aria-label="Search" onclick="openGlobalSearchModal('{{ route('sys-search') }}')">
            </div>
            <x-tabler.button type="button" class="btn-primary" onclick="window.location.reload();" icon="ti ti-refresh" text="Refresh" />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="row">

        <!-- Application Environment & Server Stats -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <div class="avatar-initial rounded-circle bg-transparent text-primary">
                                        <i class="ti ti-server-2 fs-1"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $appName }}</h5>
                                    <p class="mb-0 text-muted">Server Time: <span id="serverTime">{{ formatTanggalIndo($serverTime) }}</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <div class="avatar-initial rounded-circle bg-transparent text-info">
                                        <i class="ti ti-box fs-1"></i>
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
                            <h3 class="card-title mb-2">Disk Space</h3>
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
                                <a href="{{ route('sys.dashboard', ['refresh_monitoring' => true]) }}" class="btn btn-action btn-outline-primary" title="Refresh Server Monitoring">
                                    <i class="ti ti-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row  mt-3">
                        <div class="col-md-6  border-end">
                            <h4 class="mb-2 h3 card-title">Project Size <span class="text-muted">({{ $serverMonitoringData['project_size']['data']['size_formatted'] ?? 'N/A' }})</span></h4>
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
                            <h4 class="mb-2 h3 card-title">Database Size</h4>
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
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-muted mb-1">Total Users</div>
                            <div class="h2 mb-0">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-primary-lt rounded-circle p-3" style="width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                                <i class="ti ti-users icon-lg text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-muted mb-1">Total Roles</div>
                            <div class="h2 mb-0">{{ $totalRoles }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-green-lt rounded-circle p-3" style="width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                                <i class="ti ti-shield-lock icon-lg text-green"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-muted mb-1">Total Permissions</div>
                            <div class="h2 mb-0">{{ $totalPermissions }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-danger-lt rounded-circle p-3" style="width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                                <i class="ti ti-lock icon-lg text-danger-lt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-muted mb-1">Today's Activities</div>
                            <div class="h2 mb-0">{{ $todayActivities }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-cyan-lt rounded-circle p-3" style="width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                                <i class="ti ti-activity icon-lg text-cyan"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Management Menu -->
        <div class="col-12 mb-4">
            <h3 class="card-title mb-4 text-center">System Management Functions</h3>
            <div class="row g-4">
                <!-- Activity Log -->
                <!-- Activity Log -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('activity-log.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-primary-lt">
                                <i class="ti ti-activity"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-0 text-reset h3 card-title fs-4">Activity Log</h4>
                                <small class="text-muted">Track all system activities and user actions</small>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>

                <!-- Roles -->
                <!-- Roles -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('sys.roles.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-green-lt">
                                <i class="ti ti-shield-lock"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-0 text-reset h3 card-title fs-4">User Roles</h4>
                                <small class="text-muted">Manage user roles and permissions</small>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>

                <!-- Permissions -->
                <!-- Permissions -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('sys.permissions.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-yellow-lt">
                                <i class="ti ti-lock"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-0 text-reset h3 card-title fs-4">Permissions</h4>
                                <small class="text-muted">Manage system permissions</small>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>

                <!-- App Configuration -->
                <!-- App Configuration -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('app-config') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-orange-lt">
                                <i class="ti ti-settings"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-0 text-reset h3 card-title fs-4">App Configuration</h4>
                                <small class="text-muted">Configure application settings</small>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>

                <!-- Backup Management -->
                <!-- Backup Management -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('sys.backup.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-cyan-lt">
                                <i class="ti ti-database"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-0 text-reset h3 card-title fs-4">Backup Management</h4>
                                <small class="text-muted">Create and manage system backups</small>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>

                <!-- Notifications -->
                <!-- Notifications -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('notifications.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-red-lt">
                                <i class="ti ti-bell"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-0 text-reset h3 card-title fs-4">Notifications</h4>
                                <small class="text-muted">Manage system notifications</small>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>

                <!-- Error Log -->
                <!-- Error Log -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('sys.error-log.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-red-lt">
                                <i class="ti ti-bug"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-0 text-reset h3 card-title fs-4">Error Log</h4>
                                <small class="text-muted">View and manage system errors</small>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>

                <!-- Testing -->
                <!-- Testing -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('sys.test.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-purple-lt">
                                <i class="ti ti-flask"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-0 text-reset h3 card-title fs-4">Test Features</h4>
                                <small class="text-muted">Test system functionality</small>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>

                <!-- Documentation -->
                <!-- Documentation -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('sys.documentation.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-gray-lt">
                                <i class="ti ti-file-text"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-0 text-reset h3 card-title fs-4">Documentation</h4>
                                <small class="text-muted">System documentation and guides</small>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="col-12 mb-4">
            <!-- User Role Distribution List -->
            <div class="mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="card-title">User Distribution by Role</h3>
                        <div class="badges-list">
                            @forelse($roleUserCounts as $roleData)
                                <span class="badge bg-azure-lt p-2">
                                    {{ Str::ucfirst($roleData['name']) }}
                                    <span class="badge badge-outline text-reset ms-2">{{ $roleData['count'] }}</span>
                                </span>
                            @empty
                                <div class="text-center w-100">
                                    <p class="text-muted py-4 mb-0">No roles found</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Combined Recent Activity Card -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Activity</h3>
                        </div>
                        <div class="card-body">
                            <!-- Summary stats ommitted for brevity in replacement search matches -->
                            @php
                                $todayCount = $recentLogs->where('created_at', '>=', now()->startOfDay())->count();
                                $yesterdayCount = \App\Models\Sys\Activity::whereBetween('created_at', [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()])->count();
                                $percentChange = $yesterdayCount > 0 ? round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100) : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="text-muted">Today's Activity</div>
                                <div class="d-flex align-items-baseline">
                                    <h2 class="mb-0 me-2">{{ $todayCount }}</h2>
                                    @if($percentChange != 0)
                                        <span class="text-{{ $percentChange > 0 ? 'green' : 'red' }} d-inline-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                @if($percentChange > 0)
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 15l6 -6l6 6" />
                                                @else
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" />
                                                @endif
                                            </svg>
                                            {{ abs($percentChange) }}%
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ abs($percentChange) }}% {{ $percentChange >= 0 ? 'more' : 'less' }} than yesterday</small>
                            </div>

                            <!-- Mini Sparkline Chart -->
                            <div id="activitySparkline" style="height: 6rem; margin-bottom: 1.5rem;"></div>
                        </div>

                        <!-- Activity List -->
                        <div class="list-group list-group-flush">
                            @forelse($recentLogs->take(5) as $log)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar avatar-sm" style="background-image: url('{{ $log->causer && $log->causer->avatar_url ? $log->causer->avatar_url : 'https://ui-avatars.com/api/?name=' . urlencode($log->causer ? $log->causer->name : 'System') . '&color=7F9CF5' }}')"></span>
                                        </div>
                                        <div class="col text-truncate">
                                            <span class="text-reset d-block">{{ Str::limit($log->description, 50) }}</span>
                                            <div class="d-block text-muted text-truncate mt-n1">
                                                {{ $log->causer ? $log->causer->name : 'System' }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <span class="text-muted">{{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted py-4">
                                    No recent activities
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Combined Recent Error Card -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Errors</h3>
                        </div>
                        <div class="card-body">
                            <!-- Summary Stats -->
                            @php
                                $recentErrors = \App\Models\Sys\ErrorLog::with('user')->latest()->limit(10)->get();
                                $todayErrorCount = $recentErrors->where('created_at', '>=', now()->startOfDay())->count();
                                $yesterdayErrorCount = \App\Models\Sys\ErrorLog::whereBetween('created_at', [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()])->count();
                                $errorPercentChange = $yesterdayErrorCount > 0 ? round((($todayErrorCount - $yesterdayErrorCount) / $yesterdayErrorCount) * 100) : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="text-muted">Today's Errors</div>
                                <div class="d-flex align-items-baseline">
                                    <h2 class="mb-0 me-2">{{ $todayErrorCount }}</h2>
                                    @if($errorPercentChange != 0)
                                        <span class="text-{{ $errorPercentChange > 0 ? 'red' : 'green' }} d-inline-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                @if($errorPercentChange > 0)
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 15l6 -6l6 6" />
                                                @else
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" />
                                                @endif
                                            </svg>
                                            {{ abs($errorPercentChange) }}%
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ abs($errorPercentChange) }}% {{ $errorPercentChange >= 0 ? 'more' : 'less' }} than yesterday</small>
                            </div>

                            <!-- Mini Sparkline Chart -->
                            <div id="errorSparkline" style="height: 6rem; margin-bottom: 1.5rem;"></div>
                        </div>

                        <!-- Error List -->
                        <div class="list-group list-group-flush">
                            @forelse($recentErrors->take(5) as $error)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar avatar-sm bg-red-lt">
                                                <i class="ti ti-bug"></i>
                                            </span>
                                        </div>
                                        <div class="col text-truncate">
                                            <span class="text-reset d-block">{{ Str::limit(strip_tags($error->message), 50) }}</span>
                                            <div class="d-block text-muted text-truncate mt-n1">
                                                {{ $error->exception_class ?? 'Unknown Error' }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <span class="text-muted">{{ \Carbon\Carbon::parse($error->created_at)->translatedFormat('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted py-4">
                                    No recent errors
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Parse chart data from JSON
            const activityChartData = {!! $activityChartData !!};
            const errorChartData = {!! $errorChartData !!};

            // Helper function to get Tabler colors from CSS variables
            function getTablerColor(colorName, defaultColor) {
                const root = getComputedStyle(document.documentElement);
                const colorVar = '--tblr-' + colorName;
                const colorValue = root.getPropertyValue(colorVar).trim();
                
                if (colorValue) {
                    return colorValue;
                }
                if (colorName === 'primary') return '#0054a6'; 
                if (colorName === 'danger') return '#d63939';
                
                return defaultColor || '#000000';
            }

            // Activity Sparkline Chart (Mini Area Chart)
            const activitySparkline = {
            chart: {
                height: '140', 
                type: 'area',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
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
                    opacityFrom: 0.6, 
                    opacityTo: 0.2, 
                    stops: [0, 90, 100]
                },
            },
            series: activityChartData.series,
            xaxis: {
                categories: activityChartData.labels,
                labels: {
                    show: true,
                    style: {
                        colors: '#64748b',
                        fontSize: '11px',
                        fontFamily: 'inherit',
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                 tooltip: {
                    enabled: false
                }
            },
            yaxis: {
                show: true,
                 labels: {
                    show: true,
                    style: {
                         colors: '#64748b',
                         fontSize: '11px',
                         fontFamily: 'inherit',
                    }
                }
            },
             grid: {
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                 padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 10
                }
            },
            colors: [getTablerColor("primary", "#0054a6")],
            tooltip: {
                theme: 'dark',
                 y: {
                     formatter: function (val) {
                         return val;
                     }
                 }
            }
        }

        // Error Sparkline Chart (Mini Area Chart) - Red theme
        const errorSparkline = {
            chart: {
                height: 140,
                type: 'area',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
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
                    opacityFrom: 0.6,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                },
            },
            series: errorChartData.series,
            xaxis: {
                categories: errorChartData.labels,
                labels: {
                    show: true,
                    style: {
                        colors: '#64748b', // Text Muted Color
                         fontSize: '11px',
                        fontFamily: 'inherit',
                    }
                },
                axisBorder: {
                     show: false
                },
                axisTicks: {
                    show: false
                },
                tooltip: {
                    enabled: false
                }
            },
             yaxis: {
                show: true,
                labels: {
                    show: true,
                    style: {
                         colors: '#64748b',
                         fontSize: '11px',
                         fontFamily: 'inherit',
                    }
                }
            },
            grid: {
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                         show: true
                    }
                },
                 yaxis: {
                    lines: {
                        show: true
                    }
                },
                 padding: {
                     top: 0,
                     right: 0,
                     bottom: 0,
                     left: 10
                }
            },
            colors: [getTablerColor("danger", "#d63939")],
            tooltip: {
                 theme: 'dark',
                 y: {
                     formatter: function (val) {
                         return val;
                     }
                 }
            }
        }


        if (window.loadApexCharts) {
            window.loadApexCharts().then((ApexCharts) => {
                const activitySparklineEl = document.querySelector('#activitySparkline');
                if (activitySparklineEl) {
                    const activitySparklineObj = new ApexCharts(activitySparklineEl, activitySparkline);
                    activitySparklineObj.render();
                }

                const errorSparklineEl = document.querySelector('#errorSparkline');
                if (errorSparklineEl) {
                    const errorSparklineObj = new ApexCharts(errorSparklineEl, errorSparkline);
                    errorSparklineObj.render();
                }
            });
        }

        // Update server time by fetching from server endpoint
        function updateServerTime() {
            axios.get('{{ route('sys.server-time') }}')
                .then(function(response) {
                    if (response.data.server_time) {
                        document.getElementById('serverTime').textContent = response.data.server_time;
                    }
                })
                .catch(function(error) {
                    console.error('Error fetching server time:', error);
                });
        }

        // Update the time every 30 seconds to maintain accuracy
        setInterval(updateServerTime, 30000);

        // Initial call to update time immediately
        setTimeout(updateServerTime, 1000);
        });
    </script>
@endpush
