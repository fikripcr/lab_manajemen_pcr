<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
</head>

@php
    // Default to 'basic' layout if not specified
    $authLayout = $authLayout ?? 'basic';
@endphp

@if($authLayout === 'basic')
    {{-- BASIC LAYOUT: Centered card --}}
    <body class="d-flex flex-column">
        <div class="page page-center">
            <div class="container container-tight py-4">
                <div class="text-center mb-4">
                    <a href="." class="navbar-brand navbar-brand-autodark">
                        <img src="{{ asset('assets/img/digilab-crop.png') }}" height="36" alt="{{ config('app.name') }}">
                    </a>
                </div>
                @yield('content')
            </div>
        </div>
    </body>

@elseif($authLayout === 'cover')
    {{-- COVER LAYOUT: Split screen with background image --}}
    <body class="d-flex flex-column bg-white">
        <div class="row g-0 flex-fill">
            <div class="col-12 col-lg-6 col-xl-4 border-top-wide border-primary d-flex flex-column justify-content-center">
                <div class="container container-tight my-5 px-lg-5">
                    <div class="text-center mb-4">
                        <a href="." class="navbar-brand navbar-brand-autodark">
                            <img src="{{ asset('assets/img/digilab-crop.png') }}" height="36" alt="{{ config('app.name') }}">
                        </a>
                    </div>
                    @yield('content')
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-8 d-none d-lg-block">
                {{-- Background cover image --}}
                <div class="bg-cover h-100 min-vh-100" style="background-image: url({{ asset('assets/img/auth/bg-cover.jpg') }})"></div>
            </div>
        </div>
    </body>

@elseif($authLayout === 'illustration')
    {{-- ILLUSTRATION LAYOUT: Side-by-side with SVG illustration --}}
    <body class="d-flex flex-column">
        <div class="page page-center">
            <div class="container container-normal py-4">
                <div class="row align-items-center g-4">
                    <div class="col-lg">
                        <div class="container-tight">
                            <div class="text-center mb-4">
                                <a href="." class="navbar-brand navbar-brand-autodark">
                                    <img src="{{ asset('assets/img/digilab-crop.png') }}" height="36" alt="{{ config('app.name') }}">
                                </a>
                            </div>
                            @yield('content')
                        </div>
                    </div>
                    <div class="col-lg d-none d-lg-block">
                        <img src="{{ asset('assets/img/illustrations/auth-illustration.svg') }}" height="400" class="d-block mx-auto" alt="Illustration">
                    </div>
                </div>
            </div>
        </div>
    </body>
@endif

</html>
