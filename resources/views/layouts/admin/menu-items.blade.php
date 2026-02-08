<li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('lab.dashboard') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <i class="ti ti-layout-dashboard fs-2"></i>
        </span>
        <span class="nav-link-title">Dashboard</span>
    </a>
</li>

<li class="nav-item">
    <div class="nav-link disabled text-uppercase text-muted fs-5 fw-bold mt-2">
        Master Data
    </div>
</li>

<li class="nav-item {{ request()->routeIs('labs.*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('lab.labs.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <i class="ti ti-flask fs-2"></i>
        </span>
        <span class="nav-link-title">Data Lab</span>
    </a>
</li>

<li class="nav-item {{ request()->routeIs('inventaris.*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('lab.inventaris.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <i class="ti ti-package fs-2"></i>
        </span>
        <span class="nav-link-title">Data Inventaris</span>
    </a>
</li>

<li class="nav-item dropdown {{ request()->routeIs('semesters.*') || request()->routeIs('mata-kuliah.*') || request()->routeIs('jadwal.*') ? 'active' : '' }}">
    <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->routeIs('semesters.*') || request()->routeIs('mata-kuliah.*') || request()->routeIs('jadwal.*') ? 'true' : 'false' }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <i class="ti ti-book-2 fs-2"></i>
        </span>
        <span class="nav-link-title">Perkuliahan</span>
    </a>
    <div class="dropdown-menu {{ request()->routeIs('semesters.*') || request()->routeIs('mata-kuliah.*') || request()->routeIs('jadwal.*') ? 'show' : '' }}">
        <a class="dropdown-item {{ request()->routeIs('semesters.*') ? 'active' : '' }}" href="{{ route('semesters.index') }}">
            Data Semester
        </a>
        <a class="dropdown-item {{ request()->routeIs('mata-kuliah.*') ? 'active' : '' }}" href="{{ route('mata-kuliah.index') }}">
            Data Mata Kuliah
        </a>
        <a class="dropdown-item {{ request()->routeIs('jadwal.*') ? 'active' : '' }}" href="{{ route('jadwal.index') }}">
            Jadwal Perkuliahan
        </a>
    </div>
</li>

<li class="nav-item dropdown {{ request()->routeIs('pengumuman.*') || request()->routeIs('berita.*') ? 'active' : '' }}">
    <a class="nav-link dropdown-toggle" href="#navbar-info" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->routeIs('pengumuman.*') || request()->routeIs('berita.*') ? 'true' : 'false' }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <i class="ti ti-info-circle fs-2"></i>
        </span>
        <span class="nav-link-title">Info Publik</span>
    </a>
    <div class="dropdown-menu {{ request()->routeIs('pengumuman.*') || request()->routeIs('berita.*') ? 'show' : '' }}">
        <a class="dropdown-item {{ request()->routeIs('pengumuman.*') ? 'active' : '' }}" href="{{ route('pengumuman.index') }}">
            Pengumuman
        </a>
        <a class="dropdown-item {{ request()->routeIs('berita.*') ? 'active' : '' }}" href="{{ route('berita.index') }}">
            Berita
        </a>
    </div>
</li>

<li class="nav-item {{ request()->routeIs('software-requests.*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('software-requests.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <i class="ti ti-device-laptop fs-2"></i>
        </span>
        <span class="nav-link-title">Software Requests</span>
    </a>
</li>

<li class="nav-item">
    <div class="nav-link disabled text-uppercase text-muted fs-5 fw-bold mt-2">
        Others
    </div>
</li>

<li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('lab.users.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <i class="ti ti-users fs-2"></i>
        </span>
        <span class="nav-link-title">Users</span>
    </a>
</li>

<li class="nav-item {{ request()->routeIs('sys.dashboard') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('sys.dashboard') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <i class="ti ti-settings fs-2"></i>
        </span>
        <span class="nav-link-title">System Management</span>
    </a>
</li>
