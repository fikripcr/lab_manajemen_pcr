@php
    use App\Http\Controllers\Sys\ThemeTablerController;
    
    // Load theme controller
    $themeController = app(ThemeTablerController::class);
    // Use 'tabler' as the standardized mode
    $themeData = $themeController->getThemeData('tabler');
    $layoutData = $themeController->getLayoutData('tabler');

    $dark = ($themeData['theme'] ?? 'light') === 'dark';
@endphp

<!DOCTYPE html>
<html lang="en" {!! $themeController->getHtmlAttributes('tabler') !!}>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>@yield('error-code', '404') - @yield('title', 'Error') | {{ config('app.name') }}</title>
    
    <meta name="description" content="@yield('message', 'An error occurred')">
    <meta name="robots" content="noindex, nofollow">
    
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    @vite(['resources/css/tabler.css'])

    {{-- Theme Custom Styles --}}
    {!! $themeController->getStyleBlock('tabler') !!}
</head>
<body class="border-top-wide border-primary d-flex flex-column {{ $layoutData['bodyClass'] ?? '' }}" 
      data-theme-density="{{ $themeData['themeDensity'] ?? 'standard' }}"
      data-theme-font-size="{{ $themeData['themeFontSize'] ?? '14px' }}"
      data-theme-icon-weight="{{ $themeData['themeIconWeight'] ?? '1.5' }}"
      data-theme-texture="{{ $themeData['themeTexture'] ?? 'none' }}">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="empty">
                <div class="empty-header">@yield('error-code', '404')</div>
                <p class="empty-title">@yield('title', 'Error')</p>
                <p class="empty-subtitle text-secondary">
                    @yield('message', 'An unexpected error occurred.')
                </p>
                <div class="empty-action">
                    <a href="javascript:history.back()" class="btn btn-primary shadow-sm px-4">
                        <i class="ti ti-arrow-left me-2"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>