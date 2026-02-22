@php
    $mode = $mode ?? 'sys'; // 'sys' or 'auth'
    $isAuthMode = $mode === 'auth';

    // Initialize defaults if variables are missing (e.g. outside sys routes)
    $themeData = $themeData ?? [
        'theme' => env('TABLER_THEME', 'light'),
        'themePrimary' => env('TABLER_THEME_PRIMARY', '#206bc4'),
        'themeFont' => env('TABLER_THEME_FONT', 'inter'),
        'themeBase' => env('TABLER_THEME_BASE', 'gray'),
        'themeRadius' => env('TABLER_THEME_RADIUS', '1'),
        'themeBg' => env('TABLER_THEME_BG', ''),
        'themeSidebarBg' => env('TABLER_SIDEBAR_BG', ''),
        'themeHeaderTopBg' => env('TABLER_HEADER_TOP_BG', ''),
        'themeHeaderOverlapBg' => env('TABLER_HEADER_OVERLAP_BG', ''),
        'themeHeaderSticky' => env('TABLER_HEADER_STICKY', 'false'),
        'themeCardStyle' => env('TABLER_CARD_STYLE', 'flat'),
        'themeBoxedBg' => env('TABLER_BOXED_BG', ''),
        'authLayout' => env('AUTH_LAYOUT', 'basic'),
        'authFormPosition' => env('AUTH_FORM_POSITION', 'left'),
    ];

    $layoutData = $layoutData ?? [
        'layout' => env('TABLER_LAYOUT', 'vertical'),
        'containerWidth' => env('TABLER_CONTAINER_WIDTH', 'standard'),
    ];
@endphp

<div class="settings">
		<a href="#" class="btn btn-floating btn-icon btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSettings" aria-controls="offcanvasSettings" aria-label="Theme Settings">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
				class="icon icon-tabler icon-tabler-brush">
				<path d="M3 21v-4a4 4 0 1 1 4 4h-4" />
				<path d="M21 3a16 16 0 0 0 -12.8 10.2" />
				<path d="M21 3a16 16 0 0 1 -10.2 12.8" />
				<path d="M10.6 9a9 9 0 0 1 4.4 4.4" />
			</svg>
		</a>

    <form class="offcanvas offcanvas-end offcanvas-narrow" tabindex="-1" id="offcanvasSettings">
        {{-- Hidden field for mode (required by controller) --}}
        <input type="hidden" name="mode" value="{{ $mode }}">
        
        <div class="offcanvas-header border-bottom">
            <h2 class="offcanvas-title">Theme Settings</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <div class="settings-content" style="">
                
                <div class="row g-3">
                    @if($isAuthMode)
                    {{-- Auth Layout Mode --}}
                    <div class="col-12">
                        <label class="form-label">Auth Layout</label>
                        <select name="auth-layout" class="form-select">
                            <option value="basic">Basic (Centered)</option>
                            <option value="cover">Cover (With Image)</option>
                            <option value="illustration">Illustration (With SVG)</option>
                        </select>
                    </div>

                    {{-- Form Position --}}
                    <div class="col-12">
                        <label class="form-label">Form Position</label>
                        <div class="form-selectgroup">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="auth-form-position" value="left" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">Left</span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="auth-form-position" value="right" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">Right</span>
                            </label>
                        </div>
                        <small class="form-hint">For Cover & Illustration layouts</small>
                    </div>
                    @endif

                    {{-- Color Mode --}}
                    <div class="col-6">
                        <label class="form-label">Color Mode</label>
                        <div class="form-selectgroup">
                            @foreach(['light', 'dark'] as $themeMode)
                            <label class="form-selectgroup-item">
                                <input type="radio" name="theme" value="{{ $themeMode }}" class="form-selectgroup-input" {{ ($themeData['theme'] ?? 'light') === $themeMode ? 'checked' : '' }}>
                                <span class="form-selectgroup-label">
                                    {{ ucfirst($themeMode) }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Theme Base & Radius --}}
                    <div class="col-6">
                        <label class="form-label">Theme Base</label>
                        <select name="theme-base" class="form-select">
                            @foreach(['slate', 'gray', 'zinc', 'neutral', 'stone'] as $base)
                            <option value="{{ $base }}" {{ ($themeData['themeBase'] ?? 'gray') === $base ? 'selected' : '' }}>{{ ucfirst($base) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Corner Radius</label>
                         <select name="theme-radius" class="form-select">
                            @foreach(['0', '0.25', '0.5', '0.75', '1'] as $radius)
                            <option value="{{ $radius }}" {{ ($themeData['themeRadius'] ?? '1') === $radius ? 'selected' : '' }}>{{ $radius }}rem</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Card Style --}}
                    <div class="col-12">
                        <label class="form-label">Card Style</label>
                        <select name="theme-card-style" class="form-select">
                            @foreach([
                                'flat' => 'Flat (Minimalist)',
                                'shadow' => 'Shadow (Floating)',
                                'border' => 'Bordered (High Contrast)',
                                'modern' => 'Modern (Soft Shadow)'
                            ] as $val => $label)
                            <option value="{{ $val }}" {{ ($themeData['themeCardStyle'] ?? 'flat') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Advanced Customization Section --}}
                    <div class="col-12">
                        <div class="divider-text my-2">Advanced Customization</div>
                    </div>

                    {{-- UI Density --}}
                    <div class="col-12">
                        <label class="form-label">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-grid me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M14 14h6v6h-6z" /></svg>
                            UI Density
                        </label>
                        <select name="theme-density" class="form-select">
                            <option value="compact" {{ ($themeData['themeDensity'] ?? 'standard') === 'compact' ? 'selected' : '' }}>Compact (High Density)</option>
                            <option value="standard" {{ ($themeData['themeDensity'] ?? 'standard') === 'standard' ? 'selected' : '' }}>Standard (Default)</option>
                            <option value="spacious" {{ ($themeData['themeDensity'] ?? 'standard') === 'spacious' ? 'selected' : '' }}>Spacious (Low Density)</option>
                            <option value="ultra-spacious" {{ ($themeData['themeDensity'] ?? 'standard') === 'ultra-spacious' ? 'selected' : '' }}>Ultra Spacious</option>
                        </select>
                        <small class="form-hint">Controls spacing and padding throughout the UI</small>
                    </div>

                    {{-- Base Font Size --}}
                    <div class="col-12">
                        <label class="form-label">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-letter-a me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 20v-12a4 4 0 0 1 4 -4h2a4 4 0 0 1 4 4v12" /><path d="M7 13l10 0" /></svg>
                            Font Family & Size
                        </label>
                        <div class="row g-2">
                            <div class="col-6">
                                <select name="theme-font" class="form-select">
                                    <option value="inter" {{ ($themeData['themeFont'] ?? 'inter') === 'inter' ? 'selected' : '' }}>Inter</option>
                                    <option value="roboto" {{ ($themeData['themeFont'] ?? 'inter') === 'roboto' ? 'selected' : '' }}>Roboto</option>
                                    <option value="poppins" {{ ($themeData['themeFont'] ?? 'inter') === 'poppins' ? 'selected' : '' }}>Poppins</option>
                                    <option value="public-sans" {{ ($themeData['themeFont'] ?? 'inter') === 'public-sans' ? 'selected' : '' }}>Public Sans</option>
                                    <option value="nunito" {{ ($themeData['themeFont'] ?? 'inter') === 'nunito' ? 'selected' : '' }}>Nunito</option>
                                    <option value="sarabun" {{ ($themeData['themeFont'] ?? 'inter') === 'sarabun' ? 'selected' : '' }}>Sarabun</option>
                                </select>
                                <small class="form-hint text-muted">Font Family</small>
                            </div>
                            <div class="col-6">
                                <select name="theme-font-size" class="form-select">
                                    <option value="13px" {{ ($themeData['themeFontSize'] ?? '14px') === '13px' ? 'selected' : '' }}>13px</option>
                                    <option value="14px" {{ ($themeData['themeFontSize'] ?? '14px') === '14px' ? 'selected' : '' }}>14px (Default)</option>
                                    <option value="15px" {{ ($themeData['themeFontSize'] ?? '14px') === '15px' ? 'selected' : '' }}>15px</option>
                                    <option value="16px" {{ ($themeData['themeFontSize'] ?? '14px') === '16px' ? 'selected' : '' }}>16px</option>
                                </select>
                                <small class="form-hint text-muted">Font Size</small>
                            </div>
                        </div>
                    </div>

                    {{-- Icon Stroke Weight --}}
                    <div class="col-12">
                        <label class="form-label">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-palette me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-6a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h6z" /><path d="M12 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M9 15m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M15 15m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                            Icon Weight
                        </label>
                        <select name="theme-icon-weight" class="form-select">
                            <option value="1" {{ ($themeData['themeIconWeight'] ?? '1.5') === '1' ? 'selected' : '' }}>Light (1.0)</option>
                            <option value="1.25" {{ ($themeData['themeIconWeight'] ?? '1.5') === '1.25' ? 'selected' : '' }}>Medium Light (1.25)</option>
                            <option value="1.5" {{ ($themeData['themeIconWeight'] ?? '1.5') === '1.5' ? 'selected' : '' }}>Regular (1.5 - Default)</option>
                            <option value="1.75" {{ ($themeData['themeIconWeight'] ?? '1.5') === '1.75' ? 'selected' : '' }}>Medium Bold (1.75)</option>
                            <option value="2" {{ ($themeData['themeIconWeight'] ?? '1.5') === '2' ? 'selected' : '' }}>Bold (2.0)</option>
                        </select>
                        <small class="form-hint">Stroke width for all icons</small>
                    </div>

                    {{-- Background Texture --}}
                    <div class="col-12">
                        <label class="form-label">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-background me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 15l3 3l3 -3l-3 -3z" /><path d="M6 6l3 3l-3 3l-3 -3z" /><path d="M15 6l3 3l-3 3l-3 -3z" /><path d="M6 15l3 3l-3 3l-3 -3z" /><path d="M9 9l6 6" /><path d="M15 9l-6 6" /></svg>
                            Background Texture
                        </label>
                        <select name="theme-texture" class="form-select">
                            <option value="none" {{ ($themeData['themeTexture'] ?? 'none') === 'none' ? 'selected' : '' }}>None (Plain)</option>
                            <option value="dots-light" {{ ($themeData['themeTexture'] ?? 'none') === 'dots-light' ? 'selected' : '' }}>Dots Light (8px)</option>
                            <option value="dots-dense" {{ ($themeData['themeTexture'] ?? 'none') === 'dots-dense' ? 'selected' : '' }}>Dots Dense (5px)</option>
                            <option value="grid-fine" {{ ($themeData['themeTexture'] ?? 'none') === 'grid-fine' ? 'selected' : '' }}>Grid Fine (10px)</option>
                            <option value="grid-medium" {{ ($themeData['themeTexture'] ?? 'none') === 'grid-medium' ? 'selected' : '' }}>Grid Medium (20px)</option>
                            <option value="grid-large" {{ ($themeData['themeTexture'] ?? 'none') === 'grid-large' ? 'selected' : '' }}>Grid Large (40px)</option>
                            <option value="diagonal" {{ ($themeData['themeTexture'] ?? 'none') === 'diagonal' ? 'selected' : '' }}>Diagonal Lines</option>
                            <option value="honeycomb" {{ ($themeData['themeTexture'] ?? 'none') === 'honeycomb' ? 'selected' : '' }}>Honeycomb</option>
                            <option value="topography" {{ ($themeData['themeTexture'] ?? 'none') === 'topography' ? 'selected' : '' }}>Topography</option>
                            <option value="waves" {{ ($themeData['themeTexture'] ?? 'none') === 'waves' ? 'selected' : '' }}>Waves</option>
                            <option value="crosshatch" {{ ($themeData['themeTexture'] ?? 'none') === 'crosshatch' ? 'selected' : '' }}>Crosshatch</option>
                            <option value="circles" {{ ($themeData['themeTexture'] ?? 'none') === 'circles' ? 'selected' : '' }}>Circles</option>
                        </select>
                        <small class="form-hint">Subtle background pattern (adapts to dark mode) - Opacity enhanced for better visibility</small>
                    </div>

                    @unless($isAuthMode)
                    {{-- Page Layout (Moved UI) --}}
                    <div class="col-12">
                        <label class="form-label">Page Layout</label>
                        <select name="layout" class="form-select">
                            @foreach([
                                'vertical' => 'Vertical (Sidebar + Header)',
                                'horizontal' => 'Horizontal (Top Navbar)',
                                'condensed' => 'Condensed',
                            ] as $value => $label)
                            <option value="{{ $value }}" {{ ($layoutData['layout'] ?? 'vertical') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Header Mode (Sticky/Scrollable/Hidden) --}}
                    <div class="col-12" id="header-mode-section">
                        <label class="form-label">Header Mode</label>
                        <div class="form-selectgroup">
                            <label class="form-selectgroup-item" id="header-scrollable">
                                <input type="radio" name="theme-header-sticky" value="false" class="form-selectgroup-input" {{ ($themeData['themeHeaderSticky'] ?? 'false') === 'false' ? 'checked' : '' }}>
                                <span class="form-selectgroup-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-vertical me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12h18" /><path d="M12 3v18" /><path d="M15 6l-3 -3l-3 3" /><path d="M15 18l-3 3l-3 -3" /></svg>
                                    Scrollable
                                </span>
                            </label>
                            <label class="form-selectgroup-item" id="header-fixed">
                                <input type="radio" name="theme-header-sticky" value="true" class="form-selectgroup-input" {{ ($themeData['themeHeaderSticky'] ?? 'false') === 'true' ? 'checked' : '' }}>
                                <span class="form-selectgroup-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pin me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 4.5l-4 4l-4 1.5l-1.5 1.5l7 7l1.5 -1.5l1.5 -4l4 -4" /><path d="M9 15l-4.5 4.5" /><path d="M14.5 4l5.5 5.5" /></svg>
                                    Fixed
                                </span>
                            </label>
                            <label class="form-selectgroup-item" id="header-hidden">
                                <input type="radio" name="theme-header-sticky" value="hidden" class="form-selectgroup-input" {{ ($themeData['themeHeaderSticky'] ?? 'false') === 'hidden' ? 'checked' : '' }}>
                                <span class="form-selectgroup-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" /><path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" /><path d="M3 3l18 18" /></svg>
                                    Hidden
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Container Width --}}
                    <div class="col-12">
                        <label class="form-label">Container Width</label>
                        <div class="form-selectgroup">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="container-width" value="standard" class="form-selectgroup-input" {{ ($layoutData['containerWidth'] ?? 'standard') === 'standard' ? 'checked' : '' }}>
                                <span class="form-selectgroup-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-box me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12v9" /><path d="M12 12l-8 -4.5" /></svg>
                                    Standard
                                </span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="container-width" value="fluid" class="form-selectgroup-input" {{ ($layoutData['containerWidth'] ?? 'standard') === 'fluid' ? 'checked' : '' }}>
                                <span class="form-selectgroup-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-horizontal me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 8l-4 4l4 4" /><path d="M17 8l4 4l-4 4" /><path d="M3 12h18" /></svg>
                                    Fluid
                                </span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="container-width" value="boxed" class="form-selectgroup-input" {{ ($layoutData['containerWidth'] ?? 'standard') === 'boxed' ? 'checked' : '' }}>
                                <span class="form-selectgroup-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-box-padding me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h16v16h-16z" /><path d="M8 16v-8h8" /></svg>
                                    Boxed
                                </span>
                            </label>
                        </div>
                    </div>
                    @endunless

                    {{-- Custom Backgrounds --}}
                    <div class="col-12">
                        <label class="form-label">Custom Presets</label>
                        
                        {{-- Colour Preset --}}
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-4"><small>Colour Primary</small></div>
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    <div class="color-picker-component" data-target="theme-primary" data-default="#206bc4"></div>
                                    <input type="hidden" name="theme-primary" value="{{ $themeData['themePrimary'] ?? '#206bc4' }}">
                                    <button class="btn btn-icon btn-sm btn-outline-secondary ms-2" type="button" data-reset-bg="theme-primary" title="Reset">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-rotate-clockwise-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 4.55a8 8 0 0 1 6 14.9m0 -4.45v5h5" /><path d="M5.63 7.16l0 .01" /><path d="M4.06 11l0 .01" /><path d="M4.63 15.1l0 .01" /><path d="M7.16 18.37l0 .01" /><path d="M11 19.94l0 .01" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Body --}}
                        <div class="row g-2 mb-2 align-items-center" id="body-bg-preset">
                            <div class="col-4"><small>Body</small></div>
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    <div class="color-picker-component" data-target="theme-bg" data-default="#f4f6fa"></div>
                                    <input type="hidden" name="theme-bg" value="{{ $themeData['themeBg'] ?: '#f4f6fa' }}">
                                    <button class="btn btn-icon btn-sm btn-outline-secondary ms-2" type="button" data-reset-bg="theme-bg" title="Reset">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-rotate-clockwise-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 4.55a8 8 0 0 1 6 14.9m0 -4.45v5h5" /><path d="M5.63 7.16l0 .01" /><path d="M4.06 11l0 .01" /><path d="M4.63 15.1l0 .01" /><path d="M7.16 18.37l0 .01" /><path d="M11 19.94l0 .01" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        @unless($isAuthMode)
                        {{-- Sidebar / Menu --}}
                        <div class="row g-2 mb-2 align-items-center" id="sidebar-menu-preset">
                            <div class="col-4"><small>Sidebar / Menu</small></div>
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    <div class="color-picker-component" data-target="theme-sidebar-bg" data-default="#ffffff"></div>
                                    <input type="hidden" name="theme-sidebar-bg" value="{{ $themeData['themeSidebarBg'] ?: '#ffffff' }}">
                                    <button class="btn btn-icon btn-sm btn-outline-secondary ms-2" type="button" data-reset-bg="theme-sidebar-bg" title="Reset">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-rotate-clockwise-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 4.55a8 8 0 0 1 6 14.9m0 -4.45v5h5" /><path d="M5.63 7.16l0 .01" /><path d="M4.06 11l0 .01" /><path d="M4.63 15.1l0 .01" /><path d="M7.16 18.37l0 .01" /><path d="M11 19.94l0 .01" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Header Top --}}
                        <div class="row g-2 mb-2 align-items-center" id="header-top-preset">
                            <div class="col-4"><small>Header (Top)</small></div>
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    <div class="color-picker-component" data-target="theme-header-top-bg" data-default="#ffffff"></div>
                                    <input type="hidden" name="theme-header-top-bg" value="{{ $themeData['themeHeaderTopBg'] ?: '#ffffff' }}">
                                    <button class="btn btn-icon btn-sm btn-outline-secondary ms-2" type="button" data-reset-bg="theme-header-top-bg" title="Reset">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-rotate-clockwise-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 4.55a8 8 0 0 1 6 14.9m0 -4.45v5h5" /><path d="M5.63 7.16l0 .01" /><path d="M4.06 11l0 .01" /><path d="M4.63 15.1l0 .01" /><path d="M7.16 18.37l0 .01" /><path d="M11 19.94l0 .01" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Header Overlap (NEW) --}}
                        <div class="row g-2 mb-2 align-items-center" id="header-overlap-preset">
                            <div class="col-4"><small>Header (Overlap)</small></div>
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    <div class="color-picker-component" data-target="theme-header-overlap-bg" data-default="#1e293b"></div>
                                    <input type="hidden" name="theme-header-overlap-bg" value="{{ $themeData['themeHeaderOverlapBg'] ?? '#1e293b' }}">
                                    <button class="btn btn-icon btn-sm btn-outline-secondary ms-2" type="button" data-reset-bg="theme-header-overlap-bg" title="Reset">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-rotate-clockwise-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 4.55a8 8 0 0 1 6 14.9m0 -4.45v5h5" /><path d="M5.63 7.16l0 .01" /><path d="M4.06 11l0 .01" /><path d="M4.63 15.1l0 .01" /><path d="M7.16 18.37l0 .01" /><path d="M11 19.94l0 .01" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Boxed Background (NEW) --}}
                        <div class="row g-2 align-items-center" id="boxed-bg-preset">
                            <div class="col-4"><small>Boxed Background</small></div>
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    <div class="color-picker-component" data-target="theme-boxed-bg" data-default="#e2e8f0"></div>
                                    <input type="hidden" name="theme-boxed-bg" value="{{ $themeData['themeBoxedBg'] ?? '#e2e8f0' }}">
                                    <button class="btn btn-icon btn-sm btn-outline-secondary ms-2" type="button" data-reset-bg="theme-boxed-bg" title="Reset">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-rotate-clockwise-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 4.55a8 8 0 0 1 6 14.9m0 -4.45v5h5" /><path d="M5.63 7.16l0 .01" /><path d="M4.06 11l0 .01" /><path d="M4.63 15.1l0 .01" /><path d="M7.16 18.37l0 .01" /><path d="M11 19.94l0 .01" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endunless

                            </div>
                        </div>




                </div> <!-- End Row -->
            </div>

            {{-- Action Buttons --}}
            <div class="mt-auto pt-3 border-top text-center">
                <button type="button" class="btn btn-primary  m-2" id="apply-settings">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 12l5 5l10 -10"></path>
                            </svg>
                            Apply
                        </button>
                </button>
            </div>
        </div>
    </form>
</div>
