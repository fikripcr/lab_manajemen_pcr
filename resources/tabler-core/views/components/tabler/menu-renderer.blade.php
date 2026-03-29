@props(['type' => 'sidebar'])

@php
    $currentRoute = request()->route()?->getName() ?? '';
    
    $isPemutu = \Illuminate\Support\Str::startsWith($currentRoute, 'pemutu.');
    $isHr = \Illuminate\Support\Str::startsWith($currentRoute, 'hr.');
    $isLab = \Illuminate\Support\Str::startsWith($currentRoute, 'lab.');
    $isKegiatan = \Illuminate\Support\Str::startsWith($currentRoute, 'Kegiatan.');
    $isPmb = \Illuminate\Support\Str::startsWith($currentRoute, 'pmb.');
    $isEoffice = \Illuminate\Support\Str::startsWith($currentRoute, 'eoffice.');
    $isCbt = \Illuminate\Support\Str::startsWith($currentRoute, 'cbt.');
    $isSurvei = \Illuminate\Support\Str::startsWith($currentRoute, 'survei.');
    $isProject = \Illuminate\Support\Str::startsWith($currentRoute, 'projects.');
    $isCms = \Illuminate\Support\Str::startsWith($currentRoute, 'cms.');

    // General context (Dashboard or System pages)
    $isSys = !$isPemutu && !$isHr && !$isLab && !$isKegiatan && !$isPmb && !$isEoffice && !$isCbt && !$isSurvei && !$isProject && !$isCms;
@endphp

@if($type === 'sidebar')
    <ul class="navbar-nav pt-lg-3">
        {{-- Always show the Home entry point --}}
        <x-tabler.menu-home :type="$type" />

        @if($isSys)
            {{-- In Dashboard: Show Quick Access to all modules and system tools --}}
            <x-tabler.menu-quick-access :type="$type" />

            <li class="nav-item mt-3">
                <span class="nav-link disabled text-uppercase text-muted small">Sistem & Konten</span>
            </li>
            <x-tabler.menu-cms :type="$type" />
            <x-tabler.menu-master :type="$type" />
        @else
            {{-- Inside a Module: Show Module-Specific immersive menu --}}
            <li class="nav-divider my-2"></li>

            @if($isPemutu) <x-tabler.menu-pemutu :type="$type" /> @endif
            @if($isHr) <x-tabler.menu-hr :type="$type" /> @endif
            @if($isLab) <x-tabler.menu-lab :type="$type" /> @endif
            @if($isKegiatan) <x-tabler.menu-kegiatan :type="$type" /> @endif
            @if($isPmb) <x-tabler.menu-pmb :type="$type" /> @endif
            @if($isEoffice) <x-tabler.menu-eoffice :type="$type" /> @endif
            @if($isCbt) <x-tabler.menu-cbt :type="$type" /> @endif
            @if($isSurvei) <x-tabler.menu-survei :type="$type" /> @endif
            @if($isProject) <x-tabler.menu-project :type="$type" /> @endif
            @if($isCms) <x-tabler.menu-cms :type="$type" /> @endif
        @endif
    </ul>

@elseif($type === 'navbar')
    <ul class="navbar-nav">
        {{-- Navbar stays consistent with top-level entry points --}}
        <x-tabler.menu-home :type="$type" />
        <x-tabler.menu-cms :type="$type" />
        <x-tabler.menu-master :type="$type" />
    </ul>
@endif
