@props(['type' => 'sidebar'])

@php
    $isActive = request()->routeIs('dashboard');
@endphp

<li class="nav-item{{ $isActive ? ' active' : '' }}">
    <a class="nav-link" href="{{ route('dashboard') }}">
        <span class="nav-link-icon d-lg-inline-block">
            <x-tabler.icon-svg name="home" />
        </span>
        <span class="nav-link-title">Beranda</span>
    </a>
</li>
