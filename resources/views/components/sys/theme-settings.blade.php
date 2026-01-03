{{-- Theme Settings Floating Button & Offcanvas --}}
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

    <form class="offcanvas offcanvas-start offcanvas-narrow" tabindex="-1" id="offcanvasSettings">
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
                            @foreach(['sans-serif', 'serif', 'monospace', 'comic'] as $font)
                            <option value="{{ $font }}" {{ ($themeData['themeFont'] ?? 'sans-serif') === $font ? 'selected' : '' }}>{{ ucfirst($font) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Theme Base & Radius --}}
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

                    {{-- Color Scheme --}}
                    <div class="col-12">
                        <label class="form-label">Color Scheme</label>
                        <div class="row g-2">
                            @foreach(['blue', 'azure', 'indigo', 'purple', 'pink', 'red', 'orange', 'yellow', 'lime', 'green', 'teal', 'cyan'] as $color)
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="theme-primary" type="radio" value="{{ $color }}" class="form-colorinput-input" {{ ($themeData['themePrimary'] ?? 'blue') === $color ? 'checked' : '' }} />
                                    <span class="form-colorinput-color bg-{{ $color }}"></span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12"><hr class="my-1"></div>

                    {{-- Layout --}}
                    <div class="col-12">
                        <label class="form-label">Page Layout</label>
                        <div class="row g-2">
                             @php
                                $layouts = [
                                    'vertical' => 'Vertical',
                                    'horizontal' => 'Horizontal',
                                    'boxed' => 'Boxed',
                                    'fluid' => 'Fluid',
                                    'condensed' => 'Condensed',
                                    'navbar-sticky' => 'Navbar Sticky',
                                    'navbar-overlap' => 'Navbar Overlap',
                                    'navbar-dark' => 'Navbar Dark',
                                    'combo' => 'Combined',
                                    'fluid-vertical' => 'Fluid Vertical',
                                    'vertical-transparent' => 'Vertical Transparent',
                                ];
                            @endphp
                            @foreach($layouts as $value => $label)
                            <div class="col-6">
                                <label class="form-selectgroup-item w-100">
                                    <input type="radio" name="layout" value="{{ $value }}" class="form-selectgroup-input" {{ ($layoutData['layout'] ?? 'vertical') === $value ? 'checked' : '' }} />
                                    <span class="form-selectgroup-label d-flex align-items-center justify-content-center w-100 p-2">
                                        {{ $label }}
                                    </span>
                                </label>
                            </div>
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
        "theme-base": "{{ $themeData['themeBase'] ?? 'gray' }}",
        "theme-font": "{{ $themeData['themeFont'] ?? 'sans-serif' }}",
        "theme-primary": "{{ $themeData['themePrimary'] ?? 'blue' }}",
        "theme-radius": "{{ $themeData['themeRadius'] ?? '1' }}",
    }

    var form = document.getElementById("offcanvasSettings")
    var resetButton = document.getElementById("reset-changes")
    var applyButton = document.getElementById("apply-settings")

    // Sync radio buttons with localStorage on load
    // Sync inputs with localStorage on load
    var checkItems = function () {
        for (var key in themeConfig) {
            var value = window.localStorage["tabler-" + key] || themeConfig[key]
            var inputs = form.querySelectorAll(`[name="${key}"]`)
            
            if (inputs.length > 0) {
                if (inputs[0].tagName === 'SELECT') {
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
    form.addEventListener("change", function (event) {
        var target = event.target, name = target.name, value = target.value

        // Theme properties (live preview)
        for (var key in themeConfig) {
            if (name === key) {
                document.documentElement.setAttribute("data-bs-" + key, value)
                window.localStorage.setItem("tabler-" + key, value)
            }
        }
        
        // Layout preview (calls global function from sys.js)
        if (name === 'layout' && typeof window.previewLayout === 'function') {
            window.previewLayout(value)
        }
    })

    // Reset preview to server defaults
    resetButton.addEventListener("click", function () {
        for (var key in themeConfig) {
            document.documentElement.setAttribute("data-bs-" + key, themeConfig[key])
            window.localStorage.removeItem("tabler-" + key)
        }
        checkItems()
    })

    // Apply settings to .env
    applyButton.addEventListener("click", function () {
        var formData = new FormData()

        // Theme settings
        var themeFields = ['theme', 'theme-primary', 'theme-font', 'theme-base', 'theme-radius']
        themeFields.forEach(function(field) {
            var selected = form.querySelector(`input[name="${field}"]:checked`)
            if (selected) {
                formData.append(field.replace(/-/g, '_'), selected.value)
            }
        })

        // Layout (single selection)
        var layout = form.querySelector('input[name="layout"]:checked')
        if (layout) {
            formData.append('layout', layout.value)
        }

        Swal.fire({
            title: 'Applying...',
            text: 'Writing to .env',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        })

        axios.post('{{ route("sys.layout.apply") }}', formData)
            .then(function (response) {
                if (response.data.success) {
                    for (var key in themeConfig) {
                        window.localStorage.removeItem("tabler-" + key)
                    }
                        // Simply reload immediately
                        window.location.reload();
                }
            })
    })

    checkItems()
})
</script>
@endpush