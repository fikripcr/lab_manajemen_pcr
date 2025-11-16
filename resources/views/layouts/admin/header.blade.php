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
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search..." />
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Notification -->
            <li class="nav-item navbar-dropdown dropdown-notification dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="bx bx-bell bx-sm"></i>
                    @if (auth()->user() && auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger rounded-pill">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 me-2">Notifications</h5>
                            @if (auth()->user() && auth()->user()->unreadNotifications->count() > 0)
                                <a href="#" class="text-muted" id="markAllAsReadBtn">Mark all as read</a>
                            @endif
                        </div>
                    </li>

                    @if (auth()->user() && auth()->user()->notifications()->limit(5)->count() > 0)
                        @foreach (auth()->user()->notifications()->latest()->limit(5)->get() as $notification)
                            <li>
                                <a class="dropdown-item" href="{{ route('notifications.mark-as-read', $notification->id) }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar avatar-online">
                                                @if (is_null($notification->read_at))
                                                    <span class="badge rounded-pill bg-danger">NEW</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                            <p class="mb-0">{{ Str::limit($notification->data['body'] ?? 'New notification', 80) }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);">
                                <p class="text-center mb-0">No notifications found</p>
                            </a>
                        </li>
                    @endif

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
                        <a class="dropdown-item" href="{{ route('users.show', encryptId(auth()->user()->id)) }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5' }}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ auth()->user()->name }}</span>
                                    <small class="text-muted">{{ auth()->user()->roles->first()?->name ?? 'User' }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('users.show', encryptId(auth()->user()->id)) }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    @if(session('original_user_id'))
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.switch-back') }}">
                                <i class="bx bx-log-out me-2"></i>
                                <span class="align-middle">Kembali ke Akun Asli</span>
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
