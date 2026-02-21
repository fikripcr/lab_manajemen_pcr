@php
    use App\Http\Controllers\Sys\ThemeTablerController;
    $themeController = app(ThemeTablerController::class);
    $themeData       = $themeController->getThemeData('tabler');
    $dark            = ($themeData['theme'] ?? 'light') === 'dark';
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

    <style>
        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        body {
            background: {{ $dark ? '#0f1117' : '#f0f2f7' }};
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .exam-page-wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }
        .exam-page-body {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .exam-page-body::-webkit-scrollbar { width: 6px; }
        .exam-page-body::-webkit-scrollbar-track { background: transparent; }
        .exam-page-body::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="exam-page-wrapper">
        {{-- Sticky Header Slot --}}
        @yield('exam-header')

        {{-- Main Scrollable Content --}}
        <div class="exam-page-body">
            @yield('content')
        </div>
    </div>

    @vite(['resources/js/tabler.js'])

    @stack('scripts')
</body>
</html>
