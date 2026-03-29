@props(['type' => 'sidebar'])

@php
    $menu = [
        [
            'type'          => 'dropdown',
            'title'         => 'Penerimaan Mahasiswa Baru',
            'id'            => 'navbar-pmb',
            'icon'          => 'ti ti-school',
            'active_routes' => ['pmb.*'],
            'children'      => [
                [
                    'title'         => 'Master Data',
                    'id'            => 'navbar-pmb-master',
                    'icon'          => 'ti ti-database',
                    'active_routes' => ['pmb.periode.*', 'pmb.jalur.*', 'pmb.prodi.*', 'pmb.jenis-dokumen.*'],
                    'children'      => [
                        ['title' => 'Periode', 'route' => 'pmb.periode.index', 'active_routes' => ['pmb.periode.*'], 'icon' => 'ti ti-calendar'],
                        ['title' => 'Jalur Pendaftaran', 'route' => 'pmb.jalur.index', 'active_routes' => ['pmb.jalur.*'], 'icon' => 'ti ti-map-2'],
                        ['title' => 'Jenis Dokumen', 'route' => 'pmb.jenis-dokumen.index', 'active_routes' => ['pmb.jenis-dokumen.*'], 'icon' => 'ti ti-file-text'],
                    ],
                ],
                ['title' => 'Calon Mahasiswa Baru', 'route' => 'pmb.camaba.index', 'active_routes' => ['pmb.camaba.*'], 'icon' => 'ti ti-users'],
                ['title' => 'Pendaftar', 'route' => 'pmb.pendaftar.index', 'active_routes' => ['pmb.pendaftar.*'], 'icon' => 'ti ti-file-text'],
                ['title' => 'Pembayaran', 'route' => 'pmb.verification.payments', 'active_routes' => ['pmb.verification.payments*'], 'icon' => 'ti ti-cash'],
                ['title' => 'Sesi Ujian', 'route' => 'pmb.sesi-ujian.index', 'active_routes' => ['pmb.sesi-ujian.*'], 'icon' => 'ti ti-clock'],
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
                            @if(isset($child['children']) && count($child['children']) > 0)
                                @php $isChildActive = $isActive($child['active_routes'] ?? []); @endphp
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
                                            <a class="dropdown-item{{ $isActive($subchild['active_routes'] ?? $subchild['route'] ?? null) ? ' active' : '' }}"
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
