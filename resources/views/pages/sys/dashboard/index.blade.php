@extends('layouts.sys.app')

@section('title', 'System Dashboard')

@section('content')
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
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-muted mb-1">Total Users</div>
                            <div class="h2 mb-0">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-primary-lt rounded-circle p-3" style="width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /></svg>
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-danger-lt" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" /><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8 11v-4a4 4 0 1 1 8 0v4" /></svg>
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-cyan" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4l2 2" /><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" /></svg>
                            </div>
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
                <!-- Activity Log -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('activity-log.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-primary-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4l2 2" /><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" /></svg>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-reset">Activity Log</h6>
                                <small class="text-muted">Track all system activities and user actions</small>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                        </div>
                    </a>
                </div>

                <!-- Roles -->
                <!-- Roles -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('sys.roles.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-green-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /></svg>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-reset">User Roles</h6>
                                <small class="text-muted">Manage user roles and permissions</small>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                        </div>
                    </a>
                </div>

                <!-- Permissions -->
                <!-- Permissions -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('sys.permissions.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-yellow-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" /><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8 11v-4a4 4 0 1 1 8 0v4" /></svg>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-reset">Permissions</h6>
                                <small class="text-muted">Manage system permissions</small>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                        </div>
                    </a>
                </div>

                <!-- App Configuration -->
                <!-- App Configuration -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('app-config') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-orange-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-reset">App Configuration</h6>
                                <small class="text-muted">Configure application settings</small>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                        </div>
                    </a>
                </div>

                <!-- Backup Management -->
                <!-- Backup Management -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('sys.backup.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-cyan-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" /><path d="M4 6v6c0 1.657 3.582 3 8 3s8 -1.343 8 -3v-6" /><path d="M4 12v6c0 1.657 3.582 3 8 3s8 -1.343 8 -3v-6" /></svg>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-reset">Backup Management</h6>
                                <small class="text-muted">Create and manage system backups</small>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                        </div>
                    </a>
                </div>

                <!-- Notifications -->
                <!-- Notifications -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('notifications.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-red-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-reset">Notifications</h6>
                                <small class="text-muted">Manage system notifications</small>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                        </div>
                    </a>
                </div>

                <!-- Error Log -->
                <!-- Error Log -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('sys.error-log.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-red-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-reset">Error Log</h6>
                                <small class="text-muted">View and manage system errors</small>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                        </div>
                    </a>
                </div>

                <!-- Testing -->
                <!-- Testing -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('sys.test.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-purple-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-reset">Test Features</h6>
                                <small class="text-muted">Test system functionality</small>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                        </div>
                    </a>
                </div>

                <!-- Documentation -->
                <!-- Documentation -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('sys.documentation.index') }}" class="card card-link text-decoration-none">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="avatar me-3 bg-gray-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><path d="M3 6l0 13" /><path d="M12 6l0 13" /><path d="M21 6l0 13" /></svg>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-reset">Documentation</h6>
                                <small class="text-muted">System documentation and guides</small>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
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
                            <!-- Summary Stats -->
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
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7l10 10m0 -10v10h-10"/>
                                                @else
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17l10 -10m0 10v-10h-10"/>
                                                @endif
                                            </svg>
                                            {{ abs($percentChange) }}%
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ abs($percentChange) }}% {{ $percentChange >= 0 ? 'more' : 'less' }} than yesterday</small>
                            </div>

                            <!-- Mini Sparkline Chart -->
                            <div id="activitySparkline" style="height: 100px; margin-bottom: 1.5rem;"></div>
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
                                            <span class="text-muted">{{ $log->created_at->format('d M Y') }}</span>
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
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7l10 10m0 -10v10h-10"/>
                                                @else
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17l10 -10m0 10v-10h-10"/>
                                                @endif
                                            </svg>
                                            {{ abs($errorPercentChange) }}%
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ abs($errorPercentChange) }}% {{ $errorPercentChange >= 0 ? 'more' : 'less' }} than yesterday</small>
                            </div>

                            <!-- Mini Sparkline Chart -->
                            <div id="errorSparkline" style="height: 100px; margin-bottom: 1.5rem;"></div>
                        </div>

                        <!-- Error List -->
                        <div class="list-group list-group-flush">
                            @forelse($recentErrors->take(5) as $error)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar avatar-sm bg-red-lt">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                                            </span>
                                        </div>
                                        <div class="col text-truncate">
                                            <span class="text-reset d-block">{{ Str::limit(strip_tags($error->message), 50) }}</span>
                                            <div class="d-block text-muted text-truncate mt-n1">
                                                {{ $error->exception_class ?? 'Unknown Error' }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <span class="text-muted">{{ $error->created_at->format('d M Y') }}</span>
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
    <!-- Apex Charts JS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        // Parse chart data from JSON
        const activityChartData = {!! $activityChartData !!};
        const errorChartData = {!! $errorChartData !!};

        // Activity Sparkline Chart (Mini Area Chart)
        const activitySparkline = {
            chart: {
                height: 100,
                type: 'area',
                sparkline: {
                    enabled: true
                },
                toolbar: {
                    show: false
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
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [50, 100, 100, 100]
                },
            },
            series: activityChartData.series,
            colors: ['#0d6efd'],
            tooltip: {
                fixed: {
                    enabled: false
                },
                x: {
                    show: false
                },
                y: {
                    title: {
                        formatter: function (seriesName) {
                            return ''
                        }
                    }
                },
                marker: {
                    show: false
                }
            }
        }

        const activitySparklineEl = document.querySelector('#activitySparkline');
        if (activitySparklineEl) {
            const activitySparklineObj = new ApexCharts(activitySparklineEl, activitySparkline);
            activitySparklineObj.render();
        }

        // Error Sparkline Chart (Mini Area Chart) - Red theme
        const errorSparkline = {
            chart: {
                height: 100,
                type: 'area',
                sparkline: {
                    enabled: true
                },
                toolbar: {
                    show: false
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
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [50, 100, 100, 100]
                },
            },
            series: errorChartData.series,
            colors: ['#dc3545'],
            tooltip: {
                fixed: {
                    enabled: false
                },
                x: {
                    show: false
                },
                y: {
                    title: {
                        formatter: function (seriesName) {
                            return ''
                        }
                    }
                },
                marker: {
                    show: false
                }
            }
        }

        const errorSparklineEl = document.querySelector('#errorSparkline');
        if (errorSparklineEl) {
            const errorSparklineObj = new ApexCharts(errorSparklineEl, errorSparkline);
            errorSparklineObj.render();
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
        setTimeout(updateServerTime, 1000); // Wait 1 second to allow initial render
    </script>
@endpush
