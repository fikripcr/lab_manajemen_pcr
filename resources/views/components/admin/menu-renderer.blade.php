@php
    // Define the Admin menu structure directly here (Single Source of Truth)
    $menu = [
        [
            'type'  => 'header',
            'title' => 'Summary',
        ],
        [
            'type'  => 'item',
            'title' => 'Dashboard',
            'route' => 'dashboard',
            'icon'  => 'ti ti-layout-dashboard',
        ],
        [
            'type'  => 'header',
            'title' => 'Master Data',
        ],
        [
            'type'  => 'item',
            'title' => 'Data Lab',
            'route' => 'labs.index',
            'active_routes' => ['labs.*'],
            'icon'  => 'ti ti-flask',
        ],
        [
            'type'  => 'item',
            'title' => 'Data Inventaris',
            'route' => 'inventaris.index',
            'active_routes' => ['inventaris.*'],
            'icon'  => 'ti ti-package',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Perkuliahan',
            'id'            => 'navbar-extra',
            'icon'          => 'ti ti-book-2',
            'active_routes' => ['semesters.*', 'mata-kuliah.*', 'jadwal.*'],
            'children'      => [
                [
                    'title'         => 'Data Semester',
                    'route'         => 'semesters.index',
                    'active_routes' => ['semesters.*'],
                ],
                [
                    'title'         => 'Data Mata Kuliah',
                    'route'         => 'mata-kuliah.index',
                    'active_routes' => ['mata-kuliah.*'],
                ],
                [
                    'title'         => 'Jadwal Perkuliahan',
                    'route'         => 'jadwal.index',
                    'active_routes' => ['jadwal.*'],
                ],
            ],
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Info Publik',
            'id'            => 'navbar-info',
            'icon'          => 'ti ti-info-circle',
            'active_routes' => ['pengumuman.*', 'berita.*'],
            'children'      => [
                [
                    'title'         => 'Pengumuman',
                    'route'         => 'pengumuman.index',
                    'active_routes' => ['pengumuman.*'],
                ],
                [
                    'title'         => 'Berita',
                    'route'         => 'berita.index',
                    'active_routes' => ['berita.*'],
                ],
            ],
        ],
        [
            'type'  => 'item',
            'title' => 'Software Requests',
            'route' => 'software-requests.index',
            'active_routes' => ['admin.software-requests.*'],
            'icon'  => 'ti ti-device-laptop',
        ],
        [
            'type'  => 'header',
            'title' => 'Others',
        ],
        [
            'type'  => 'item',
            'title' => 'Users',
            'route' => 'users.index',
            'active_routes' => ['users.*'],
            'icon'  => 'ti ti-users',
        ],
        [
            'type'  => 'item',
            'title' => 'System Management',
            'route' => 'sys.dashboard',
            'icon'  => 'ti ti-settings',
        ],
    ];

    // Helper to check active state
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

    // Helper to render icon (SVG or Class)
    $renderIcon = function($icon, $extraClass = '') {
        if (empty($icon)) return '';
        if (str_contains($icon, '<svg')) {
            return $icon;
        }
        return '<i class="'.$icon.' '.$extraClass.'"></i>';
    };
@endphp

@if($type === 'sidebar')
    <ul class="navbar-nav pt-lg-3">
        {{-- Back to Main Apps (Example Static Link) --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                </span>
                <span class="nav-link-title">Main Website</span>
            </a>
        </li>
        @foreach($menu as $item)
            @if(($item['type'] ?? 'item') === 'header')
                <li class="nav-item mt-3">
                    <span class="nav-link disabled text-uppercase text-muted small fw-bold">{{ $item['title'] ?? '' }}</span>
                </li>
            @elseif(($item['type'] ?? 'item') === 'item')
                <li class="nav-item{{ $isActive($item['active_routes'] ?? $item['route'] ?? null) ? ' active' : '' }}">
                    <a class="nav-link" href="{{ isset($item['route']) ? route($item['route']) : '#' }}">
                        <span class="nav-link-icon d-lg-inline-block">
                            {!! $renderIcon($item['icon'] ?? '', 'fs-2') !!}
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                </li>
            @elseif(($item['type'] ?? 'item') === 'dropdown')
                <li class="nav-item dropdown{{ $isActive($item['active_routes'] ?? []) ? ' active' : '' }}">
                    <a class="nav-link dropdown-toggle{{ $isActive($item['active_routes'] ?? []) ? ' show' : '' }}" 
                       href="#{{ $item['id'] ?? 'menu-'.Str::random(5) }}" 
                       data-bs-toggle="dropdown" 
                       data-bs-auto-close="false" 
                       role="button" 
                       aria-expanded="{{ $isActive($item['active_routes'] ?? []) ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-lg-inline-block">
                            {!! $renderIcon($item['icon'] ?? '', 'fs-2') !!}
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                    <div class="dropdown-menu{{ $isActive($item['active_routes'] ?? []) ? ' show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                @foreach($item['children'] ?? [] as $child)
                                    <a class="dropdown-item{{ $isActive($child['active_routes'] ?? $child['route'] ?? '') ? ' active' : '' }}" 
                                       href="{{ isset($child['route']) ? route($child['route']) : '#' }}">
                                        @if(!empty($child['icon']))
                                            {!! $renderIcon($child['icon'], 'icon-inline me-1') !!}
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
    </ul>

@elseif($type === 'navbar')
    <ul class="navbar-nav">
        @foreach($menu as $item)
            @if(($item['type'] ?? 'item') === 'item')
                <li class="nav-item{{ $isActive($item['active_routes'] ?? $item['route'] ?? null) ? ' active' : '' }}">
                    <a class="nav-link" href="{{ isset($item['route']) ? route($item['route']) : '#' }}">
                        <span class="nav-link-icon d-lg-inline-block">
                            {!! $renderIcon($item['icon'] ?? '', 'fs-2') !!}
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                </li>
            @elseif(($item['type'] ?? 'item') === 'dropdown')
                <li class="nav-item dropdown{{ $isActive($item['active_routes'] ?? []) ? ' active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#{{ $item['id'] ?? 'nav-drop' }}" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-lg-inline-block">
                            {!! $renderIcon($item['icon'] ?? '', 'fs-2') !!}
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                @foreach($item['children'] ?? [] as $child)
                                    <a class="dropdown-item{{ $isActive($child['active_routes'] ?? $child['route'] ?? '') ? ' active' : '' }}" href="{{ isset($child['route']) ? route($child['route']) : '#' }}">
                                        @if(!empty($child['icon']))
                                           {!! $renderIcon($child['icon'], 'icon-inline me-1') !!}
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
    </ul>
@endif
