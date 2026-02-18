@php
    use App\Http\Controllers\Sys\ThemeTablerController;
    
    // Load theme controller
    $themeController = app(ThemeTablerController::class);
    // Always use 'sys' config as we consolidated to it
    $themeData = $themeController->getThemeData('sys');
    $layoutData = $themeController->getLayoutData('sys');

    // Extract layout configuration variables
    $condensed = $layoutData['layoutCondensed'] ?? false;
    $sticky = $layoutData['layoutSticky'] ?? false;
    $dark = ($themeData['theme'] ?? 'light') === 'dark';
    $navbarClass = $layoutData['navbarClass'] ?? '';
    
    // Hide components based on configuration
    $hideBrand = $layoutData['hideBrand'] ?? false;
    $hideMenu = $layoutData['layoutSidebar'] ?? false; // If sidebar present, often hide menu in header
@endphp

<!DOCTYPE html>
<html lang="en" {!! $themeController->getHtmlAttributes('sys') !!}>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>@yield('title') - {{ config('app.name') }}</title>

    <meta name="description" content="@yield('meta_description', config('app.name'))" />

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- CSS Files --}}
    @yield('css')
    @vite(['resources/css/tabler.css'])
    
    @stack('styles')
</head>

<body class="{{ $layoutData['bodyClass'] ?? '' }}">
    <div class="page">
        {{-- Sidebar --}}
        @if(!empty($layoutData['layoutSidebar']))
             @include('layouts.tabler.sidebar', ['dark' => $dark])
        @endif

        {{-- Header --}}
        @include('layouts.tabler.header', [
            'condensed' => $condensed, 
            'sticky' => $sticky, 
            'dark' => $dark,
            'hideBrand' => $hideBrand,
            'hideMenu' => !empty($layoutData['layoutSidebar']), // If we have sidebar, standard menu is hidden/moved
            'navbarClass' => $navbarClass,
            'layoutData' => $layoutData
        ])

        {{-- Content Wrapper --}}
        <div class="page-wrapper">
             {{-- Page Header --}}
            @if(\Illuminate\Support\Facades\View::hasSection('pretitle') || \Illuminate\Support\Facades\View::hasSection('title') || \Illuminate\Support\Facades\View::hasSection('actions'))
            <div class="page-header d-print-none">
                <div class="{{ $layoutData['containerClass'] ?? 'container-xl' }}">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            @if(\Illuminate\Support\Facades\View::hasSection('pretitle'))
                            <div class="page-pretitle">
                                @yield('pretitle')
                            </div>
                            @endif
                            <h2 class="page-title">
                                @yield('title')
                            </h2>
                        </div>
                        {{-- Page Actions --}}
                        <div class="col-auto ms-auto d-print-none">
                            @yield('actions')
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            {{-- Main Body --}}
            <div class="page-body">
                <div class="{{ $layoutData['containerClass'] ?? 'container-xl' }}">
                    @yield('content')
                </div>
            </div>

            {{-- Footer --}}
            <footer class="footer footer-transparent d-print-none">
                <div class="{{ $layoutData['containerClass'] ?? 'container-xl' }}">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"><a href="#" class="link-secondary">Documentation</a></li>
                                <li class="list-inline-item"><a href="#" class="link-secondary">License</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; {{ date('Y') }}
                                    <a href="." class="link-secondary">{{ config('app.name') }}</a>.
                                    All rights reserved.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @vite([
        'resources/js/tabler.js'
    ])

    @stack('scripts')
    
    {{-- Global Alert/Toast/Modal handling if needed --}}
</body>
</html>
