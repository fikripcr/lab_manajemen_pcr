@php
    use App\Http\Controllers\Sys\ThemeTablerController;
    
    // Load theme controller
    $themeController = app(ThemeTablerController::class);
    $themeData = $themeController->getThemeData('tabler');
    $layoutData = $themeController->getLayoutData('tabler');

    $dark = ($themeData['theme'] ?? 'light') === 'dark';
@endphp

<!DOCTYPE html>
<html lang="en" {!! $themeController->getHtmlAttributes('tabler') !!}>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>@yield('title', config('app.name'))</title>

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
    </style>
    
    {{-- Theme Custom Styles --}}
    {!! $themeController->getStyleBlock('tabler') !!}
    
    @stack('styles')
    
    <style>
        body {
            background-color: var(--tblr-bg-surface-secondary);
        }
        .blank-layout-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
</head>

<body class="d-flex flex-column {{ $layoutData['bodyClass'] ?? '' }}">
    <div class="page page-center blank-layout-container">
        <div class="container container-tight py-4">
            @yield('content')
        </div>
    </div>

    {{-- Global Generic Modal --}}
    <div class="modal modal-blur fade" id="modalAction" tabindex="-1" aria-hidden="true" style="z-index: 99999;">
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
