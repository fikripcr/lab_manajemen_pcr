<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('error-code', '404') - @yield('title', 'Error') | {{ config('app.name') }}</title>
    <meta name="description" content="@yield('message', 'An error occurred')">
    <meta name="robots" content="noindex, nofollow">
    
    <link rel="icon" type="image/x-icon" href="{{ Vite::asset('resources/assets/admin/img/favicon/favicon.ico') }}">
    
    @include('partials.theme-loader')
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Bebas+Neue&family=Courier+Prime:wght@400;700&family=Caveat:wght@400;700&family=EB+Garamond:wght@400;600&family=Outfit:wght@300;400;600&family=Poppins:wght@300;400;600&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/sys.css'])
</head>
<body class="d-flex flex-column">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="empty">
                <div class="empty-header">@yield('error-code', '404')</div>
                <p class="empty-title">@yield('title', 'Error')</p>
                <p class="empty-subtitle text-secondary">
                    @yield('message', 'An unexpected error occurred.')
                </p>
                <div class="empty-action">
                    <a href="javascript:history.back()" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 12l14 0" />
                            <path d="M5 12l6 6" />
                            <path d="M5 12l6 -6" />
                        </svg>
                        Go Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>