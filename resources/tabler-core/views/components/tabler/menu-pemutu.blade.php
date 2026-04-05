@props(['type' => 'sidebar'])

@php
    // Helper: check permission
    $canAccess = function (?string $permission = null): bool {
        if (! $permission) return true;
        return auth()->check() && auth()->user()->can($permission);
    };

    // --- MENU STRUCTURE ---
    $menuItems = [
        [
            'type'  => 'item',
            'title' => 'Dashboard',
            'route' => 'pemutu.dashboard',
            'icon'  => 'ti ti-layout-dashboard',
            'permission' => 'pemutu.dashboard.view',
        ],

        [
            'type'          => 'dropdown',
            'title'         => 'Master Data',
            'id'            => 'navbar-master-data',
            'icon'          => 'ti ti-database',
            'active_routes' => ['pemutu.label.*', 'pemutu.periode-spmi.*', 'pemutu.periode-kpi.*', 'pemutu.tim-mutu.*', 'pemutu.pegawai.*', 'hr.struktur-organisasi.*'],
            'permission'    => null, // Show if any child is accessible
            'children'      => [
                [
                    'title' => 'Data Pegawai',
                    'route' => 'pemutu.pegawai.index',
                    'active_routes' => ['pemutu.pegawai.*'],
                    'icon' => 'ti ti-users',
                    'permission' => 'pemutu.pegawai.view',
                ],
                [
                    'title' => 'Struktur Organisasi',
                    'route' => 'hr.struktur-organisasi.index',
                    'active_routes' => ['hr.struktur-organisasi.*'],
                    'icon' => 'ti ti-hierarchy-2',
                    'permission' => null, // Semua bisa lihat
                ],
                [
                    'title' => 'Label',
                    'route' => 'pemutu.label.index',
                    'active_routes' => ['pemutu.label.*'],
                    'icon' => 'ti ti-tags',
                    'permission' => 'pemutu.label.view',
                ],
                [
                    'title' => 'Periode SPMI',
                    'route' => 'pemutu.periode-spmi.index',
                    'active_routes' => ['pemutu.periode-spmi.*'],
                    'icon' => 'ti ti-refresh',
                    'permission' => 'pemutu.periode-spmi.view',
                ],
                [
                    'title' => 'Periode KPI',
                    'route' => 'pemutu.periode-kpi.index',
                    'active_routes' => ['pemutu.periode-kpi.*'],
                    'icon' => 'ti ti-calendar',
                    'permission' => 'pemutu.periode-kpi.view',
                ],
                [
                    'title' => 'Tim Mutu',
                    'route' => 'pemutu.tim-mutu.index',
                    'active_routes' => ['pemutu.tim-mutu.*'],
                    'icon' => 'ti ti-users-group',
                    'permission' => 'pemutu.tim-mutu.view',
                ],
            ],
        ],

        [
            'type'          => 'item',
            'title'         => 'Approval Dokumen',
            'route'         => 'pemutu.approval.index',
            'active_routes' => ['pemutu.approval.*'],
            'icon'          => 'ti ti-file-check',
            'permission'    => 'pemutu.approval.view',
        ],

        [
            'type'  => 'header',
            'title' => 'Siklus PPEPP',
        ],
        [
            'title'         => 'Penetapan',
            'type'          => 'dropdown',
            'id'            => 'navbar-penetapan',
            'icon'          => 'ti ti-file-text',
            'active_routes' => ['pemutu.dokumen.*', 'pemutu.standar.*', 'pemutu.indikator.*'],
            'children'      => [
                [
                    'title'         => 'Dokumen',
                    'route'         => 'pemutu.dokumen.index',
                    'query'         => ['jenis' => 'visi'],
                    'active_routes' => ['pemutu.dokumen.*'],
                    'icon'          => 'ti ti-file-text',
                    'permission'    => 'pemutu.dokumen.view',
                ],
                [
                    'title'         => 'Standar',
                    'route'         => 'pemutu.standar.index',
                    'active_routes' => ['pemutu.standar.*'],
                    'icon'          => 'ti ti-award',
                    'permission'    => 'pemutu.standar.view',
                ],
                [
                    'title'         => 'Indikator',
                    'route'         => 'pemutu.indikator.index',
                    'active_routes' => ['pemutu.indikator.*'],
                    'icon'          => 'ti ti-target',
                    'permission'    => 'pemutu.indikator.view',
                ],
            ],
        ],
        [
            'type'          => 'item',
            'title'         => 'Pelaksanaan/Pemantauan',
            'route'         => 'pemutu.pemantauan.index',
            'active_routes' => ['pemutu.pemantauan.*'],
            'icon'          => 'ti ti-broadcast',
            'permission'    => 'pemutu.pemantauan.view',
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
                    'permission'    => 'pemutu.evaluasi-diri.view',
                ],
                [
                    'title'         => 'Evaluasi KPI',
                    'route'         => 'pemutu.evaluasi-kpi.index',
                    'active_routes' => ['pemutu.evaluasi-kpi.*'],
                    'icon'          => 'ti ti-clipboard-data',
                    'permission'    => 'pemutu.evaluasi-kpi.view',
                ],
                [
                    'title'         => 'Audit Mutu Internal',
                    'route'         => 'pemutu.ami.index',
                    'active_routes' => ['pemutu.ami.*'],
                    'icon'          => 'ti ti-shield-check',
                    'permission'    => 'pemutu.ami.view',
                ],
            ],
        ],
        [
            'type'          => 'item',
            'title'         => 'Pengendalian',
            'route'         => 'pemutu.pengendalian.index',
            'active_routes' => ['pemutu.pengendalian.*'],
            'icon'          => 'ti ti-settings-check',
            'permission'    => 'pemutu.pengendalian.view',
        ],
        [
            'type'          => 'item',
            'title'         => 'Peningkatan',
            'route'         => 'pemutu.peningkatan.index',
            'active_routes' => ['pemutu.peningkatan.*'],
            'icon'          => 'ti ti-trending-up',
            'permission'    => 'pemutu.peningkatan.view',
        ],
        [
            'title'         => 'Summary',
            'id'            => 'navbar-summary-new',
            'icon'          => 'ti ti-chart-pie',
            'active_routes' => ['pemutu.summary.*'],
            'type'          => 'dropdown',
            'children'      => [
                [
                    'title'         => 'Summary Dokumen',
                    'route'         => 'pemutu.summary.standar',
                    'active_routes' => ['pemutu.summary.standar'],
                    'icon'          => 'ti ti-file-analytics',
                    'permission'    => 'pemutu.summary.view',
                ],
                [
                    'title'         => 'Indikator Standar',
                    'route'         => 'pemutu.summary.standar',
                    'active_routes' => ['pemutu.summary.standar'],
                    'icon'          => 'ti ti-book',
                    'permission'    => 'pemutu.summary.view',
                ],
                [
                    'title'         => 'Indikator Performa',
                    'route'         => 'pemutu.summary.performa',
                    'active_routes' => ['pemutu.summary.performa'],
                    'icon'          => 'ti ti-chart-line',
                    'permission'    => 'pemutu.summary.view',
                ],
                [
                    'title'         => 'Histori PPEPP 5 Tahun',
                    'route'         => 'pemutu.summary.five-year',
                    'active_routes' => ['pemutu.summary.five-year'],
                    'icon'          => 'ti ti-dot',
                    'permission'    => 'pemutu.summary.view',
                ],
            ],
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

    // Check if dropdown has any accessible child
    $hasAccessibleChild = function ($children) use ($canAccess) {
        foreach ($children as $child) {
            if ($canAccess($child['permission'] ?? null)) return true;
        }
        return false;
    };
@endphp

@if($type === 'sidebar')
    <ul class="navbar-nav pt-lg-3">

        @foreach($menuItems as $item)
            @if(($item['type'] ?? 'item') === 'header')
                <li class="nav-item mt-3">
                    <span class="nav-link disabled text-uppercase text-muted small">{{ $item['title'] ?? '' }}</span>
                </li>

            @elseif(($item['type'] ?? 'item') === 'item')
                @if($canAccess($item['permission'] ?? null))
                    <li class="nav-item{{ $isActive($item['route'] ?? null) ? ' active' : '' }}">
                        <a class="nav-link" href="{{ isset($item['route']) && $item['route'] !== '#' ? route($item['route']) : '#' }}">
                            <span class="nav-link-icon d-lg-inline-block">
                                @if(!empty($item['icon'])) <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $item['icon']) }}" /> @endif
                            </span>
                            <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                        </a>
                    </li>
                @endif

            @elseif(($item['type'] ?? 'item') === 'dropdown')
                {{-- Only show dropdown if parent or any child is accessible --}}
                @if($canAccess($item['permission'] ?? null) || $hasAccessibleChild($item['children'] ?? []))
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
                                            @php
                                                $isChildActive = $isActive($child['active_routes'] ?? []);
                                                $canAccessChild = $canAccess($child['permission'] ?? null);
                                            @endphp
                                            @if($canAccessChild)
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
                                                                $subCanAccess = $canAccess($subchild['permission'] ?? null);
                                                            @endphp
                                                            @if($subCanAccess)
                                                                <a class="dropdown-item{{ $subIsActive ? ' active' : '' }}"
                                                                   href="{{ $subHref }}">
                                                                    @if(!empty($subchild['icon']))
                                                                        <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $subchild['icon']) }}" class="icon-inline me-1" />
                                                                    @endif
                                                                    {{ $subchild['title'] ?? '' }}
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            @php
                                                $canAccessChild = $canAccess($child['permission'] ?? null);
                                            @endphp
                                            @if($canAccessChild)
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
                                                    @if(!empty($child['icon']))
                                                        <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $child['icon']) }}" class="icon-inline me-1" />
                                                    @endif
                                                    {{ $child['title'] ?? '' }}
                                                </a>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </li>
                @endif
            @endif
        @endforeach
    </ul>

@elseif($type === 'navbar')
    <ul class="navbar-nav">
        @foreach($menuItems as $item)
            @if(($item['type'] ?? 'item') === 'item')
                @if($canAccess($item['permission'] ?? null))
                    <li class="nav-item{{ $isActive($item['route'] ?? null) ? ' active' : '' }}">
                        <a class="nav-link" href="{{ isset($item['route']) && $item['route'] !== '#' ? route($item['route']) : '#' }}">
                            <span class="nav-link-icon d-lg-inline-block">
                                @if(!empty($item['icon'])) <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $item['icon']) }}" /> @endif
                            </span>
                            <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                        </a>
                    </li>
                @endif
            @elseif(($item['type'] ?? 'item') === 'dropdown')
                @if($canAccess($item['permission'] ?? null) || $hasAccessibleChild($item['children'] ?? []))
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
                                            @if($canAccess($child['permission'] ?? null))
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
                                                            @if($canAccess($subchild['permission'] ?? null))
                                                                <a class="dropdown-item{{ $isActive($subchild['route'] ?? null) ? ' active' : '' }}"
                                                                   href="{{ isset($subchild['route']) && $subchild['route'] !== '#' ? route($subchild['route']) : '#' }}">
                                                                    @if(!empty($subchild['icon']))
                                                                        <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $subchild['icon']) }}" class="icon-inline me-1" />
                                                                    @endif
                                                                    {{ $subchild['title'] ?? '' }}
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            @if($canAccess($child['permission'] ?? null))
                                                <a class="dropdown-item{{ $isActive($child['active_routes'] ?? $child['route'] ?? '') ? ' active' : '' }}"
                                                   href="{{ isset($child['route']) && $child['route'] !== '#' ? route($child['route']) : '#' }}">
                                                    @if(!empty($child['icon']))
                                                       <x-tabler.icon-svg name="{{ str_replace('ti ti-', '', $child['icon']) }}" class="icon-inline me-1" />
                                                    @endif
                                                    {{ $child['title'] ?? '' }}
                                                </a>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </li>
                @endif
            @endif
        @endforeach
    </ul>
@endif
