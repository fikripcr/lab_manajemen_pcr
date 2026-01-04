<div class="settings">
		<a href="#" class="btn btn-floating btn-icon btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSettings" aria-controls="offcanvasSettings" aria-label="Theme Settings">
			<!-- Download SVG icon from http://tabler.io/icons/icon/brush -->
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
				class="icon icon-1">
				<path d="M3 21v-4a4 4 0 1 1 4 4h-4" />
				<path d="M21 3a16 16 0 0 0 -12.8 10.2" />
				<path d="M21 3a16 16 0 0 1 -10.2 12.8" />
				<path d="M10.6 9a9 9 0 0 1 4.4 4.4" />
			</svg>
		</a>

    <form class="offcanvas offcanvas-end offcanvas-narrow" tabindex="-1" id="offcanvasSettings">
        <div class="offcanvas-header border-bottom">
            <h2 class="offcanvas-title">Theme Settings</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <div class="settings-content" style="overflow-y: auto; overflow-x: hidden; flex: 1;">
                
                <div class="row g-3">
                    {{-- Color Mode & Font Family --}}
                    <div class="col-6">
                        <label class="form-label">Color Mode</label>
                        <div class="form-selectgroup">
                            @foreach(['light', 'dark'] as $mode)
                            <label class="form-selectgroup-item">
                                <input type="radio" name="theme" value="{{ $mode }}" class="form-selectgroup-input" {{ ($themeData['theme'] ?? 'light') === $mode ? 'checked' : '' }}>
                                <span class="form-selectgroup-label">
                                    {{-- Icons optional, text is fine --}}
                                    {{ ucfirst($mode) }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Font Family</label>
                        <select name="theme-font" class="form-select">
                            @foreach([
                                'inter' => 'Inter', 
                                'roboto' => 'Roboto', 
                                'poppins' => 'Poppins', 
                                'public-sans' => 'Public Sans', 
                                'nunito' => 'Nunito',
                                'sans-serif' => 'Sans Serif', 
                            ] as $val => $label)
                            <option value="{{ $val }}" {{ ($themeData['themeFont'] ?? 'inter') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
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
                            @foreach(['0', '0.5', '1', '1.5', '2'] as $radius)
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

                    <div class="col-12">
                        <label class="form-label">Navigation Mode</label>
                        <div class="form-selectgroup">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="theme-header-sticky" value="false" class="form-selectgroup-input" {{ !filter_var($themeData['themeHeaderSticky'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}>
                                <span class="form-selectgroup-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-vertical me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12h18" /><path d="M12 3v18" /><path d="M15 6l-3 -3l-3 3" /><path d="M15 18l-3 3l-3 -3" /></svg>
                                    Scrollable
                                </span>
                            </label>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="theme-header-sticky" value="true" class="form-selectgroup-input" {{ filter_var($themeData['themeHeaderSticky'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}>
                                <span class="form-selectgroup-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pin me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 4.5l-4 4l-4 1.5l-1.5 1.5l7 7l1.5 -1.5l1.5 -4l4 -4" /><path d="M9 15l-4.5 4.5" /><path d="M14.5 4l5.5 5.5" /></svg>
                                    Fixed
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
                        </div>
                    </div>

                    {{-- Custom Backgrounds --}}
                    <div class="col-12">
                        <label class="form-label">Custom Backgrounds</label>
                        
                        {{-- Body --}}
                        <div class="row g-2 mb-2 align-items-center">
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

                        {{-- Sidebar / Menu --}}
                        <div class="row g-2 mb-2 align-items-center">
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
                        <div class="row g-2 mb-2 align-items-center">
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

                        {{-- Boxed Background (NEW) --}}
                        <div class="row g-2 align-items-center">
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

                        <div class="form-text small mt-1">Override background colors. Use Reset to restore defaults.</div>
                    </div>

                    {{-- Color Primary --}}
                    <div class="col-12">
                        <label class="form-label">Color Primary</label>
                        <div class="row g-2 align-items-center">
                            <div class="col-4"><small>Primary Color</small></div>
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
                        <div class="form-text small mt-1">Choose your theme primary color. Presets available in color picker.</div>
                    </div>

                    <div class="col-12"><hr class="my-1"></div>

                    {{-- Page Layout (changed to radio list) --}}
                    <div class="col-12">
                        <label class="form-label">Page Layout</label>
                        <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                             @php
                                $layouts = [
                                    'vertical' => 'Vertical Without Header',
                                    'combo' => 'Vertical With Header',
                                    'vertical-transparent' => 'Vertical Transparent',
                                    'horizontal' => 'Horizontal',
                                    'condensed' => 'Condensed',
                                    'navbar-overlap' => 'Navbar Overlap',
                                    'boxed' => 'Boxed',
                                ];
                            @endphp
                            @foreach($layouts as $value => $label)
                            <label class="form-selectgroup-item flex-fill">
                                <input type="radio" name="layout" value="{{ $value }}" class="form-selectgroup-input" {{ ($layoutData['layout'] ?? 'vertical') === $value ? 'checked' : '' }} />
                                <div class="form-selectgroup-label d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="form-selectgroup-check"></span>
                                    </div>
                                    <div>{{ $label }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                </div> <!-- End Row -->
            </div>

            {{-- Action Buttons --}}
            <div class="mt-auto pt-3 border-top">
                <div class="row g-2">
                    <div class="col-6">
                        <button type="button" class="btn btn-primary w-100" id="apply-settings">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 12l5 5l10 -10"></path>
                            </svg>
                            Apply
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-secondary w-100" id="reset-changes">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    var themeConfig = {
        "theme": "{{ $themeData['theme'] ?? 'light' }}",
        "theme-primary": "{{ $themeData['themePrimary'] ?? 'blue' }}",
        "theme-base": "{{ $themeData['themeBase'] ?? 'gray' }}",
        "theme-font": "{{ $themeData['themeFont'] ?? 'sans-serif' }}",
        "theme-radius": "{{ $themeData['themeRadius'] ?? '1' }}",
        "theme-bg": "{{ $themeData['themeBg'] ?? '' }}",
        "theme-sidebar-bg": "{{ $themeData['themeSidebarBg'] ?? '' }}",
        "theme-header-top-bg": "{{ $themeData['themeHeaderTopBg'] ?? '' }}",
        "theme-header-sticky": "{{ filter_var($themeData['themeHeaderSticky'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false' }}",
        "theme-card-style": "{{ $themeData['themeCardStyle'] ?? 'flat' }}",
    }

    var form = document.getElementById("offcanvasSettings")
    var resetButton = document.getElementById("reset-changes")
    var applyButton = document.getElementById("apply-settings")

    // Initialize Pickr instances
    var pickrInstances = {};

    document.querySelectorAll('.color-picker-component').forEach(el => {
        var targetName = el.getAttribute('data-target');
        var defaultValue = el.getAttribute('data-default');
        
        // Get initial value from hidden input
        var hiddenInput = form.querySelector(`input[name="${targetName}"]`);
        var initialValue = hiddenInput ? hiddenInput.value : defaultValue;

        const pickr = Pickr.create({
            el: el,
            theme: 'nano', // or 'monolith', or 'classic'
            default: initialValue,
            swatches: [
                'rgba(244, 246, 250, 1)',
                'rgba(255, 255, 255, 1)',
                'rgba(0, 0, 0, 1)',
                'rgba(255, 0, 0, 0.5)',
                'rgba(32, 107, 196, 1)'
            ],
            components: {
                // Main components
                preview: true,
                opacity: true,
                hue: true,

                // Input / output Options
                interaction: {
                    hex: true,
                    rgba: true,
                    input: true,
                    save: true
                }
            }
        });

        pickrInstances[targetName] = pickr;

        // Sync Pickr -> Hidden Input -> Live Preview
        pickr.on('change', (color, source, instance) => {
            var rgbaColor = color.toRGBA().toString(0); // 0 decimal places for alpha if 1, else precision
            
            // Update hidden input
            if(hiddenInput) {
                hiddenInput.value = rgbaColor;
                // Manually trigger handleThemeChange
                handleThemeChange({ target: hiddenInput });
            }
        });
        
        // Optional: on save
        pickr.on('save', (color, instance) => {
             pickr.hide();
        });
    });


    // Sync inputs with localStorage on load
    var checkItems = function () {
        for (var key in themeConfig) {
            var value = window.localStorage["tabler-" + key] || themeConfig[key]
            var inputs = form.querySelectorAll(`[name="${key}"]`)
            
            if (inputs.length > 0) {
                if (key.includes('-bg') || key === 'theme-primary') {
                     var finalVal = value || (key === 'theme-bg' ? '#f4f6fa' : (key === 'theme-primary' ? '#206bc4' : '#ffffff'));
                     inputs[0].value = finalVal;
                     // Update Pickr if exists
                     if(pickrInstances[key]) {
                         pickrInstances[key].setColor(finalVal);
                     }
                } else if (inputs[0].tagName === 'SELECT') {
                    inputs[0].value = value
                } else {
                    inputs.forEach((input) => {
                        input.checked = input.value === value
                    })
                }
            }
        }
    }

    // Handle theme changes (live preview)
    function handleThemeChange(event) {
        var target = event.target, name = target.name, value = target.value

        if (name === 'theme-bg') {
             document.documentElement.style.setProperty('--tblr-body-bg', value)
             window.localStorage.setItem("tabler-" + name, value)

        } else if (name === 'theme-sidebar-bg') {
             document.documentElement.style.setProperty('--tblr-sidebar-bg', value)
             document.documentElement.setAttribute('data-bs-has-sidebar-bg', '')
             window.localStorage.setItem("tabler-" + name, value)

        } else if (name === 'theme-header-top-bg') {
             document.documentElement.style.setProperty('--tblr-header-top-bg', value)
             document.documentElement.setAttribute('data-bs-has-header-top-bg', '')
             window.localStorage.setItem("tabler-" + name, value)

        } else if (name === 'theme-header-sticky') {
             const header = document.querySelector('header.navbar');
             var isSticky = (value === 'true');
             
             if(header) {
                 if (isSticky) header.classList.add('sticky-top');
                 else header.classList.remove('sticky-top');
             }
             window.localStorage.setItem("tabler-" + name, value)

        } else if (name === 'theme-card-style') {
             if (value === 'default') {
                 document.documentElement.removeAttribute("data-bs-card-style")
             } else {
                 document.documentElement.setAttribute("data-bs-card-style", value)
             }
             window.localStorage.setItem("tabler-" + name, value)
             
        } else {
            for (var key in themeConfig) {
                if (name === key) {
                    document.documentElement.setAttribute("data-bs-" + key, value)
                    window.localStorage.setItem("tabler-" + key, value)
                }
            }
        }
        
        // Layout preview
        if (name === 'layout' && typeof window.previewLayout === 'function') {
            window.previewLayout(value)
        }
    }

    // Listen to both 'change' (for select/radio) and 'input' (for color picker dragging)
    form.addEventListener("change", handleThemeChange);
    // form.addEventListener("input", handleThemeChange); // Not needed for hidden inputs driven by Pickr

    // Universal Reset Bg Button
    var resetBgButtons = form.querySelectorAll('button[data-reset-bg]');
    resetBgButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var targetName = this.getAttribute('data-reset-bg');
            var bgInput = form.querySelector(`input[name="${targetName}"]`);
            var defaultVal = (targetName === 'theme-bg') ? '#f4f6fa' : '#ffffff';
            
            // Visual Reset Input
            bgInput.value = defaultVal;

            // Visual Reset Pickr
            if(pickrInstances[targetName]) {
                pickrInstances[targetName].setColor(defaultVal);
            }
            
            // Logic Reset
            if(targetName === 'theme-bg') document.documentElement.style.removeProperty('--tblr-body-bg');
            if(targetName === 'theme-sidebar-bg') {
                document.documentElement.style.removeProperty('--tblr-sidebar-bg');
                document.documentElement.removeAttribute('data-bs-has-sidebar-bg');
            }
            if(targetName === 'theme-header-top-bg') {
                document.documentElement.style.removeProperty('--tblr-header-top-bg');
                document.documentElement.removeAttribute('data-bs-has-header-top-bg');
            }
            if(targetName === 'theme-boxed-bg') {
                defaultVal = '#e2e8f0';
                bgInput.value = defaultVal;
                if(pickrInstances[targetName]) {
                    pickrInstances[targetName].setColor(defaultVal);
                }
                document.documentElement.style.removeProperty('--tblr-boxed-bg');
            }
            if(targetName === 'theme-primary') {
                defaultVal = '#206bc4';
                bgInput.value = defaultVal;
                if(pickrInstances[targetName]) {
                    pickrInstances[targetName].setColor(defaultVal);
                }
            }

            window.localStorage.removeItem("tabler-" + targetName);
        });
    });

    // Preset Color Buttons for Primary Color
    var presetButtons = form.querySelectorAll('button[data-preset-color]');
    presetButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var color = this.getAttribute('data-preset-color');
            var input = form.querySelector('input[name="theme-primary"]');
            if (input) {
                input.value = color;
                if(pickrInstances['theme-primary']) {
                    pickrInstances['theme-primary'].setColor(color);
                }
            }
        });
    });

    // Reset All
    resetButton.addEventListener("click", function () {
        for (var key in themeConfig) {
            if (key.includes('-bg')) {
                 if(key === 'theme-bg') document.documentElement.style.removeProperty('--tblr-body-bg');
                 if(key === 'theme-sidebar-bg') {
                     document.documentElement.style.removeProperty('--tblr-sidebar-bg');
                     document.documentElement.removeAttribute('data-bs-has-sidebar-bg');
                 }
                 if(key === 'theme-header-top-bg') {
                     document.documentElement.style.removeProperty('--tblr-header-top-bg');
                     document.documentElement.removeAttribute('data-bs-has-header-top-bg');
                 }
                 
                 window.localStorage.removeItem("tabler-" + key)
                 // Input visual reset
                 var bgInput = form.querySelector(`input[name="${key}"]`);
                 var defaultVal = (key === 'theme-bg') ? '#f4f6fa' : '#ffffff';
                 if(bgInput) bgInput.value = defaultVal;
                 
                  // Pickr visual reset
                 if(pickrInstances[key]) {
                     pickrInstances[key].setColor(defaultVal);
                 }

            } else if (key === 'theme-card-style') {
                 document.documentElement.removeAttribute("data-bs-card-style")
                 if (themeConfig[key] && themeConfig[key] !== 'default') {
                     document.documentElement.setAttribute("data-bs-card-style", themeConfig[key])
                 }
                 window.localStorage.removeItem("tabler-" + key)
            } else {
                document.documentElement.setAttribute("data-bs-" + key, themeConfig[key])
                window.localStorage.removeItem("tabler-" + key)
            }
        }
        checkItems()
    })

    // Apply settings to .env
    applyButton.addEventListener("click", function () {
        var formData = new FormData()

        // Regular inputs (exclude primary from auto-loop, handle manually)
        var themeFields = ['theme', 'theme-font', 'theme-base', 'theme-radius', 'theme-card-style']
        themeFields.forEach(function(field) {
            var selected = form.querySelector(`input[name="${field}"]:checked`) || form.querySelector(`select[name="${field}"]`)
            if (selected) {
                formData.append(field.replace(/-/g, '_'), selected.value)
            }
        })

        // Theme Primary
        var selectedPrimary = form.querySelector('input[name="theme-primary"]:checked');
        if (selectedPrimary) {
             if (selectedPrimary.value === 'custom') {
                 formData.append('theme_primary', form.querySelector('input[name="theme-primary-custom"]').value);
             } else {
                 formData.append('theme_primary', selectedPrimary.value);
             }
        }


        // Background inputs
        var bgFields = ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-boxed-bg'];
        bgFields.forEach(function(bg) {
            var input = form.querySelector(`input[name="${bg}"]`);
            if (input) {
                formData.append(bg.replace(/-/g, '_'), input.value || '');
            }
        });

        // Sticky Header
        var stickyInput = form.querySelector('input[name="theme-header-sticky"]:checked');
        formData.append('theme_header_sticky', stickyInput ? stickyInput.value : 'false');

        // Layout
        var layout = form.querySelector('input[name="layout"]:checked')
        if (layout) {
            formData.append('layout', layout.value)
        }

        // Container Width
        var containerWidth = form.querySelector('input[name="container-width"]:checked')
        if (containerWidth) {
            formData.append('container_width', containerWidth.value)
        }


        // Debug: Log FormData content
        console.log('Sending settings to backend:');
        for (let pair of formData.entries()) {
            console.log(`  ${pair[0]}: ${pair[1]}`);
        }

        Swal.fire({
            title: 'Applying...',
            text: 'Writing to .env',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        })

        axios.post('{{ route("sys.layout.apply") }}', formData)
            .then(function (response) {
                console.log('Backend response:', response.data);
                if (response.data.success) {
                    for (var key in themeConfig) {
                        window.localStorage.removeItem("tabler-" + key)
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.data.message || 'Settings saved!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                }
            })
            .catch(function (error) {
                console.error('Error saving settings:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.response?.data?.message || error.message || 'Failed to apply settings.',
                });
            });
    })

    checkItems()
})
</script>


@endpush