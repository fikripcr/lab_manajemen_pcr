/**
 * ThemeTabler - Simplified Theme System
 * Handles live preview and form submission only
 * Server is the single source of truth (no localStorage/cookies)
 */
class ThemeTabler {
    constructor(mode = 'sys') {
        this.mode = mode; // 'sys' or 'auth'

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

        // Layout Visibility Config
        this.LAYOUT_config = {
            'vertical': ['header-overlap-preset'],
            'horizontal': ['header-overlap-preset'],
            'condensed': ['sidebar-menu-preset']
        };

        this.listeners = [];
        this.form = null;
        this.pickrInstances = {};

        // Auth Form Positioning Elements
        this.formColumn = document.querySelector('[data-form-column]');
        this.mediaColumn = document.querySelector('[data-media-column]');

        // Font Stacks
        this.fontStacks = {
            'inter': "'Inter', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
            'roboto': "'Roboto', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Helvetica Neue, sans-serif",
            'poppins': "'Poppins', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
            'public-sans': "'Public Sans', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
            'nunito': "'Nunito', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif"
        };
    }

    // ==========================================
    // Live Preview Logic
    // ==========================================

    subscribe(callback) { this.listeners.push(callback); }

    applySetting(name, value, save = false) {
        const root = document.documentElement;

        // 1. Handle Theme Mode Changes
        if (name === 'theme') {
            root.setAttribute('data-bs-theme', value);

            // Clear custom backgrounds when switching to dark
            if (value === 'dark') {
                this._clearCustomBackgrounds(root);
            }

            this.listeners.forEach(cb => cb(name, value));
            return;
        }

        // 2. Handle Dark Mode Backgrounds (Skip in dark mode)
        if (this._isDarkMode() && ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'].includes(name)) {
            return;
        }

        // 3. Handle Mapped Settings
        if (this.themeMap[name]) {
            this._applyMappedSetting(root, name, value);
        }
        // 4. Handle Font
        else if (name === 'theme-font') {
            root.setAttribute('data-bs-theme-font', value);
            if (this.fontStacks[value]) {
                root.style.setProperty('--tblr-font-sans-serif', this.fontStacks[value]);
            }
        }
        // 5. Handle Standard Attributes
        else if (['theme-base'].includes(name)) {
            root.setAttribute('data-bs-' + name, value);
        }
        // 6. Handle Special Cases
        else {
            this._applySpecialCases(name, value);
        }

        this.listeners.forEach(cb => cb(name, value));
    }

    _clearCustomBackgrounds(root) {
        root.style.removeProperty('--tblr-body-bg');
        root.style.removeProperty('--tblr-sidebar-bg');
        root.style.removeProperty('--tblr-sidebar-text');
        root.style.removeProperty('--tblr-header-top-bg');
        root.style.removeProperty('--tblr-header-top-text');
        root.style.removeProperty('--tblr-header-overlap-bg');
        root.style.removeProperty('--tblr-boxed-bg');
        root.style.removeProperty('--tblr-body-text');

        root.removeAttribute('data-bs-has-theme-bg');
        root.removeAttribute('data-bs-has-sidebar-bg');
        root.removeAttribute('data-bs-has-header-top-bg');
        const layout = this.form?.querySelector('select[name="layout"]')?.value || document.body.className.match(/layout-(\w+)/)?.[1];

        if (layout !== 'condensed') {
            root.removeAttribute('data-bs-has-header-overlap-bg');
            root.style.removeProperty('--tblr-header-overlap-bg');
        } else {
            // Ensure it's there for condensed depth
            root.setAttribute('data-bs-has-header-overlap-bg', '');
        }
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

                // Auto-Contrast
                if (name === 'theme-sidebar-bg') {
                    this._updateSidebarContrast(root, value);
                }
                if (name === 'theme-header-top-bg') {
                    this._updateHeaderTopContrast(root, value);
                }
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

    _applySticky(value) {
        const wrapper = document.getElementById('header-sticky-wrapper');
        const topHeader = wrapper?.querySelector('header.navbar');
        if (!wrapper || !topHeader) return;

        const isSticky = value === 'true' || value === true;
        const isHidden = value === 'hidden';

        wrapper.classList.remove('sticky-top');
        topHeader.classList.remove('sticky-top');

        // Handle Hidden state in live preview
        if (isHidden) {
            wrapper.style.setProperty('display', 'none', 'important');
        } else {
            wrapper.style.removeProperty('display');
        }

        if (isSticky) {
            // Always apply to wrapper in unified condensed/vertical/horizontal layouts
            wrapper.classList.add('sticky-top');
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

    _getLuminance(color) {
        let r, g, b;

        if (color.startsWith('#')) {
            const hex = color.slice(1);
            if (hex.length === 3) {
                r = parseInt(hex[0] + hex[0], 16);
                g = parseInt(hex[1] + hex[1], 16);
                b = parseInt(hex[2] + hex[2], 16);
            } else if (hex.length === 6) {
                r = parseInt(hex.substring(0, 2), 16);
                g = parseInt(hex.substring(2, 4), 16);
                b = parseInt(hex.substring(4, 6), 16);
            } else {
                return 1;
            }
        } else if (color.startsWith('rgb')) {
            const match = color.match(/\d+/g);
            if (!match || match.length < 3) return 1;
            r = parseInt(match[0]);
            g = parseInt(match[1]);
            b = parseInt(match[2]);
        } else {
            return 1;
        }

        return (0.299 * r + 0.587 * g + 0.114 * b) / 255;
    }

    _updateSidebarContrast(root, color) {
        const isDark = this._getLuminance(color) < 0.6;
        root.style.setProperty('--tblr-sidebar-text', isDark ? '#ffffff' : '#1e293b');
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
    }

    // ==========================================
    // UI Logic (Settings Panel)
    // ==========================================

    initSettingsPanel() {
        this.form = document.getElementById('offcanvasSettings');
        if (!this.form) return;

        this.initPickr();
        this.bindEvents();

        // Trigger initial visibility state based on current values
        const layout = this.form.querySelector('select[name="layout"]')?.value;
        const width = this.form.querySelector('select[name="container-width"]')?.value;
        const theme = document.documentElement.getAttribute('data-bs-theme') || 'light';

        if (layout) this.handleLayoutChange(layout);
        if (width) this.handleContainerWidthChange(width);
        this.handleThemeModeChange(theme, true);
    }

    async initPickr() {
        if (typeof window.Pickr === 'undefined') {
            try {
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
                swatches: ['#f4f6fa', '#ffffff', '#206bc4', '#d63939', '#fd7e14', '#2fb344', '#1f2937'],
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
                this.applySetting(target, ''); // Reset
            });
        });
    }

    handleLayoutChange(layout) {
        const basicElements = ['sidebar-menu-preset', 'header-overlap-preset', 'header-mode-section'];
        basicElements.forEach(id => { const el = document.getElementById(id); if (el) el.style.display = ''; });

        ['header-scrollable', 'header-fixed', 'header-hidden'].forEach(id => { const el = document.getElementById(id); if (el) el.style.display = ''; });

        const toHide = this.LAYOUT_config[layout] || [];
        toHide.forEach(id => { const el = document.getElementById(id); if (el) el.style.display = 'none'; });

        // Update body classes for live preview
        document.body.classList.remove('layout-vertical', 'layout-horizontal', 'layout-condensed');
        document.body.classList.add(`layout-${layout}`);

        // Sync Sidebar visibility
        const sidebar = document.querySelector('aside.navbar-vertical');
        if (sidebar) {
            sidebar.style.display = (layout === 'horizontal' || layout === 'condensed') ? 'none' : '';
        }

        // Sync header class and root attribute
        const header = document.querySelector('header.navbar');
        const root = document.documentElement;
        if (header) {
            if (layout === 'condensed') {
                header.classList.add('navbar-overlap');
                // Condensed typically implies dark header text for contrast
                header.classList.add('navbar-dark', 'text-white');

                // Ensure attribute is set to trigger CSS visibility
                root.setAttribute('data-bs-has-header-overlap-bg', '');
            } else {
                header.classList.remove('navbar-overlap');
                // Non-condensed layouts (Vertical/Horizontal) default to light header
                header.classList.remove('navbar-dark', 'text-white');

                // Only remove attribute if no custom color is set
                const customColor = this.form.querySelector('input[name="theme-header-overlap-bg"]')?.value;
                if (!customColor || customColor === '#1e293b') {
                    root.removeAttribute('data-bs-has-header-overlap-bg');
                }
            }
        }
    }

    handleContainerWidthChange(width) {
        const boxedBgPreset = document.getElementById('boxed-bg-preset');
        if (boxedBgPreset) {
            boxedBgPreset.style.display = width === 'boxed' ? '' : 'none';
        }
    }

    handleThemeModeChange(theme, isInit = false) {
        const presets = ['body-bg-preset', 'sidebar-menu-preset', 'header-top-preset', 'header-overlap-preset', 'boxed-bg-preset'];
        const isDark = theme === 'dark';

        presets.forEach(id => { const el = document.getElementById(id); if (el) el.style.display = isDark ? 'none' : ''; });

        if (isDark) {
            ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'].forEach(key => {
                const val = this.form.querySelector(`input[name="${key}"]`)?.value;
                if (val) sessionStorage.setItem(`saved_${key}`, val);
            });
        } else {
            ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'].forEach(key => {
                const saved = isInit ? null : sessionStorage.getItem(`saved_${key}`);
                const input = this.form.querySelector(`input[name="${key}"]`);
                const val = saved || (input ? input.value : '');

                if (val) {
                    this.applySetting(key, val, false);
                }
            });
        }
    }

    async handleApply() {
        if (!window.axios) return console.error('Axios missing');

        const btn = this.form.querySelector('.btn-primary');
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Saving...';
        btn.disabled = true;

        try {
            const formData = new FormData(this.form);
            const data = Object.fromEntries(formData.entries());

            // Add mode parameter
            data._mode = this.mode;

            const response = await axios.post('/theme/save', data);

            if (response.data.success) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Theme settings saved successfully'
                });

                // Reload to sync with server
                setTimeout(() => window.location.reload(), 500);
            }
        } catch (error) {
            console.error('Save failed:', error);
            Swal.fire('Error', 'Failed to save settings', 'error');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
}

export default ThemeTabler;
