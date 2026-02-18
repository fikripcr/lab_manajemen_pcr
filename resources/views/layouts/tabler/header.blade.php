@php
    $condensed    = $condensed ?? false;
    $sticky       = $sticky ?? false;

    $dark         = $dark ?? false;
    $hideBrand    = $hideBrand ?? false;
    $hideMenu     = $hideMenu ?? false;
    $navbarClass = $navbarClass ?? '';


    // Apply sticky-top directly to the header if enabled
    $headerStickyClass = $sticky ? 'sticky-top' : '';

    $menuGroup = request()->is('sys*') ? 'sys' : 'admin';
@endphp

    {{-- Primary Header --}}
    <header class="navbar navbar-expand-lg{{ $dark ? ' navbar-dark text-white' : '' }}{{ $navbarClass ? ' ' . $navbarClass : '' }} {{ $headerStickyClass }} d-print-none"{!! $dark ? ' data-bs-theme="dark"' : '' !!}>
        <div class="{{ $layoutData['navbarContainerClass'] ?? 'container-xl' }}">
            {{-- Mobile Toggle --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
					aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
            
            {{-- Brand/Logo --}}
            @unless($hideBrand)
            <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href="{{ $menuGroup === 'sys' ? route('sys.dashboard') : route('lab.dashboard') }}">
                    <img src="{{  asset('images/logo-apps.png') }}" width="120" height="22" alt="{{ config('app.name') }}" class="navbar-brand-image">
                </a>
            </div>
            @endunless

            {{-- Right Side Navigation --}}
            <div class="navbar-nav flex-row order-md-last">

                {{-- Apps Dropdown --}}
                <div class="nav-item dropdown flex">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show apps">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M14 7l6 0" /><path d="M17 4l0 6" /></svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow dropdown-menu-card">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Quick Access</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="row g-0">
                                    <div class="col-4">
                                        <a href="{{ route('lab.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 19h16" /><path d="M4 15l4 -6l4 2l4 -5l4 4" /></svg>
                                            <h5>Lab</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('hr.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                                            <h5>HR</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('eoffice.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M12 12l0 .01" /><path d="M3 13a20 20 0 0 0 18 0" /></svg>
                                            <h5>E-Office</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('cbt.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19l18 0" /><path d="M5 6m0 1a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v8a1 1 0 0 1 -1 1h-12a1 1 0 0 1 -1 -1z" /></svg>
                                            <h5>CBT</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('Kegiatan.Kegiatans.index') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                                            <h5>Event</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('pemutu.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M13 17.5v4.5l-2 -1.5l-2 1.5v-4.5" /><path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -1 1.73" /><path d="M6 9l12 0" /><path d="M6 12l3 0" /><path d="M6 15l2 0" /></svg>
                                            <h5>Pemutu</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('pmb.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M16 19h6" /><path d="M19 16v6" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /></svg>
                                            <h5>PMB</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('survei.index') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>
                                            <h5>Survei</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('sys.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>
                                            <h5>System</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Global Search --}}
                <div class="d-none d-md-flex">
                    <a href="javascript:void(0)" class="nav-link px-0" onclick="openGlobalSearchModal('{{ route('global-search') }}')" title="Global Search">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                    </a>
                </div>

                {{-- Dark Mode Toggle --}}
                <div class="nav-item d-flex">
                    <a href="javascript:void(0);" onclick="toggleTheme('dark')" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
                    </a>
                    <a href="javascript:void(0);" onclick="toggleTheme('light')" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
                    </a>
                </div>

                {{-- Notifications --}}
                <div class="nav-item dropdown me-3 dropdown-notification">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                        <span class="badge bg-red notification-count" style="display: none;"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow dropdown-menu-card"{{ $dark ? ' data-bs-theme="light"' : '' }}>
                        <div class="card">
                            <div class="card-header d-flex">
                                <h3 class="card-title">Notifications</h3>
                                <div class="btn-close ms-auto" data-bs-dismiss="dropdown"></div>
                            </div>
                            <div class="list-group list-group-flush list-group-hoverable" id="notifications-list" style="max-height: 20rem; overflow-y: auto;">
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col text-truncate">
                                            <div class="text-body d-block">Loading notifications...</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <a href="#" class="btn w-100" id="markAllAsReadBtn">
                                            Mark all as read
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="{{ route('notifications.index') }}" class="btn btn-primary w-100">
                                            View all
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- User Menu --}}
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-sm" style="background-image: url('{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5' }}')"></span>
                        <div class="d-none d-xl-block ps-2">
                            <div>{{ auth()->user()->name }}</div>
                            <div class="mt-1 small text-secondary">{{ auth()->user()->roles->first()?->name ?? 'User' }}</div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow"{{ $dark ? ' data-bs-theme="light"' : '' }}>
                        <a href="{{ route('lab.users.show', auth()->user()->encrypted_id) }}" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            My Profile
                        </a>
                        <a href="#" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                            Settings
                        </a>

                        {{-- Role Switching --}}
                        @if(getAllUserRoles()->count() > 1)
                        <div class="dropdown-divider"></div>
                        <div class="dropend">
                            <a class="dropdown-item dropdown-toggle" href="#sidebar-authentication" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h.5" /><path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M20.2 20.2l1.8 1.8" /></svg>
                                Switch Role
                            </a>
                            <div class="dropdown-menu">
                                @foreach(getAllUserRoles() as $role)
                                <a class="dropdown-item{{ $role == getActiveRole() ? ' active' : '' }}" href="javascript:void(0)" onclick="switchRole('{{ $role }}')">
                                    {{ $role }}
                                    @if($role == getActiveRole())
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler ms-auto" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                    @endif
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Impersonate Switch Back --}}
                        @if(app('impersonate')->isImpersonating())
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('lab.users.switch-back') }}" class="dropdown-item text-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" /></svg>
                            Switch Back to Original Account
                        </a>
                        @endif

                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M9 12h12l-3 -3" /><path d="M18 15l3 -3" /></svg>
                            Logout
                        </a>
                        <form id="logout-form-header" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </div>
            </div>

            {{-- Menu Container --}}
            {{-- In Condensed: Visible Always --}}
            {{-- In Vertical ($hideMenu): Visible Only on Mobile --}}
            @if($condensed || $hideMenu)
            <div class="collapse navbar-collapse" id="navbar-menu">
                @if($hideMenu)
                    {{-- Vertical Layout Mobile Menu (Hidden on Desktop) --}}
                    <div class="d-lg-none">
                        <x-tabler.menu-renderer type="navbar" :group="$menuGroup" />
                    </div>
                @else
                    {{-- Condensed Layout Menu --}}
                    <x-tabler.menu-renderer type="navbar" :group="$menuGroup" />
                @endif
            </div>
            @endif
        </div>
    </header>



    {{-- Secondary Menu Bar (non-condensed only, and NO Sidebar) --}}
    {{-- If sidebar is present (e.g. combo layout), menu is likely there, so don't show secondary top bar --}}
    @if(!$condensed && empty($layoutData['layoutSidebar']))
    <header class="navbar-expand-md">
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="navbar"{{ isset($darkSecondary) && $darkSecondary ? ' data-bs-theme="dark"' : '' }}>
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                        <x-tabler.menu-renderer type="navbar" :group="$menuGroup" />
                    </div>
                </div>
            </div>
        </div>
    </header>
    @endif



@push('scripts')
<script>
    function switchRole(role) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("lab.users.switch-role", "") }}/' + role;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;

        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endpush
