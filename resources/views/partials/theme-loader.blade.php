<script>
// CRITICAL: Load theme IMMEDIATELY before any rendering
(function() {
    'use strict';
    
    // Helper to safely get from localStorage
    const get = (key, def = '') => localStorage.getItem('tabler-' + key) || def;
    const root = document.documentElement;

    // 1. Core Settings
    const theme = get('theme', 'light');
    const font = get('theme-font', 'inter');
    const radius = get('theme-radius', '1');
    const primary = get('theme-primary', '#206bc4');
    const cardStyle = get('theme-card-style', 'flat');
    const base = get('theme-base', 'gray');

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

    // 4. Apply CSS Variables
    root.style.setProperty('--tblr-border-radius', radius + 'rem');
    root.style.setProperty('--tblr-primary', primary);

    // Radius variants (crucial for consistency)
    const setRadiusVariants = (val) => {
        const r = parseFloat(val);
        if (!isNaN(r)) {
            root.style.setProperty('--tblr-border-radius-sm', (r * 0.75) + 'rem');
            root.style.setProperty('--tblr-border-radius-lg', (r * 1.25) + 'rem');
            root.style.setProperty('--tblr-border-radius-pill', '100rem');
        }
    };
    setRadiusVariants(radius);

    // 5. Apply Backgrounds (CSS Var + Boolean Attribute)
    // IMPORTANT: Skip background colors in dark mode to use Tabler defaults
    const setBg = (prop, val, attr) => {
        if (val && val !== '') {
            root.style.setProperty(prop, val);
            if (attr) root.setAttribute(attr, '');
        } else {
            root.style.removeProperty(prop);
            if (attr) root.removeAttribute(attr);
        }
    };

    // Only apply custom background colors in LIGHT mode
    if (theme === 'light') {
        setBg('--tblr-body-bg', bg);
        setBg('--tblr-sidebar-bg', sidebarBg, 'data-bs-has-sidebar-bg');
        setBg('--tblr-header-top-bg', headerTopBg, 'data-bs-has-header-top-bg');
        setBg('--tblr-header-overlap-bg', headerOverlapBg, 'data-bs-has-header-overlap-bg');
        setBg('--tblr-boxed-bg', boxedBg);
    }
    // Dark mode: Don't apply any custom background colors

})();
</script>