<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('sys.dashboard') }}" class="app-brand-link">
            <img class="w-100" src="{{ asset('assets-global/img/digilab-crop.png') }}" />
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- System Dashboard -->
        <li class="menu-item ">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-left-arrow-alt"></i>
                <div data-i18n="System Dashboard">Back to Main Apps</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Summary</span>
        </li>
        <li class="menu-item {{ request()->routeIs('sys.dashboard') ? 'active' : '' }}">
            <a href="{{ route('sys.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-arch"></i>
                <div data-i18n="System Dashboard">Dashboard</div>
            </a>
        </li>
        <!-- Access Control -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Access Control</span>
        </li>
        <li class="menu-item {{ request()->routeIs('sys.roles.*') ? 'active' : '' }}">
            <a href="{{ route('sys.roles.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-lock-alt"></i>
                <div data-i18n="Roles">Roles</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('sys.permissions.*') ? 'active' : '' }}">
            <a href="{{ route('sys.permissions.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-key"></i>
                <div data-i18n="Permissions">Permissions</div>
            </a>
        </li>


        <!-- System Log -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">System Log</span>
        </li>
        <li class="menu-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <a href="{{ route('notifications.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bell"></i>
                <div data-i18n="Notifications">Notifications</div>
            </a>
        </li>
        <!-- Activity Log -->
        <li class="menu-item {{ request()->routeIs('activity-log.*') ? 'active' : '' }}">
            <a href="{{ route('activity-log.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-history"></i>
                <div data-i18n="Activity Log">Activity</div>
            </a>
        </li>
        <!-- Error Log -->
        <li class="menu-item {{ request()->routeIs('sys.error-log.*') ? 'active' : '' }}">
            <a href="{{ route('sys.error-log.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-error"></i>
                <div data-i18n="Error Log">Error</div>
            </a>
        </li>

        <!-- Others -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Others</span>
        </li>
        <!-- App Configuration -->
        <li class="menu-item {{ request()->routeIs('app-config') ? 'active' : '' }}">
            <a href="{{ route('app-config') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="App Config">App Configuration</div>
            </a>
        </li>
        <!-- System Guide -->
        <li class="menu-item {{ request()->routeIs('sys.test.*') ? 'active' : '' }}">
            <a href="{{ route('sys.test.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-wrench"></i>
                <div data-i18n="Documentation">Test Features</div>
            </a>
        </li>

        <!-- Backup Management -->
        <li class="menu-item {{ request()->routeIs('sys.backup.*') ? 'active' : '' }}">
            <a href="{{ route('sys.backup.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-archive"></i>
                <div data-i18n="Backup">Backup Management</div>
            </a>
        </li>

        <!-- System Guide -->
        <li class="menu-item {{ request()->routeIs('sys.documentation.*') ? 'active' : '' }}">
            <a href="{{ route('sys.documentation.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-code-alt"></i>
                <div data-i18n="Documentation">Development Guide</div>
            </a>
        </li>
    </ul>
</aside>
