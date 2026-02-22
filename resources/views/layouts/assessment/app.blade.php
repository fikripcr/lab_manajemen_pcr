@php
    use App\Http\Controllers\Sys\ThemeTablerController;
    $themeController = app(ThemeTablerController::class);
    $themeData = $themeController->getThemeData('tabler');
    $layoutData = $themeController->getLayoutData('tabler');
@endphp
<!DOCTYPE html>
<html lang="id" {!! $themeController->getHtmlAttributes('tabler') !!}>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    
    {{-- Critical CSS for Assessment (Prevents FOUC) --}}
    <style>
        .assessment-hero { background: linear-gradient(135deg, #206bc4 0%, #1e293b 100%); }
        .assessment-card { border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.12); }
    </style>
    
    @vite(['resources/css/tabler.css'])
    {!! $themeController->getStyleBlock('tabler') !!}
    @stack('css')
</head>
<body class="antialiased" data-theme-density="{{ $themeData['themeDensity'] ?? 'standard' }}">
    @yield('assessment-header')
    @yield('content')
    @vite(['resources/js/tabler.js'])
    @stack('scripts')
</body>
</html>
