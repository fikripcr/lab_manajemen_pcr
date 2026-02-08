<aside class="navbar navbar-vertical d-none d-lg-flex navbar-expand-lg">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('lab.dashboard') }}" class="navbar-brand navbar-brand-autodark">
                <img src="{{ asset('images/logo-apps.png') }}" width="110" height="32" alt="{{ config('app.name') }}" class="navbar-brand-image">
            </a>
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <x-tabler.menu-renderer type="sidebar" group="admin" />
        </div>
    </div>
</aside>
