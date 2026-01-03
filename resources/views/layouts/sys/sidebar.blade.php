@php
    $dark = $dark ?? ($layoutData['layoutSidebarDark'] ?? true);

@endphp

<aside class="navbar navbar-vertical navbar-expand-lg"{!! $dark ? ' data-bs-theme="dark"' : '' !!}>
    <div class="container-fluid">
        {{-- 1. NAVBAR TOGGLER (for mobile collapse) --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" 
                aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- 2. NAVBAR BRAND (logo) --}}
        <div class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('sys.dashboard') }}">
                <img src="{{ asset('assets/img/digilab-crop.png') }}" alt="{{ config('app.name') }}" class="navbar-brand-image">
            </a>
        </div>

        {{-- 3. NAVBAR SIDE (user menu - visible only on mobile with d-lg-none) --}}
        <div class="navbar-nav flex-row d-lg-none">
            {{-- Notifications --}}
            <div class="nav-item dropdown me-2">
                <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                    <span class="badge bg-red badge-notification badge-blink notification-count">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow"{{ $dark ? ' data-bs-theme="light"' : '' }}>
                    <span class="dropdown-header">Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('notifications.index') }}" class="dropdown-item">View all</a>
                </div>
            </div>

            {{-- User Avatar Dropdown --}}
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url('{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5' }}')"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow"{{ $dark ? ' data-bs-theme="light"' : '' }}>
                    <a href="{{ route('users.show', auth()->user()->encrypted_id) }}" class="dropdown-item">Profile</a>
                    <a href="#" class="dropdown-item">Settings</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">Logout</a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
        </div>

        {{-- 4. COLLAPSE with NAVBAR MENU --}}
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                {{-- Back to Main Apps --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                        </span>
                        <span class="nav-link-title">Back to Main Apps</span>
                    </a>
                </li>

                {{-- SUMMARY --}}
                <li class="nav-item mt-3"><span class="nav-link disabled text-uppercase text-muted small">Summary</span></li>
                <li class="nav-item{{ request()->routeIs('sys.dashboard') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('sys.dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v8h-6z" /><path d="M4 16h6v4h-6z" /><path d="M14 12h6v8h-6z" /><path d="M14 4h6v4h-6z" /></svg>
                        </span>
                        <span class="nav-link-title">Dashboard</span>
                    </a>
                </li>

                {{-- ACCESS CONTROL --}}
                <li class="nav-item mt-3"><span class="nav-link disabled text-uppercase text-muted small">Access Control</span></li>
                <li class="nav-item{{ request()->routeIs('sys.roles.*') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('sys.roles.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg>
                        </span>
                        <span class="nav-link-title">Roles</span>
                    </a>
                </li>
                <li class="nav-item{{ request()->routeIs('sys.permissions.*') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('sys.permissions.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l6.558 -6.558l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0z" /></svg>
                        </span>
                        <span class="nav-link-title">Permissions</span>
                    </a>
                </li>

                {{-- SYSTEM LOG --}}
                <li class="nav-item mt-3"><span class="nav-link disabled text-uppercase text-muted small">System Log</span></li>
                <li class="nav-item{{ request()->routeIs('notifications.*') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('notifications.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                        </span>
                        <span class="nav-link-title">Notifications</span>
                    </a>
                </li>
                <li class="nav-item{{ request()->routeIs('activity-log.*') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('activity-log.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4l2 2" /><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" /></svg>
                        </span>
                        <span class="nav-link-title">Activity</span>
                    </a>
                </li>
                <li class="nav-item{{ request()->routeIs('sys.error-log.*') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('sys.error-log.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>
                        </span>
                        <span class="nav-link-title">Error Log</span>
                    </a>
                </li>

                {{-- OTHERS --}}
                <li class="nav-item mt-3"><span class="nav-link disabled text-uppercase text-muted small">Others</span></li>
                <li class="nav-item{{ request()->routeIs('app-config') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('app-config') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                        </span>
                        <span class="nav-link-title">App Configuration</span>
                    </a>
                </li>
                <li class="nav-item{{ request()->routeIs('sys.test.*') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('sys.test.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 3l0 12l3 3l3 -3l0 -12" /><path d="M9 15l6 0" /></svg>
                        </span>
                        <span class="nav-link-title">Test Features</span>
                    </a>
                </li>
                <li class="nav-item{{ request()->routeIs('sys.backup.*') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('sys.backup.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l6 0" /></svg>
                        </span>
                        <span class="nav-link-title">Backup Management</span>
                    </a>
                </li>
                <li class="nav-item{{ request()->routeIs('sys.documentation.*') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('sys.documentation.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><path d="M3 6l0 13" /><path d="M12 6l0 13" /><path d="M21 6l0 13" /></svg>
                        </span>
                        <span class="nav-link-title">Development Guide</span>
                    </a>
                </li>

                {{-- DUMMY MENUS FOR SCROLL TESTING --}}
                <li class="nav-item mt-3"><span class="nav-link disabled text-uppercase text-muted small">Scroll Test</span></li>
                 @foreach(range(1, 10) as $i)
                 <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                             <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /></svg>
                        </span>
                        <span class="nav-link-title">Dummy Item {{ $i }}</span>
                    </a>
                </li>
                 @endforeach
            </ul>
        </div>
    </div>
</aside>
