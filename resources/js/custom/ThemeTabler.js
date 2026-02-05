/**
 * ThemeTabler - Simplified Theme System
 * Handles live preview and form submission.
 * Uses a "Unified State" pattern: One refresh() function updates EVERYTHING.
 * Server is the SINGLE source of truth (No sessionStorage/localStorage).
 */
class ThemeTabler {
    constructor(mode = 'sys') {
        this.mode = mode; // 'sys' or 'auth'
        this.form = null;
        this.pickrInstances = {};

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

        // Font Stacks
        this.fontStacks = {
            'inter': "'Inter', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
            'roboto': "'Roboto', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Helvetica Neue, sans-serif",
            'poppins': "'Poppins', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
            'public-sans': "'Public Sans', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
            'nunito': "'Nunito', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif"
        };
    }

    initSettingsPanel() {
        this.form = document.getElementById('offcanvasSettings');
        if (!this.form) return;

        this.initPickr();
        this.bindEvents();

        // Initial Refresh to sync UI with Server Data
        this.refresh();
    }

    // ==========================================
    // Core Logic: Unified Refresh
    // ==========================================

    /**
     * Reads current form state and updates ALL UI (Visibility, CSS, Classes)
     */
    refresh() {
        if (!this.form) return;

        // 1. Read State from DOM Inputs
        // Helper to get value from Select OR Radio
        const getVal = (name) => {
            const radio = this.form.querySelector(`input[name="${name}"]:checked`);
            if (radio) return radio.value;
            const select = this.form.querySelector(`select[name="${name}"]`);
            return select ? select.value : null;
        };

        const state = {
            layout: getVal('layout') || 'vertical',
            width: getVal('container-width') || 'standard',
            theme: document.documentElement.getAttribute('data-bs-theme') || 'light',
            font: getVal('theme-font'),
            base: getVal('theme-base'),
            sticky: getVal('theme-header-sticky')
        };

        // 2. Update Structural Classes (Body & Header)
        this._updateStructure(state);

        // 3. Update Settings Panel Visibility
        this._updateVisibility(state);

        // 4. Update CSS Variables & Attributes (Live Preview)
        this._updateStyles(state);
    }

    _updateStructure(state) {
        const root = document.documentElement;
        const body = document.body;
        const header = document.querySelector('header.navbar');
        const sidebar = document.querySelector('aside.navbar-vertical');

        // Layout Classes
        body.classList.remove('layout-vertical', 'layout-horizontal', 'layout-condensed', 'layout-boxed');
        body.classList.add(`layout-${state.layout}`);

        if (state.width === 'boxed') {
            body.classList.add('layout-boxed');
            body.setAttribute('data-container-width', 'boxed');
        } else {
            body.setAttribute('data-container-width', state.width);
        }

        // DYNAMIC CONTAINER WIDTH UPDATE (Live Preview Fix)
        const targetClass = state.width === 'fluid' ? 'container-fluid' : 'container-xl';
        const removeClass = state.width === 'fluid' ? 'container-xl' : 'container-fluid';

        // 1. Page Content (Wrap in page-wrapper usually)
        const pageContainers = document.querySelectorAll('.page-wrapper .container-xl, .page-wrapper .container-fluid');
        pageContainers.forEach(el => {
            el.classList.remove(removeClass);
            el.classList.add(targetClass);
        });

        // 2. Headers (Navbar)
        const headers = document.querySelectorAll('header.navbar');
        headers.forEach(header => {
            // Find inner container regardless of current class
            const container = header.querySelector('.container-xl, .container-fluid');
            if (container) {
                container.classList.remove(removeClass);
                container.classList.add(targetClass);
            }
        });

        // 3. Vertical Mobile Menu (Functionality specific)
        // Check for the container inside the separate collapse block if exists
        const mobileMenuContainer = document.querySelector('#navbar-menu > .d-lg-none > .container-xl, #navbar-menu > .d-lg-none > .container-fluid');
        if (mobileMenuContainer) {
            mobileMenuContainer.classList.remove(removeClass);
            mobileMenuContainer.classList.add(targetClass);
        }

        // Sidebar Visibility
        if (sidebar) {
            sidebar.style.display = (state.layout === 'horizontal' || state.layout === 'condensed') ? 'none' : '';
        }

        // Header Structure
        if (header) {
            if (state.layout === 'condensed') {
                header.classList.add('navbar-overlap', 'navbar-dark', 'text-white');
                // Condensed ALWAYS implies header overlap background support
                root.setAttribute('data-bs-has-header-overlap-bg', '');
            } else {
                header.classList.remove('navbar-overlap', 'navbar-dark', 'text-white');
                // Remove unless user set a custom color (handled in _updateStyles)
                if (!this._hasCustomColor('theme-header-overlap-bg')) {
                    root.removeAttribute('data-bs-has-header-overlap-bg');
                }
            }
        }

        // Sticky Header / Hidden Header
        const stickyWrapper = document.getElementById('header-sticky-wrapper');
        const topHeader = stickyWrapper?.querySelector('header.navbar');
        if (stickyWrapper && topHeader) {
            stickyWrapper.classList.remove('sticky-top');
            topHeader.classList.remove('sticky-top');
            stickyWrapper.style.removeProperty('display');

            if (state.sticky === 'hidden') {
                stickyWrapper.style.setProperty('display', 'none', 'important');
            } else if (state.sticky === 'true' || state.sticky === true) {
                stickyWrapper.classList.add('sticky-top');
            }
        }
    }

    _updateVisibility(state) {
        // Helper to show/hide by ID
        const setVisible = (ids, show) => {
            if (!Array.isArray(ids)) ids = [ids];
            ids.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.display = show ? '' : 'none';
            });
        };

        const isDark = state.theme === 'dark';

        // 1. Color Presets: Hide ALL custom colors in Dark Mode
        const colorPresets = ['body-bg-preset', 'sidebar-menu-preset', 'header-top-preset', 'header-overlap-preset', 'boxed-bg-preset'];
        setVisible(colorPresets, !isDark);

        if (!isDark) {
            // Refine visibility based on Layout/Width rules
            // Header Overlap Preset -> Only show if Condensed OR if user wants to override (optional, but sticking to previous logic: condensed only or vertical/horizontal?)
            // Previous logic: Hidden for Vertical/Horizontal. Visible for Condensed.
            setVisible('header-overlap-preset', state.layout === 'condensed');

            // Sidebar Menu Preset -> Hide if Condensed (since Condensed uses top nav mostly?)
            // Previous logic: Hides 'sidebar-menu-preset' for Condensed.
            if (state.layout === 'condensed') {
                setVisible('sidebar-menu-preset', false);
            }

            // Boxed BG Preset -> Only show if Boxed
            setVisible('boxed-bg-preset', state.width === 'boxed');
        }

        // 2. Auth Flow Visibility
        if (this.mode === 'auth') {
            const position = this.form.querySelector('input[name="auth-form-position"]:checked')?.value;
            const formCol = document.querySelector('[data-form-column]');
            const mediaCol = document.querySelector('[data-media-column]');
            if (formCol && mediaCol) {
                if (position === 'right') {
                    formCol.style.order = '2';
                    mediaCol.style.order = '1';
                } else {
                    formCol.style.order = '';
                    mediaCol.style.order = '';
                }
            }
        }
    }

    _updateStyles(state) {
        const root = document.documentElement;

        // In Dark Mode, we only skip specific "Background" colors.
        const backgroundKeys = ['theme-bg', 'theme-sidebar-bg', 'theme-header-top-bg', 'theme-header-overlap-bg', 'theme-boxed-bg'];

        // Iterate over all mapped settings and apply
        for (const [name, rule] of Object.entries(this.themeMap)) {
            const input = this.form.querySelector(`input[name="${name}"]`);
            // Value is from Input OR Font Select OR empty
            let val = input ? input.value : (name === 'theme-primary' ? state.primary : null);

            // Special handling for non-inputs
            if (name === 'theme-radius') val = this.form.querySelector('select[name="theme-radius"]')?.value;
            if (name === 'theme-card-style') val = this.form.querySelector('select[name="theme-card-style"]')?.value;

            // If Dark Mode AND it's a background key -> Force Remove (Do not apply)
            if (state.theme === 'dark' && backgroundKeys.includes(name)) {
                this._removeSingleStyle(root, name, rule);
                continue;
            }

            if (val) {
                this._applySingleStyle(root, name, val, rule);
            } else {
                this._removeSingleStyle(root, name, rule);
            }
        }

        // Font Family
        if (state.font) {
            root.setAttribute('data-bs-theme-font', state.font);
            if (this.fontStacks[state.font]) {
                root.style.setProperty('--tblr-font-sans-serif', this.fontStacks[state.font]);
            }
        }

        // Theme Base
        if (state.base) {
            root.setAttribute('data-bs-theme-base', state.base);
        }
    }

    _applySingleStyle(root, name, value, rule) {
        // Attribute Handling
        if (rule.attr) {
            if (name === 'theme-card-style' && value === 'default') {
                root.removeAttribute('data-bs-card-style');
            } else {
                root.setAttribute(rule.attr, value === true || value === 'true' ? '' : value);
            }
        }

        // CSS Variable Handling
        if (rule.var) {
            if (name === 'theme-radius') {
                this._applyRadius(root, rule.var, value);
            } else {
                root.style.setProperty(rule.var, value);
                // Auto-Contrast logic
                if (name === 'theme-sidebar-bg') this._updateContrast(root, value, '--tblr-sidebar-text', '--tblr-sidebar-text-muted');
                if (name === 'theme-header-top-bg') this._updateContrast(root, value, '--tblr-header-top-text', '--tblr-header-top-text-muted');
                if (name === 'theme-bg') this._updateContrast(root, value, '--tblr-body-text');
            }
        }
    }

    _removeSingleStyle(root, name, rule) {
        if (rule.var) root.style.removeProperty(rule.var);
        if (rule.attr) root.removeAttribute(rule.attr);

        // Clean up contrast vars
        if (name === 'theme-sidebar-bg') { root.style.removeProperty('--tblr-sidebar-text'); root.style.removeProperty('--tblr-sidebar-text-muted'); }
        if (name === 'theme-header-top-bg') { root.style.removeProperty('--tblr-header-top-text'); root.style.removeProperty('--tblr-header-top-text-muted'); }
        if (name === 'theme-bg') { root.style.removeProperty('--tblr-body-text'); }
    }

    _hasCustomColor(name) {
        const input = this.form.querySelector(`input[name="${name}"]`);
        return input && input.value && input.value !== '';
    }

    // ==========================================
    // Utilities
    // ==========================================

    _applyRadius(root, varName, value) {
        const val = parseFloat(value);
        if (!isNaN(val)) {
            root.style.setProperty(varName, val + 'rem');
            root.style.setProperty(varName + '-sm', (val * 0.75) + 'rem');
            root.style.setProperty(varName + '-lg', (val * 1.25) + 'rem');
            root.style.setProperty(varName + '-pill', '100rem');
        }
    }

    _updateContrast(root, color, textVar, mutedVar = null) {
        const isDark = this._getLuminance(color) < 0.6;
        root.style.setProperty(textVar, isDark ? '#ffffff' : '#1e293b');
        if (mutedVar) {
            root.style.setProperty(mutedVar, isDark ? 'rgba(255, 255, 255, 0.7)' : '#6c757d');
        }
    }

    _getLuminance(color) {
        // Simple shim for luminance calculation
        let r = 0, g = 0, b = 0;
        if (color.startsWith('#')) {
            const hex = color.slice(1);
            if (hex.length === 3) { r = parseInt(hex[0] + hex[0], 16); g = parseInt(hex[1] + hex[1], 16); b = parseInt(hex[2] + hex[2], 16); }
            else if (hex.length === 6) { r = parseInt(hex.slice(0, 2), 16); g = parseInt(hex.slice(2, 4), 16); b = parseInt(hex.slice(4, 6), 16); }
        } else if (color.startsWith('rgb')) {
            const m = color.match(/\d+/g);
            if (m) { r = m[0]; g = m[1]; b = m[2]; }
        }
        return (0.299 * r + 0.587 * g + 0.114 * b) / 255;
    }

    // ==========================================
    // Init & Events
    // ==========================================

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

            pickr.on('change', (color) => {
                const rgba = color.toRGBA().toString(0);
                pickr.applyColor(true);
                if (hiddenInput) {
                    hiddenInput.value = rgba;
                    this.refresh(); // Trigger unified refresh
                }
            });
            pickr.on('save', () => pickr.hide());
        });
    }

    bindEvents() {
        // Change Event: Triggers Refresh
        this.form.addEventListener('change', (e) => {
            const { name, value } = e.target;
            if (!name) return;

            // Special handling for Theme Mode toggle (Dark -> Light reset)
            if (name === 'theme') {
                document.documentElement.setAttribute('data-bs-theme', value);
                if (value === 'dark') {
                    // Optionally clear inputs or just let refresh() handle visibility
                    // Current refresh() hides presets, which is enough.
                }
            }

            this.refresh();
        });

        // Apply Button
        document.getElementById('apply-settings')?.addEventListener('click', () => this.handleApply());

        // Reset Buttons
        this.form.querySelectorAll('button[data-reset-bg]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const target = e.currentTarget.getAttribute('data-reset-bg');
                const defaults = { 'theme-bg': '#f4f6fa', 'theme-primary': '#206bc4', 'theme-header-overlap-bg': '#1e293b', 'theme-boxed-bg': '#e2e8f0' };
                const val = defaults[target] || '#ffffff';

                const input = this.form.querySelector(`input[name="${target}"]`);
                if (input) input.value = val;
                this.pickrInstances[target]?.setColor(val, true);
                this.refresh();
            });
        });
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
                Toast.fire({ icon: 'success', title: 'Theme settings saved successfully' });
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
