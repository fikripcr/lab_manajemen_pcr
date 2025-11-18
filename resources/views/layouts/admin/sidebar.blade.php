<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <img class="w-100" src="{{ asset('digilab-crop.png') }}" />
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master Data</span>
        </li>


        <li class="menu-item {{ request()->routeIs('labs.*') ? 'active' : '' }}">
            <a href="{{ route('labs.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-alt"></i>
                <div data-i18n="Documentation">Data Lab</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('inventaris.*') ? 'active' : '' }}">
            <a href="{{ route('inventaris.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div data-i18n="Documentation">Data Inventaris</div>
            </a>
        </li>

        <!-- Academic Data Menu -->
        <li class="menu-item {{ request()->routeIs('semesters.*') || request()->routeIs('mata-kuliah.*') || request()->routeIs('jadwal.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-calendar"></i>
                <div data-i18n="Perkuliahan">Perkuliahan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('semesters.*') ? 'active' : '' }}">
                    <a href="{{ route('semesters.index') }}" class="menu-link">
                        <div data-i18n="Data Semester">Data Semester</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('mata-kuliah.*') ? 'active' : '' }}">
                    <a href="{{ route('mata-kuliah.index') }}" class="menu-link">
                        <div data-i18n="Data Mata Kuliah">Data Mata Kuliah</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                    <a href="{{ route('jadwal.index') }}" class="menu-link">
                        <div data-i18n="Jadwal Perkuliahan">Jadwal Perkuliahan</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('pengumuman.*') || request()->routeIs('berita.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-bell"></i>
                <div data-i18n="Pengumuman">Info Publik</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('pengumuman.*') ? (request()->routeIs('pengumuman.create') || request()->routeIs('pengumuman.edit') ? '' : 'active') : '' }}">
                    <a href="{{ route('pengumuman.index') }}" class="menu-link">
                        <div>Pengumuman</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('berita.*') ? (request()->routeIs('berita.create') || request()->routeIs('berita.edit') ? '' : 'active') : '' }}">
                    <a href="{{ route('berita.index') }}" class="menu-link">
                        <div>Berita</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Software Requests Menu -->
        <li class="menu-item {{ request()->routeIs('admin.software-requests.*') ? 'active' : '' }}">
            <a href="{{ route('software-requests.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-extension"></i>
                <div data-i18n="Software Requests">Software Requests</div>
            </a>
        </li>


        <!-- System Management -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">System Management</span>
        </li>

        <!-- System Dashboard -->
        <li class="menu-item {{ request()->routeIs('sys.dashboard') ? 'active' : '' }}">
            <a href="{{ route('sys.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="System Dashboard">System Dashboard</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Documentation">User Management</div>
            </a>
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
                <div data-i18n="Activity Log">Activity Log</div>
            </a>
        </li>
        <!-- Roles & Permissions Menu -->
        <li class="menu-item {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-shield"></i>
                <div data-i18n="Roles & Permissions">Access Control</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <a href="{{ route('roles.index') }}" class="menu-link">
                        <div data-i18n="Roles">Roles</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                    <a href="{{ route('permissions.index') }}" class="menu-link">
                        <div data-i18n="Permissions">Permissions</div>
                    </a>
                </li>
            </ul>
        </li>



        <!-- App Configuration -->
        <li class="menu-item {{ request()->routeIs('app-config') ? 'active' : '' }}">
            <a href="{{ route('app-config') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="App Config">App Configuration</div>
            </a>
        </li>

        <!-- Backup Management -->
        <li class="menu-item {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
            <a href="{{ route('admin.backup.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-archive"></i>
                <div data-i18n="Backup">Backup Management</div>
            </a>
        </li>

        <!-- System Guide -->
        <li class="menu-item {{ request()->routeIs('admin.documentation') ? 'active' : '' }}">
            <a href="{{ route('admin.documentation') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-code-alt"></i>
                <div data-i18n="Documentation">Development Guide</div>
            </a>
        </li>
    </ul>
</aside>
