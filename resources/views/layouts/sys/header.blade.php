@php
    $condensed    = $condensed ?? false;
    $sticky       = $sticky ?? false;
    $stickyWrapper= $stickyWrapper ?? false; 
    $dark         = $dark ?? false;
    $hideBrand    = $hideBrand ?? false;
    $hideMenu     = $hideMenu ?? false;
    $navbarClass = $navbarClass ?? '';

    // Logic for sticky header in overlap vs other layouts
    $layout = $layoutData['layout'] ?? '';
    $isOverlap = $layout === 'navbar-overlap';
    
    // If overlap, handle sticky on the HEADER itself, BUT remove the true 'navbar-overlap' class 
    // from the header so it doesn't become huge. We will render a background div instead.
    $wrapperStickyClass = ($sticky && !$isOverlap) ? 'sticky-top' : '';
    $headerStickyClass = ($sticky && $isOverlap) ? 'sticky-top' : '';
    
    // Remove 'navbar-overlap' from the actual navbar classes if we are manually handling it
    if ($isOverlap) {
        $navbarClass = str_replace('navbar-overlap', '', $navbarClass);
    }
@endphp

<div id="header-sticky-wrapper" class="{{ $wrapperStickyClass }} w-100">

    {{-- Primary Header --}}
    <header class="navbar navbar-expand-md{{ $dark ? ' navbar-dark text-white' : '' }}{{ $navbarClass ? ' ' . $navbarClass : '' }} {{ $headerStickyClass }} d-print-none"{!! $dark ? ' data-bs-theme="dark"' : '' !!}>
        <div class="{{ $layoutData['navbarContainerClass'] ?? 'container-xl' }}">
            {{-- Mobile Toggle --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
					aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

            {{-- Brand/Logo --}}
            @unless($hideBrand)
            <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href="{{ route('sys.dashboard') }}">
                    <img src="{{ asset('assets/img/digilab-crop.png') }}" alt="{{ config('app.name') }}" class="navbar-brand-image">
                </a>
            </div>
            @endunless

            {{-- Right Side Navigation --}}
            <div class="navbar-nav flex-row order-md-last">
                {{-- Theme Toggle --}}
                <div class="d-none d-md-flex">
                    <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
                    </a>
                    <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
                    </a>
                </div>

                {{-- Apps Dropdown --}}
                <div class="nav-item dropdown d-none d-md-flex">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show apps">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M14 7l6 0" /><path d="M17 4l0 6" /></svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow dropdown-menu-card">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Quick Access</h3>
                            </div>
                            <div class="card-body p-2">
                                <div class="row g-2">
                                    <div class="col-4">
                                        <a href="{{ route('dashboard') }}" class="text-center d-block text-secondary p-2 rounded hover-bg-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v8h-6z" /><path d="M4 16h6v4h-6z" /><path d="M14 12h6v8h-6z" /><path d="M14 4h6v4h-6z" /></svg>
                                            <div class="small">Main Apps</div>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('app-config') }}" class="text-center d-block text-secondary p-2 rounded hover-bg-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
                                            <div class="small">App Config</div>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('sys.documentation.index') }}" class="text-center d-block text-secondary p-2 rounded hover-bg-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" /><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8 11v-4a4 4 0 1 1 8 0v4" /></svg>
                                            <div class="small">Development Guide</div>
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

                {{-- Notifications --}}
                <div class="nav-item dropdown d-none d-md-flex me-3 dropdown-notification">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                        <span class="badge bg-red notification-count"></span>
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
                        <a href="{{ route('users.show', auth()->user()->encrypted_id) }}" class="dropdown-item">
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
                        <a href="{{ route('users.switch-back') }}" class="dropdown-item text-warning">
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

            {{-- Condensed: menu inside same collapse --}}
            @if($condensed)
            <div class="collapse navbar-collapse" id="navbar-menu">
                @unless($hideMenu)
                    @include('layouts.sys.list-menu')
                @endunless
            </div>
            @endif
        </div>
    </header>

    {{-- Overlap Background Decoration --}}
    @if($isOverlap)
    <div class="navbar-overlap" style="position: absolute; top: 0; left: 0; width: 100%; height: 6.25rem; z-index: -1; background-color: var(--tblr-header-top-bg, var(--tblr-bg-surface-dark));"></div>
    @endif

    {{-- Secondary Menu Bar (non-condensed only, and NO Sidebar) --}}
    {{-- If sidebar is present (e.g. combo layout), menu is likely there, so don't show secondary top bar --}}
    @if(!$condensed && empty($layoutData['layoutSidebar']))
    <header class="navbar-expand-md">
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="navbar"{{ isset($darkSecondary) && $darkSecondary ? ' data-bs-theme="dark"' : '' }}>
                <div class="{{ $layoutData['navbarContainerClass'] ?? 'container-xl' }}">
                    @unless($hideMenu)
                        @include('layouts.sys.list-menu')
                    @endunless
                </div>
            </div>
        </div>
    </header>
    @endif

</div>



@if($stickyWrapper)
</div>
@endif

@push('scripts')
<script>
    function switchRole(role) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("users.switch-role", "") }}/' + role;

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
