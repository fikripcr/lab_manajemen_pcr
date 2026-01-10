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
            'theme-bg': { var: '--tblr-body-bg', attr: 'data-bs-has-theme-bg' },
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
            ...(mode === 'auth' ? { 'auth-layout': 'basic', 'auth-form-position': 'left' } : {})
        };

        // Layout Visibility Config (What to HIDE for each layout)
        // Shows only relevant color presets per layout type
        // NOTE: boxed-bg-preset visibility is controlled by container-width, not layout
        this.LAYOUT_config = {
            'vertical': ['header-overlap-preset'],
            'horizontal': ['header-overlap-preset'],
            'condensed': ['sidebar-menu-preset', 'header-overlap-preset'],
            'navbar-overlap': ['sidebar-menu-preset', 'body-bg-preset', 'header-fixed']
        };

        this.listeners = [];
        this.form = null;
        this.pickrInstances = {};

        // Auth Form Positioning Elements
        this.formColumn = document.querySelector('[data-form-column]');
        this.mediaColumn = document.querySelector('[data-media-column]');
    }

    // ==========================================
    // Core Storage & State Logic
    // ==========================================

    _k(name) { return this.prefix + name; } // Private key helper
    getSetting(name) { return localStorage.getItem(this._k(name)); }
    saveSetting(name, val) { localStorage.setItem(this._k(name), val); }
    removeSetting(name) { localStorage.removeItem(this._k(name)); }

    subscribe(callback) { this.listeners.push(callback); }

    loadTheme() {
        // CRITICAL: Apply theme mode FIRST before any other settings
        // This ensures _isDarkMode() returns correct value for background handling
        const themeMode = this.getSetting('theme') ?? this.defaults['theme'];
        this.applySetting('theme', themeMode, false);

        // Then apply all other settings
        Object.keys(this.defaults).forEach(key => {
            if (key !== 'theme') { // Skip theme as it's already applied
                this.applySetting(key, this.getSetting(key) ?? this.defaults[key], false);
            }
        });

        // Initialize Auth Form Position helper if elements exist
        if (this.mode === 'auth' && (this.formColumn || this.mediaColumn)) {
            const pos = this.getSetting('auth-form-position') || this.defaults['auth-form-position'] || 'left';
            this._applyAuthPosition(pos);
        }
    }

    resetSetting(name) {
        const def = this.defaults[name] || '';
        this.applySetting(name, def);
        this.removeSetting(name);
        return def;
    }

    getAllSettings() {
        return Object.keys(this.defaults).reduce((acc, key) => {
            acc[key] = this.getSetting(key) || this.defaults[key];
            return acc;
        }, {});
    }

    // ==========================================
    // Application Logic
    // ==========================================

    applySetting(name, value, save = true) {
        const root = document.documentElement;

        // 1. Handle Theme Mode Changes (Clear custom BGs when switching to dark)
        if (name === 'theme') {
            root.setAttribute('data-bs-theme', value);

            // Immediately clear custom background colors when switching to dark mode
            if (value === 'dark') {
                this._clearCustomBackgrounds(root);
            }

            if (save) this.saveSetting(name, value);
            this.listeners.forEach(cb => cb(name, value));
            return;
        }

        // 2. Handle Dark Mode Backgrounds (Skip custom BGs in dark mode)
        if (this._isDarkMode() && ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'].includes(name)) {
            if (save) this.saveSetting(name, value);
            return;
        }

        // 3. Handle Mapped Settings (CSS Vars / Data Attrs)
        if (this.themeMap[name]) {
            this._applyMappedSetting(root, name, value);
        }
        // 4. Handle Standard Attributes
        else if (['theme-font', 'theme-base'].includes(name)) {
            root.setAttribute('data-bs-' + name, value);
        }
        // 5. Handle Special Cases
        else {
            this._applySpecialCases(name, value);
        }

        if (save) this.saveSetting(name, value);
        this.listeners.forEach(cb => cb(name, value));
    }

    _clearCustomBackgrounds(root) {
        // Remove CSS custom properties
        root.style.removeProperty('--tblr-body-bg');
        root.style.removeProperty('--tblr-sidebar-bg');
        root.style.removeProperty('--tblr-sidebar-text'); // Remove contrast override
        root.style.removeProperty('--tblr-header-top-bg');
        root.style.removeProperty('--tblr-header-top-text'); // Remove contrast override
        root.style.removeProperty('--tblr-header-overlap-bg');
        root.style.removeProperty('--tblr-boxed-bg');
        root.style.removeProperty('--tblr-body-text');

        // Remove data attributes
        root.removeAttribute('data-bs-has-theme-bg');
        root.removeAttribute('data-bs-has-sidebar-bg');
        root.removeAttribute('data-bs-has-header-top-bg');
        root.removeAttribute('data-bs-has-header-overlap-bg');
    }

    _isDarkMode() {
        return document.documentElement.getAttribute('data-bs-theme') === 'dark';
    }

    _applyMappedSetting(root, name, value) {
        const rule = this.themeMap[name];

        // Attribute Handling
        if (name === 'theme-card-style' && value === 'default') {
            root.removeAttribute('data-bs-card-style');
        } else if (rule.attr) {
            root.setAttribute(rule.attr, value === true || value === 'true' ? '' : value);
        }

        // CSS Variable Handling
        if (rule.var) {
            if (name === 'theme-radius') {
                this._applyRadius(root, rule.var, value);
            } else if (value) {
                root.style.setProperty(rule.var, value);

                // Auto-Contrast: Update text color if sidebar background changes
                if (name === 'theme-sidebar-bg') {
                    this._updateSidebarContrast(root, value);
                }
                // Auto-Contrast: Update text color if header top background changes
                if (name === 'theme-header-top-bg') {
                    this._updateHeaderTopContrast(root, value);
                }
                // Auto-Contrast: Update text color if body background changes
                if (name === 'theme-bg') {
                    this._updateBodyContrast(root, value);
                }
            } else {
                root.style.removeProperty(rule.var);
                if (name === 'theme-sidebar-bg') {
                    root.style.removeProperty('--tblr-sidebar-text');
                }
                if (name === 'theme-header-top-bg') {
                    root.style.removeProperty('--tblr-header-top-text');
                }
                if (name === 'theme-bg') {
                    root.style.removeProperty('--tblr-body-text');
                }
            }
        }
    }

    _applyRadius(root, varName, value) {
        const val = parseFloat(value);
        if (!isNaN(val)) {
            root.style.setProperty(varName, val + 'rem');
            root.style.setProperty(varName + '-sm', (val * 0.75) + 'rem');
            root.style.setProperty(varName + '-lg', (val * 1.25) + 'rem');
            root.style.setProperty(varName + '-pill', '100rem');
        } else {
            [varName, varName + '-sm', varName + '-lg', varName + '-pill'].forEach(v => root.style.removeProperty(v));
        }
    }

    _applySpecialCases(name, value) {
        if (this.mode !== 'sys') return;

        if (name === 'container-width') {
            document.body.setAttribute('data-container-width', value);
            value === 'boxed' ? document.body.classList.add('layout-boxed') : document.body.classList.remove('layout-boxed');
        } else if (name === 'theme-header-sticky') {
            this._applySticky(value === 'true' || value === true);
        } else if (name === 'auth-form-position') {
            this._applyAuthPosition(value);
        }
    }

    _applySticky(isSticky) {
        const wrapper = document.getElementById('header-sticky-wrapper');
        const topHeader = wrapper?.querySelector('header.navbar');
        if (!wrapper || !topHeader) return;

        const layout = this.getSetting('layout') || 'vertical';
        wrapper.classList.remove('sticky-top');
        topHeader.classList.remove('sticky-top');

        if (isSticky) {
            layout === 'navbar-overlap' ? topHeader.classList.add('sticky-top') : wrapper.classList.add('sticky-top');
        }
    }

    _applyAuthPosition(position) {
        if (!this.formColumn || !this.mediaColumn) return;

        if (position === 'right') {
            this.formColumn.style.order = '2';
            this.mediaColumn.style.order = '1';
        } else {
            this.formColumn.style.order = '';
            this.mediaColumn.style.order = '';
        }
    }

    // Calculate luminance to determine if background is dark or light
    _getLuminance(color) {
        const rgb = color.match(/\d+/g);
        if (!rgb) return 1; // Default to light if invalid
        const r = parseInt(rgb[0]), g = parseInt(rgb[1]), b = parseInt(rgb[2]);
        // Relative luminance formula (per W3C)
        return (0.299 * r + 0.587 * g + 0.114 * b) / 255;
    }

    _updateSidebarContrast(root, color) {
        // Threshold: < 0.5 is dark -> white text, >= 0.5 is light -> dark text
        const isDark = this._getLuminance(color) < 0.6; // Slightly higher threshold for better readability
        root.style.setProperty('--tblr-sidebar-text', isDark ? '#ffffff' : '#1e293b');
        // We can also adjust muted text if needed
        root.style.setProperty('--tblr-sidebar-text-muted', isDark ? 'rgba(255, 255, 255, 0.7)' : '#6c757d');
    }

    _updateHeaderTopContrast(root, color) {
        const isDark = this._getLuminance(color) < 0.6;
        root.style.setProperty('--tblr-header-top-text', isDark ? '#ffffff' : '#1e293b');
        root.style.setProperty('--tblr-header-top-text-muted', isDark ? 'rgba(255, 255, 255, 0.7)' : '#6c757d');
    }

    _updateBodyContrast(root, color) {
        const isDark = this._getLuminance(color) < 0.6;
        root.style.setProperty('--tblr-body-text', isDark ? '#ffffff' : '#1e293b');
        // Optional: muted text for body if needed, currently focusing on headers
    }

    // ==========================================
    // UI Logic (Settings Panel)
    // ==========================================

    initSettingsPanel() {
        this.form = document.getElementById('offcanvasSettings');
        if (!this.form) return;

        this.initPickr();
        // setTimeout(() => this.syncFormWithStorage(), 100);
        this.bindEvents();
    }

    async initPickr() {
        // Lazy load Pickr if not already available
        if (typeof window.Pickr === 'undefined') {
            try {
                // Determine if we are in admin or Guest/Auth context to resolve path correctly?
                // Actually Vite handles efficient bundling of node_modules.
                const module = await import('@simonwep/pickr');
                window.Pickr = module.default;
                await import('@simonwep/pickr/dist/themes/nano.min.css');
            } catch (error) {
                console.error('Failed to load Pickr:', error);
                return;
            }
        }

        document.querySelectorAll('.color-picker-component').forEach(el => {
            const target = el.getAttribute('data-target');
            const hiddenInput = this.form.querySelector(`input[name="${target}"]`);
            const defaultVal = el.getAttribute('data-default');
            const initialVal = hiddenInput ? hiddenInput.value : defaultVal;

            const pickr = window.Pickr.create({
                el, theme: 'nano', default: initialVal,
                swatches: ['#f4f6fa', '#ffffff', '#206bc4', '#a55eea', '#d63939', '#fd7e14', '#2fb344'],
                components: { preview: true, opacity: true, hue: true, interaction: { hex: true, rgba: true, input: true, save: false } }
            });

            this.pickrInstances[target] = pickr;

            pickr.on('change', (color, source, inst) => {
                const rgba = color.toRGBA().toString(0);
                inst.applyColor(true);
                if (hiddenInput) {
                    hiddenInput.value = rgba;
                    this.applySetting(target, rgba);
                }
            });
            pickr.on('save', () => pickr.hide());
        });
    }

    syncFormWithStorage() {
        const settings = this.getAllSettings();
        Object.keys(settings).forEach(key => {
            const inputs = this.form.querySelectorAll(`[name="${key}"]`);
            if (!inputs.length) return;

            const value = settings[key];
            const input = inputs[0];

            if (key.includes('-bg') || key === 'theme-primary') {
                input.value = value;
                const pickr = this.pickrInstances[key];
                if (pickr) {
                    pickr.setColor(value || pickr._options?.default || '#ffffff');
                }
            } else if (input.tagName === 'SELECT') {
                input.value = value;
            } else { // Radio
                inputs.forEach(r => {
                    r.checked = (r.value === value);
                    if (r.checked) r.dispatchEvent(new Event('change', { bubbles: true }));
                });
            }
        });

        // Apply Layout & Theme Visibility
        const layout = this.form.querySelector('select[name="layout"]')?.value;
        if (layout) this.handleLayoutChange(layout);

        const containerWidth = this.form.querySelector('input[name="container-width"]:checked')?.value;
        if (containerWidth) this.handleContainerWidthChange(containerWidth);

        const theme = this.form.querySelector('input[name="theme"]:checked')?.value;
        if (theme) this.handleThemeModeChange(theme);
    }

    bindEvents() {
        this.form.addEventListener('change', (e) => {
            const { name, value } = e.target;
            if (!name) return;

            this.applySetting(name, value);
            if (name === 'layout') this.handleLayoutChange(value);
            if (name === 'container-width') this.handleContainerWidthChange(value);
            if (name === 'theme') this.handleThemeModeChange(value);
        });

        document.getElementById('apply-settings')?.addEventListener('click', () => this.handleApply());

        this.form.querySelectorAll('button[data-reset-bg]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const target = e.currentTarget.getAttribute('data-reset-bg');
                const defaults = { 'theme-bg': '#f4f6fa', 'theme-primary': '#206bc4', 'theme-header-overlap-bg': '#1e293b', 'theme-boxed-bg': '#e2e8f0' };
                const val = defaults[target] || '#ffffff';

                this.form.querySelector(`input[name="${target}"]`).value = val;
                this.pickrInstances[target]?.setColor(val, true);
                this.resetSetting(target);
            });
        });
    }

    handleLayoutChange(layout) {
        // Reset all to visible first (except boxed-bg which depends on container-width)
        const basicElements = ['sidebar-menu-preset', 'header-overlap-preset', 'header-mode-section'];
        basicElements.forEach(id => { const el = document.getElementById(id); if (el) el.style.display = ''; });

        // Ensure header mode options are visible
        ['header-scrollable', 'header-fixed', 'header-hidden'].forEach(id => { const el = document.getElementById(id); if (el) el.style.display = ''; });

        // Hide specific elements based on config
        const toHide = this.LAYOUT_config[layout] || [];
        toHide.forEach(id => { const el = document.getElementById(id); if (el) el.style.display = 'none'; });
    }

    handleContainerWidthChange(width) {
        // Show/hide boxed background preset based on container width
        const boxedBgPreset = document.getElementById('boxed-bg-preset');
        if (boxedBgPreset) {
            boxedBgPreset.style.display = width === 'boxed' ? '' : 'none';
        }
    }

    handleThemeModeChange(theme) {
        const presets = ['body-bg-preset', 'sidebar-menu-preset', 'header-top-preset', 'header-overlap-preset', 'boxed-bg-preset'];
        const isDark = theme === 'dark';

        presets.forEach(id => { const el = document.getElementById(id); if (el) el.style.display = isDark ? 'none' : ''; });

        if (isDark) {
            // Save current values to session
            ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'].forEach(key => {
                const val = this.form.querySelector(`input[name="${key}"]`)?.value;
                if (val) sessionStorage.setItem(`saved_${key}`, val);
            });
        } else {
            // Restore (Light mode) and immediately re-apply custom backgrounds
            ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'].forEach(key => {
                const saved = sessionStorage.getItem(`saved_${key}`);
                const input = this.form.querySelector(`input[name="${key}"]`);
                const val = saved || (input ? input.value : '') || this.getSetting(key);

                // Re-apply immediately (not just save)
                if (val) {
                    this.applySetting(key, val, false);
                }
            });

            const layout = this.form.querySelector('select[name="layout"]')?.value;
            if (layout) this.handleLayoutChange(layout);
        }
    }

    async handleApply() {
        if (!window.axios) return console.error('Axios missing');

        const loader = window.Swal ? window.Swal.fire({ title: 'Applying...', didOpen: () => window.Swal.showLoading() }) : null;
        const formData = new FormData();
        const getInput = (name) => this.form.querySelector(`input[name="${name}"]:checked`)?.value || this.form.querySelector(`select[name="${name}"]`)?.value;
        const append = (k, v) => v && formData.append(k.replace(/-/g, '_'), v);

        // Standard fields
        ['theme', 'layout', 'theme-font', 'theme-base', 'theme-radius', 'theme-card-style', 'auth-layout', 'auth-form-position'].forEach(f => append(f, getInput(f)));
        append('theme_primary', this.form.querySelector('input[name="theme-primary"]')?.value);
        append('theme_header_sticky', getInput('theme-header-sticky') || 'false');
        append('container_width', getInput('container-width'));

        // Backgrounds (only in light mode)
        if (getInput('theme') !== 'dark') {
            ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'].forEach(f => {
                const inputEl = this.form.querySelector(`input[name="${f}"]`);
                if (inputEl) {
                    append(f, inputEl.value || '');
                }
            });
        }

        try {
            const res = await window.axios.post('/sys/layout/apply', formData);
            if (res.data.success) {
                // Don't clear localStorage - we need it to persist settings across reloads
                // The .env values are now the source of truth, but localStorage provides instant preview
                if (window.Swal) {
                    loader?.close();
                    window.Swal.fire({ icon: 'success', title: 'Settings saved!', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
                }
                setTimeout(() => window.location.reload(), 500);
            }
        } catch (e) {
            console.error(e);
            if (loader) loader.close();
            if (window.Swal) window.Swal.fire({ icon: 'error', title: 'Error', text: e.message, toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
        }
    }
}

export default ThemeManager;
