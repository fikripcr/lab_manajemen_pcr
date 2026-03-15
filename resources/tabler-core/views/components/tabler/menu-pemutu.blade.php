@props(['type' => 'sidebar'])

@php
    // --- 1. ADMIN MENU STRUCTURE ---
    $adminMenu = [
        [
            'type'  => 'item',
            'title' => 'Dashboard',
            'route' => 'pemutu.dashboard',
            'icon'  => 'ti ti-layout-dashboard',
        ],
        [
            'type'  => 'item',
            'title' => 'Jadwal Rapat',
            'route' => 'Kegiatan.rapat.index',
            'icon'  => 'ti ti-calendar-event',
        ],
        [
            'type'  => 'item',
            'title' => 'Data Pegawai',
            'route' => 'hr.pegawai.index',
            'icon'  => 'ti ti-user-circle',
        ],

        [
            'type'          => 'dropdown',
            'title'         => 'Master Data',
            'id'            => 'navbar-master-data',
            'icon'          => 'ti ti-database',
            'active_routes' => ['pemutu.label.*', 'pemutu.label-type.*', 'pemutu.periode-spmi.*', 'pemutu.periode-kpi.*', 'pemutu.tim-mutu.*'],
            'children'      => [
                [
                    'title' => 'Label',
                    'route' => 'pemutu.label.index',
                    'active_routes' => ['pemutu.label.*', 'pemutu.label-type.*'],
                    'icon' => 'ti ti-tags',
                    'can' => 'admin',
                ],
                [
                    'title' => 'Periode SPMI',
                    'route' => 'pemutu.periode-spmi.index',
                    'active_routes' => ['pemutu.periode-spmi.*'],
                    'icon' => 'ti ti-refresh',
                    'can' => 'admin',
                ],
                [
                    'title' => 'Periode KPI',
                    'route' => 'pemutu.periode-kpi.index',
                    'active_routes' => ['pemutu.periode-kpi.*'],
                    'icon' => 'ti ti-calendar',
                    'can' => 'admin',
                ],
                [
                    'title' => 'Tim Mutu',
                    'route' => 'pemutu.tim-mutu.index',
                    'active_routes' => ['pemutu.tim-mutu.*'],
                    'icon' => 'ti ti-users-group',
                    'can' => 'admin',
                ],
            ],
        ],

        [
            'type'  => 'header',
            'title' => 'Siklus SPMI',
        ],
        [
            'title'         => 'Penetapan',
            'type'          => 'dropdown',
            'id'            => 'navbar-penetapan',
            'icon'          => 'ti ti-file-text',
            'active_routes' => ['pemutu.dokumen.*', 'pemutu.dokumen-spmi.index', 'pemutu.dokumen-spmi.show', 'pemutu.dokumen-spmi.create', 'pemutu.dokumen-spmi.edit', 'pemutu.standar.*', 'pemutu.indikator.*', 'pemutu.renop.*'],
            'children'      => [
                [
                    'title'         => 'Dokumen',
                    'route'         => 'pemutu.dokumen.index',
                    'query'         => ['jenis' => 'visi'],
                    'active_routes' => ['pemutu.dokumen.*', 'pemutu.dokumen-spmi.index', 'pemutu.dokumen-spmi.show', 'pemutu.dokumen-spmi.create', 'pemutu.dokumen-spmi.edit'],
                    'icon'          => 'ti ti-file-text',
                ],
                [
                    'title'         => 'Indikator',
                    'route'         => 'pemutu.indikator.index',
                    'active_routes' => ['pemutu.indikator.*', 'pemutu.renop.*'],
                    'icon'          => 'ti ti-target',
                ],
            ],
        ],
        [
            'title'         => 'Pelaksanaan',
            'type'          => 'dropdown',
            'id'            => 'navbar-pelaksanaan',
            'icon'          => 'ti ti-broadcast',
            'active_routes' => ['pemutu.pelaksanaan.*'],
            'children'      => [
                [
                    'title'         => 'Pemantauan',
                    'route'         => 'pemutu.pelaksanaan.pemantauan.index',
                    'active_routes' => ['pemutu.pelaksanaan.pemantauan.*'],
                    'icon'          => 'ti ti-device-heart-monitor',
                ],
            ],
        ],
        [
            'title'         => 'Evaluasi',
            'type'          => 'dropdown',
            'id'            => 'navbar-evaluasi',
            'icon'          => 'ti ti-chart-bar',
            'active_routes' => ['pemutu.evaluasi-diri.*', 'pemutu.evaluasi-kpi.*', 'pemutu.ami.*'],
            'children'      => [
                [
                    'title'         => 'Evaluasi Diri',
                    'route'         => 'pemutu.evaluasi-diri.index',
                    'active_routes' => ['pemutu.evaluasi-diri.*'],
                    'icon'          => 'ti ti-clipboard-check',
                ],
                [
                    'title'         => 'Evaluasi KPI',
                    'route'         => 'pemutu.evaluasi-kpi.index',
                    'active_routes' => ['pemutu.evaluasi-kpi.*'],
                    'icon'          => 'ti ti-clipboard-data',
                ],
                [
                    'title'         => 'Audit Mutu Internal',
                    'route'         => 'pemutu.ami.index',
                    'active_routes' => ['pemutu.ami.*'],
                    'icon'          => 'ti ti-shield-check',
                ],
            ],
        ],
        [
            'type'          => 'item',
            'title'         => 'Pengendalian',
            'route'         => 'pemutu.pengendalian.index',
            'active_routes' => ['pemutu.pengendalian.*'],
            'icon'          => 'ti ti-settings-check',
        ],
        [
            'type'          => 'item',
            'title'         => 'Peningkatan',
            'route'         => 'pemutu.peningkatan.index',
            'active_routes' => ['pemutu.peningkatan.*'],
            'icon'          => 'ti ti-trending-up',
        ],
        [
            'title'         => 'Summary',
            'id'            => 'navbar-summary-new',
            'icon'          => 'ti ti-chart-pie',
            'active_routes' => ['pemutu.indikator-summary.*', 'pemutu.dokumen-spmi.summary'],
            'type'          => 'dropdown',
            'children'      => [
                [
                    'title'         => 'Summary Dokumen',
                    'route'         => 'pemutu.dokumen-spmi.summary',
                    'active_routes' => ['pemutu.dokumen-spmi.summary'],
                    'icon'          => 'ti ti-file-analytics',
                ],
                [
                    'title'         => 'Indikator Standar',
                    'route'         => 'pemutu.indikator-summary.standar',
                    'active_routes' => ['pemutu.indikator-summary.standar', 'pemutu.indikator-summary.data-standar'],
                    'icon'          => 'ti ti-book',
                ],
                [
                    'title'         => 'Indikator Performa',
                    'route'         => 'pemutu.indikator-summary.performa',
                    'active_routes' => ['pemutu.indikator-summary.performa', 'pemutu.indikator-summary.data-performa'],
                    'icon'          => 'ti ti-chart-line',
                ],
            ],
        ],
    ];

    // --- SELECTION LOGIC ---
    $menu = $adminMenu;

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

    // $renderIcon function removed, using x-icon-svg component directly
@endphp

@if($type === 'sidebar')
    <ul class="navbar-nav pt-lg-3">

        @foreach($menu as $item)
            @if(($item['type'] ?? 'item') === 'header')
                <li class="nav-item mt-3">
                    <span class="nav-link disabled text-uppercase text-muted small">{{ $item['title'] ?? '' }}</span>
                </li>
            @elseif(($item['type'] ?? 'item') === 'item')
                <li class="nav-item{{ $isActive($item['route'] ?? null) ? ' active' : '' }}">
                    <a class="nav-link" href="{{ (isset($item['route']) && $item['route'] !== '#') ? route($item['route']) : '#' }}">
                        <span class="nav-link-icon d-lg-inline-block">
                            @if(!empty($item['icon'])) <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $item['icon']) }}" /> @endif
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
                            @if(!empty($item['icon'])) <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $item['icon']) }}" /> @endif
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                    <div class="dropdown-menu{{ $isActive($item['active_routes'] ?? []) ? ' show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                @foreach($item['children'] ?? [] as $child)
                                    @if(($child['type'] ?? 'item') === 'header')
                                        <span class="dropdown-header">{{ $child['title'] ?? '' }}</span>
                                    @elseif(isset($child['children']) && count($child['children']) > 0)
                                        {{-- Recursive Nesting --}}
                                        @php
                                            $isChildActive = $isActive($child['active_routes'] ?? []);
                                        @endphp
                                        <div class="dropend">
                                            <a class="dropdown-item dropdown-toggle{{ $isChildActive ? ' show' : '' }}"
                                               href="#{{ $child['id'] ?? 'submenu-'.Str::random(5) }}"
                                               data-bs-toggle="dropdown"
                                               data-bs-auto-close="false"
                                               role="button"
                                               aria-expanded="{{ $isChildActive ? 'true' : 'false' }}">
                                                @if(!empty($child['icon']))
                                                    <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $child['icon']) }}" class="icon-inline me-1" />
                                                @endif
                                                {{ $child['title'] ?? '' }}
                                            </a>
                                            <div class="dropdown-menu{{ $isChildActive ? ' show' : '' }}">
                                                @foreach($child['children'] as $subchild)
                                                    @php
                                                        $subHref = (isset($subchild['route']) && $subchild['route'] !== '#')
                                                            ? route($subchild['route'], $subchild['query'] ?? [])
                                                            : '#';
                                                        $subIsActive = !empty($subchild['query'])
                                                            ? $isActive($subchild['active_routes'] ?? []) && collect($subchild['query'])->every(fn($v, $k) => request($k) == $v)
                                                            : $isActive($subchild['active_routes'] ?? $subchild['route'] ?? null);
                                                    @endphp
                                                    <a class="dropdown-item{{ $subIsActive ? ' active' : '' }}"
                                                       href="{{ $subHref }}">
                                                        @if(!empty($subchild['icon']))
                                                            <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $subchild['icon']) }}" class="icon-inline me-1" />
                                                        @endif
                                                        {{ $subchild['title'] ?? '' }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        @php
                                            $childHref = (isset($child['route']) && $child['route'] !== '#')
                                                ? route($child['route'], $child['query'] ?? [])
                                                : '#';
                                            $childIsActive = !empty($child['query'])
                                                ? $isActive($child['active_routes'] ?? []) && collect($child['query'])->every(fn($v, $k) => request($k) == $v)
                                                : $isActive($child['active_routes'] ?? $child['route'] ?? '');
                                        @endphp
                                        <a class="dropdown-item{{ $childIsActive ? ' active' : '' }}"
                                           href="{{ $childHref }}">
                                            {{-- Icons optional in submenus --}}
                                            @if(!empty($child['icon']))
                                                <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $child['icon']) }}" class="icon-inline me-1" />
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
                            @if(!empty($item['icon'])) <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $item['icon']) }}" /> @endif
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                </li>
            @elseif(($item['type'] ?? 'item') === 'dropdown')
                <li class="nav-item dropdown{{ $isActive($item['active_routes'] ?? []) ? ' active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#{{ $item['id'] ?? 'nav-drop' }}" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-lg-inline-block">
                            @if(!empty($item['icon'])) <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $item['icon']) }}" /> @endif
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                @foreach($item['children'] ?? [] as $child)
                                    @if(($child['type'] ?? 'item') === 'header')
                                         <h6 class="dropdown-header">{{ $child['title'] ?? '' }}</h6>
                                    @elseif(isset($child['children']) && count($child['children']) > 0)
                                        @php $isChildActive = $isActive($child['active_routes'] ?? []); @endphp
                                        <div class="dropend">
                                            <a class="dropdown-item dropdown-toggle{{ $isChildActive ? ' show' : '' }}"
                                               href="javascript:void(0)"
                                               data-bs-toggle="dropdown"
                                               data-bs-auto-close="outside"
                                               role="button"
                                               aria-expanded="{{ $isChildActive ? 'true' : 'false' }}">
                                                @if(!empty($child['icon']))
                                                   <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $child['icon']) }}" class="icon-inline me-1" />
                                                @endif
                                                {{ $child['title'] ?? '' }}
                                            </a>
                                            <div class="dropdown-menu{{ $isChildActive ? ' show' : '' }}">
                                                @foreach($child['children'] as $subchild)
                                                    <a class="dropdown-item{{ $isActive($subchild['route'] ?? null) ? ' active' : '' }}"
                                                       href="{{ (isset($subchild['route']) && $subchild['route'] !== '#') ? route($subchild['route']) : '#' }}">
                                                        @if(!empty($subchild['icon']))
                                                            <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $subchild['icon']) }}" class="icon-inline me-1" />
                                                        @endif
                                                        {{ $subchild['title'] ?? '' }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <a class="dropdown-item{{ $isActive($child['active_routes'] ?? $child['route'] ?? '') ? ' active' : '' }}"
                                           href="{{ (isset($child['route']) && $child['route'] !== '#') ? route($child['route']) : '#' }}">
                                            @if(!empty($child['icon']))
                                               <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $child['icon']) }}" class="icon-inline me-1" />
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
@endif
