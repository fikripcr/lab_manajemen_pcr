<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class InjectLayoutData
{
    /**
     * Layout presets matching Tabler's exact configuration.
     *
     * Tabler's default layout = horizontal (navbar visible, no sidebar)
     * Sidebar only appears when layout-sidebar: true
     */
    /**
     * Default layout configuration (Base).
     * These values are used unless overridden by a specific preset.
     */
    protected array $defaultLayout = [
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
    ];

    /**
     * Layout presets (Overrides).
     * Only define values that differ from $defaultLayout.
     */
    protected array $layoutPresets = [
        // === VERTICAL (Sidebar, No Topbar) ===
        'vertical'       => [
            'layoutSidebar'    => true,
            'layoutHideTopbar' => true,
        ],

        // === HORIZONTAL (Topbar, No Sidebar) ===
        'horizontal'     => [
            // Uses defaults
        ],

        // === CONDENSED (Horizontal Condensed) ===
        'condensed'      => [
            'layoutNavbarCondensed' => true,
        ],

        // === COMBO (Sidebar + Header Top) ===
        'combo'          => [
            'layoutSidebar' => true,
        ],

        // === NAVBAR OVERLAP (Dark overlap header) ===
        'navbar-overlap' => [
            'layoutNavbarCondensed' => true,
            'layoutNavbarDark'      => true,
            'layoutNavbarClass'     => 'navbar-overlap',
            'pageHeaderClass'       => 'text-white',
        ],
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ... (Theme Data Loading - Unchanged) ...
        $currentTheme = env('TABLER_THEME', 'light');

        $themeData = [
            'theme'             => $currentTheme,
            'themePrimary'      => env('TABLER_THEME_PRIMARY', 'blue'),
            'themeFont'         => env('TABLER_THEME_FONT', 'sans-serif'),
            'themeBase'         => env('TABLER_THEME_BASE', 'gray'),
            'themeRadius'       => env('TABLER_THEME_RADIUS', '1'),
            'themeCardStyle'    => env('TABLER_CARD_STYLE', 'default'),
            'themeHeaderSticky' => env('TABLER_HEADER_STICKY', 'false'), // Always string for consistent comparison
            'themeBoxedBg'      => env('TABLER_BOXED_BG', '#e2e8f0'),
        ];

        // IMPORTANT: Only inject custom background colors in LIGHT mode
        // In dark mode, skip these to use Tabler's default dark theme colors
        if ($currentTheme === 'light') {
            $themeData['themeBg']              = env('TABLER_THEME_BG', '');
            $themeData['themeSidebarBg']       = env('TABLER_SIDEBAR_BG', '');
            $themeData['themeHeaderTopBg']     = env('TABLER_HEADER_TOP_BG', '');
            $themeData['themeHeaderOverlapBg'] = env('TABLER_HEADER_OVERLAP_BG', '');
        } else {
            // Dark mode: set empty values so no custom colors are applied
            $themeData['themeBg']              = '';
            $themeData['themeSidebarBg']       = '';
            $themeData['themeHeaderTopBg']     = '';
            $themeData['themeHeaderOverlapBg'] = '';
        }

        // Get layout preset and merge with defaults
        $layoutKey = env('TABLER_LAYOUT', 'vertical');
        $preset    = $this->layoutPresets[$layoutKey] ?? $this->layoutPresets['vertical'];

        $containerWidth = env('TABLER_CONTAINER_WIDTH', 'standard');

        // Merge Default + Preset + Runtime Config
        $layoutData = array_merge($this->defaultLayout, $preset, [
            'layout'         => $layoutKey,
            'containerWidth' => $containerWidth,
        ]);

        // Check for Hidden Header Mode
        $headerMode = env('TABLER_HEADER_STICKY', 'false');
        if ($headerMode === 'hidden') {
            $layoutData['layoutHideTopbar'] = true;
        }

        // Override for Boxed Container Width
        if ($containerWidth === 'boxed') {
            $layoutData['bodyClass']      = trim(($layoutData['bodyClass'] ?? '') . ' layout-boxed');
            $layoutData['containerClass'] = 'container';
            // Also ensure some padding or alignment if needed, but 'layout-boxed' on body usually handles it.
        }

        // Share with all views
        View::share('themeData', $themeData);
        View::share('layoutData', $layoutData);

        return $next($request);
    }
}
