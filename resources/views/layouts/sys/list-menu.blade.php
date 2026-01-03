{{--
    Navbar Menu Items
    Used in header for horizontal layouts
    Matches Tabler's navbar-menu.html structure
--}}
<ul class="navbar-nav">

    {{-- Dashboard --}}
    <li class="nav-item{{ request()->routeIs('sys.dashboard') ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('sys.dashboard') }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v8h-6z" /><path d="M4 16h6v4h-6z" /><path d="M14 12h6v8h-6z" /><path d="M14 4h6v4h-6z" /></svg>
            </span>
            <span class="nav-link-title">Dashboard</span>
        </a>
    </li>

    {{-- Access Control Dropdown --}}
    <li class="nav-item dropdown{{ request()->routeIs('sys.roles.*', 'sys.permissions.*') ? ' active' : '' }}">
        <a class="nav-link dropdown-toggle" href="#navbar-access" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg>
            </span>
            <span class="nav-link-title">Access Control</span>
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item{{ request()->routeIs('sys.roles.*') ? ' active' : '' }}" href="{{ route('sys.roles.index') }}">Roles</a>
            <a class="dropdown-item{{ request()->routeIs('sys.permissions.*') ? ' active' : '' }}" href="{{ route('sys.permissions.index') }}">Permissions</a>
        </div>
    </li>

    {{-- System Log Dropdown --}}
    <li class="nav-item dropdown{{ request()->routeIs('notifications.*', 'activity-log.*', 'sys.error-log.*') ? ' active' : '' }}">
        <a class="nav-link dropdown-toggle" href="#navbar-syslog" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4l2 2" /><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" /></svg>
            </span>
            <span class="nav-link-title">System Log</span>
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item{{ request()->routeIs('notifications.*') ? ' active' : '' }}" href="{{ route('notifications.index') }}">Notifications</a>
            <a class="dropdown-item{{ request()->routeIs('activity-log.*') ? ' active' : '' }}" href="{{ route('activity-log.index') }}">Activity</a>
            <a class="dropdown-item{{ request()->routeIs('sys.error-log.*') ? ' active' : '' }}" href="{{ route('sys.error-log.index') }}">Error Log</a>
        </div>
    </li>

    {{-- Others Dropdown --}}
    <li class="nav-item dropdown{{ request()->routeIs('app-config', 'sys.test.*', 'sys.backup.*', 'sys.documentation.*') ? ' active' : '' }}">
        <a class="nav-link dropdown-toggle" href="#navbar-others" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
            </span>
            <span class="nav-link-title">Others</span>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
            <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                    <a class="dropdown-item{{ request()->routeIs('app-config') ? ' active' : '' }}" href="{{ route('app-config') }}">
                        App Configuration
                    </a>
                    <a class="dropdown-item{{ request()->routeIs('sys.test.*') ? ' active' : '' }}" href="{{ route('sys.test.index') }}">
                        Test Features
                    </a>
                </div>
                <div class="dropdown-menu-column">
                    <a class="dropdown-item{{ request()->routeIs('sys.backup.*') ? ' active' : '' }}" href="{{ route('sys.backup.index') }}">
                        Backup Management
                    </a>
                    <a class="dropdown-item{{ request()->routeIs('sys.documentation.*') ? ' active' : '' }}" href="{{ route('sys.documentation.index') }}">
                        Development Guide
                    </a>
                </div>
            </div>
        </div>
    </li>
</ul>
