@props(['type' => 'sidebar'])

@php
    $menu = [
        [
            'type'          => 'dropdown',
            'title'         => 'Layanan Lab',
            'id'            => 'navbar-services',
            'icon'          => 'ti ti-activity',
            'active_routes' => [
                'lab.labs.*', 'lab.inventaris.*', 'lab.kegiatan.*', 'lab.log-lab.*', 
                'lab.surat-bebas.*', 'lab.laporan-kerusakan.*', 'lab.log-pc.*', 
                'lab.software-requests.*', 'akademik.semesters.*', 'akademik.mata-kuliah.*', 
                'lab.jadwal.*', 'lab.periode-request.*',
            ],
            'children'      => [
                [
                    'title'         => 'Master Data',
                    'id'            => 'navbar-lab-master',
                    'icon'          => 'ti ti-database',
                    'active_routes' => ['lab.labs.*', 'lab.inventaris.*', 'akademik.semesters.*', 'akademik.mata-kuliah.*', 'lab.jadwal.*'],
                    'children'      => [
                        ['title' => 'Data Lab', 'route' => 'lab.labs.index', 'active_routes' => ['lab.labs.*'], 'icon' => 'ti ti-flask'],
                        ['title' => 'Data Inventaris', 'route' => 'lab.inventaris.index', 'active_routes' => ['lab.inventaris.*'], 'icon' => 'ti ti-package'],
                        ['title' => 'Data Semester', 'route' => 'akademik.semesters.index', 'active_routes' => ['akademik.semesters.*'], 'icon' => 'ti ti-calendar-stats'],
                        ['title' => 'Data Mata Kuliah', 'route' => 'akademik.mata-kuliah.index', 'active_routes' => ['akademik.mata-kuliah.*'], 'icon' => 'ti ti-book'],
                        ['title' => 'Jadwal Perkuliahan', 'route' => 'lab.jadwal.index', 'active_routes' => ['lab.jadwal.*'], 'icon' => 'ti ti-calendar-event'],
                    ],
                ],
                ['title' => 'Peminjaman Lab', 'route' => 'lab.kegiatan.index', 'active_routes' => ['lab.kegiatan.*'], 'icon' => 'ti ti-calendar'],
                ['title' => 'Log Penggunaan Lab', 'route' => 'lab.log-lab.index', 'active_routes' => ['lab.log-lab.*'], 'icon' => 'ti ti-file-time'],
                ['title' => 'Log Penggunaan PC', 'route' => 'lab.log-pc.index', 'active_routes' => ['lab.log-pc.*'], 'icon' => 'ti ti-device-desktop-analytics'],
                ['title' => 'Surat Bebas Lab', 'route' => 'lab.surat-bebas.index', 'active_routes' => ['lab.surat-bebas.*'], 'icon' => 'ti ti-certificate'],
                ['title' => 'Laporan Kerusakan', 'route' => 'lab.laporan-kerusakan.index', 'active_routes' => ['lab.laporan-kerusakan.*'], 'icon' => 'ti ti-report-medical'],
                [
                    'title'         => 'Software Requests',
                    'id'            => 'navbar-software-nested',
                    'icon'          => 'ti ti-device-laptop',
                    'active_routes' => ['lab.software-requests.*', 'lab.periode-request.*'],
                    'children'      => [
                        ['title' => 'Daftar Pengajuan', 'route' => 'lab.software-requests.index', 'active_routes' => ['lab.software-requests.*'], 'icon' => 'ti ti-list'],
                        ['title' => 'Periode Pengajuan', 'route' => 'lab.periode-request.index', 'active_routes' => ['lab.periode-request.*'], 'icon' => 'ti ti-calendar-stats'],
                    ],
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
