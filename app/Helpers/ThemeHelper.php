<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;

class ThemeHelper
{
    protected static $config     = null;
    protected static $configFile = 'theme.json';

    /**
     * Load Config from JSON (Storage)
     */
    public static function loadConfig()
    {
        if (self::$config !== null) {
            return;
        }

        if (Storage::exists(self::$configFile)) {
            self::$config = json_decode(Storage::get(self::$configFile), true);
        } else {
            // Default Config if file misses
            self::$config = [
                'theme'             => 'light',
                'layout'            => 'vertical', // vertical, horizontal, condensed, navbar-overlap
                'container_width'   => 'standard', // standard, fluid, boxed
                'header_sticky'     => 'false',
                'card_style'        => 'flat',
                'primary_color'     => '#206bc4',
                'font_family'       => 'inter',
                'radius'            => '1',
                // Backgrounds
                'bg_body'           => '',
                'bg_sidebar'        => '',
                'bg_header_top'     => '',
                'bg_header_overlap' => '',
                'bg_boxed'          => '',
            ];
            // Auto-create file
            Storage::put(self::$configFile, json_encode(self::$config, JSON_PRETTY_PRINT));
        }
    }

    /**
     * Save current config to JSON
     */
    public static function saveConfig()
    {
        if (self::$config === null) {
            return;
        }

        Storage::put(self::$configFile, json_encode(self::$config, JSON_PRETTY_PRINT));
    }

    /**
     * Set a setting value (Updates JSON config)
     */
    public static function set($key, $value)
    {
        self::loadConfig();

        // Map common keys if needed, or use direct keys
        // We use the same map as get() but reversed logic effectively
        $map = [
            'theme'                   => 'theme',
            'theme-font'              => 'font_family',
            'theme_font'              => 'font_family', // Handle snake_case input
            'theme-base'              => 'base',
            'theme_base'              => 'base',
            'theme-radius'            => 'radius',
            'theme_radius'            => 'radius',
            'theme-primary'           => 'primary_color',
            'theme_primary'           => 'primary_color',
            'theme-card-style'        => 'card_style',
            'theme_card_style'        => 'card_style',
            'container-width'         => 'container_width',
            'container_width'         => 'container_width',
            'theme-header-sticky'     => 'header_sticky',
            'theme_header_sticky'     => 'header_sticky',
            'theme-bg'                => 'bg_body',
            'theme_bg'                => 'bg_body',
            'theme-sidebar-bg'        => 'bg_sidebar',
            'theme_sidebar_bg'        => 'bg_sidebar',
            'theme-header-top-bg'     => 'bg_header_top',
            'theme_header_top_bg'     => 'bg_header_top',
            'theme-header-overlap-bg' => 'bg_header_overlap',
            'theme_header_overlap_bg' => 'bg_header_overlap',
            'theme-boxed-bg'          => 'bg_boxed',
            'theme_boxed_bg'          => 'bg_boxed',
            'layout'                  => 'layout',
            'auth-layout'             => 'auth_layout',
            'auth_layout'             => 'auth_layout',
            'auth-form-position'      => 'auth_form_position',
            'auth_form_position'      => 'auth_form_position',
        ];

        $configKey                = $map[$key] ?? $key;
        self::$config[$configKey] = $value;
        self::saveConfig();
    }

    /**
     * Get a setting value (Priority: Cookie > JSON Config > Default)
     */
    public static function get($key, $default = null)
    {
        // 1. Cookie (User Preference)
        if (isset($_COOKIE['tblr_' . $key])) {
            return $_COOKIE['tblr_' . $key];
        }

        // 2. JSON Config (Global Setting)
        self::loadConfig();

        // Map cookie/helper keys to JSON config keys
        $map = [
            'theme'                   => 'theme',
            'theme-font'              => 'font_family',
            'theme-base'              => 'base', // Not in JSON yet, default used
            'theme-radius'            => 'radius',
            'theme-primary'           => 'primary_color',
            'theme-card-style'        => 'card_style',
            'container-width'         => 'container_width',
            'theme-header-sticky'     => 'header_sticky',
            'theme-bg'                => 'bg_body',
            'theme-sidebar-bg'        => 'bg_sidebar',
            'theme-header-top-bg'     => 'bg_header_top',
            'theme-header-overlap-bg' => 'bg_header_overlap',
            'theme-boxed-bg'          => 'bg_boxed',
        ];

        if (isset($map[$key]) && isset(self::$config[$map[$key]])) {
            return self::$config[$map[$key]];
        }

        // Original defaults (some keys might not be in JSON config yet)
        $defaults = [
            'theme'                   => 'light',
            'theme-font'              => 'inter',
            'theme-base'              => 'gray',
            'theme-radius'            => '1',
            'theme-primary'           => '#206bc4',
            'theme-card-style'        => 'flat',
            'container-width'         => 'standard',
            'theme-header-sticky'     => 'false',
            // Default backgrounds are empty
            'theme-bg'                => '',
            'theme-sidebar-bg'        => '',
            'theme-header-top-bg'     => '',
            'theme-header-overlap-bg' => '',
            'theme-boxed-bg'          => '',
        ];

        // 3. Default from hardcoded array if not found in config
        return $defaults[$key] ?? $default;
    }

    /**
     * Calculate and return Layout Data (Replaces InjectLayoutData Middleware)
     */
    public static function getLayoutData()
    {
        self::loadConfig(); // Ensure config is loaded

        $layout = self::get('layout', 'vertical'); // Cookie 'layout' isn't standard, usually 'theme-layout' or just from config.
                                                   // Note: The JS manager doesn't currently allow switching "Layout Structure" (Vertical/Horizontal),
                                                   // so this usually falls back to JSON config 'layout'.

        // However, let's allow cookie override if we add that feature later
        $layout         = isset($_COOKIE['tblr_layout']) ? $_COOKIE['tblr_layout'] : (self::$config['layout'] ?? 'vertical');
        $containerWidth = self::get('container-width', 'standard');

        // Defaults
        $data = [
            'layoutSidebar'         => false,
            'layoutSidebarDark'     => false,
            'layoutHideTopbar'      => false,
            'layoutNavbarCondensed' => false,
            'layoutNavbarHideBrand' => false,
            'layoutNavbarSticky'    => false,
            'layoutNavbarDark'      => false,
            'layoutNavbarClass'     => '',
            'bodyClass'             => '',
            'pageHeaderClass'       => '',
            'containerClass'        => 'container-xl',
            'navbarContainerClass'  => 'container-xl',
            'layout'                => $layout,
            'containerWidth'        => $containerWidth,
        ];

        // Layout Presets
        switch ($layout) {
            case 'vertical':
                $data['layoutSidebar'] = true;
                break;
            case 'condensed':
                $data['layoutNavbarCondensed'] = true;
                break;
            case 'navbar-overlap':
                $data['layoutNavbarCondensed'] = true;
                $data['layoutNavbarDark']      = true;
                $data['layoutNavbarClass']     = 'navbar-overlap';
                $data['pageHeaderClass']       = 'text-white';
                break;
            case 'horizontal':
            default:
                // Use defaults
                break;
        }

        // Header Sticky (Hidden logic is handled here too)
        $headerMode = self::get('theme-header-sticky');
        if ($headerMode === 'hidden') {
            $data['layoutHideTopbar'] = true;
        }

        // Boxed Modification
        if ($containerWidth === 'boxed') {
            $data['bodyClass'] .= ' layout-boxed';
            $data['containerClass'] = 'container';
        } else if ($containerWidth === 'fluid') {
            $data['containerClass']       = 'container-fluid';
            $data['navbarContainerClass'] = 'container-fluid';
        }

        return $data;
    }

    /**
     * Generate the <style> block content for global theme variables
     */
    public static function getStyleBlock()
    {
        $theme   = self::get('theme');
        $isLight = $theme === 'light';

        $radius  = (float) self::get('theme-radius', 1);
        $primary = self::get('theme-primary');
        $font    = self::get('theme-font');

        $css = [];

        // 1. Radius
        $css[] = "--tblr-border-radius: {$radius}rem;";
        $css[] = "--tblr-border-radius-sm: " . ($radius * 0.75) . "rem;";
        $css[] = "--tblr-border-radius-lg: " . ($radius * 1.25) . "rem;";
        $css[] = "--tblr-border-radius-pill: 100rem;";

        // 2. Primary Color
        if ($primary) {
            $css[] = "--tblr-primary: {$primary};";
            // Simple hex to rgb conversion if needed for rgba usage, but Tabler handles this often internally
            // We'll stick to simple var for now as per JS logic
        }

        // 3. Font Family
        $fonts = [
            'inter'       => "'Inter', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
            'roboto'      => "'Roboto', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Helvetica Neue, sans-serif",
            'poppins'     => "'Poppins', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
            'public-sans' => "'Public Sans', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
            'nunito'      => "'Nunito', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
        ];

        if (isset($fonts[$font])) {
            $css[] = "--tblr-font-sans-serif: {$fonts[$font]};";
        }

        // 4. Backgrounds & Auto-Contrast (Light Mode Only)
        if ($isLight) {
            $bgSettings = [
                'theme-bg'                => '--tblr-body-bg',
                'theme-sidebar-bg'        => '--tblr-sidebar-bg',
                'theme-header-top-bg'     => '--tblr-header-top-bg',
                'theme-header-overlap-bg' => '--tblr-header-overlap-bg',
                'theme-boxed-bg'          => '--tblr-boxed-bg',
            ];

            foreach ($bgSettings as $key => $var) {
                $val = self::get($key);
                if ($val) {
                    $css[] = "{$var}: {$val};";

                    // Auto-Contrast Logic
                    $target = null;
                    if ($key === 'theme-sidebar-bg') {
                        $target = 'sidebar';
                    }

                    if ($key === 'theme-header-top-bg') {
                        $target = 'header-top';
                    }

                    if ($key === 'theme-bg') {
                        $target = 'body';
                    }

                    if ($target) {
                        $isDark = self::getLuminance($val) < 0.6;
                        $text   = $isDark ? '#ffffff' : '#1e293b';
                        $muted  = $isDark ? 'rgba(255, 255, 255, 0.7)' : '#6c757d';

                        if ($target === 'sidebar') {
                            $css[] = "--tblr-sidebar-text: {$text};";
                            $css[] = "--tblr-sidebar-text-muted: {$muted};";
                        } elseif ($target === 'header-top') {
                            $css[] = "--tblr-header-top-text: {$text};";
                            $css[] = "--tblr-header-top-text-muted: {$muted};";
                        } elseif ($target === 'body') {
                            $css[] = "--tblr-body-text: {$text};";
                        }
                    }
                }
            }
        }

        return ":root { " . implode(' ', $css) . " }";
    }

    /**
     * Get HTML attributes for the <body> or <html> tag
     */
    public static function getHtmlAttributes()
    {
        $theme     = self::get('theme');
        $font      = self::get('theme-font');
        $base      = self::get('theme-base');
        $cardStyle = self::get('theme-card-style');

        $attrs = [
            "data-bs-theme=\"{$theme}\"",
            "data-bs-theme-font=\"{$font}\"",
            "data-bs-theme-base=\"{$base}\"",
            "data-bs-card-style=\"{$cardStyle}\"",
        ];

        // Mapped attributes for background detection
        if (self::hasBg('theme-bg')) {
            $attrs[] = 'data-bs-has-theme-bg';
        }

        if (self::hasBg('theme-sidebar-bg')) {
            $attrs[] = 'data-bs-has-sidebar-bg';
        }

        if (self::hasBg('theme-header-top-bg')) {
            $attrs[] = 'data-bs-has-header-top-bg';
        }

        if (self::hasBg('theme-header-overlap-bg')) {
            $attrs[] = 'data-bs-has-header-overlap-bg';
        }

        return implode(' ', $attrs);
    }

    public static function getBodyAttributes()
    {
        $width = self::get('container-width');
        return "data-container-width=\"{$width}\"";
    }

    public static function getBodyClasses()
    {
        $classes = [];
        if (self::get('container-width') === 'boxed') {
            $classes[] = 'layout-boxed';
        }
        return implode(' ', $classes);
    }

    /**
     * Check if specific background is set (for data-bs-has-* attributes)
     */
    public static function hasBg($key)
    {
        return self::get('theme') === 'light' && ! empty(self::get($key));
    }

    /**
     * Calculate relative luminance
     */
    private static function getLuminance($color)
    {
        $color = trim($color);
        if (empty($color)) {
            return 1;
        }

        $r = 0;
        $g = 0;
        $b = 0;

        if (strpos($color, '#') === 0) {
            $hex = substr($color, 1);
            if (strlen($hex) === 3) {
                $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
                $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
                $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
            } elseif (strlen($hex) === 6) {
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
            }
        } elseif (preg_match('/rgba?\((\d+),\s*(\d+),\s*(\d+)/', $color, $matches)) {
            $r = $matches[1];
            $g = $matches[2];
            $b = $matches[3];
        } else {
            return 1; // Default/Fallback
        }

        return (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
    }
}
