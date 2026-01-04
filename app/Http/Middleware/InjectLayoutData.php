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
    protected array $layoutPresets = [
        // === SIDEBAR LAYOUTS (hide topbar/navbar) ===
        'vertical'       => [
            'layoutSidebar'         => true,
            'layoutHideTopbar'      => true,
            'layoutNavbarCondensed' => false,
            'layoutNavbarHideBrand' => false,
            'layoutNavbarSticky'    => false,
            'layoutNavbarDark'      => false,
            'layoutNavbarClass'     => '',
            'bodyClass'             => '',
            'pageHeaderClass'       => '',
            'containerClass'        => 'container-xl',
            'navbarContainerClass'  => 'container-xl',
        ],

        // === HORIZONTAL LAYOUTS (navbar visible, no sidebar) ===
        'horizontal'     => [
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
        ],

        // === CONDENSED LAYOUT (horizontal condensed navbar) ===
        'condensed'      => [
            'layoutSidebar'         => false,
            'layoutSidebarDark'     => false,
            'layoutHideTopbar'      => false,
            'layoutNavbarCondensed' => true,
            'layoutNavbarHideBrand' => false,
            'layoutNavbarSticky'    => false,
            'layoutNavbarDark'      => false,
            'layoutNavbarClass'     => '',
            'bodyClass'             => '',
            'pageHeaderClass'       => '',
            'containerClass'        => 'container-xl',
            'navbarContainerClass'  => 'container-xl',
        ],

        // === COMBO: sidebar + header top (non-condensed) ===
        'combo'          => [
            'layoutSidebar'         => true,
            'layoutHideTopbar'      => false,
            'layoutNavbarCondensed' => false, // Show full header top + separate menu
            'layoutNavbarHideBrand' => false,
            'layoutNavbarSticky'    => false,
            'layoutNavbarDark'      => false,
            'layoutNavbarClass'     => '',
            'bodyClass'             => '',
            'pageHeaderClass'       => '',
            'containerClass'        => 'container-xl',
            'navbarContainerClass'  => 'container-xl',
        ],

        // === NAVBAR VARIATIONS (based on horizontal) ===

        'navbar-overlap' => [
            'layoutSidebar'         => false,
            'layoutSidebarDark'     => false,
            'layoutHideTopbar'      => false,
            'layoutNavbarCondensed' => true, // Menu inside navbar, not separate bar
            'layoutNavbarHideBrand' => false,
            'layoutNavbarSticky'    => false,
            'layoutNavbarDark'      => true, // Dark navbar like Tabler reference
            'layoutNavbarClass'     => 'navbar-overlap',
            'bodyClass'             => '',
            'pageHeaderClass'       => 'text-white',
            'containerClass'        => 'container-xl',
            'navbarContainerClass'  => 'container-xl', // Fluid for overlap
        ],

    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Theme settings from config
        $themeData = [
            'theme'                => env('TABLER_THEME', 'light'),
            'themePrimary'         => env('TABLER_THEME_PRIMARY', 'blue'),
            'themeFont'            => env('TABLER_THEME_FONT', 'sans-serif'),
            'themeBase'            => env('TABLER_THEME_BASE', 'gray'),
            'themeRadius'          => env('TABLER_THEME_RADIUS', '1'),
            'themeBg'              => env('TABLER_THEME_BG', ''),
            'themeSidebarBg'       => env('TABLER_SIDEBAR_BG', ''),
            'themeHeaderTopBg'     => env('TABLER_HEADER_TOP_BG', ''),
            'themeHeaderOverlapBg' => env('TABLER_HEADER_OVERLAP_BG', ''), // New
            'themeHeaderSticky'    => env('TABLER_HEADER_STICKY', false),
            'themeCardStyle'       => env('TABLER_CARD_STYLE', 'default'),
            'themeBoxedBg'         => env('TABLER_BOXED_BG', '#e2e8f0'),
        ];

        // Get layout preset
        $layoutKey = env('TABLER_LAYOUT', 'vertical');
        $preset    = $this->layoutPresets[$layoutKey] ?? $this->layoutPresets['vertical'];

        $containerWidth = env('TABLER_CONTAINER_WIDTH', 'standard');

        // Layout data - using Tabler's exact variable names
        $layoutData = array_merge([
            'layout'         => $layoutKey,
            'containerWidth' => $containerWidth,
        ], $preset);

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
