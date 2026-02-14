<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sys\ThemeTablerRequest;
use Exception;
use Illuminate\Support\Facades\Storage;

class ThemeTablerController extends Controller
{
    /**
     * Get theme configuration data for a specific mode
     */
    public function getThemeData(string $mode = 'sys'): array
    {
        $config = $this->loadConfig($mode);

        // Return formatted data for Blade views
        return [
            'theme'                => $config['theme'] ?? 'light',
            'themePrimary'         => $config['primary_color'] ?? '#206bc4',
            'themeFont'            => $config['font_family'] ?? 'inter',
            'themeBase'            => $config['base'] ?? 'gray',
            'themeRadius'          => $config['radius'] ?? '1',
            'themeBg'              => $config['bg_body'] ?? '',
            'themeSidebarBg'       => $config['bg_sidebar'] ?? '',
            'themeHeaderTopBg'     => $config['bg_header_top'] ?? '',
            'themeHeaderOverlapBg' => $config['bg_header_overlap'] ?? '',
            'themeHeaderSticky'    => $config['header_sticky'] ?? 'false',
            'themeCardStyle'       => $config['card_style'] ?? 'flat',
            'themeBoxedBg'         => $config['bg_boxed'] ?? '',
            'layout'               => $config['layout'] ?? 'vertical',
            'containerWidth'       => $config['container_width'] ?? 'standard',
            'authLayout'           => $config['auth_layout'] ?? 'basic',
            'authFormPosition'     => $config['auth_form_position'] ?? 'left',
        ];
    }

    /**
     * Get layout data for blade views
     */
    public function getLayoutData(string $mode = 'sys'): array
    {
        $config = $this->loadConfig($mode);

        return [
            'containerWidth'        => $config['container_width'] ?? 'standard',
            'layout'                => $config['layout'] ?? 'vertical',
            'layoutSidebar'         => ! in_array($config['layout'] ?? 'vertical', ['condensed', 'horizontal']),
            'layoutHideTopbar'      => ($config['header_sticky'] ?? 'false') === 'hidden',
            'layoutNavbarSticky'    => ($config['header_sticky'] ?? 'false') === 'true',
            'layoutNavbarCondensed' => ($config['layout'] ?? 'vertical') === 'condensed',
            'layoutNavbarClass'     => ($config['layout'] ?? 'vertical') === 'condensed' ? 'navbar-overlap' : '',
        ];
    }

    /**
     * Store theme settings
     */
    public function store(ThemeTablerRequest $request)
    {
        $mode = $request->input('mode', 'sys');

        $validated = $request->validated();

        // Remove mode from data
        unset($validated['mode']);

        // Map dashed keys to internal config keys
        $configData = $this->mapToConfigKeys($validated);

        // Load existing config and merge
        $existingConfig = $this->loadConfig($mode);
        $mergedConfig   = array_merge($existingConfig, $configData);

        // Save to JSON
        $this->saveConfig($mode, $mergedConfig);

        // Log activity
        logActivity('config', "Theme settings updated for {$mode} mode (JSON)", auth()->user() ?? null);

        return response()->json([
            'success' => true,
            'message' => 'Theme settings saved successfully!',
        ]);
    }

    /**
     * Generate inline CSS style block for theme customization
     */
    public function getStyleBlock(string $mode = 'sys'): string
    {
        $config = $this->loadConfig($mode);
        $css    = [];
        $bgCss  = [];

        // Primary Color
        if (! empty($config['primary_color'])) {
            $primaryRgb = $this->hexToRgb($config['primary_color']);
            $css[]      = "--tblr-primary: {$config['primary_color']};";
            $css[]      = "--tblr-primary-rgb: {$primaryRgb};";
        }

        // Font Family
        if (! empty($config['font_family'])) {
            $fontStack = $this->getFontStack($config['font_family']);
            $css[]     = "--tblr-font-sans-serif: {$fontStack};";
        }

        // Border Radius
        if (isset($config['radius'])) {
            $radius = (float) $config['radius'];
            $css[]  = "--tblr-border-radius: {$radius}rem;";
            $css[]  = "--tblr-border-radius-sm: " . ($radius * 0.75) . "rem;";
            $css[]  = "--tblr-border-radius-lg: " . ($radius * 1.25) . "rem;";
            $css[]  = "--tblr-border-radius-pill: 100rem;";
        }

        // Background Colors (only if not empty and NOT in dark mode)
        if (($config['theme'] ?? 'light') !== 'dark') {
            if (! empty($config['bg_body'])) {
                $bgCss[]   = "--tblr-body-bg: {$config['bg_body']};";
                $textColor = $this->getContrastColor($config['bg_body']);
                $bgCss[]   = "--tblr-body-text: {$textColor};";
            }

            if (! empty($config['bg_sidebar'])) {
                $bgCss[]    = "--tblr-sidebar-bg: {$config['bg_sidebar']};";
                $textColor  = $this->getContrastColor($config['bg_sidebar']);
                $bgCss[]    = "--tblr-sidebar-text: {$textColor};";
                $mutedColor = $this->getLuminance($config['bg_sidebar']) < 0.6
                    ? 'rgba(255, 255, 255, 0.7)'
                    : '#6c757d';
                $bgCss[] = "--tblr-sidebar-text-muted: {$mutedColor};";
            }

            if (! empty($config['bg_header_top'])) {
                $bgCss[]   = "--tblr-header-top-bg: {$config['bg_header_top']};";
                $textColor = $this->getContrastColor($config['bg_header_top']);
                $bgCss[]   = "--tblr-header-top-text: {$textColor};";
            }

            if (! empty($config['bg_boxed'])) {
                $bgCss[] = "--tblr-boxed-bg: {$config['bg_boxed']};";
            }
        }

        // Always allow Header Overlap BG in Dark Mode if needed for depth
        if (! empty($config['bg_header_overlap'])) {
            $overlapCss   = [];
            $overlapCss[] = "--tblr-header-overlap-bg: {$config['bg_header_overlap']};";

            // Generate contrast text color
            $textColor    = $this->getContrastColor($config['bg_header_overlap']);
            $overlapCss[] = "--tblr-header-overlap-text: {$textColor};";

            // Generate muted text color
            $mutedColor = $this->getLuminance($config['bg_header_overlap']) < 0.6
                ? 'rgba(255, 255, 255, 0.7)'
                : '#6c757d';
            $overlapCss[] = "--tblr-header-overlap-text-muted: {$mutedColor};";

            // If in dark mode, we add these to the main :root to ensure they override fallbacks
            if (($config['theme'] ?? 'light') === 'dark') {
                $css = array_merge($css, $overlapCss);
            } else {
                $bgCss = array_merge($bgCss, $overlapCss);
            }
        }

        if (empty($css) && empty($bgCss)) {
            return '';
        }

        $style = "<style>\n";

        if (! empty($css)) {
            $cssString  = implode("\n    ", $css);
            $style     .= ":root {\n    {$cssString}\n}\n";
        }

        if (! empty($bgCss)) {
            $bgCssString  = implode("\n    ", $bgCss);
            // Wrap in :not selector to allow live preview to override server styles
            $style .= "html:not([data-bs-theme=\"dark\"]) {\n    {$bgCssString}\n}\n";
        }

        $style .= "</style>";

        return $style;
    }

    /**
     * Generate HTML attributes for <html> tag
     */
    public function getHtmlAttributes(string $mode = 'sys'): string
    {
        $config     = $this->loadConfig($mode);
        $attributes = [];

        // Theme mode (light/dark)
        $theme        = $config['theme'] ?? 'light';
        $attributes[] = "data-bs-theme=\"{$theme}\"";

        // Font family
        if (! empty($config['font_family'])) {
            $attributes[] = "data-bs-theme-font=\"{$config['font_family']}\"";
        }

        // Theme base
        if (! empty($config['base'])) {
            $attributes[] = "data-bs-theme-base=\"{$config['base']}\"";
        }

        // Card style
        if (! empty($config['card_style']) && $config['card_style'] !== 'flat') {
            $attributes[] = "data-bs-card-style=\"{$config['card_style']}\"";
        }

        // Background indicators
        if ($theme !== 'dark') {
            if (! empty($config['bg_body'])) {
                $attributes[] = 'data-bs-has-theme-bg';
            }
            if (! empty($config['bg_sidebar'])) {
                $attributes[] = 'data-bs-has-sidebar-bg';
            }
            if (! empty($config['bg_header_top'])) {
                $attributes[] = 'data-bs-has-header-top-bg';
            }
        }

        // Overlap indicator: Allow in dark mode if layout is condensed to provide visual depth
        if (! empty($config['bg_header_overlap']) || ($config['layout'] ?? '') === 'condensed') {
            if ($theme !== 'dark' || ($config['layout'] ?? '') === 'condensed') {
                $attributes[] = 'data-bs-has-header-overlap-bg';
            }
        }

        return implode(' ', $attributes);
    }

    /**
     * Generate body classes and attributes
     */
    public function getBodyAttributes(string $mode = 'sys'): string
    {
        $config     = $this->loadConfig($mode);
        $attributes = [];

        // Container width
        if (! empty($config['container_width'])) {
            $attributes[] = "data-container-width=\"{$config['container_width']}\"";
        }

        // Layout classes
        $layout  = $config['layout'] ?? 'vertical';
        $classes = ["layout-{$layout}"];

        if ($layout === 'horizontal') {
            $classes[] = 'layout-horizontal';
        }

        // Boxed layout
        if (($config['container_width'] ?? '') === 'boxed') {
            $classes[] = 'layout-boxed';
        }

        if (! empty($classes)) {
            $attributes[] = 'class="' . implode(' ', $classes) . '"';
        }

        return implode(' ', $attributes);
    }

    /**
     * Load config from JSON file
     */
    private function loadConfig(string $mode): array
    {
        $filename = "theme-{$mode}.json";

        if (Storage::exists($filename)) {
            try {
                $content = Storage::get($filename);
                $config  = json_decode($content, true);
                if (is_array($config)) {
                    return array_merge($this->getDefaultConfig(), $config);
                }
            } catch (Exception $e) {
                // Log error and return defaults
                \Log::warning("Failed to load theme config for {$mode}: " . $e->getMessage());
            }
        }

        // Return defaults and create file
        $defaults = $this->getDefaultConfig();
        $this->saveConfig($mode, $defaults);
        return $defaults;
    }

    /**
     * Save config to JSON file
     */
    private function saveConfig(string $mode, array $data): void
    {
        $filename = "theme-{$mode}.json";

        // Remove null values
        $data = array_filter($data, fn($value) => $value !== null);

        Storage::put($filename, json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Get default configuration
     */
    private function getDefaultConfig(): array
    {
        return [
            'theme'              => 'light',
            'layout'             => 'vertical',
            'container_width'    => 'standard',
            'header_sticky'      => 'false',
            'card_style'         => 'flat',
            'primary_color'      => '#206bc4',
            'font_family'        => 'inter',
            'base'               => 'gray',
            'radius'             => '1',
            'bg_body'            => '',
            'bg_sidebar'         => '',
            'bg_header_top'      => '',
            'bg_header_overlap'  => '',
            'bg_boxed'           => '',
            'auth_layout'        => 'basic',
            'auth_form_position' => 'left',
        ];
    }

    /**
     * Map form keys to config keys
     */
    private function mapToConfigKeys(array $data): array
    {
        $map = [
            'theme'                   => 'theme',
            'theme-font'              => 'font_family',
            'theme-base'              => 'base',
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
            'layout'                  => 'layout',
            'auth-layout'             => 'auth_layout',
            'auth-form-position'      => 'auth_form_position',
        ];

        $result = [];
        foreach ($data as $key => $value) {
            $configKey          = $map[$key] ?? $key;
            $result[$configKey] = $value;
        }

        return $result;
    }

    /**
     * Get font stack for a font family
     */
    private function getFontStack(string $font): string
    {
        $stacks = [
            'inter'       => "'Inter', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
            'roboto'      => "'Roboto', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Helvetica Neue, sans-serif",
            'poppins'     => "'Poppins', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
            'public-sans' => "'Public Sans', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
            'nunito'      => "'Nunito', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif",
        ];

        return $stacks[$font] ?? $stacks['inter'];
    }

    /**
     * Convert color (hex/rgb/rgba) to RGB string "r, g, b"
     */
    private function hexToRgb(string $color): string
    {
        [$r, $g, $b] = $this->extractRgb($color);
        return "{$r}, {$g}, {$b}";
    }

    /**
     * Calculate luminance of a color
     */
    private function getLuminance(string $color): float
    {
        [$r, $g, $b] = $this->extractRgb($color);
        return (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
    }

    /**
     * Extract RGB components from color string
     */
    private function extractRgb(string $color): array
    {
        // Handle RGB/RGBA using simple string cleanup
        if (str_contains($color, ',')) {
            $parts = explode(',', preg_replace('/[^0-9,]/', '', $color));
            return [
                (int) ($parts[0] ?? 0),
                (int) ($parts[1] ?? 0),
                (int) ($parts[2] ?? 0),
            ];
        }

        // Handle Hex (3 or 6 chars)
        $hex = ltrim($color, '#');

        if (strlen($hex) === 3) {
            $r = hexdec($hex[0] . $hex[0]);
            $g = hexdec($hex[1] . $hex[1]);
            $b = hexdec($hex[2] . $hex[2]);
        } else {
            // Ensure 6 chars
            $hex = str_pad($hex, 6, '0');
            $r   = hexdec(substr($hex, 0, 2));
            $g   = hexdec(substr($hex, 2, 2));
            $b   = hexdec(substr($hex, 4, 2));
        }

        return [$r, $g, $b];
    }

    /**
     * Get contrasting text color for a background
     */
    private function getContrastColor(string $bgColor): string
    {
        $luminance = $this->getLuminance($bgColor);
        return $luminance < 0.6 ? '#ffffff' : '#1e293b';
    }
}
