@props(['type' => 'sidebar', 'group' => 'sys'])

@php
    // --- 1. ADMIN MENU STRUCTURE ---
    $adminMenu = [
        [
            'type'  => 'header',
            'title' => 'Ringkasan',
        ],
        [
            'type'  => 'item',
            'title' => 'Dashboard',
            'route' => 'lab.dashboard',
            'icon'  => 'ti ti-layout-dashboard',
        ],
        [
            'type'  => 'header',
            'title' => 'Master Data',
        ],
        [
            'type'  => 'item',
            'title' => 'Data Lab',
            'route' => 'lab.labs.index',
            'active_routes' => ['lab.labs.*'],
            'icon'  => 'ti ti-flask',
        ],
        [
            'type'  => 'item',
            'title' => 'Data Inventaris',
            'route' => 'lab.inventaris.index',
            'active_routes' => ['lab.inventaris.*'],
            'icon'  => 'ti ti-package',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Perkuliahan',
            'id'            => 'navbar-extra',
            'icon'          => 'ti ti-book-2',
            'active_routes' => ['lab.semesters.*', 'lab.mata-kuliah.*', 'lab.jadwal.*'],
            'children'      => [
                [
                    'title'         => 'Data Semester',
                    'route'         => 'lab.semesters.index',
                    'active_routes' => ['lab.semesters.*'],
                ],
                [
                    'title'         => 'Data Mata Kuliah',
                    'route'         => 'lab.mata-kuliah.index',
                    'active_routes' => ['lab.mata-kuliah.*'],
                ],
                [
                    'title'         => 'Jadwal Perkuliahan',
                    'route'         => 'lab.jadwal.index',
                    'active_routes' => ['lab.jadwal.*'],
                ],
            ],
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Info Publik',
            'id'            => 'navbar-info',
            'icon'          => 'ti ti-info-circle',
            'active_routes' => ['lab.pengumuman.*', 'lab.berita.*'],
            'children'      => [
                [
                    'title'         => 'Pengumuman',
                    'route'         => 'lab.pengumuman.index',
                    'active_routes' => ['lab.pengumuman.*'],
                ],
                [
                    'title'         => 'Berita',
                    'route'         => 'lab.berita.index',
                    'active_routes' => ['lab.berita.*'],
                ],
            ],
        ],
        [
            'type'  => 'item',
            'title' => 'Permintaan Perangkat Lunak',
            'route' => 'lab.software-requests.index',
            'active_routes' => ['lab.software-requests.*'],
            'icon'  => 'ti ti-device-laptop',
        ],
        [
            'type' => 'dropdown',
            'title' => 'Penjaminan Mutu',
            'icon' => 'ti ti-checkbox',
            'route' => '#',
            'active_routes' => ['pemutu.*'],
            'can' => 'admin',
            'children' => [
                [
                    'title' => 'Struktur Organisasi',
                    'route' => 'pemutu.org-units.index',
                    'active_routes' => ['pemutu.org-units.*'],
                ],
                [
                    'title' => 'Dokumen',
                    'route' => 'pemutu.dokumens.index',
                    'active_routes' => ['pemutu.dokumens.*'],
                ],
                [
                    'title' => 'Personel',
                    'route' => 'pemutu.personils.index',
                    'active_routes' => ['pemutu.personils.*'],
                ],
                [
                    'title' => 'Label & Kategori',
                    'route' => 'pemutu.labels.index',
                    'active_routes' => ['pemutu.labels.*', 'pemutu.label-types.*'],
                ],
                [
                    'title' => 'Indikator',
                    'route' => 'pemutu.indikators.index',
                    'active_routes' => ['pemutu.indikators.*'],
                ],
            ],
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'HR & Kepegawaian',
            'id'            => 'navbar-hr',
            'icon'          => 'ti ti-briefcase',
            'active_routes' => ['hr.*'],
            'children'      => [
                [
                    'title'         => 'Data Pegawai',
                    'route'         => 'hr.pegawai.index',
                    'active_routes' => ['hr.pegawai.*'],
                ],
                [
                    'title'         => 'Struktur Organisasi',
                    'route'         => 'hr.org-units.index',
                    'active_routes' => ['hr.org-units.*'],
                ],
                [
                    'title'         => 'Approval Data',
                    'route'         => 'hr.approval.index',
                    'active_routes' => ['hr.approval.*'],
                ],
                [
                    'title'         => 'Perizinan',
                    'route'         => 'hr.perizinan.index',
                    'active_routes' => ['hr.perizinan.*'],
                ],
                [
                    'title'         => 'Indisipliner',
                    'route'         => 'hr.indisipliner.index',
                    'active_routes' => ['hr.indisipliner.*'],
                ],
                [
                    'title'         => 'Master Data HR',
                    'route'         => 'hr.status-pegawai.index',
                    'active_routes' => ['hr.status-pegawai.*', 'hr.status-aktifitas.*', 'hr.jabatan-fungsional.*', 'hr.jenis-izin.*', 'hr.jenis-indisipliner.*', 'hr.jenis-shift.*'],
                ],
                [
                    'title'         => 'Tanggal Libur',
                    'route'         => 'hr.tanggal-libur.index',
                    'active_routes' => ['hr.tanggal-libur.*'],
                ],
                [
                    'title'         => 'Mesin Presensi',
                    'route'         => 'hr.att-device.index',
                    'active_routes' => ['hr.att-device.*'],
                ],
                [
                    'title'         => 'Presensi Online',
                    'route'         => 'hr.presensi.index',
                    'active_routes' => ['hr.presensi.*'],
                    'icon'          => 'ti ti-fingerprint',
                    'badge'         => function() {
                        // Check if user is currently on presensi page
                        return request()->routeIs('hr.presensi.*');
                    },
                ],
            ],
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'E-Office',
            'id'            => 'navbar-eoffice',
            'icon'          => 'ti ti-mail-opened',
            'active_routes' => ['eoffice.*'],
            'children'      => [
                [
                    'title'         => 'Layanan Saya',
                    'route'         => 'eoffice.layanan.index',
                    'active_routes' => ['eoffice.layanan.*'],
                ],
                [
                    'title'         => 'Buat Pengajuan',
                    'route'         => 'eoffice.layanan.services',
                ],
                [
                    'type'  => 'header',
                    'title' => 'Master Data',
                ],
                [
                    'title'         => 'Jenis Layanan',
                    'route'         => 'eoffice.jenis-layanan.index',
                    'active_routes' => ['eoffice.jenis-layanan.*'],
                ],
                [
                    'title'         => 'Master Isian',
                    'route'         => 'eoffice.kategori-isian.index',
                    'active_routes' => ['eoffice.kategori-isian.*'],
                ],
                [
                    'title'         => 'Daftar Perusahaan',
                    'route'         => 'eoffice.perusahaan.index',
                    'active_routes' => ['eoffice.perusahaan.*'],
                ],
                [
                    'title'         => 'Kategori Perusahaan',
                    'route'         => 'eoffice.kategori-perusahaan.index',
                    'active_routes' => ['eoffice.kategori-perusahaan.*'],
                ],
            ],
        ],
        [
            'type'  => 'header',
            'title' => 'Lainnya',
        ],
        [
            'type'  => 'item',
            'title' => 'Pengguna',
            'route' => 'lab.users.index',
            'active_routes' => ['lab.users.*'],
            'icon'  => 'ti ti-users',
        ],
        [
            'type'  => 'item',
            'title' => 'Manajemen Sistem',
            'route' => 'sys.dashboard',
            'icon'  => 'ti ti-settings',
        ],
    ];

    // --- 2. SYS MENU STRUCTURE ---
    $sysMenu = [
        [
            'type'  => 'header',
            'title' => 'Ringkasan',
        ],
        [
            'type'  => 'item',
            'title' => 'Dashboard',
            'route' => 'sys.dashboard',
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>',
        ],
        [
            'type'  => 'header',
            'title' => 'Kontrol Akses',
        ],
        [
            'type'          => 'dropdown',
            'title' => 'Kontrol Akses',
            'id'            => 'navbar-access',
            'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /><path d="M12 11m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 12l0 2.5" /></svg>',
            'active_routes' => ['sys.roles.*', 'sys.permissions.*'],
            'children'      => [
                [
                    'title'         => 'Peran (Roles)',
                    'route'         => 'sys.roles.index',
                    'active_routes' => ['sys.roles.*'],
                ],
                [
                    'title'         => 'Izin (Permissions)',
                    'route'         => 'sys.permissions.index',
                    'active_routes' => ['sys.permissions.*'],
                ],
            ],
        ],
        [
            'type'  => 'header',
            'title' => 'Log Sistem',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'System Log',
            'id'            => 'navbar-syslog',
            'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17l0 -5" /><path d="M12 17l0 -1" /><path d="M15 17l0 -3" /></svg>',
            'active_routes' => ['notifications.*', 'activity-log.*', 'sys.error-log.*'],
            'children'      => [
                [
                    'title'         => 'Notifikasi',
                    'route'         => 'notifications.index',
                    'active_routes' => ['notifications.*'],
                ],
                [
                    'title'         => 'Aktivitas',
                    'route'         => 'activity-log.index',
                    'active_routes' => ['activity-log.*'],
                ],
                [
                    'title'         => 'Log Error',
                    'route'         => 'sys.error-log.index',
                    'active_routes' => ['sys.error-log.*'],
                ],
            ],
        ],
        [
            'type'  => 'header',
            'title' => 'Lainnya',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Lainnya',
            'id'            => 'navbar-others',
            'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M8 12l.01 0" /><path d="M12 12l.01 0" /><path d="M16 12l.01 0" /></svg>',
            'active_routes' => ['app-config', 'sys.test.*', 'sys.backup.*', 'sys.documentation.*'],
            'children'      => [
                [
                    'title'         => 'Konfigurasi Aplikasi',
                    'route'         => 'app-config',
                    'active_routes' => ['app-config'],
                ],
                [
                    'title'         => 'Fitur Uji Coba',
                    'route'         => 'sys.test.index',
                    'active_routes' => ['sys.test.*'],
                ],
                [
                    'title'         => 'Manajemen Backup',
                    'route'         => 'sys.backup.index',
                    'active_routes' => ['sys.backup.*'],
                ],
                [
                    'title'         => 'Panduan Pengembangan',
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
            <a class="nav-link" href="{{ route('lab.dashboard') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                </span>
                <span class="nav-link-title">Kembali ke Aplikasi Utama</span>
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
