<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.webp" alt=""> -->
        <h1 class="sitename">
            <a href="." class="navbar-brand navbar-brand-autodark">
                <img src="{{ asset('images/logo-apps.png') }}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
            </a>
        </h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
          <li><a href="{{ route('public.announcements.index') }}" class="{{ request()->routeIs('public.announcements.index') ? 'active' : '' }}">Announcements</a></li>
          <li><a href="{{ route('public.request-software') }}" class="{{ request()->routeIs('public.request-software') ? 'active' : '' }}">Request Software</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="{{route('login')}}">Login</a>

    </div>
  </header>

