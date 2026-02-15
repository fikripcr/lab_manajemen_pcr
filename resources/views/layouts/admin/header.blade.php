@php
    $condensed    = $condensed ?? false;
    $sticky       = $sticky ?? false;

    $dark         = $dark ?? false;
    $hideBrand    = $hideBrand ?? false;
    $hideMenu     = $hideMenu ?? false;
    $navbarClass = $navbarClass ?? '';


    // Apply sticky-top directly to the header if enabled
    $headerStickyClass = $sticky ? 'sticky-top' : '';

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
                <a href="{{ route('lab.dashboard') }}">
                    <img src="{{  asset('images/logo-apps.png') }}" width="120" height="22" alt="{{ config('app.name') }}" class="navbar-brand-image">
                </a>
            </div>
            @endunless

            {{-- Right Side Navigation --}}
            <div class="navbar-nav flex-row order-md-last">

                {{-- Quick Access / Apps Dropdown --}}
                <div class="nav-item dropdown d-none d-md-flex">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show apps">
                        <i class="ti ti-apps fs-2"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow dropdown-menu-card"{{ $dark ? ' data-bs-theme="light"' : '' }}>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Akses Cepat</h3>
                            </div>
                            <div class="card-body p-2">
                                <div class="row g-2">
                                    <div class="col-4">
                                        <a href="{{ route('lab.dashboard') }}" class="text-center d-block text-secondary p-2 rounded hover-bg-light">
                                            <i class="ti ti-layout-dashboard fs-2 d-block mb-1"></i>
                                            <div class="small">Dashboard</div>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('lab.labs.index') }}" class="text-center d-block text-secondary p-2 rounded hover-bg-light">
                                            <i class="ti ti-flask fs-2 d-block mb-1"></i>
                                            <div class="small">Lab</div>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('lab.inventaris.index') }}" class="text-center d-block text-secondary p-2 rounded hover-bg-light">
                                            <i class="ti ti-package fs-2 d-block mb-1"></i>
                                            <div class="small">Inventaris</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dark Mode Toggle --}}
                <div class="d-none d-md-flex">
                    <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <i class="ti ti-moon fs-2"></i>
                    </a>
                    <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <i class="ti ti-sun fs-2"></i>
                    </a>
                </div>

                {{-- Global Search --}}
                <div class="d-none d-md-flex">
                    <a href="javascript:void(0)" class="nav-link px-0" onclick="openGlobalSearchModal('{{ route('global-search') }}')" title="Global Search">
                        <i class="ti ti-search fs-2"></i>
                    </a>
                </div>

                {{-- Notifications --}}
                <div class="nav-item dropdown me-3 dropdown-notification">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                        <i class="ti ti-bell fs-2"></i>
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
                            <div class="card-footer text-center">
                                <x-tabler.button href="{{ route('notifications.index') }}" style="link" size="sm">View All</x-tabler.button>
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
                        <a href="{{ route('lab.users.show', auth()->user()->id) }}" class="dropdown-item">
                            <i class="ti ti-user me-2"></i> My Profile
                        </a>
                        
                        {{-- Switch Role logic --}}
                        @if(getAllUserRoles()->count() > 1)
                            <div class="dropdown-divider"></div>
                             @foreach(getAllUserRoles() as $role)
                                <a class="dropdown-item" href="javascript:void(0)" onclick="switchRole('{{ $role }}')">
                                    <i class="ti ti-arrows-exchange me-2"></i> Switch to {{ $role }}
                                </a>
                            @endforeach
                        @endif

                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                            <i class="ti ti-logout me-2"></i> Logout
                        </a>
                        <form id="logout-form-header" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </div>
            </div>

            {{-- Menu Container (Mobile) --}}
            {{-- In Condensed: Visible Always --}}
            {{-- In Vertical ($hideMenu): Visible Only on Mobile --}}
            @if($condensed || $hideMenu)
            <div class="collapse navbar-collapse" id="navbar-menu">
                @if($hideMenu)
                    {{-- Vertical Layout Mobile Menu (Hidden on Desktop) --}}
                    <div class="d-lg-none">
                        <x-tabler.menu-renderer type="navbar" />
                    </div>
                @else
                    {{-- Condensed Layout Menu --}}
                    <x-tabler.menu-renderer type="navbar" />
                @endif
            </div>
            @endif
        </div>
    </header>

    {{-- Secondary Menu Bar (non-condensed only, and NO Sidebar) --}}
    @if(!$condensed && empty($layoutData['layoutSidebar']))
    <header class="navbar-expand-md">
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="navbar"{{ $dark ? ' data-bs-theme="dark"' : '' }}>
                <div class="{{ $layoutData['navbarContainerClass'] ?? 'container-xl' }}">
                     <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lab.dashboard') }}" >
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-home fs-2"></i>
                                </span>
                                <span class="nav-link-title">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lab.labs.index') }}" >
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-flask fs-2"></i>
                                </span>
                                <span class="nav-link-title">Laboratorium</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lab.inventaris.index') }}" >
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="ti ti-package fs-2"></i>
                                </span>
                                <span class="nav-link-title">Inventaris Utama</span>
                            </a>
                        </li>
                     </ul>
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
        form.action = '{{ route("lab.users.switch-role") }}/' + role;

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
