<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input type="text" class="form-control border-0 shadow-none search-input" id="global-search-input" placeholder="Search" aria-label="Search..." autocomplete="off" readonly style="background-color: transparent; border: none; box-shadow: none; margin-left: 0.5rem;" />
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Notification -->
            <li class="nav-item navbar-dropdown dropdown-notification dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="bx bx-bell bx-sm"></i>
                    <span class="badge bg-danger rounded-pill" id="notification-count">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 me-2">Notifications</h5>
                            <a href="#" class="text-muted" id="markAllAsReadBtn">Mark all as read</a>
                        </div>
                    </li>

                    <li>
                        <div class="dropdown-menu-scrollable" data-bs-simple="true">
                            <ul class="menu border-0" id="notifications-list">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">
                                        <p class="text-center mb-0">Loading notifications...</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item text-center text-primary" href="{{ route('notifications.index') }}">
                            <small>View all notifications</small>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ Notification -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('assets-admin') }}/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('users.show', auth()->user()->encrypted_id) }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5' }}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ auth()->user()->name }}</span>
                                    <small class="text-muted">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('users.show', auth()->user()->encrypted_id) }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    @if(app('impersonate')->isImpersonating())
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.switch-back') }}">
                                <i class="bx bx-log-out me-2"></i>
                                <span class="align-middle">Switch Back to Original Account</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">Settings</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>

<!-- Search Input Styles -->
<style>
.search-input:focus {
    outline: none;
    border-color: transparent !important;
    box-shadow: none !important;
}

.search-input {
    cursor: pointer;
}

#modal-search-input {
    border: 1px solid #d9dee3;
    padding: 0.5rem 1rem;
}

#modal-search-input:focus {
    outline: 2px solid #3498db !important;
    outline-offset: 2px;
}
</style>

<!-- Global Search Modal -->
<div class="modal fade" id="global-search-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center w-100">
                    <i class="bx bx-search fs-4 lh-0 me-2"></i>
                    <input type="text" class="form-control border-0 shadow-none flex-grow-1"
                           id="modal-search-input"
                           placeholder="Search users, roles, permissions..."
                           aria-label="Search..." autocomplete="off" style="border-radius: 0.375rem;"/>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="search-results-container p-3">
                    <p class="text-center text-muted mb-0 py-5">Start typing to search...</p>
                </div>
            </div>
        </div>
    </div>
</div>
