@props(['type' => 'sidebar'])

@php
    $menu = [
        [
            'type'          => 'dropdown',
            'title'         => 'Sumber Daya Manusia',
            'id'            => 'navbar-hr',
            'icon'          => 'ti ti-briefcase',
            'active_routes' => ['hr.*'],
            'children'      => [
                [
                    'title'         => 'Master Data',
                    'route'         => 'hr.status-pegawai.index',
                    'active_routes' => ['hr.status-pegawai.*', 'hr.master-status-aktifitas.*', 'hr.jabatan-fungsional.*', 'hr.jenis-izin.*', 'hr.jenis-indisipliner.*', 'hr.jenis-shift.*'],
                    'icon'          => 'ti ti-database',
                ],
                [
                    'title'         => 'Pegawai',
                    'route'         => 'hr.pegawai.index',
                    'active_routes' => ['hr.pegawai.*'],
                    'icon'          => 'ti ti-users',
                ],
                [
                    'title'         => 'Approval Data',
                    'route'         => 'hr.approval.index',
                    'active_routes' => ['hr.approval.*'],
                    'icon'          => 'ti ti-check',
                ],
                [
                    'title'         => 'Perizinan',
                    'route'         => 'hr.perizinan.index',
                    'active_routes' => ['hr.perizinan.*'],
                    'icon'          => 'ti ti-file-certificate',
                ],
                [
                    'title'         => 'Lembur',
                    'route'         => 'hr.lembur.index',
                    'active_routes' => ['hr.lembur.*'],
                    'icon'          => 'ti ti-clock-hour-4',
                ],
                [
                    'title'         => 'Indisipliner',
                    'route'         => 'hr.indisipliner.index',
                    'active_routes' => ['hr.indisipliner.*'],
                    'icon'          => 'ti ti-alert-circle',
                ],
                [
                    'title'         => 'Tanggal Libur',
                    'route'         => 'hr.tanggal-libur.index',
                    'active_routes' => ['hr.tanggal-libur.*'],
                    'icon'          => 'ti ti-calendar-off',
                ],
                [
                    'title'         => 'Mesin Presensi',
                    'route'         => 'hr.att-device.index',
                    'active_routes' => ['hr.att-device.*'],
                    'icon'          => 'ti ti-device-computer-camera',
                ],
                [
                    'title'         => 'Presensi Online',
                    'route'         => 'hr.presensi.index',
                    'active_routes' => ['hr.presensi.*'],
                    'icon'          => 'ti ti-fingerprint',
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
