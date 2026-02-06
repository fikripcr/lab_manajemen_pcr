<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo-apps.png') }}" width="110" height="32" alt="{{ config('app.name') }}" class="navbar-brand-image">
            </a>
        </h1>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
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
                    <a class="nav-link" href="{{ route('labs.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" /><path d="M4 6v6c0 1.657 3.582 3 8 3s8 -1.343 8 -3v-6" /><path d="M4 12v6c0 1.657 3.582 3 8 3s8 -1.343 8 -3v-6" /></svg>
                        </span>
                        <span class="nav-link-title">Data Lab</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('inventaris.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('inventaris.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>
                        </span>
                        <span class="nav-link-title">Data Inventaris</span>
                    </a>
                </li>

                <li class="nav-item dropdown {{ request()->routeIs('semesters.*') || request()->routeIs('mata-kuliah.*') || request()->routeIs('jadwal.*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->routeIs('semesters.*') || request()->routeIs('mata-kuliah.*') || request()->routeIs('jadwal.*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
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

                <li class="nav-item {{ request()->routeIs('admin.software-requests.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('software-requests.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7h3a1 1 0 0 0 1 -1v-1a2 2 0 0 1 4 0v1a1 1 0 0 0 1 1h3a1 1 0 0 1 1 1v3a1 1 0 0 0 1 1h1a2 2 0 0 1 0 4h-1a1 1 0 0 0 -1 1v3a1 1 0 0 1 -1 1h-3a1 1 0 0 0 -1 1v1a2 2 0 0 1 -4 0v-1a1 1 0 0 0 -1 -1h-3a1 1 0 0 1 -1 -1v-3a1 1 0 0 0 -1 -1h-1a2 2 0 0 1 0 -4h1a1 1 0 0 0 1 -1v-3a1 1 0 0 1 1 -1" /></svg>
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
                    <a class="nav-link" href="{{ route('users.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                        </span>
                        <span class="nav-link-title">Users</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('sys.dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('sys.dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v8h-6z" /><path d="M4 16h6v4h-6z" /><path d="M14 12h6v8h-6z" /><path d="M14 4h6v4h-6z" /></svg>
                        </span>
                        <span class="nav-link-title">System Management</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
