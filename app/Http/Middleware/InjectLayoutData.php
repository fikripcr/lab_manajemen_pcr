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
        'vertical'             => [
            'layoutSidebar'         => true,
            'layoutSidebarDark'     => true,
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
        'horizontal'           => [
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
        'condensed'            => [
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

        // === COMBO: sidebar + condensed navbar (navbar desktop only) ===
        'combo'                => [
            'layoutSidebar'         => true,
            'layoutSidebarDark'     => true,
            'layoutHideTopbar'      => false,
            'layoutNavbarCondensed' => true,
            'layoutNavbarHideBrand' => true,
            'layoutNavbarSticky'    => false,
            'layoutNavbarDark'      => false,
            'layoutNavbarClass'     => 'd-none d-lg-flex',
            'bodyClass'             => '',
            'pageHeaderClass'       => '',
            'containerClass'        => 'container-xl',
            'navbarContainerClass'  => 'container-xl',
        ],

        // === BODY CLASS VARIATIONS (based on horizontal) ===
        'boxed'                => [
            'layoutSidebar'         => false,
            'layoutSidebarDark'     => false,
            'layoutHideTopbar'      => false,
            'layoutNavbarCondensed' => false,
            'layoutNavbarHideBrand' => false,
            'layoutNavbarSticky'    => false,
            'layoutNavbarDark'      => false,
            'layoutNavbarClass'     => '',
            'bodyClass'             => 'layout-boxed',
            'pageHeaderClass'       => '',
            'containerClass'        => 'container',
            'navbarContainerClass'  => 'container-xl',
        ],
        'fluid'                => [
            'layoutSidebar'         => false,
            'layoutSidebarDark'     => false,
            'layoutHideTopbar'      => false,
            'layoutNavbarCondensed' => false,
            'layoutNavbarHideBrand' => false,
            'layoutNavbarSticky'    => false,
            'layoutNavbarDark'      => false,
            'layoutNavbarClass'     => '',
            'bodyClass'             => 'layout-fluid',
            'pageHeaderClass'       => '',
            'containerClass'        => 'container-xl',
            'navbarContainerClass'  => 'container-xl',
        ],

        // === NAVBAR VARIATIONS (based on horizontal) ===
        'navbar-sticky'        => [
            'layoutSidebar'             => false,
            'layoutSidebarDark'         => false,
            'layoutHideTopbar'          => false,
            'layoutNavbarCondensed'     => false,
            'layoutNavbarHideBrand'     => false,
            'layoutNavbarSticky'        => true,
            'layoutNavbarStickyWrapper' => true, // IMPORTANT: Wrap navbar in sticky-top div
            'layoutNavbarDark'          => false,
            'layoutNavbarClass'         => '',
            'bodyClass'                 => '',
            'pageHeaderClass'           => '',
            'containerClass'            => 'container-xl',
            'navbarContainerClass'      => 'container-xl',
        ],
        'navbar-overlap'       => [
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
            'navbarContainerClass'  => 'container-fluid', // Fluid for overlap
        ],
        'navbar-dark'          => [
            'layoutSidebar'         => false,
            'layoutSidebarDark'     => false,
            'layoutHideTopbar'      => false,
            'layoutNavbarCondensed' => false, // Menu in separate navbar below
            'layoutNavbarHideBrand' => false,
            'layoutNavbarSticky'    => false,
            'layoutNavbarDark'      => true,
            'layoutNavbarClass'     => '',
            'bodyClass'             => '',
            'pageHeaderClass'       => '',
            'containerClass'        => 'container-xl',
            'navbarContainerClass'  => 'container-xl',
        ],

        // === VERTICAL + FLUID COMBO ===
        'fluid-vertical'       => [
            'layoutSidebar'         => true,
            'layoutSidebarDark'     => true,
            'layoutHideTopbar'      => true,
            'layoutNavbarCondensed' => false,
            'layoutNavbarHideBrand' => false,
            'layoutNavbarSticky'    => false,
            'layoutNavbarDark'      => false,
            'layoutNavbarClass'     => '',
            'bodyClass'             => 'layout-fluid',
            'pageHeaderClass'       => '',
            'containerClass'        => 'container-xl',
            'navbarContainerClass'  => 'container-xl',
        ],

        // === VERTICAL TRANSPARENT (light sidebar) ===
        'vertical-transparent' => [
            'layoutSidebar'         => true,
            'layoutSidebarDark'     => false, // Light sidebar for transparent effect
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
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Theme settings from config
        $themeData = [
            'theme'        => env('TABLER_THEME', 'light'),
            'themePrimary' => env('TABLER_THEME_PRIMARY', 'blue'),
            'themeFont'    => env('TABLER_THEME_FONT', 'sans-serif'),
            'themeBase'    => env('TABLER_THEME_BASE', 'gray'),
            'themeRadius'  => env('TABLER_THEME_RADIUS', '1'),
            'themeBg'      => env('TABLER_THEME_BG', ''),
        ];

        // Get layout preset
        $layoutKey = env('TABLER_LAYOUT', 'vertical');
        $preset    = $this->layoutPresets[$layoutKey] ?? $this->layoutPresets['vertical'];

        // Layout data - using Tabler's exact variable names
        $layoutData = array_merge(['layout' => $layoutKey], $preset);

        // Share with all views
        View::share('themeData', $themeData);
        View::share('layoutData', $layoutData);

        return $next($request);
    }
}
