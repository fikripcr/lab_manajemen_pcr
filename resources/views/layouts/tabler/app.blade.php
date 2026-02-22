@php
    use App\Http\Controllers\Sys\ThemeTablerController;
    
    // Load theme controller
    $themeController = app(ThemeTablerController::class);
    // Use 'tabler' as the standardized mode
    $themeData = $themeController->getThemeData('tabler');
    $layoutData = $themeController->getLayoutData('tabler');

    // Extract layout configuration variables
    $condensed = $layoutData['layoutNavbarCondensed'] ?? false;
    $sticky = $layoutData['layoutNavbarSticky'] ?? false;
    $dark = ($themeData['theme'] ?? 'light') === 'dark';
    $navbarClass = $layoutData['layoutNavbarClass'] ?? '';
    
    // Hide components based on configuration
    $hideBrand = $layoutData['hideBrand'] ?? false;
    $hideMenu = $layoutData['layoutSidebar'] ?? false; // If sidebar present, often hide menu in header
@endphp

<!DOCTYPE html>
<html lang="en" {!! $themeController->getHtmlAttributes('tabler') !!}>

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
    
    {{-- Custom Modal Z-Index Fix --}}
    <style>
        /* Ensure modal and backdrop have proper z-index */
        .modal.modal-blur {
            z-index: 99999 !important;
        }
        .modal-backdrop {
            z-index: 99998 !important;
        }
        /* Ensure modal dialog is above backdrop */
        .modal-dialog {
            z-index: 100000 !important;
            position: relative;
        }
        /* Ensure TinyMCE and SweetAlert appear above the modal */
        .tox-tinymce-aux, .tox-tinymce-aux * {
            z-index: 100002 !important;
        }
        .swal2-container {
            z-index: 100002 !important;
        }
    </style>
    
    {{-- Theme Custom Styles --}}
    {{-- {!! $themeController->getFontLink('tabler') !!} --}}
    {!! $themeController->getStyleBlock('tabler') !!}
    
    @stack('styles')
</head>

<body class="{{ $layoutData['bodyClass'] ?? '' }}">
    <div class="page">
        {{-- Sidebar --}}
        @if(!empty($layoutData['layoutSidebar']))
             @include('layouts.tabler.sidebar', ['dark' => $dark])
        @endif

        {{-- Header --}}
        {{-- <div id="header-sticky-wrapper" class="{{ $sticky ? 'sticky-top' : '' }}"> --}}
            @include('layouts.tabler.header', [
                'condensed' => $condensed, 
                'sticky' => $sticky, 
                'dark' => $dark,
                'hideBrand' => $hideBrand,
                'hideMenu' => !empty($layoutData['layoutSidebar']), // If we have sidebar, standard menu is hidden/moved
                'navbarClass' => $navbarClass,
                'layoutData' => $layoutData
            ])
        {{-- </div> --}}

        {{-- Content Wrapper --}}
        <div class="page-wrapper">
            {{-- Page Header --}}
            @if(\Illuminate\Support\Facades\View::hasSection('header'))
                <div class="page-header d-print-none">
                    <div class="{{ $layoutData['containerClass'] ?? 'container-xl' }}">
                        @yield('header')
                    </div>
                </div>
            @elseif(\Illuminate\Support\Facades\View::hasSection('pretitle') || \Illuminate\Support\Facades\View::hasSection('title') || \Illuminate\Support\Facades\View::hasSection('actions'))
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
                    <x-tabler.flash-message />
                    
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

     {{-- Theme Settings Component --}}
    @if(env('THEME_CUSTOMIZATION_ENABLED', true))
        <x-tabler.theme-settings mode="tabler" :themeData="$themeData" :layoutData="$layoutData" />
    @endif

    {{-- Global Search Modal Component --}}
    <x-tabler.modal-global-search />

    {{-- Global Generic Modal --}}
    <div class="modal modal-blur fade" id="modalAction" tabindex="-1" aria-hidden="true" data-bs-focus="false" style="z-index: 99999;">
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
        'resources/js/tabler.js'
    ])

    @stack('scripts')
    
</body>
</html>
