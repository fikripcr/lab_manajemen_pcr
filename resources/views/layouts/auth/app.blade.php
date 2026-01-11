@php
    use App\Http\Controllers\Sys\ThemeTablerController;
    
    // Load theme controller for auth
    $themeController = app(ThemeTablerController::class);
    $themeData = $themeController->getThemeData('auth');
    
    // Get layout preferences
    $authLayout = $themeData['authLayout'] ?? 'basic';
    $authFormPosition = $themeData['authFormPosition'] ?? 'left';
    
    // Initialize layoutData for theme-settings component
    $layoutData = $themeController->getLayoutData('auth');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {!! $themeController->getHtmlAttributes('auth') !!}>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Nunito:wght@300;400;600;700&family=Poppins:wght@300;400;500;600;700&family=Public+Sans:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/auth.css', 'resources/js/auth.js'])

    {{-- Theme Styles (Server Side) - Must be loaded AFTER css bundle to override defaults --}}
    {!! $themeController->getStyleBlock('auth') !!}
</head>

<body class="d-flex flex-column" {!! $themeController->getBodyAttributes('auth') !!}>
    @if($authLayout === 'basic')
        {{-- BASIC LAYOUT: Centered card --}}
        <main class="page page-center">
            <div class="container container-tight py-4">
                    <div class="text-center mb-4">
                        <a href="." class="navbar-brand navbar-brand-autodark">
                            <img src="{{ asset('images/digilab-crop.png') }}" width="200" height="36" alt="{{ config('app.name') }}">
                        </a>
                    </div>
                @yield('content')
            </div>
        </main>

    @elseif($authLayout === 'cover')
        {{-- COVER LAYOUT: Split screen with background image --}}
        @php
            // Determine column order and content alignment based on form position
            $formIsLeft = $authFormPosition === 'left';
            $formOrder = $formIsLeft ? 'order-1' : 'order-2';
            $coverOrder = $formIsLeft ? 'order-2' : 'order-1';
            $contentAlign = $formIsLeft ? 'align-items-end text-end' : 'align-items-start text-start';
            $gradientDir = $formIsLeft ? '315deg' : '135deg'; // Flip gradient direction
        @endphp
        
        <main class="row g-0 flex-fill">
            <div class="col-12 col-lg-6 col-xl-5 {{ $formOrder }} d-flex flex-column justify-content-center" data-form-column>
                <div class="container container-tight my-5 px-lg-5">
                    <div class="text-center mb-4">
                        <a href="." class="navbar-brand navbar-brand-autodark">
                            <img src="{{ asset('assets/img/digilab-crop.png') }}" width="200" height="36" alt="{{ config('app.name') }}">
                        </a>
                    </div>
                    @yield('content')
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-7 {{ $coverOrder }} d-none d-lg-block" data-media-column>
                {{-- Background cover image with overlay content --}}
                <div class="bg-cover h-100 min-vh-100 position-relative" style="background-image: url({{ asset('images/bg-auth-cover.png') }})">
                    {{-- Dark gradient overlay for text readability --}}
                    <div class="position-absolute top-0 start-0 w-100 h-100" 
                         style="background: linear-gradient({{ $gradientDir }}, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.3) 100%);"></div>
                    
                    {{-- Content overlay --}}
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column {{ $contentAlign }} p-4 p-lg-5">
                        {{-- Logo at top --}}
                        <div class="mb-auto">
                            <img src="{{ asset('images/digilab-crop.png') }}" 
                                 width="180" height="33" alt="{{ config('app.name') }}" >
                        </div>
                        
                        {{-- Testimonial at bottom --}}
                        <div class="text-white">
                            <blockquote class="mb-4">
                                <p class="fs-2 fw-bold lh-sm mb-0" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                    "Manajemen laboratorium yang lebih cepat, efisien, dan modern untuk pertumbuhan institusi Anda."
                                </p>
                            </blockquote>
                            
                            <div class="d-flex align-items-center gap-3">
                                {{-- Avatar group --}}
                                <div class="avatar-list avatar-list-stacked">
                                    <span class="avatar avatar-sm avatar-rounded" style="background-image: url('https://ui-avatars.com/api/?name=Admin+Lab&background=3b82f6&color=fff');"></span>
                                    <span class="avatar avatar-sm avatar-rounded" style="background-image: url('https://ui-avatars.com/api/?name=Lab+Manager&background=10b981&color=fff');"></span>
                                    <span class="avatar avatar-sm avatar-rounded" style="background-image: url('https://ui-avatars.com/api/?name=Lab+Staff&background=f59e0b&color=fff');"></span>
                                </div>
                                
                                <div>
                                    <div class="fw-semibold ms-2">
                                        Dipercaya oleh 50+ Laboratorium
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <img src="{{ asset('images/digilab-crop.png') }}" width="200" height="36" alt="{{ config('app.name') }}">
                                </a>
                            </div>
                            @yield('content')
                        </div>
                    </div>
                    <div class="col-lg d-none d-lg-block" data-media-column>
                        <img src="{{ asset('images/bg-auth-illustration.png') }}"  class="img d-block mx-auto" alt="Illustration">
                    </div>
                </div>
            </div>
        </main>
    @endif
    {{-- Theme Settings Component (Unified with sys) --}}
    @if(env('THEME_CUSTOMIZATION_ENABLED', true))
        <x-sys.theme-settings mode="auth" :themeData="$themeData" :layoutData="$layoutData" />
    @endif
</body>


@stack('scripts')
</html>
