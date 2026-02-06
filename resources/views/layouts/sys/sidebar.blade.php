
<aside class="navbar navbar-vertical d-none d-lg-flex navbar-expand-lg">
    <div class="container-fluid">
        {{-- 1. NAVBAR TOGGLER (for mobile collapse) --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
                aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- 2. NAVBAR BRAND (logo) --}}
        <div class="navbar-brand navbar-brand-autodark">
            <a href="." class="navbar-brand navbar-brand-autodark">
                <img src="{{ asset('images/logo-apps.png') }}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
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
            <x-tabler.menu-renderer type="sidebar" group="sys" />
        </div>
    </div>
</aside>
