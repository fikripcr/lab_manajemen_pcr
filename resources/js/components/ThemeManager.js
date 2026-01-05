/**
 * ThemeManager - Core Theme System
 * Manages Tabler theme settings (shared between sys & auth sections)
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
}

// Export for ES6 modules
export default ThemeManager;
