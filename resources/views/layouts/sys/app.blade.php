<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>@yield('title') - {{ config('app.name') }}</title>

    <meta name="description" content="System Management Dashboard" />

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ Vite::asset('resources/assets/sys/img/favicon/favicon.ico') }}" />

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite([
        'resources/css/sys.css',
    ])

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Nunito:wght@300;400;600;700&family=Poppins:wght@300;400;500;600;700&family=Public+Sans:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    @stack('css')
</head>

<body class="{{ $layoutData['bodyClass'] ?? '' }}" data-container-width="{{ $layoutData['containerWidth'] ?? 'standard' }}">
    {{-- Tabler Theme Script - Must load before body content to prevent flash --}}
    <script>
        (function() {
            const serverDefaults = {
                "theme": "{{ $themeData['theme'] ?? 'light' }}",
                "theme-base": "{{ $themeData['themeBase'] ?? 'gray' }}",
                "theme-font": "{{ $themeData['themeFont'] ?? 'sans-serif' }}",
                "theme-primary": "{{ $themeData['themePrimary'] ?? 'blue' }}",
                "theme-radius": "{{ $themeData['themeRadius'] ?? '1' }}",
                "theme-bg": "{{ $themeData['themeBg'] ?? '' }}",
                "theme-sidebar-bg": "{{ $themeData['themeSidebarBg'] ?? '' }}",
                "theme-sidebar-bg": "{{ $themeData['themeSidebarBg'] ?? '' }}",
                'theme-header-top-bg': "{{ $themeData['themeHeaderTopBg'] ?? '' }}",
                'theme-header-sticky': "{{ filter_var($themeData['themeHeaderSticky'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false' }}",
                'theme-card-style': "{{ $themeData['themeCardStyle'] ?? 'default' }}",
            }

            for (const key in serverDefaults) {
                const storedTheme = localStorage.getItem('tabler-' + key)
                const value = storedTheme || serverDefaults[key]
                
                // Special handling for background colors
                if (key === 'theme-bg' && value) {
                    document.documentElement.style.setProperty('--tblr-body-bg', value)
                } else if (key === 'theme-sidebar-bg' && value) {
                    document.documentElement.style.setProperty('--tblr-sidebar-bg', value)
                    document.documentElement.setAttribute('data-bs-has-sidebar-bg', '')
                } else if (key === 'theme-header-top-bg' && value) {
                    document.documentElement.style.setProperty('--tblr-header-top-bg', value)
                    document.documentElement.setAttribute('data-bs-has-header-top-bg', '')
                } else if (key === 'theme-boxed-bg' && value) {
                    document.documentElement.style.setProperty('--tblr-boxed-bg', value)
                } else if (key === 'theme-header-sticky') {
                     const isSticky = (value === 'true');
                     const header = document.querySelector('header.navbar');
                     if (header) {
                         if(isSticky) header.classList.add('sticky-top');
                         else header.classList.remove('sticky-top');
                     }
                } else if (key === 'theme-card-style') {
                     // Always apply if valid
                     if (value && value !== 'default') {
                         document.documentElement.setAttribute('data-bs-card-style', value)
                     }
                } else if (value !== 'light' && value !== 'gray' && value !== 'sans-serif' && value !== 'blue' && value !== '1') {
                    document.documentElement.setAttribute('data-bs-' + key, value)
                }
            }
        })();
    </script>
    
    <div class="page">
        {{-- SIDEBAR: only shown when layoutSidebar is true --}}
        @if($layoutData['layoutSidebar'] ?? false)
            @include('layouts.sys.sidebar', [
                'dark' => $layoutData['layoutSidebarDark'] ?? true
            ])
        @endif

        {{-- NAVBAR/HEADER: shown unless layoutHideTopbar is true --}}
        @unless($layoutData['layoutHideTopbar'] ?? false)
            @include('layouts.sys.header', [
                'condensed'    => $layoutData['layoutNavbarCondensed'] ?? false,
                'sticky'       => filter_var($themeData['themeHeaderSticky'] ?? ($layoutData['layoutNavbarSticky'] ?? false), FILTER_VALIDATE_BOOLEAN),
                'stickyWrapper'=> $layoutData['layoutNavbarStickyWrapper'] ?? false,
                'dark'         => $layoutData['layoutNavbarDark'] ?? false,
                'hideBrand'    => $layoutData['layoutNavbarHideBrand'] ?? false,
                'hideMenu'     => $layoutData['layoutSidebar'] ?? false, // Hide menu in navbar if sidebar exists
                'navbarClass'  => $layoutData['layoutNavbarClass'] ?? '',
            ])
        @endunless
        

        <div class="page-wrapper">
            {{-- Page Header: Optional, define @section('header') in pages --}}
            @hasSection('header')
            <div class="page-header d-print-none {{ $layoutData['pageHeaderClass'] ?? '' }}">
                <div class="container-xl">
                    @yield('header')
                </div>
            </div>
            @endif

            {{-- Page Body --}}
            <div class="page-body mb-0">
                {{-- Boxed layout uses container at .page level, others use container-xl here --}}
                <div class="{{ $layoutData['containerClass'] ?? 'container-xl' }}">
                    <x-sys.flash-message />
                    @yield('content')
                </div>
            </div>

            {{-- Footer --}}
            @include('layouts.sys.footer')
        </div>
    </div>

    {{-- Theme Settings Component --}}
    <x-sys.theme-settings />

    {{-- Global Search Modal Component --}}
    <x-sys.modal-global-search />

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
        'resources/js/sys.js'
    ])

    @stack('scripts')
</body>

</html>
