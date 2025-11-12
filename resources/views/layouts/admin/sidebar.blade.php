<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <img class="w-100" src="{{asset('digilab-crop.png')}}"/>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{request()->routeIs('dashboard')? 'active' : ''}}">
            <a href="{{route('dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master Data</span>
        </li>


        <li class="menu-item {{request()->routeIs('labs.*')? 'active' : ''}}">
            <a href="{{route('labs.index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-alt"></i>
                <div data-i18n="Documentation">Data Lab</div>
            </a>
        </li>

        <li class="menu-item {{request()->routeIs('inventories.*')? 'active' : ''}}">
            <a href="{{route('inventories.index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div data-i18n="Documentation">Data Inventaris</div>
            </a>
        </li>





        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Pages</span>
        </li>
                <li class="menu-item {{request()->routeIs('pengumuman.*') || request()->routeIs('berita.*') ? 'active open' : ''}}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-bell"></i>
                <div data-i18n="Pengumuman">Info Publik</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{request()->routeIs('pengumuman.*') ? (request()->routeIs('pengumuman.create') || request()->routeIs('pengumuman.edit') ? '' : 'active') : ''}}">
                    <a href="{{route('pengumuman.index')}}" class="menu-link">
                        <div>Pengumuman</div>
                    </a>
                </li>
                <li class="menu-item {{request()->routeIs('berita.*') ? (request()->routeIs('berita.create') || request()->routeIs('berita.edit') ? '' : 'active') : ''}}">
                    <a href="{{route('berita.index')}}" class="menu-link">
                        <div>Berita</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Misc -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Misc</span></li>
                <li class="menu-item {{request()->routeIs('users.*')? 'active' : ''}}">
            <a href="{{route('users.index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Documentation">User Management</div>
            </a>
        </li>
         <!-- Roles & Permissions Menu -->
        <li class="menu-item {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-shield"></i>
                <div data-i18n="Roles & Permissions">Access Control</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <a href="{{ route('roles.index') }}" class="menu-link">
                        <div data-i18n="Roles">Roles</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                    <a href="{{ route('permissions.index') }}" class="menu-link">
                        <div data-i18n="Permissions">Permissions</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/" target="_blank" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Documentation">Documentation</div>
            </a>
        </li>
    </ul>
</aside>
