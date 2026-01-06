<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    @include('partials.theme-loader')

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Nunito:wght@300;400;600;700&family=Poppins:wght@300;400;500;600;700&family=Public+Sans:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
</head>

@php
    // Default to 'basic' layout if not specified, utilizing .env settings
    $authLayout = $authLayout ?? env('AUTH_LAYOUT', 'basic');
    $authFormPosition = $authFormPosition ?? env('AUTH_FORM_POSITION', 'left');
@endphp

<body class="d-flex flex-column">
    @if($authLayout === 'basic')
        {{-- BASIC LAYOUT: Centered card --}}
        <main class="page page-center">
            <div class="container container-tight py-4">
                <div class="text-center mb-4">
                    <a href="." class="navbar-brand navbar-brand-autodark">
                        <img src="{{ asset('assets/img/digilab-crop.png') }}" width="200" height="36" alt="{{ config('app.name') }}">
                    </a>
                </div>
                @yield('content')
            </div>
        </main>

    @elseif($authLayout === 'cover')
        {{-- COVER LAYOUT: Split screen with background image --}}
        <main class="row g-0 flex-fill">
            <div class="col-12 col-lg-6 col-xl-5  d-flex flex-column justify-content-center" data-form-column>
                <div class="container container-tight my-5 px-lg-5">
                    <div class="text-center mb-4">
                        <a href="." class="navbar-brand navbar-brand-autodark">
                            <img src="{{ asset('assets/img/digilab-crop.png') }}" width="200" height="36" alt="{{ config('app.name') }}">
                        </a>
                    </div>
                    @yield('content')
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-7 d-none d-lg-block" data-media-column>
                {{-- Background cover image --}}
                <div class="bg-cover h-100 min-vh-100" style="background-image: url({{ asset('assets/img/auth/bg-cover.jpg') }})"></div>
            </div>
        </main>

    @else
        {{-- ILLUSTRATION LAYOUT: Content with decorative illustration --}}
        <main class="page page-center">
            <div class="container container-normal py-4">
                <div class="row align-items-center g-4">
                    <div class="col-lg" data-form-column>
                        <div class="container-tight">
                            <div class="text-center mb-4">
                                <a href="." class="navbar-brand navbar-brand-autodark">
                                    <img src="{{ asset('assets/img/digilab-crop.png') }}" width="200" height="36" alt="{{ config('app.name') }}">
                                </a>
                            </div>
                            @yield('content')
                        </div>
                    </div>
                    <div class="col-lg d-none d-lg-block" data-media-column>
                        <img src="{{ asset('assets/img/illustrations/auth-illustration.png') }}"  class="img d-block mx-auto" alt="Illustration">
                    </div>
                </div>
            </div>
        </main>
    @endif
    {{-- Theme Settings Component (Unified with sys) --}}
    @if(env('THEME_CUSTOMIZATION_ENABLED', true))
        <x-sys.theme-settings mode="auth" />
    @endif
</body>


@stack('scripts')
</html>
