@php
    use App\Http\Controllers\Sys\ThemeTablerController;

    // Load theme controller for ADMIN mode but using SYS config to share settings
    $themeController = app(ThemeTablerController::class);
    // CRITICAL: We load 'sys' config so Admin looks exactly like Sys
    $themeData = $themeController->getThemeData('sys');
    
    // Layout data
    $layoutData = $themeController->getLayoutData('sys');
@endphp

<!DOCTYPE html>
<html lang="en" {!! $themeController->getHtmlAttributes('sys') !!}>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>@yield('title') - {{ config('app.name') }}</title>

    <meta name="description" content="" />

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite([
        'resources/css/admin.css',
    ])

    {{-- Theme Styles (Server Side) - USES SYS CONFIG --}}
    {!! $themeController->getStyleBlock('sys') !!}

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Nunito:wght@300;400;600;700&family=Poppins:wght@300;400;500;600;700&family=Public+Sans:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    @stack('css')
</head>

<body {!! $themeController->getBodyAttributes('sys') !!}>
    <div class="page">
        {{-- SIDEBAR --}}
        @if($layoutData['layoutSidebar'] ?? false)
            @include('layouts.admin.sidebar', [
                'dark' => $layoutData['layoutSidebarDark'] ?? true
            ])
        @endif

        {{-- HEADER --}}
        @unless($layoutData['layoutHideTopbar'] ?? false)
            @include('layouts.admin.header', [
                'condensed'    => $layoutData['layoutNavbarCondensed'] ?? false,
                'sticky'       => filter_var($themeData['themeHeaderSticky'] ?? ($layoutData['layoutNavbarSticky'] ?? false), FILTER_VALIDATE_BOOLEAN),
                'stickyWrapper'=> $layoutData['layoutNavbarStickyWrapper'] ?? false,
                'dark'         => $layoutData['layoutNavbarDark'] ?? false,
                'hideBrand'    => $layoutData['layoutNavbarHideBrand'] ?? false,
                'hideMenu'     => $layoutData['layoutSidebar'] ?? false, 
                'navbarClass'  => $layoutData['layoutNavbarClass'] ?? '',
            ])
        @endunless
        
        <main class="page-wrapper">
            {{-- Page Header --}}
            @hasSection('header')
            <div class="page-header d-print-none {{ $layoutData['pageHeaderClass'] ?? '' }}">
                <div class="container-xl">
                    @yield('header')
                </div>
            </div>
            @endif

            {{-- Page Body --}}
            <div class="page-body mb-0">
                <div class="{{ $layoutData['containerClass'] ?? 'container-xl' }}">
                    {{-- Validating Flash Messages Component --}}
                    @if(session('success') || session('error') || session('warning'))
                         <div class="alert alert-important alert-dismissible {{ session('success') ? 'alert-success' : (session('error') ? 'alert-danger' : 'alert-warning') }}" role="alert">
                            <div class="d-flex">
                                <div>
                                    @if(session('success'))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                    @elseif(session('error'))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.84 2.75z" /></svg>
                                    @endif
                                </div>
                                <div>
                                    {{ session('success') ?? session('error') ?? session('warning') }}
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>

            {{-- Footer --}}
            @include('layouts.admin.footer')
        </main>
    </div>

    @if(env('THEME_CUSTOMIZATION_ENABLED', true))
        <x-tabler.theme-settings mode="sys" :themeData="$themeData" :layoutData="$layoutData" />
    @endif

    {{-- Global Search Modal Component --}}
    <x-tabler.modal-global-search /> 

    {{-- Global Generic Modal --}}
    <div class="modal modal-blur fade" id="modalAction" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="modalContent">
                <div class="modal-header">
                    <h5 class="modal-title">Loading...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-5">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite([
        'resources/js/admin.js'
    ])

    <script>
        window.appRoutes = {
            notificationsUnreadCount: '{{ route('notifications.unread-count') }}',
            notificationsIndex: '{{ route('notifications.index') }}',
            notificationsDropdownData: '{{ route('notifications.dropdown-data') }}',
            notificationsMarkAllAsRead: '{{ route('notifications.mark-all-as-read') }}',
            globalSearch: '{{ route('global-search') }}',
        };
    </script>

    @stack('scripts')
</body>
</html>
