@props(['type' => 'sidebar'])

@php
    $menu = [
        [
            'title'         => 'Manajemen Proyek',
            'id'            => 'navbar-projects',
            'icon'          => 'ti ti-layout-dashboard',
            'route'         => 'projects.index',
            'active_routes' => ['projects.*'],
        ]
    ];

    $isActive = function ($routes) {
        if (empty($routes)) return false;
        if (is_array($routes)) {
            foreach ($routes as $route) {
                if (request()->routeIs($route)) return true;
            }
            return false;
        }
        return request()->routeIs($routes);
    };
@endphp

@foreach($menu as $item)
    <li class="nav-item{{ $isActive($item['route'] ?? null) ? ' active' : '' }}">
        <a class="nav-link" href="{{ (isset($item['route']) && $item['route'] !== '#') ? route($item['route']) : '#' }}">
            <span class="nav-link-icon d-lg-inline-block">
                @if(!empty($item['icon']))<x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $item['icon']) }}" />@endif
            </span>
            <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
        </a>
    </li>
@endforeach
