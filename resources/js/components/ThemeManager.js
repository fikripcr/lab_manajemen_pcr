/**
 * ThemeManager - Core Theme System
 * Manages Tabler theme settings and the Settings Panel UI (shared between sys & auth sections)
 */
class ThemeManager {
    constructor(mode = 'sys') {
        this.mode = mode; // 'sys' or 'auth'
        this.prefix = 'tabler-';

        // Configuration map for CSS vars and data attributes
        this.themeMap = {
            'theme-bg': { var: '--tblr-body-bg' },
            'theme-sidebar-bg': { var: '--tblr-sidebar-bg', attr: 'data-bs-has-sidebar-bg' },
            'theme-header-top-bg': { var: '--tblr-header-top-bg', attr: 'data-bs-has-header-top-bg' },
            'theme-header-overlap-bg': { var: '--tblr-header-overlap-bg', attr: 'data-bs-has-header-overlap-bg' },
            'theme-boxed-bg': { var: '--tblr-boxed-bg' },
            'theme-primary': { var: '--tblr-primary' },
            'theme-card-style': { attr: 'data-bs-card-style' },
            'theme-radius': { var: '--tblr-border-radius' },
        };

        // Default values
        this.defaults = {
            'theme': 'light',
            'theme-font': 'inter',
            'theme-base': 'gray',
            'theme-radius': '1',
            'theme-primary': '#206bc4',
            'theme-bg': '',
            'theme-card-style': 'flat',
            'theme-sidebar-bg': '',
            'theme-header-top-bg': '',
            'theme-header-overlap-bg': '',
            'theme-header-sticky': 'false',
            'theme-boxed-bg': '',
            'container-width': 'standard',
        };

        // Auth-specific defaults
        if (mode === 'auth') {
            this.defaults['auth-layout'] = 'basic';
            this.defaults['auth-form-position'] = 'left';
        }

        // Listeners for setting changes
        this.listeners = [];

        // UI Components
        this.form = null;
        this.applyButton = null;
        this.pickrInstances = {};
    }

    /**
     * Subscribe to setting changes
     * @param {Function} callback - Function(name, value)
     */
    subscribe(callback) {
        this.listeners.push(callback);
    }

    /**
     * Load and apply theme from localStorage
     */
    loadTheme() {
        // Apply each setting from localStorage or defaults
        Object.keys(this.defaults).forEach(key => {
            const storedValue = this.getSetting(key);
            const value = storedValue !== null ? storedValue : this.defaults[key];
            this.applySetting(key, value, false); // false = don't save back to localStorage
        });
    }

    /**
     * Get setting from localStorage
     */
    getSetting(name) {
        const key = this.prefix + name;
        const value = localStorage.getItem(key);
        return value;
    }

    /**
     * Save setting to localStorage
     */
    saveSetting(name, value) {
        const key = this.prefix + name;
        localStorage.setItem(key, value);
    }

    /**
     * Remove setting from localStorage
     */
    removeSetting(name) {
        const key = this.prefix + name;
        localStorage.removeItem(key);
    }

    /**
     * Apply a single theme setting
     * @param {string} name - Setting name (e.g., 'theme', 'theme-primary')
     * @param {string} value - Setting value
     * @param {boolean} save - Whether to save to localStorage (default: true)
     */
    applySetting(name, value, save = true) {
        const root = document.documentElement;

        // IMPORTANT: Skip background colors in dark mode (except primary)
        const bgColorSettings = ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'];
        const currentTheme = root.getAttribute('data-bs-theme');
        const isDarkMode = currentTheme === 'dark';

        if (isDarkMode && bgColorSettings.includes(name)) {
            // In dark mode, don't apply any custom background colors
            // Just save to localStorage if requested, but don't apply to DOM
            if (save) {
                this.saveSetting(name, value);
            }
            return; // Skip DOM application
        }

        // Handle mapped settings (CSS vars + data attributes)
        if (this.themeMap[name]) {
            const rule = this.themeMap[name];

            // Special case: card style 'default' removes attribute
            if (name === 'theme-card-style' && value === 'default') {
                root.removeAttribute('data-bs-card-style');
            }
            // Set data attribute
            else if (rule.attr) {
                root.setAttribute(rule.attr, value === true || value === 'true' ? '' : value);
            }

            // Set CSS variable
            if (rule.var) {
                if (name === 'theme-radius') {
                    // Radius needs 'rem' suffix and affects sm/lg variants
                    const val = parseFloat(value);
                    if (!isNaN(val)) {
                        root.style.setProperty(rule.var, val + 'rem');
                        root.style.setProperty(rule.var + '-sm', (val * 0.75) + 'rem');
                        root.style.setProperty(rule.var + '-lg', (val * 1.25) + 'rem');
                        root.style.setProperty(rule.var + '-pill', '100rem'); // Ensure pills stay round
                    }
                } else if (value) {
                    root.style.setProperty(rule.var, value);
                } else {
                    // Empty value = remove CSS var and its variants (for radius)
                    root.style.removeProperty(rule.var);
                    if (name === 'theme-radius') {
                        root.style.removeProperty(rule.var + '-sm');
                        root.style.removeProperty(rule.var + '-lg');
                        root.style.removeProperty(rule.var + '-pill');
                    }
                }
            }
        }
        // Handle standard data-bs-* attributes
        else if (['theme', 'theme-font', 'theme-base'].includes(name)) {
            root.setAttribute('data-bs-' + name, value);
        }
        // Handle container width
        else if (name === 'container-width' && this.mode === 'sys') {
            document.body.setAttribute('data-container-width', value);
            if (value === 'boxed') {
                document.body.classList.add('layout-boxed');
            } else {
                document.body.classList.remove('layout-boxed');
            }
        }
        // Handle sticky header
        else if (name === 'theme-header-sticky' && this.mode === 'sys') {
            this.applySticky(value === 'true' || value === true);
        }

        // Save to localStorage if requested
        if (save) {
            this.saveSetting(name, value);
        }

        // Notify listeners
        this.listeners.forEach(callback => callback(name, value));
    }

    /**
     * Apply sticky header logic
     */
    applySticky(isSticky) {
        const wrapper = document.getElementById('header-sticky-wrapper');
        const topHeader = wrapper ? wrapper.querySelector('header.navbar') : null;

        if (!wrapper || !topHeader) return;

        // Get current layout
        const layout = this.getSetting('layout') || 'vertical';

        // Reset
        wrapper.classList.remove('sticky-top');
        topHeader.classList.remove('sticky-top');

        if (isSticky) {
            if (layout === 'navbar-overlap') {
                topHeader.classList.add('sticky-top');
            } else {
                wrapper.classList.add('sticky-top');
            }
        }
    }

    /**
     * Reset a setting to its default value
     * @param {string} name - Setting name
     */
    resetSetting(name) {
        const defaultValue = this.defaults[name] || '';
        this.applySetting(name, defaultValue);
        this.removeSetting(name);
        return defaultValue;
    }

    /**
     * Get all current settings (from localStorage or defaults)
     */
    getAllSettings() {
        const settings = {};
        Object.keys(this.defaults).forEach(key => {
            settings[key] = this.getSetting(key) || this.defaults[key];
        });
        return settings;
    }

    // ==========================================
    // UI Settings Panel Methods (Formerly ThemeSettings.js)
    // ==========================================

    /**
     * Initialize settings panel UI
     */
    initSettingsPanel() {
        this.form = document.getElementById('offcanvasSettings');
        this.applyButton = document.getElementById('apply-settings');

        if (!this.form) {
            // Panel might not exist in some views
            return;
        }

        this.initPickr();

        // Delay sync to ensure Tabler CSS is ready (Fix for visual state sync)
        setTimeout(() => {
            this.syncFormWithStorage();
        }, 100);

        this.bindEvents();
    }

    /**
     * Initialize Pickr color pickers
     */
    initPickr() {
        if (typeof window.Pickr === 'undefined') {
            console.error('ThemeManager: Pickr not loaded');
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
                    this.applySetting(targetName, rgbaColor);
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
        const allSettings = this.getAllSettings();

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
                        const shouldCheck = input.value === value;
                        input.checked = shouldCheck;

                        // Dispatch change event to force Tabler CSS update (Fix for visual state sync)
                        if (shouldCheck) {
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                        }
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
        this.applySetting(name, value);

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

            // Save current values to sessionStorage for restoration
            const bgSettings = ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'];
            bgSettings.forEach(setting => {
                const input = this.form.querySelector(`input[name="${setting}"]`);
                if (input && input.value) {
                    sessionStorage.setItem(`saved_${setting}`, input.value);
                }
            });

            // applySetting will now automatically skip bg colors in dark mode
        } else {
            // Light mode - show all color presets, but respect layout settings
            colorPresets.forEach(el => {
                if (el) el.style.display = '';
            });

            // Restore custom colors from sessionStorage (instant) or form inputs
            const bgSettings = ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'];
            bgSettings.forEach(setting => {
                const savedValue = sessionStorage.getItem(`saved_${setting}`);
                const input = this.form.querySelector(`input[name="${setting}"]`);
                const valueToApply = savedValue || (input ? input.value : '');

                if (valueToApply) {
                    this.applySetting(setting, valueToApply);
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
        const headerScrollable = document.getElementById('header-scrollable');
        const headerFixed = document.getElementById('header-fixed');
        const headerHidden = document.getElementById('header-hidden');

        // Reset all to visible first
        [sidebarMenuPreset, headerOverlapPreset, boxedBgPreset, headerModeSection].forEach(el => {
            if (el) el.style.display = '';
        });

        // Ensure header mode options are visible by default
        if (headerScrollable) headerScrollable.style.display = '';
        if (headerFixed) headerFixed.style.display = '';
        if (headerHidden) headerHidden.style.display = '';

        switch (layout) {
            case 'vertical':
                // Vertical is now the "Combo" layout (Sidebar + Header + etc)
                // Show all (do nothing, let reset handle it)
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
                if (headerFixed) headerFixed.style.display = 'none';
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
        this.resetSetting(targetName);
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

        // Background fields - ONLY send in light mode to preserve .env values
        if (!isDarkMode) {
            const bgFields = ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'];
            bgFields.forEach(bg => {
                const input = this.form.querySelector(`input[name="${bg}"]`);
                if (input) {
                    formData.append(bg.replace(/-/g, '_'), input.value || '');
                }
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

            if (response.data.success) {
                // Clear localStorage (settings now in .env)
                Object.keys(this.defaults).forEach(key => {
                    this.removeSetting(key);
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

// Export for ES6 modules
export default ThemeManager;
