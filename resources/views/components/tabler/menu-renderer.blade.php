@props(['type' => 'sidebar', 'group' => 'sys'])

@php
    // --- 1. ADMIN MENU STRUCTURE ---
    $adminMenu = [
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
            'type' => 'dropdown',
            'title' => 'SPMI (Pemtu)',
            'icon' => 'ti ti-checkbox',
            'route' => '#',
            'active_routes' => ['pemtu.*'],
            'can' => 'admin',
            'children' => [
                [
                    'title' => 'Struktur Organisasi',
                    'route' => 'pemtu.org-units.index',
                    'active_routes' => ['pemtu.org-units.*'],
                ],
                [
                    'title' => 'Dokumen',
                    'route' => 'pemtu.dokumens.index',
                    'active_routes' => ['pemtu.dokumens.*'],
                ],
                [
                    'title' => 'Personil',
                    'route' => 'pemtu.personils.index',
                    'active_routes' => ['pemtu.personils.*'],
                ],
                [
                    'title' => 'Label',
                    'route' => 'pemtu.labels.index',
                    'active_routes' => ['pemtu.labels.*', 'pemtu.label-types.*'],
                ],
                [
                    'title' => 'Indikator',
                    'route' => 'pemtu.indikators.index',
                    'active_routes' => ['pemtu.indikators.*'],
                ],
            ],
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

    // --- 2. SYS MENU STRUCTURE ---
    $sysMenu = [
        [
            'type'  => 'header',
            'title' => 'Summary',
        ],
        [
            'type'  => 'item',
            'title' => 'Dashboard',
            'route' => 'sys.dashboard',
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>',
        ],
        [
            'type'  => 'header',
            'title' => 'Access Control',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Access Control',
            'id'            => 'navbar-access',
            'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /><path d="M12 11m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 12l0 2.5" /></svg>',
            'active_routes' => ['sys.roles.*', 'sys.permissions.*'],
            'children'      => [
                [
                    'title'         => 'Roles',
                    'route'         => 'sys.roles.index',
                    'active_routes' => ['sys.roles.*'],
                ],
                [
                    'title'         => 'Permissions',
                    'route'         => 'sys.permissions.index',
                    'active_routes' => ['sys.permissions.*'],
                ],
            ],
        ],
        [
            'type'  => 'header',
            'title' => 'System Log',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'System Log',
            'id'            => 'navbar-syslog',
            'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17l0 -5" /><path d="M12 17l0 -1" /><path d="M15 17l0 -3" /></svg>',
            'active_routes' => ['notifications.*', 'activity-log.*', 'sys.error-log.*'],
            'children'      => [
                [
                    'title'         => 'Notifications',
                    'route'         => 'notifications.index',
                    'active_routes' => ['notifications.*'],
                ],
                [
                    'title'         => 'Activity',
                    'route'         => 'activity-log.index',
                    'active_routes' => ['activity-log.*'],
                ],
                [
                    'title'         => 'Error Log',
                    'route'         => 'sys.error-log.index',
                    'active_routes' => ['sys.error-log.*'],
                ],
            ],
        ],
        [
            'type'  => 'header',
            'title' => 'Others',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Others',
            'id'            => 'navbar-others',
            'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M8 12l.01 0" /><path d="M12 12l.01 0" /><path d="M16 12l.01 0" /></svg>',
            'active_routes' => ['app-config', 'sys.test.*', 'sys.backup.*', 'sys.documentation.*'],
            'children'      => [
                [
                    'title'         => 'App Configuration',
                    'route'         => 'app-config',
                    'active_routes' => ['app-config'],
                ],
                [
                    'title'         => 'Test Features',
                    'route'         => 'sys.test.index',
                    'active_routes' => ['sys.test.*'],
                ],
                [
                    'title'         => 'Backup Management',
                    'route'         => 'sys.backup.index',
                    'active_routes' => ['sys.backup.*'],
                ],
                [
                    'title'         => 'Development Guide',
                    'route'         => 'sys.documentation.index',
                    'active_routes' => ['sys.documentation.*'],
                ],
            ],
        ],
    ];

    // --- SELECTION LOGIC ---
    $menu = ($group === 'admin') ? $adminMenu : $sysMenu;

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
    $renderIcon = function($icon) {
        if (empty($icon)) return '';
        if (str_contains($icon, '<svg')) {
            return $icon;
        }
        return '<i class="'.$icon.'"></i>';
    };
@endphp

@if($type === 'sidebar')
    <ul class="navbar-nav pt-lg-3">
        {{-- Back to Main Apps (Static) --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                </span>
                <span class="nav-link-title">Back to Main Apps</span>
            </a>
        </li>

        @foreach($menu as $item)
            @if(($item['type'] ?? 'item') === 'header')
                <li class="nav-item mt-3">
                    <span class="nav-link disabled text-uppercase text-muted small">{{ $item['title'] ?? '' }}</span>
                </li>
            @elseif(($item['type'] ?? 'item') === 'item')
                <li class="nav-item{{ $isActive($item['route'] ?? null) ? ' active' : '' }}">
                    <a class="nav-link" href="{{ (isset($item['route']) && $item['route'] !== '#') ? route($item['route']) : '#' }}">
                        <span class="nav-link-icon d-lg-inline-block">
                            {!! $renderIcon($item['icon'] ?? '') !!}
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
                            {!! $renderIcon($item['icon'] ?? '') !!}
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                    <div class="dropdown-menu{{ $isActive($item['active_routes'] ?? []) ? ' show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                @foreach($item['children'] ?? [] as $child)
                                    @if(isset($child['children']) && count($child['children']) > 0)
                                        {{-- Recursive Nesting (Tabler Dropend for deep nesting, or simple indentation?) 
                                             Tabler sidebar usually supports one level of dropdown.
                                             For deeper nesting, 'dropend' is used but in sidebar it might look odd. 
                                             Let's stick to standard dropdown-item.
                                        --}}
                                        <div class="dropend">
                                            <a class="dropdown-item dropdown-toggle" 
                                               href="#{{ $child['id'] ?? 'submenu-'.Str::random(5) }}" 
                                               data-bs-toggle="dropdown" 
                                               data-bs-auto-close="false" 
                                               role="button" 
                                               aria-expanded="false">
                                                {{ $child['title'] ?? '' }}
                                            </a>
                                            <div class="dropdown-menu">
                                                @foreach($child['children'] as $subchild)
                                                    <a class="dropdown-item{{ $isActive($subchild['route'] ?? null) ? ' active' : '' }}" 
                                                       href="{{ (isset($subchild['route']) && $subchild['route'] !== '#') ? route($subchild['route']) : '#' }}">
                                                        {{ $subchild['title'] ?? '' }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <a class="dropdown-item{{ $isActive($child['active_routes'] ?? $child['route'] ?? '') ? ' active' : '' }}" 
                                           href="{{ (isset($child['route']) && $child['route'] !== '#') ? route($child['route']) : '#' }}">
                                            {{-- Icons optional in submenus --}}
                                            @if(!empty($child['icon']))
                                                {!! $renderIcon($child['icon'], 'icon-inline me-1') !!}
                                            @endif
                                            {{ $child['title'] ?? '' }}
                                        </a>
                                    @endif
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
                <li class="nav-item{{ $isActive($item['route'] ?? null) ? ' active' : '' }}">
                    <a class="nav-link" href="{{ (isset($item['route']) && $item['route'] !== '#') ? route($item['route']) : '#' }}">
                        <span class="nav-link-icon d-lg-inline-block">
                            {!! $renderIcon($item['icon'] ?? '') !!}
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                </li>
            @elseif(($item['type'] ?? 'item') === 'dropdown')
                <li class="nav-item dropdown{{ $isActive($item['active_routes'] ?? []) ? ' active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#{{ $item['id'] ?? 'nav-drop' }}" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-lg-inline-block">
                            {!! $renderIcon($item['icon'] ?? '') !!}
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                @foreach($item['children'] ?? [] as $child)
                                    <a class="dropdown-item{{ $isActive($child['active_routes'] ?? $child['route'] ?? '') ? ' active' : '' }}" 
                                       href="{{ (isset($child['route']) && $child['route'] !== '#') ? route($child['route']) : '#' }}">
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
