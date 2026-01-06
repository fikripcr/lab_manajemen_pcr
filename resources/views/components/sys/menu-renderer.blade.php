@props(['type' => 'sidebar'])

@php
    // Define the menu structure directly here (Single Source of Truth)
    $menu = [
        [
            'type'  => 'header',
            'title' => 'Summary',
        ],
        [
            'type'  => 'item',
            'title' => 'Dashboard',
            'route' => 'sys.dashboard',
            'icon'  => 'ti ti-home',
        ],
        [
            'type'  => 'header',
            'title' => 'Access Control',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Access Control',
            'id'            => 'navbar-access',
            'icon'          => 'ti ti-shield-lock',
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
            'icon'          => 'ti ti-file-analytics',
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
            'icon'          => 'ti ti-dots-circle-horizontal',
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
        // Dummy Nested Example for User Request
        [
            'type'  => 'header',
            'title' => 'Nested Example',
        ],
        [
            'type' => 'dropdown',
            'title' => 'Level 1',
            'icon' => 'ti ti-folder',
            'id' => 'navbar-level1',
            'children' => [
                 [
                    'title' => 'Level 2 Item',
                    'icon' => 'ti ti-file',
                    'route' => 'sys.dashboard', // Dummy route
                 ],
                 [
                    'type' => 'dropdown', // Nested Dropdown
                    'title' => 'Level 2 Parent',
                    'icon' => 'ti ti-folder-open',
                    'children' => [
                         [
                            'title' => 'Level 3 Item',
                            'icon' => 'ti ti-file-text',
                            'route' => 'sys.dashboard',
                         ]
                    ]
                 ]
            ]
        ]
    ];

    // Helper to check active state
    $isActive = function ($routes) {
        if (empty($routes)) return false;
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
                    <a class="nav-link" href="{{ isset($item['route']) ? route($item['route']) : '#' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
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
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
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
                                                       href="{{ isset($subchild['route']) ? route($subchild['route']) : '#' }}">
                                                        {{ $subchild['title'] ?? '' }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <a class="dropdown-item{{ $isActive($child['active_routes'] ?? $child['route'] ?? '') ? ' active' : '' }}" 
                                           href="{{ isset($child['route']) ? route($child['route']) : '#' }}">
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
                    <a class="nav-link" href="{{ isset($item['route']) ? route($item['route']) : '#' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            {!! $renderIcon($item['icon'] ?? '') !!}
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                </li>
            @elseif(($item['type'] ?? 'item') === 'dropdown')
                <li class="nav-item dropdown{{ $isActive($item['active_routes'] ?? []) ? ' active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#{{ $item['id'] ?? 'nav-drop' }}" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            {!! $renderIcon($item['icon'] ?? '') !!}
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
