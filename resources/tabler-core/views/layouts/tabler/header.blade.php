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
    <header class="navbar navbar-expand-md{{ $dark ? ' navbar-dark text-white' : '' }}{{ $navbarClass ? ' ' . $navbarClass : '' }} {{ $headerStickyClass }} d-print-none" id="header-main" {!! $dark ? ' data-bs-theme="dark"' : '' !!}>
        <div class="{{ $layoutData['navbarContainerClass'] ?? 'container-xl' }}">
            {{-- Mobile Toggle --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
					aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
            
            {{-- Brand/Logo --}}
            @unless($hideBrand)
            <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href="{{ route('dashboard') }}">
                    <img src="{{  asset('images/logo-apps.png') }}" width="120" height="22" alt="{{ config('app.name') }}" class="navbar-brand-image">
                </a>
            </div>
            @endunless

            {{-- Right Side Navigation --}}
            <div class="navbar-nav flex-row order-md-last">
                {{-- Siklus SPMI Year Selector --}}
                @if(isset($globalSiklus) && $globalSiklus['years']->isNotEmpty())
                <div class="nav-item dropdown d-none d-md-flex me-3">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Siklus SPMI">
                        <x-tabler.icon-svg name="calendar-event" class="icon me-2" />
                        <span class="d-none d-lg-inline">Siklus/Tahun: <strong>{{ $globalSiklus['tahun'] }}</strong></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <div class="dropdown-header">Pilih Siklus SPMI</div>
                        @foreach($globalSiklus['years'] as $yr)
                        <form action="{{ route('pemutu.set-siklus') }}" method="POST">
                            @csrf
                            <input type="hidden" name="siklus_tahun" value="{{ $yr }}">
                            <button type="submit" class="dropdown-item {{ $yr == $globalSiklus['tahun'] ? 'active' : '' }}">
                                Tahun {{ $yr }}
                            </button>
                        </form>
                        @endforeach
                    </div>
                </div>
                @endif
                {{-- Apps Dropdown --}}
                <div class="nav-item dropdown flex">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show apps">
                        <x-tabler.icon-svg name="apps" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow dropdown-menu-card">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Quick Access</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="row g-0">
                                    <div class="col-4">
                                        <a href="{{ route('lab.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-primary py-2 px-2 link-hoverable">
                                            <x-tabler.icon-svg name="chart-line" class="icon mb-2" />
                                            <h5>Lab</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('hr.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-primary py-2 px-2 link-hoverable">
                                            <x-tabler.icon-svg name="users" class="icon mb-2" />
                                            <h5>HR</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('eoffice.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-primary py-2 px-2 link-hoverable">
                                            <x-tabler.icon-svg name="e-office" class="icon mb-2" />
                                            <h5>E-Office</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('cbt.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-primary py-2 px-2 link-hoverable">
                                            <x-tabler.icon-svg name="device-laptop" class="icon mb-2" />
                                            <h5>CBT</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('Kegiatan.Kegiatans.index') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-primary py-2 px-2 link-hoverable">
                                            <x-tabler.icon-svg name="calendar-event" class="icon mb-2" />
                                            <h5>Kegiatan</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('pemutu.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-primary py-2 px-2 link-hoverable">
                                            <x-tabler.icon-svg name="certificate" class="icon mb-2" />
                                            <h5>Pemutu</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('pmb.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-primary py-2 px-2 link-hoverable">
                                            <x-tabler.icon-svg name="user-plus" class="icon mb-2" />
                                            <h5>PMB</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('survei.index') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-primary py-2 px-2 link-hoverable">
                                            <x-tabler.icon-svg name="file-text" class="icon mb-2" />
                                            <h5>Umpan Balik</h5>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('sys.dashboard') }}" class="d-flex flex-column align-items-center justify-content-center text-center text-primary py-2 px-2 link-hoverable">
                                            <x-tabler.icon-svg name="settings" class="icon mb-2" />
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
                        <x-tabler.icon-svg name="search" />
                    </a>
                </div>



                {{-- Notifications --}}
                <div class="nav-item dropdown me-3">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                        <x-tabler.icon-svg name="bell" />
                        @if(isset($unreadCount) && $unreadCount > 0)
                        <span class="badge bg-red badge-notification badge-pill notification-count">{{ $unreadCount }}</span>
                        @else
                        <span class="badge bg-red badge-notification badge-pill notification-count" style="display: none;"></span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow dropdown-menu-card shadow dropdown-notification dropdown-opaque"
                         {{ $dark ? ' data-bs-theme="light"' : '' }}>
                        <div class="card">
                            <div class="card-header d-flex">
                                <h3 class="card-title">Notifikasi</h3>
                                <div class="btn-close ms-auto" data-bs-dismiss="dropdown"></div>
                            </div>
                            <div class="list-group list-group-flush list-group-hoverable" id="notifications-list" style="max-height: 20rem; overflow-y: auto;">
                                @if(isset($topNotifications) && $topNotifications->count() > 0)
                                    @foreach($topNotifications as $notification)
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="status-dot {{ is_null($notification->read_at) ? 'status-dot-animated bg-red' : 'd-none' }} d-block"></span>
                                            </div>
                                            <div class="col text-truncate">
                                                <a href="{{ $notification->data['action_url'] ?? '#' }}" class="text-body d-block fw-medium">{{ $notification->data['title'] ?? 'Notifikasi' }}</a>
                                                <div class="d-block text-truncate mt-1 small">
                                                    {{ \Illuminate\Support\Str::limit($notification->data['body'] ?? '', 80) }}
                                                </div>
                                                <div class="d-block mt-1 small opacity-75">
                                                    {{ $notification->created_at ? $notification->created_at->diffForHumans() : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                <div class="list-group-item list-group-item-empty">
                                    <div class="text-center">
                                        <div class="text-muted mb-2">
                                            <x-tabler.icon-svg name="bell" class="icon icon-lg opacity-20" />
                                        </div>
                                        <p class="text-secondary fw-medium">Tidak ada notifikasi baru</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <form action="{{ route('sys.notifications.mark-all-as-read') }}" method="POST" id="markAllReadForm" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary w-100" 
                                                onclick="return confirm('Apakah Anda yakin ingin menandai semua notifikasi sebagai telah dibaca?');">
                                                Tandai sudah dibaca
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col">
                                        <a href="{{ route('sys.profile') }}#tabs-notification" class="btn btn-primary w-100 text-white">
                                            Lihat semua
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
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow dropdown-opaque"{{ $dark ? ' data-bs-theme="light"' : '' }}>
                        <a href="{{ route('sys.profile') }}" class="dropdown-item">
                            <x-tabler.icon-svg name="user" class="icon dropdown-item-icon" />
                            My Profile
                        </a>
                        <a href="#" class="dropdown-item">
                            <x-tabler.icon-svg name="settings" class="icon dropdown-item-icon" />
                            Settings
                        </a>

                        {{-- Theme Toggle --}}
                        <div class="dropdown-divider"></div>
                        {{-- Shown when light mode is active → click to switch to dark --}}
                        <a href="javascript:void(0)" class="dropdown-item hide-theme-dark" onclick="toggleTheme('dark')">
                            <x-tabler.icon-svg name="moon" class="icon dropdown-item-icon" />
                            Dark Mode
                        </a>
                        {{-- Shown when dark mode is active → click to switch to light --}}
                        <a href="javascript:void(0)" class="dropdown-item hide-theme-light" onclick="toggleTheme('light')">
                            <x-tabler.icon-svg name="sun" class="icon dropdown-item-icon" />
                            Light Mode
                        </a>


                        {{-- Role Switching --}}
                        @if(getAllUserRoles()->count() > 1)
                        <div class="dropdown-divider"></div>
                        <div class="dropend">
                            <a class="dropdown-item dropdown-toggle" href="#sidebar-authentication" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
                                <x-tabler.icon-svg name="user-search" class="icon dropdown-item-icon" />
                                Switch Role
                            </a>
                            <div class="dropdown-menu">
                                @foreach(getAllUserRoles() as $role)
                                <a class="dropdown-item{{ $role == getActiveRole() ? ' active' : '' }}" href="javascript:void(0)" onclick="switchRole('{{ $role }}')">
                                    {{ $role }}
                                    @if($role == getActiveRole())
                                    <x-tabler.icon-svg name="check" class="icon icon-tabler ms-auto" />
                                    @endif
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Impersonate Switch Back --}}
                        @if(app('impersonate')->isImpersonating())
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('sys.users.switch-back') }}" class="dropdown-item text-warning">
                            <x-tabler.icon-svg name="arrow-back-up" class="icon dropdown-item-icon" />
                            Switch Back to Original Account
                        </a>
                        @endif

                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                            <x-tabler.icon-svg name="logout" class="icon dropdown-item-icon" />
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
                        <x-tabler.menu-renderer type="navbar" group="admin" />
                    </div>
                @else
                    {{-- Condensed Layout Menu --}}
                    <x-tabler.menu-renderer type="navbar" group="admin" />
                @endif
            </div>
            @endif
        </div>
    </header>



    {{-- Secondary Menu Bar (non-condensed only, and NO Sidebar) --}}
    {{-- If sidebar is present (e.g. combo layout), menu is likely there, so don't show secondary top bar --}}
    @if(!$condensed && empty($layoutData['layoutSidebar']))
    <header class="navbar navbar-expand-md{{ isset($darkSecondary) && $darkSecondary ? ' navbar-dark text-white' : '' }} d-print-none" id="navbar-secondary">
        <div class="{{ $layoutData['navbarContainerClass'] ?? 'container-xl' }}">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                    <x-tabler.menu-renderer type="navbar" group="admin" />
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
        form.action = '{{ route("sys.users.switch-role", "") }}/' + role;

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
