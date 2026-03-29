@props(['type' => 'sidebar'])

@php
    $menu = [
        [
            'type'  => 'header',
            'title' => 'Data Utama',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Master Data',
            'id'            => 'navbar-master-data',
            'icon'          => 'ti ti-database',
            'active_routes' => ['hr.struktur-organisasi.*', 'hr.pegawai.*', 'akademik.mahasiswa.*', 'hr.personil.*'],
            'children' => [
                [
                    'title' => 'Struktur Organisasi',
                    'route' => 'hr.struktur-organisasi.index',
                    'active_routes' => ['hr.struktur-organisasi.*'],
                    'icon'  => 'ti ti-hierarchy-2',
                ],
                [
                    'title' => 'Pegawai',
                    'route' => 'hr.pegawai.index',
                    'active_routes' => ['hr.pegawai.*'],
                    'icon'  => 'ti ti-users',
                ],
                [
                    'title' => 'Mahasiswa',
                    'route' => 'akademik.mahasiswa.index',
                    'active_routes' => ['akademik.mahasiswa.*'],
                    'icon'  => 'ti ti-school',
                ],
                [
                    'title' => 'Personil',
                    'route' => 'hr.personil.index',
                    'active_routes' => ['hr.personil.*'],
                    'icon'  => 'ti ti-user-check',
                ],
                [
                    'title' => 'Users / Pengguna',
                    'route' => 'sys.users.index',
                    'active_routes' => ['sys.users.*'],
                    'icon'  => 'ti ti-users-group',
                    'can'   => 'admin',
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
    @if(($item['type'] ?? 'item') === 'header')
        @if($type === 'sidebar')
            <li class="nav-item mt-3">
                <span class="nav-link disabled text-uppercase text-muted small">{{ $item['title'] ?? '' }}</span>
            </li>
        @else
            <h6 class="dropdown-header">{{ $item['title'] ?? '' }}</h6>
        @endif
    @elseif(($item['type'] ?? 'item') === 'dropdown')
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
