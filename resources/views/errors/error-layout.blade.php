<!DOCTYPE html>

<html lang="en" class="light-style" dir="ltr" data-theme="theme-default" data-assets-path="{{ url('build/assets/') }}/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Error - @yield('title', 'Page Not Found')</title>

    <meta name="description" content="@yield('description', 'An error occurred on our server')" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ Vite::asset('resources/assets/admin/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Core CSS -->
    @vite(['resources/assets/admin/vendor/css/core.css', 'resources/assets/admin/vendor/css/theme-default.css'])
    <style>
        .error {
            position: relative;
            font-size: 10rem;
            line-height: 1;
            text-align: center;
            margin-bottom: 1rem;
        }

        .error[data-text]::after {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            color: #e0e0e0;
            z-index: -1;
        }
    </style>
</head>

<body>
    <!-- Full viewport container -->
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <!-- Error -->
        <div class="container-xxl">
            <div class="misc-wrapper text-center">
                <div class="error mx-auto" data-text="@yield('error-code', '404')">
                    <p class="m-b-10" style="font-size: 8rem; font-weight: bold; color: #636363;">@yield('error-code', '404')</p>
                </div>
                <h2 class="mb-2 mx-2">@yield('title', 'Error')</h2>
                <p class="mb-4 mx-2">@yield('message', 'An unexpected error occurred.')</p>
                <div class="mt-4">
                    <a href="javascript:history.back()" class="btn btn-primary">← Go Back</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
