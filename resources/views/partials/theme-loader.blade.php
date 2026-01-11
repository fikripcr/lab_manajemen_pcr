<script>
// CRITICAL: Load theme IMMEDIATELY before any rendering
(function() {
    'use strict';
    
    // Helper to safely get from localStorage
    const get = (key, def = '') => localStorage.getItem('tabler-' + key) || def;
    const root = document.documentElement;
    const body = document.body;

    // Helper: Calculate Luminance for Auto-Contrast
    const getLuminance = (color) => {
        let r, g, b;
        if (!color) return 1;

        if (color.startsWith('#')) {
            const hex = color.substring(1);
            const bigint = parseInt(hex.length === 3 ? hex.split('').map(c=>c+c).join('') : hex, 16);
            r = (bigint >> 16) & 255;
            g = (bigint >> 8) & 255;
            b = bigint & 255;
        } else {
            const rgb = color.match(/\d+/g);
            if (!rgb || rgb.length < 3) return 1;
            r = parseInt(rgb[0]);
            g = parseInt(rgb[1]);
            b = parseInt(rgb[2]);
        }
        return (0.299 * r + 0.587 * g + 0.114 * b) / 255;
    };

    // Helper: Apply Contrast Variables based on background color
    const applyContrast = (target, color) => {
        const isDark = getLuminance(color) < 0.6;
        const text = isDark ? '#ffffff' : '#1e293b';
        const muted = isDark ? 'rgba(255, 255, 255, 0.7)' : '#6c757d';

        if (target === 'sidebar') {
            root.style.setProperty('--tblr-sidebar-text', text);
            root.style.setProperty('--tblr-sidebar-text-muted', muted);
        } else if (target === 'header-top') {
            root.style.setProperty('--tblr-header-top-text', text);
            root.style.setProperty('--tblr-header-top-text-muted', muted);
        } else if (target === 'body') {
            root.style.setProperty('--tblr-body-text', text);
        }
    };

    // 1. Core Settings
    const theme = get('theme', 'light');
    const font = get('theme-font', 'inter');
    const radius = get('theme-radius', '1');
    const primary = get('theme-primary', '#206bc4');
    const cardStyle = get('theme-card-style', 'flat');
    const base = get('theme-base', 'gray');
    const containerWidth = get('container-width', 'standard');
    const stickyHeader = get('theme-header-sticky', 'false');

    // 2. Background Settings
    const bg = get('theme-bg');
    const sidebarBg = get('theme-sidebar-bg');
    const headerTopBg = get('theme-header-top-bg');
    const headerOverlapBg = get('theme-header-overlap-bg');
    const boxedBg = get('theme-boxed-bg');

    // 3. Apply Core Attributes
    root.setAttribute('data-bs-theme', theme);
    root.setAttribute('data-bs-theme-font', font);
    root.setAttribute('data-bs-theme-base', base);
    root.setAttribute('data-bs-card-style', cardStyle);

    // 4. Apply Layout Classes (Syncs Body Class Instantly)
    if (body) {
        body.setAttribute('data-container-width', containerWidth);
        if (containerWidth === 'boxed') {
            body.classList.add('layout-boxed');
        } else {
            body.classList.remove('layout-boxed');
        }
    }

    // 5. Apply CSS Variables
    root.style.setProperty('--tblr-border-radius', radius + 'rem');
    root.style.setProperty('--tblr-primary', primary);

    // 6. Apply Font Family Inline (Eliminates FOUC)
    const fontMap = {
        'inter': "'Inter', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
        'roboto': "'Roboto', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Helvetica Neue, sans-serif",
        'poppins': "'Poppins', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
        'public-sans': "'Public Sans', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
        'nunito': "'Nunito', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif"
    };
    if (fontMap[font]) {
        root.style.setProperty('--tblr-font-sans-serif', fontMap[font]);
    }

    // Radius variants
    const setRadiusVariants = (val) => {
        const r = parseFloat(val);
        if (!isNaN(r)) {
            root.style.setProperty('--tblr-border-radius-sm', (r * 0.75) + 'rem');
            root.style.setProperty('--tblr-border-radius-lg', (r * 1.25) + 'rem');
            root.style.setProperty('--tblr-border-radius-pill', '100rem');
        }
    };
    setRadiusVariants(radius);

    // 7. Apply Backgrounds
    const setBg = (prop, val, attr, contrastTarget = null) => {
        if (val && val !== '') {
            root.style.setProperty(prop, val);
            if (attr) root.setAttribute(attr, '');
            if (contrastTarget) applyContrast(contrastTarget, val);
        } else {
            root.style.removeProperty(prop);
            if (attr) root.removeAttribute(attr);
        }
    };

    // Only apply custom background colors in LIGHT mode
    if (theme === 'light') {
        setBg('--tblr-body-bg', bg, null, 'body');
        setBg('--tblr-sidebar-bg', sidebarBg, 'data-bs-has-sidebar-bg', 'sidebar');
        setBg('--tblr-header-top-bg', headerTopBg, 'data-bs-has-header-top-bg', 'header-top');
        setBg('--tblr-header-overlap-bg', headerOverlapBg, 'data-bs-has-header-overlap-bg');
        setBg('--tblr-boxed-bg', boxedBg);
    }
})();
</script>