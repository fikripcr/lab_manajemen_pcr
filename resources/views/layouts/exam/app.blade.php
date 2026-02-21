@php
    use App\Http\Controllers\Sys\ThemeTablerController;
    $themeController = app(ThemeTablerController::class);
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
    @vite(['resources/css/tabler.css'])
    {!! $themeController->getStyleBlock('tabler') !!}
    @stack('css')
</head>
<body class="antialiased">
    @yield('exam-header')
    @yield('content')
    @vite(['resources/js/tabler.js'])
    @stack('scripts')
</body>
</html>
