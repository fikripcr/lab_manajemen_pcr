@props(['type' => 'sidebar'])

@php
    $menu = [
        [
            'title' => 'Umpan Balik',
            'route' => 'survei.index',
            'icon'  => 'ti ti-forms',
            'active_routes' => ['survei.*'],
        ],
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
