@php
    use App\Http\Controllers\Sys\ThemeTablerController;
    
    // Load theme controller
    $themeController = app(ThemeTablerController::class);
    $themeData = $themeController->getThemeData('sys');
    
    // Layout data (keep for backward compatibility)
    $layoutData = $themeController->getLayoutData('sys');
@endphp

<!DOCTYPE html>
<html lang="en" {!! $themeController->getHtmlAttributes('sys') !!}>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>@yield('title') - {{ config('app.name') }}</title>

    <meta name="description" content="
    " />

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite([
        'resources/css/sys.css',
    ])

    {{-- Theme Styles (Server Side) - MUST BE AFTER CSS TO OVERRIDE DEFAULTS --}}
    {!! $themeController->getStyleBlock('sys') !!}

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Nunito:wght@300;400;600;700&family=Poppins:wght@300;400;500;600;700&family=Public+Sans:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    @stack('css')
</head>

<body {!! $themeController->getBodyAttributes('sys') !!}>
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
        

        <main class="page-wrapper">
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
        </main>
    </div>

    {{-- Theme Settings Component --}}
    @if(env('THEME_CUSTOMIZATION_ENABLED', true))
        <x-sys.theme-settings mode="sys" :themeData="$themeData" :layoutData="$layoutData" />
    @endif

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
