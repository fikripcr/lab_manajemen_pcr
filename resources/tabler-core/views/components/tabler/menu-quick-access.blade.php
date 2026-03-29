@props(['type' => 'sidebar'])

@php
    $modules = [
        ['title' => 'Penjaminan Mutu', 'route' => 'pemutu.dashboard', 'icon' => 'shield-check'],
        ['title' => 'SDM / HR', 'route' => 'hr.dashboard', 'icon' => 'users'],
        ['title' => 'Layanan Lab', 'route' => 'lab.dashboard', 'icon' => 'flask'],
        ['title' => 'Kegiatan & Rapat', 'route' => 'Kegiatan.Kegiatans.index', 'icon' => 'calendar-event'],
        ['title' => 'PMB', 'route' => 'pmb.camaba.index', 'icon' => 'school'],
        ['title' => 'E-Office / Layanan', 'route' => 'eoffice.layanan.index', 'icon' => 'mail-opened'],
        ['title' => 'CBT / Ujian Online', 'route' => 'cbt.mata-uji.index', 'icon' => 'device-laptop'],
        ['title' => 'Survei / Umpan Balik', 'route' => 'survei.index', 'icon' => 'forms'],
        ['title' => 'Manajemen Proyek', 'route' => 'projects.index', 'icon' => 'layout-dashboard'],
    ];
@endphp

<li class="nav-item mt-3">
    <span class="nav-link disabled text-uppercase text-muted small">Akses Cepat Modul</span>
</li>

@foreach($modules as $module)
    <li class="nav-item">
        <a class="nav-link" href="{{ route($module['route']) }}">
            <span class="nav-link-icon d-lg-inline-block">
                <x-tabler.icon-svg name="{{ $module['icon'] }}" />
            </span>
            <span class="nav-link-title">{{ $module['title'] }}</span>
        </a>
    </li>
@endforeach
