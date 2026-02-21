# Project CSS Customization Guide

**Last Updated:** Februari 2026
**Tabler Version:** 1.4.0
**Bootstrap Version:** 5.3.8

Dokumen ini menjelaskan arsitektur CSS, file kustomisasi, dan cara kerja styling pada proyek ini.

---

## Table of Contents

1. [CSS Entry Points](#1-css-entry-points)
2. [Tabler CSS Architecture](#2-tabler-css-architecture)
3. [Theme Customization (ThemeTabler.css)](#3-theme-customization-themetablercss)
4. [Auth CSS](#4-auth-css)
5. [Public/Guest CSS](#5-publicguest-css)
6. [CSS Variables System](#6-css-variables-system)
7. [Dark Mode Support](#7-dark-mode-support)
8. [Component Customizations](#8-component-customizations)
9. [Vendor Overrides](#9-vendor-overrides)
10. [Best Practices](#10-best-practices)

---

## 1. CSS Entry Points

### A. Vite Configuration

**File:** `vite.config.js`

```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS Entry Points
                'resources/css/tabler.css',      // Admin/System layout
                'resources/css/auth.css',        // Authentication pages
                'resources/css/public.css',      // Public website
                // JS Entry Points
                'resources/js/tabler.js',
                'resources/js/auth.js',
                'resources/js/public.js'
            ],
            refresh: true,
        }),
    ],
});
```

### B. Entry Point Structure

```
resources/css/
├── tabler.css          # Main admin layout (imports Tabler + custom)
├── auth.css            # Authentication pages (login, register)
└── public.css          # Public website (TheProperty template)

resources/assets/
├── tabler/
│   ├── css/
│   │   └── ThemeTabler.css    # Custom Tabler overrides
│   └── js/
└── public/
    └── css/
        └── main.css           # TheProperty template styles (9211 lines)
```

### C. Layout Usage

```blade
<!-- Admin Layout -->
<x-layouts.tabler.app>
    @vite(['resources/css/tabler.css', 'resources/js/tabler.js'])
</x-layouts.tabler.app>

<!-- Auth Layout -->
<x-layouts.auth.app>
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
</x-layouts.auth.app>

<!-- Public Layout -->
<x-layouts.public.app>
    @vite(['resources/css/public.css', 'resources/js/public.js'])
</x-layouts.public.app>
```

---

## 2. Tabler CSS Architecture

### A. Import Chain

```
resources/css/tabler.css
├── @tabler/core/dist/css/tabler.min.css         # Tabler core
├── datatables.net-bs5/css/dataTables.bootstrap5.css  # DataTables
├── @tabler/core/dist/css/tabler-themes.css      # Tabler theme variants
├── @tabler/icons-webfont/dist/tabler-icons.min.css   # Icons
└── resources/assets/tabler/css/ThemeTabler.css  # Custom overrides
```

### B. Tabler Core CSS

**Package:** `@tabler/core@1.4.0`

**Features:**
- Responsive grid system
- Utility classes (spacing, typography, colors)
- Components (cards, forms, buttons, tables, modals)
- Navbar & sidebar layouts
- Dark mode support

**File Size:** ~200KB (minified)

### C. Tabler Themes

**File:** `tabler-themes.css`

**Available Themes:**
- `slate` - Dark blue-gray
- `zinc` - Neutral gray
- `orange` - Orange accent
- `cyan` - Cyan accent
- `purple` - Purple accent

**Usage:**
```blade
<!-- Via controller -->
{!! $themeController->getThemeLink('tabler') !!}

<!-- Or manual -->
<link rel="stylesheet" href="{{ asset('build/assets/tabler-themes.css') }}">
```

---

## 3. Theme Customization (ThemeTabler.css)

**File:** `resources/assets/tabler/css/ThemeTabler.css`

**Total Lines:** ~500+ lines of custom CSS

### A. Structure

```css
/* ==========================================================================
   1. BASE CUSTOMIZATIONS
   - Font definitions
   - Card customizations
   - Global tweaks
   ========================================================================== */

/* ==========================================================================
   2. COMPONENT CUSTOMIZATIONS
   - Navbar brand image
   - DataTable processing indicator
   ========================================================================== */

/* ==========================================================================
   3. LAYOUT SYSTEMS
   - Container width variants
   - Boxed layout
   - Navbar overlap (condensed mode)
   - Sticky header
   ========================================================================== */

/* ==========================================================================
   4. DYNAMIC THEME & AUTO-CONTRAST SYSTEM
   - Sidebar background customization
   - Header top background customization
   - Body background customization
   - Auto-contrast text colors
   ========================================================================== */

/* ==========================================================================
   5. SELECT2 TABLER INTEGRATION
   - Select2 dropdown styling
   - Dark mode support for Select2
   ========================================================================== */

/* ==========================================================================
   6. DROPDOWN Z-INDEX FIX
   - Dropdown menu stacking
   ========================================================================== */
```

---

### B. Base Customizations

#### Card Styling
```css
.card {
    border-radius: var(--tblr-border-radius, 0.5rem) !important;
}

.card-header {
    border-radius: var(--tblr-border-radius, 0.5rem) var(--tblr-border-radius, 0.5rem) 0 0 !important;
}
```

#### Card Style Variants
```css
/* Flat - No shadow, border only */
[data-bs-card-style="flat"] .card {
    box-shadow: none !important;
    border: 1px solid var(--tblr-border-color) !important;
}

/* Shadow - Shadow only, no border */
[data-bs-card-style="shadow"] .card {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
    border: none !important;
}

/* Border - Thick border, no shadow */
[data-bs-card-style="border"] .card {
    box-shadow: none !important;
    border: 1px solid rgba(var(--tblr-body-color-rgb), 0.16) !important;
}

/* Modern - Large shadow, rounded corners */
[data-bs-card-style="modern"] .card {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    border: none !important;
    border-radius: 12px !important;
}
```

---

### C. Layout Systems

#### 1. Container Width Variants

```css
/* Fluid - Full width */
[data-container-width="fluid"] .container-xl,
[data-container-width="fluid"] .container {
    max-width: 100% !important;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}

/* Standard, Narrow, Ultra-wide - Controlled via max-width */
[data-container-width="standard"] { max-width: 1320px; }
[data-container-width="narrow"] { max-width: 960px; }
[data-container-width="ultra-wide"] { max-width: 1920px; }
```

#### 2. Boxed Layout

```css
body.layout-boxed {
    background-color: var(--tblr-boxed-bg, #e2e8f0);
}

[data-bs-theme="dark"] body.layout-boxed {
    background-color: color-mix(in srgb, var(--tblr-bg-surface), black 40%) !important;
}

body.layout-boxed .page {
    background-color: var(--tblr-body-bg) !important;
    max-width: 1320px;
    margin: 0 auto 2rem auto;
    box-shadow: 0 0 2rem rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 0;
}

@media (min-width: 992px) {
    body.layout-boxed .navbar-vertical {
        position: absolute !important;
        left: 0;
        top: 0;
        bottom: 0;
        height: auto;
        z-index: 1030;
    }
}
```

#### 3. Navbar Overlap (Condensed Mode)

```css
/* Reset Navbar to Transparent */
.navbar-overlap {
    background: transparent !important;
    position: relative;
    z-index: auto !important;
    box-shadow: none !important;
    border-bottom: none !important;
}

/* Layer 1: Large Overlap Background (Bottom) */
.navbar-overlap::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 12rem !important;
    background: var(--tblr-header-overlap-bg, #04060b) !important;
    z-index: 0;
    visibility: hidden;
    pointer-events: none;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

/* Show overlap background when attribute is set */
:root[data-bs-has-header-overlap-bg] .navbar-overlap::after,
body.layout-condensed .navbar-overlap::after {
    visibility: visible;
}

/* Layer 2: Navbar Strip Background (Middle) */
.navbar-overlap::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--tblr-header-top-bg, transparent) !important;
    z-index: 1;
    pointer-events: none;
}

/* Lift interactive content above backgrounds */
.navbar-overlap .container-xl,
.navbar-overlap .navbar-collapse {
    position: relative;
    z-index: 1030;
}

/* Ensure content sits above overlap background */
:root[data-bs-has-header-overlap-bg] .page-wrapper,
body.layout-condensed .page-wrapper {
    position: relative;
    z-index: 2;
    background-color: transparent !important;
}
```

---

### D. Auto-Contrast System

The theme system automatically calculates text contrast based on background color luminance.

#### Sidebar Auto-Contrast

```css
/* Light theme default */
[data-bs-theme="light"] .navbar-vertical .nav-link {
    color: var(--tblr-dark, #1e293b) !important;
}

[data-bs-theme="light"] .navbar-vertical .nav-link:hover,
[data-bs-theme="light"] .navbar-vertical .nav-link.active {
    color: var(--tblr-primary, #206bc4) !important;
}

/* Auto-contrast override when custom BG is set */
:root[data-bs-has-sidebar-bg] .navbar-vertical .navbar-brand,
:root[data-bs-has-sidebar-bg] .navbar-vertical .nav-link,
:root[data-bs-has-sidebar-bg] .navbar-vertical .dropdown-toggle {
    color: var(--tblr-sidebar-text) !important;  /* Auto-calculated */
}

:root[data-bs-has-sidebar-bg] .navbar-vertical .nav-link-title {
    color: var(--tblr-sidebar-text-muted) !important;  /* Auto-calculated */
}
```

#### Header Top Auto-Contrast

```css
[data-bs-has-header-top-bg] .navbar .nav-link,
[data-bs-has-header-top-bg] .navbar .navbar-brand,
[data-bs-has-header-top-bg] .navbar .btn {
    color: var(--tblr-header-top-text) !important;  /* Auto-calculated */
}

[data-bs-has-header-top-bg] .navbar .text-secondary {
    color: var(--tblr-header-top-text-muted) !important;  /* Auto-calculated */
}
```

#### Body Background Auto-Contrast

```css
:root[data-bs-has-theme-bg] .page-header .page-title,
:root[data-bs-has-theme-bg] .breadcrumb-item,
:root[data-bs-has-theme-bg] .footer a:not(.btn) {
    color: var(--tblr-body-text) !important;  /* Auto-calculated */
}
```

---

## 4. Auth CSS

**File:** `resources/css/auth.css`

### A. Import Chain

```css
@import '@tabler/core/dist/css/tabler.min.css';
@import '@tabler/core/dist/css/tabler-themes.css';
@import '@tabler/icons-webfont/dist/tabler-icons.min.css';
```

### B. Dark Mode Background Support

```css
/* Light mode: white background */
[data-bs-theme="light"] body {
    background-color: var(--tblr-body-bg, #ffffff);
}

/* Dark mode: dark background */
[data-bs-theme="dark"] body {
    background-color: var(--tblr-body-bg, #1e293b);
}

/* Auth Cover Layout */
.page-cover {
    background-color: var(--tblr-body-bg, #ffffff);
}

/* Auth Illustration Layout */
.page-illustration {
    background-color: var(--tblr-body-bg, #ffffff);
}

/* Basic Layout */
.page-center {
    background-color: var(--tblr-body-bg, #ffffff);
}
```

---

## 5. Public/Guest CSS

**File:** `resources/css/public.css`

### A. Import Chain

```css
/* Vendor CSS */
@import '../assets/public/vendor/bootstrap/css/bootstrap.min.css';
@import '../assets/public/vendor/bootstrap-icons/bootstrap-icons.css';
@import '../assets/public/vendor/glightbox/css/glightbox.min.css';
@import '../assets/public/vendor/swiper/swiper-bundle.min.css';
@import '../assets/public/vendor/drift-zoom/drift-basic.css';

/* Main Template CSS */
@import '../assets/public/css/main.css';  /* 9211 lines */
```

### B. Template Info

**Template:** TheProperty  
**Source:** https://bootstrapmade.com/theproperty-bootstrap-real-estate-template/  
**License:** https://bootstrapmade.com/license/

### C. CSS Variables

```css
:root {
    /* Fonts */
    --default-font: "Roboto", system-ui, -apple-system, sans-serif;
    --heading-font: "Raleway", sans-serif;
    --nav-font: "Montserrat", sans-serif;
    
    /* Global Colors */
    --background-color: #ffffff;
    --default-color: #323b3b;
    --heading-color: #163535;
    --accent-color: #2c7a7b;
    --surface-color: #ffffff;
    --contrast-color: #ffffff;
    
    /* Nav Menu Colors */
    --nav-color: #323b3b;
    --nav-hover-color: #2c7a7b;
    --nav-mobile-background-color: #ffffff;
    --nav-dropdown-background-color: #ffffff;
    --nav-dropdown-color: #323b3b;
    --nav-dropdown-hover-color: #2c7a7b;
}
```

### D. Color Presets

```css
/* Light Background */
.light-background {
    --background-color: #eaf9f9;
    --surface-color: #ffffff;
}

/* Dark Background */
.dark-background {
    --background-color: #081b12;
    --default-color: #ffffff;
    --heading-color: #ffffff;
    --surface-color: #1f3028;
    --contrast-color: #ffffff;
}

/* Accent Background */
.accent-background {
    --background-color: #077f46;
    --default-color: #ffffff;
    --heading-color: #ffffff;
    --accent-color: #ffffff;
    --surface-color: #2a8f5f;
    --contrast-color: #ffffff;
}
```

---

## 6. CSS Variables System

### A. Tabler CSS Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `--tblr-body-bg` | Body background color | `#f4f6fa` |
| `--tblr-body-color` | Body text color | `#1e293b` |
| `--tblr-bg-surface` | Surface background | `#ffffff` |
| `--tblr-bg-surface-dark` | Dark surface background | `#1e293b` |
| `--tblr-bg-forms` | Form background | `#ffffff` |
| `--tblr-border-color` | Border color | `#d4d9dd` |
| `--tblr-border-radius` | Base border radius | `0.25rem` |
| `--tblr-primary` | Primary brand color | `#206bc4` |
| `--tblr-primary-rgb` | Primary RGB values | `32, 107, 196` |
| `--tblr-muted` | Muted text color | `#9aa0ac` |
| `--tblr-dark` | Dark text color | `#1e293b` |

### B. Custom CSS Variables (ThemeTabler)

| Variable | Description | Usage |
|----------|-------------|-------|
| `--tblr-sidebar-bg` | Sidebar background | Custom sidebar color |
| `--tblr-sidebar-text` | Sidebar text (auto-contrast) | Calculated from sidebar-bg |
| `--tblr-sidebar-text-muted` | Sidebar muted text | Calculated from sidebar-bg |
| `--tblr-header-top-bg` | Header top background | Custom header color |
| `--tblr-header-top-text` | Header text (auto-contrast) | Calculated from header-top-bg |
| `--tblr-header-overlap-bg` | Condensed header overlap | Dark blue for contrast |
| `--tblr-boxed-bg` | Boxed layout background | Outer background |
| `--tblr-font-sans-serif` | Font family stack | Dynamic font switching |

### C. CSS Variable Usage

```css
/* Read variable */
.card {
    background-color: var(--tblr-bg-surface);
}

/* Variable with fallback */
.card {
    border-radius: var(--tblr-border-radius, 0.5rem);
}

/* RGB variable for transparency */
.box-shadow {
    box-shadow: 0 0 0 0.25rem rgba(var(--tblr-primary-rgb), 0.25);
}

/* Color mix (modern browsers) */
.dark-overlay {
    background-color: color-mix(in srgb, var(--tblr-bg-surface), black 40%);
}
```

---

## 7. Dark Mode Support

### A. Dark Mode Selector

```css
[data-bs-theme="dark"]
```

### B. Dark Mode Overrides

#### Body & Background
```css
[data-bs-theme="dark"] body {
    background-color: var(--tblr-body-bg, #1e293b);
}

[data-bs-theme="dark"] body.layout-boxed {
    background-color: color-mix(in srgb, var(--tblr-bg-surface), black 40%) !important;
}
```

#### Select2 Dark Mode
```css
[data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection {
    background-color: var(--tblr-bg-forms, #0f172a) !important;
    border-color: var(--tblr-border-color-dark, rgba(255, 255, 255, 0.12)) !important;
    color: var(--tblr-body-color, #f8fafc) !important;
}

[data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-dropdown {
    background-color: var(--tblr-bg-surface-dark, #1e293b) !important;
    border-color: var(--tblr-border-color-dark, rgba(255, 255, 255, 0.12)) !important;
}
```

#### Navbar Overlap Dark Mode
```css
[data-bs-theme="dark"] .navbar-overlap::after {
    background: color-mix(in srgb, var(--tblr-bg-surface), black 40%) !important;
    opacity: 1 !important;
}

[data-bs-theme="dark"] .navbar-overlap::before {
    background: var(--tblr-bg-surface, #182433) !important;
}
```

---

## 8. Component Customizations

### A. Select2 Integration

**Full custom styling for Select2 to match Tabler design:**

```css
:root {
    --tblr-select2-padding: 0.4375rem 0.75rem;
    --tblr-select2-height: 2.375rem;
}

/* Base Select2 */
.select2-container--bootstrap-5 .select2-selection {
    border-radius: var(--tblr-border-radius) !important;
    border-color: var(--tblr-border-color) !important;
    font-size: 0.875rem !important;
    padding: var(--tblr-select2-padding) !important;
}

/* Focus State */
.select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: var(--tblr-primary) !important;
    box-shadow: 0 0 0 0.25rem rgba(var(--tblr-primary-rgb), 0.25) !important;
}

/* Multi-select Tags */
.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
    font-size: 0.75rem !important;
    padding: 0.125rem 0.5rem !important;
    background-color: var(--tblr-bg-surface-secondary) !important;
    border-radius: var(--tblr-border-radius-sm) !important;
}

/* Tag Remove Button */
.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
    width: 1.25rem !important;
    height: 1.25rem !important;
    background-color: var(--tblr-muted) !important;
    -webkit-mask-image: url("data:image/svg+xml,...");  /* X icon */
    mask-image: url("data:image/svg+xml,...");
}

.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
    background-color: var(--tblr-danger) !important;
}

/* Dropdown Highlight */
.select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected="true"] {
    background-color: var(--tblr-primary) !important;
    color: #ffffff !important;
}
```

### B. DataTable Processing Indicator

```css
div.dt-processing>div:last-child>div {
    position: absolute;
    top: 0;
    width: 13px;
    height: 13px;
    border-radius: 50%;
    background: var(--tblr-primary) !important;
    animation-timing-function: cubic-bezier(0, 1, 1, 0);
}
```

### C. Navbar Brand Image

```css
.navbar-brand-image {
    height: 2rem;
    width: auto;
}
```

### D. Dropdown Z-Index Fix

```css
/* Ensure dropdowns appear above content */
.navbar .dropdown-menu {
    z-index: 1050 !important;
}

.navbar-overlap .dropdown-menu {
    z-index: 1050 !important;
}

.dataTable .dropdown-menu,
.card .dropdown-menu {
    z-index: 1060 !important;
}
```

---

## 9. Vendor Overrides

### A. Bootstrap Overrides

Tabler extends Bootstrap with custom utilities. No direct Bootstrap overrides needed.

### B. DataTables Overrides

```css
/* Custom processing indicator */
div.dt-processing {
    /* See above */
}

/* Table responsive wrapper */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
```

### C. Flatpickr Overrides

Loaded via npm, minimal overrides needed:

```css
/* In tabler.css or via JS */
.flatpickr-calendar {
    border-radius: var(--tblr-border-radius) !important;
    box-shadow: var(--tblr-shadow) !important;
}
```

### D. FilePond Overrides

```css
/* Auto-loaded via FilePond plugin */
.filepond--root {
    margin-bottom: 0;
}

.filepond--panel-root {
    border-radius: var(--tblr-border-radius) !important;
}
```

---

## 10. Best Practices

### A. DO ✅

1. **Use CSS Variables**
   ```css
   /* Good */
   .card {
       background-color: var(--tblr-bg-surface);
   }
   
   /* Bad */
   .card {
       background-color: #ffffff;
   }
   ```

2. **Use `!important` Sparingly**
   Only for theme overrides that must take precedence:
   ```css
   .navbar-overlap {
       background: transparent !important;  /* Required for ::before/::after */
   }
   ```

3. **Respect Dark Mode**
   ```css
   .custom-element {
       background-color: var(--tblr-bg-surface);
   }
   
   [data-bs-theme="dark"] .custom-element {
       background-color: var(--tblr-bg-surface-dark);
   }
   ```

4. **Use Utility Classes First**
   ```blade
   <!-- Good: Use Tabler utilities -->
   <div class="d-flex align-items-center justify-content-between mb-4">
   
   <!-- Bad: Create custom CSS -->
   <style>
       .my-custom-class { display: flex; ... }
   </style>
   ```

5. **Organize Custom CSS**
   ```css
   /* ==========================================================================
      SECTION NAME
      ========================================================================== */
   
   /* Comment each major rule */
   .rule {
       /* Explain why, not what */
   }
   ```

### B. DON'T ❌

1. **Don't Modify Vendor CSS**
   ```
   ❌ Don't edit node_modules/@tabler/core/...
   ✅ Do override in ThemeTabler.css
   ```

2. **Don't Use Inline Styles**
   ```blade
   ❌ <div style="background-color: #206bc4;">
   ✅ <div class="bg-primary">
   ```

3. **Don't Hardcode Colors**
   ```css
   ❌ color: #206bc4;
   ✅ color: var(--tblr-primary);
   ```

4. **Don't Create Duplicate IDs**
   ```blade
   ❌ Multiple elements with same ID
   ✅ Use classes for reusable styles
   ```

### C. File Organization

```
resources/
├── css/
│   ├── tabler.css          # Admin entry point
│   ├── auth.css            # Auth entry point
│   └── public.css          # Public entry point
└── assets/
    └── tabler/
        └── css/
            └── ThemeTabler.css  # ALL custom overrides here
```

### D. Adding New Styles

**Step 1:** Determine scope
- Global admin style → `ThemeTabler.css`
- Page-specific → `@push('css')` in Blade
- New layout → Create new CSS entry point

**Step 2:** Add to ThemeTabler.css
```css
/* ==========================================================================
   7. NEW SECTION NAME
   ========================================================================== */

/* Comment explaining purpose */
.new-feature {
    /* Implementation */
}
```

**Step 3:** Test dark mode
```css
.new-feature {
    background-color: var(--tblr-bg-surface);
}

[data-bs-theme="dark"] .new-feature {
    background-color: var(--tblr-bg-surface-dark);
}
```

---

## Appendix: Quick Reference

### Common Utility Classes

```blade
<!-- Spacing -->
<div class="mb-3">         <!-- margin-bottom: 1rem -->
<div class="mt-4">         <!-- margin-top: 1.5rem -->
<div class="p-2">          <!-- padding: 0.5rem -->
<div class="gap-3">        <!-- gap: 1rem (flex/grid) -->

<!-- Typography -->
<h2 class="page-title">    <!-- Page title -->
<p class="text-muted">     <!-- Muted text -->
<span class="text-primary"><!-- Primary color -->

<!-- Display -->
<div class="d-none d-lg-block">  <!-- Hidden on mobile -->
<div class="d-flex">             <!-- Flexbox -->
<div class="position-relative">  <!-- Relative positioning -->

<!-- Sizing -->
<div class="w-50">         <!-- width: 50% -->
<div class="h-100">        <!-- height: 100% -->
<div class="mw-100">       <!-- max-width: 100% -->

<!-- Colors -->
<div class="bg-primary">   <!-- Primary background -->
<div class="bg-success">   <!-- Success background -->
<div class="text-white">   <!-- White text -->

<!-- Shadows & Borders -->
<div class="shadow">       <!-- Box shadow -->
<div class="border">       <!-- Border -->
<div class="rounded">      <!-- Border radius -->

<!-- Layout -->
<div class="container-xl"> <!-- Extra large container -->
<div class="row">          <!-- Grid row -->
<div class="col-md-6">     <!-- 6 columns on medium -->
```

### Custom Data Attributes

```blade
<!-- Card Style -->
<div data-bs-card-style="shadow">

<!-- Container Width -->
<body data-container-width="fluid">

<!-- Theme Backgrounds -->
:root[data-bs-has-theme-bg]
:root[data-bs-has-sidebar-bg]
:root[data-bs-has-header-top-bg]
:root[data-bs-has-header-overlap-bg]

<!-- Layout Types -->
<body class="layout-vertical">
<body class="layout-horizontal">
<body class="layout-condensed">
<body class="layout-boxed">

<!-- Dark Mode -->
<html data-bs-theme="dark">
```

---

**Dokumentasi ini adalah pelengkap dari `PROJECT_THEME_CUSTOMIZATION.md` dan `PROJECT_STANDARDS.md`.**
