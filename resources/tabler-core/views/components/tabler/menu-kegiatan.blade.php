@props(['type' => 'sidebar'])

@php
    $menu = [
        [
            'type'          => 'dropdown',
            'title'         => 'Kegiatan',
            'id'            => 'navbar-event',
            'icon'          => 'ti ti-calendar-star',
            'active_routes' => ['Kegiatan.*'],
            'children'      => [
                [
                    'title'         => 'List Kegiatan',
                    'route'         => 'Kegiatan.Kegiatans.index',
                    'active_routes' => ['Kegiatan.Kegiatans.*'],
                    'icon'          => 'ti ti-calendar-event',
                ],
                [
                    'title'         => 'Manajemen Rapat',
                    'route'         => 'Kegiatan.rapat.index',
                    'active_routes' => ['Kegiatan.rapat.*'],
                    'icon'          => 'ti ti-notes',
                ],
                [
                    'title'         => 'Buku Tamu',
                    'route'         => 'Kegiatan.tamus.index',
                    'active_routes' => ['Kegiatan.tamus.*'],
                    'icon'          => 'ti ti-book',
                ],
            ],
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
    @if(($item['type'] ?? 'item') === 'dropdown')
        <li class="nav-item dropdown{{ $isActive($item['active_routes'] ?? []) ? ' active' : '' }}">
            <a class="nav-link dropdown-toggle{{ $isActive($item['active_routes'] ?? []) ? ' show' : '' }}"
               href="#{{ $item['id'] ?? 'menu-'.Str::random(5) }}"
               data-bs-toggle="dropdown"
               data-bs-auto-close="false"
               role="button"
               aria-expanded="{{ $isActive($item['active_routes'] ?? []) ? 'true' : 'false' }}">
                <span class="nav-link-icon d-lg-inline-block">
                    @if(!empty($item['icon']))<x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $item['icon']) }}" />@endif
                </span>
                <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
            </a>
            <div class="dropdown-menu{{ $isActive($item['active_routes'] ?? []) ? ' show' : '' }}">
                <div class="dropdown-menu-columns">
                    <div class="dropdown-menu-column">
                        @foreach($item['children'] ?? [] as $child)
                             <a class="dropdown-item{{ $isActive($child['active_routes'] ?? $child['route'] ?? '') ? ' active' : '' }}"
                                href="{{ (isset($child['route']) && $child['route'] !== '#') ? route($child['route']) : '#' }}">
                                @if(!empty($child['icon']))
                                    <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $child['icon']) }}" class="icon-inline me-1" />
                                @endif
                                {{ $child['title'] ?? '' }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </li>
    @endif
@endforeach
