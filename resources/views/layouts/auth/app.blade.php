<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
</head>
<body class="d-flex flex-column">
    
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark">
                    <img src="{{ asset('assets/img/digilab-crop.png') }}" height="36" alt="">
                </a>
            </div>
            @yield('content')
        </div>
    </div>

</body>
</html>
