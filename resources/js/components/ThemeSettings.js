/**
 * ThemeSettings - Settings Panel Logic
 * Handles UI for theme settings panel (Pickr, form events, backend submission)
 */
class ThemeSettings {
    constructor(themeManager, config = {}) {
        this.themeManager = themeManager;
        this.config = config; // Server defaults from Blade
        this.form = document.getElementById('offcanvasSettings');
        this.applyButton = document.getElementById('apply-settings');
        this.pickrInstances = {};

        if (!this.form) {
            console.warn('ThemeSettings: Form #offcanvasSettings not found');
            return;
        }
    }

    /**
     * Initialize settings panel
     */
    init() {
        this.initPickr();
        this.syncFormWithStorage();
        this.bindEvents();
    }

    /**
     * Initialize Pickr color pickers
     */
    initPickr() {
        if (typeof window.Pickr === 'undefined') {
            console.error('ThemeSettings: Pickr not loaded');
            return;
        }

        document.querySelectorAll('.color-picker-component').forEach(el => {
            const targetName = el.getAttribute('data-target');
            const defaultValue = el.getAttribute('data-default');

            // Get initial value from hidden input
            const hiddenInput = this.form.querySelector(`input[name="${targetName}"]`);
            const initialValue = hiddenInput ? hiddenInput.value : defaultValue;

            const pickr = window.Pickr.create({
                el: el,
                theme: 'nano',
                default: initialValue,
                swatches: [
                    '#f4f6fa', '#ffffff', '#206bc4', '#a55eea', '#d63939', '#fd7e14', '#2fb344'
                ],
                components: {
                    preview: true,
                    opacity: true,
                    hue: true,
                    interaction: {
                        hex: true,
                        rgba: true,
                        input: true,
                        save: false
                    }
                }
            });

            this.pickrInstances[targetName] = pickr;

            // Sync Pickr → Hidden Input → Live Preview
            pickr.on('change', (color, source, instance) => {
                const rgbaColor = color.toRGBA().toString(0);
                instance.applyColor(true);

                if (hiddenInput) {
                    hiddenInput.value = rgbaColor;
                    // Apply to theme immediately (live preview)
                    this.themeManager.applySetting(targetName, rgbaColor);
                }
            });

            pickr.on('save', () => {
                pickr.hide();
            });
        });
    }

    /**
     * Sync form inputs with localStorage values
     */
    syncFormWithStorage() {
        const allSettings = this.themeManager.getAllSettings();

        Object.keys(allSettings).forEach(key => {
            const value = allSettings[key];
            const inputs = this.form.querySelectorAll(`[name="${key}"]`);

            if (inputs.length > 0) {
                // Color picker inputs (hidden)
                if (key.includes('-bg') || key === 'theme-primary') {
                    inputs[0].value = value;
                    // Update Pickr if exists (with safety check for _options)
                    if (this.pickrInstances[key] && this.pickrInstances[key]._options) {
                        const defaultColor = this.pickrInstances[key]._options.default || '#ffffff';
                        this.pickrInstances[key].setColor(value || defaultColor);
                    }
                }
                // Select dropdowns
                else if (inputs[0].tagName === 'SELECT') {
                    inputs[0].value = value;
                }
                // Radio buttons
                else {
                    inputs.forEach(input => {
                        input.checked = input.value === value;
                    });
                }
            }
        });

        // Apply layout-based visibility
        const layoutSelect = this.form.querySelector('select[name="layout"]');
        if (layoutSelect && layoutSelect.value) {
            this.handleLayoutChange(layoutSelect.value);
        }

        // Apply theme mode-based visibility
        const themeInput = this.form.querySelector('input[name="theme"]:checked');
        if (themeInput && themeInput.value) {
            this.handleThemeModeChange(themeInput.value);
        }
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Form change events (live preview)
        this.form.addEventListener('change', (e) => this.handleChange(e));

        // Apply button
        if (this.applyButton) {
            this.applyButton.addEventListener('click', () => this.handleApply());
        }

        // Reset background buttons
        this.form.querySelectorAll('button[data-reset-bg]').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleReset(e));
        });
    }

    /**
     * Handle form input changes (live preview)
     */
    handleChange(event) {
        const target = event.target;
        const name = target.name;
        const value = target.value;

        if (!name) return;

        // Apply setting immediately for live preview
        this.themeManager.applySetting(name, value);

        // Handle layout changes (show/hide relevant sections)
        if (name === 'layout') {
            this.handleLayoutChange(value);
        }

        // Handle theme mode changes (show/hide color presets)
        if (name === 'theme') {
            this.handleThemeModeChange(value);
        }

        // Special handling for layout changes (if preview function exists)
        if (name === 'layout' && typeof window.previewLayout === 'function') {
            window.previewLayout(value);
        }
    }

    /**
     * Handle theme mode change - show/hide color presets
     */
    handleThemeModeChange(theme) {
        const bodyBgPreset = document.getElementById('body-bg-preset');
        const sidebarMenuPreset = document.getElementById('sidebar-menu-preset');
        const headerTopPreset = document.getElementById('header-top-preset');
        const headerOverlapPreset = document.getElementById('header-overlap-preset');
        const boxedBgPreset = document.getElementById('boxed-bg-preset');

        const colorPresets = [bodyBgPreset, sidebarMenuPreset, headerTopPreset, headerOverlapPreset, boxedBgPreset];

        if (theme === 'dark') {
            // Dark mode - hide all color presets except primary
            colorPresets.forEach(el => {
                if (el) el.style.display = 'none';
            });

            // IMPORTANT: Remove all background color CSS variables so dark mode uses defaults
            const bgSettings = ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'];
            bgSettings.forEach(setting => {
                this.themeManager.resetSetting(setting);
            });
        } else {
            // Light mode - show all color presets, but respect layout settings
            colorPresets.forEach(el => {
                if (el) el.style.display = '';
            });

            // Restore custom colors from form inputs
            const bgSettings = ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'];
            bgSettings.forEach(setting => {
                const input = this.form.querySelector(`input[name="${setting}"]`);
                if (input && input.value) {
                    this.themeManager.applySetting(setting, input.value);
                }
            });

            // Re-apply layout-based visibility
            const layoutSelect = this.form.querySelector('select[name="layout"]');
            if (layoutSelect && layoutSelect.value) {
                this.handleLayoutChange(layoutSelect.value);
            }
        }
    }

    /**
     * Handle layout change - show/hide relevant settings
     */
    handleLayoutChange(layout) {
        const sidebarMenuPreset = document.getElementById('sidebar-menu-preset');
        const headerOverlapPreset = document.getElementById('header-overlap-preset');
        const boxedBgPreset = document.getElementById('boxed-bg-preset');
        const headerModeSection = document.getElementById('header-mode-section');

        // Reset all to visible first
        [sidebarMenuPreset, headerOverlapPreset, boxedBgPreset, headerModeSection].forEach(el => {
            if (el) el.style.display = '';
        });

        switch (layout) {
            case 'vertical':
                // Hide: Header Overlap, Boxed Background
                // Show: Sidebar/Menu, Header Mode
                if (headerOverlapPreset) headerOverlapPreset.style.display = 'none';
                if (boxedBgPreset) boxedBgPreset.style.display = 'none';
                break;

            case 'horizontal':
                // Hide: Sidebar/Menu, Boxed Background, Header Overlap
                if (sidebarMenuPreset) sidebarMenuPreset.style.display = 'none';
                if (boxedBgPreset) boxedBgPreset.style.display = 'none';
                if (headerOverlapPreset) headerOverlapPreset.style.display = 'none';
                break;

            case 'condensed':
                // Hide: Sidebar/Menu, Header Overlap, Boxed Background
                if (sidebarMenuPreset) sidebarMenuPreset.style.display = 'none';
                if (headerOverlapPreset) headerOverlapPreset.style.display = 'none';
                if (boxedBgPreset) boxedBgPreset.style.display = 'none';
                break;

            case 'navbar-overlap':
                // Hide: Sidebar/Menu, Boxed Background
                if (sidebarMenuPreset) sidebarMenuPreset.style.display = 'none';
                if (boxedBgPreset) boxedBgPreset.style.display = 'none';

                // Force Header Mode to Fixed
                const headerModeFixed = this.form.querySelector('input[name="theme-header-sticky"][value="true"]');
                if (headerModeFixed) {
                    headerModeFixed.checked = true;
                    this.themeManager.applySetting('theme-header-sticky', 'true');
                }
                break;

            case 'combo':
                // Combo - show all (already reset above)
                break;

            default:
                // Default - show all
                break;
        }
    }

    /**
     * Handle reset background button click
     */
    handleReset(event) {
        const btn = event.currentTarget;
        const targetName = btn.getAttribute('data-reset-bg');
        const bgInput = this.form.querySelector(`input[name="${targetName}"]`);

        // Determine default value
        let defaultVal = '#ffffff';
        if (targetName === 'theme-bg') defaultVal = '#f4f6fa';
        if (targetName === 'theme-primary') defaultVal = '#206bc4';
        if (targetName === 'theme-header-overlap-bg') defaultVal = '#1e293b';
        if (targetName === 'theme-boxed-bg') defaultVal = '#e2e8f0';

        // Reset input value
        bgInput.value = defaultVal;

        // Reset Pickr
        if (this.pickrInstances[targetName]) {
            this.pickrInstances[targetName].setColor(defaultVal, true); // silent
        }

        // Reset theme manager (removes CSS var and localStorage)
        this.themeManager.resetSetting(targetName);
    }

    /**
     * Handle apply button click (submit to backend)
     */
    async handleApply() {
        // Check dependencies
        if (typeof window.axios === 'undefined') {
            console.error('Axios not loaded');
            if (typeof window.Swal !== 'undefined') {
                window.Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Dependencies not loaded. Please refresh the page.',
                });
            }
            return;
        }

        // Collect form data
        const formData = new FormData();

        // Regular fields
        const themeFields = ['theme', 'layout', 'theme-font', 'theme-base', 'theme-radius', 'theme-card-style'];
        themeFields.forEach(field => {
            const selected = this.form.querySelector(`input[name="${field}"]:checked`) || this.form.querySelector(`select[name="${field}"]`);
            if (selected) {
                formData.append(field.replace(/-/g, '_'), selected.value);
            }
        });

        // Theme primary
        const primaryInput = this.form.querySelector('input[name="theme-primary"]');
        if (primaryInput && primaryInput.value) {
            formData.append('theme_primary', primaryInput.value);
        }

        // Check if dark mode is selected
        const selectedTheme = this.form.querySelector('input[name="theme"]:checked');
        const isDarkMode = selectedTheme && selectedTheme.value === 'dark';

        // Background fields - skip if dark mode (except primary which is already added)
        if (!isDarkMode) {
            const bgFields = ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'];
            bgFields.forEach(bg => {
                const input = this.form.querySelector(`input[name="${bg}"]`);
                if (input) {
                    formData.append(bg.replace(/-/g, '_'), input.value || '');
                }
            });
        } else {
            // Dark mode - send empty strings for all bg colors to clear them
            const bgFields = ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'];
            bgFields.forEach(bg => {
                formData.append(bg.replace(/-/g, '_'), '');
            });
        }

        // Sticky header
        const stickyInput = this.form.querySelector('input[name="theme-header-sticky"]:checked');
        formData.append('theme_header_sticky', stickyInput ? stickyInput.value : 'false');

        // Container width
        const containerWidth = this.form.querySelector('input[name="container-width"]:checked');
        if (containerWidth) {
            formData.append('container_width', containerWidth.value);
        }

        // Auth-specific fields (if in auth mode)
        const authLayout = this.form.querySelector('select[name="auth-layout"]');
        if (authLayout) {
            formData.append('auth_layout', authLayout.value);
        }

        const authFormPosition = this.form.querySelector('input[name="auth-form-position"]:checked');
        if (authFormPosition) {
            formData.append('auth_form_position', authFormPosition.value);
        }

        // Debug log
        console.log('Sending settings to backend:');
        for (let pair of formData.entries()) {
            console.log(`  ${pair[0]}: ${pair[1]}`);
        }

        // Show loading
        if (typeof window.Swal !== 'undefined') {
            window.Swal.fire({
                title: 'Applying...',
                text: 'Writing to .env',
                allowOutsideClick: false,
                didOpen: () => window.Swal.showLoading()
            });
        }

        try {
            const response = await window.axios.post('/sys/layout/apply', formData);

            console.log('Backend response:', response.data);

            if (response.data.success) {
                // Clear localStorage (settings now in .env)
                Object.keys(this.themeManager.defaults).forEach(key => {
                    this.themeManager.removeSetting(key);
                });

                if (typeof window.Swal !== 'undefined') {
                    await window.Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.data.message || 'Settings saved!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }

                window.location.reload();
            }
        } catch (error) {
            console.error('Error saving settings:', error);

            if (typeof window.Swal !== 'undefined') {
                window.Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.response?.data?.message || error.message || 'Failed to apply settings.',
                });
            }
        }
    }
}

export default ThemeSettings;
