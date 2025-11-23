<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Global Search Trigger -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <a href="javascript:void(0)" class="nav-link text-dark" onclick="openGlobalSearchModal('{{ route('global-search') }}')" title="Global Search">
                    <i class="bx bx-search fs-4 lh-0 me-1"></i>
                    <span>Search</span>
                </a>
            </div>
        </div>
        <!-- /Global Search Trigger -->

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
                        <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5' }}" alt class="w-px-40 h-auto rounded-circle object-fit-cover" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('users.show', auth()->user()->encrypted_id) }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5' }}" alt class="w-px-40 h-auto rounded-circle object-fit-cover" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ auth()->user()->name }}</span>
                                    <small class="text-muted">{{ getActiveRole() ?? 'User' }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <!-- Role Switching Dropdown -->
                    @if(getAllUserRoles()->count() > 1)
                        <li class="dropdown-submenu">
                            <a class="dropdown-item" href="javascript:void(0);">
                                <i class="bx bx-user-check me-2"></i>
                                <span class="align-middle">Switch Role</span>
                                <i class="bx bx-chevron-right float-end"></i>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach(getAllUserRoles() as $role)
                                    <li>
                                        <a class="dropdown-item {{ $role === getActiveRole() ? 'active' : '' }}"
                                           href="javascript:void(0)"
                                           onclick="switchRole('{{ $role }}')">
                                            {{ $role }}
                                            @if($role === getActiveRole())
                                                <i class="bx bx-check float-end"></i>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                    @endif
                    <li>
                        <a class="dropdown-item" href="{{ route('users.show', auth()->user()->encrypted_id) }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    @if(app('impersonate')->isImpersonating())
                        <li>
                            <a class="dropdown-item" href="{{ route('users.switch-back') }}">
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
        </ul>
    </div>
</nav>

<style>
.dropdown-submenu {
    position: relative;
}

.dropdown-submenu .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -6px;
    margin-left: -1px;
    border-radius: 0.375rem;
}

.dropdown-submenu:hover .dropdown-menu {
    display: block;
}

.dropdown-submenu > a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: #ccc;
    margin-top: 5px;
    margin-right: -10px;
}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left .dropdown-menu {
    left: -100%;
    margin-left: 10px;
    border-radius: 0.375rem;
}
</style>

<script>
    function switchRole(role) {
        // Create a form and submit it via POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('users.switch-role', '') }}/' + role;

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;

        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }

    // Load notification count from API
    function loadNotificationCount() {
        axios.get('{{ route('api.notifications.count') }}')
            .then(response => {
                const countElement = document.getElementById('notification-count');
                if (countElement) {
                    countElement.textContent = response.data.count;
                    // Show/hide badge based on count
                    if (response.data.count > 0) {
                        countElement.style.display = 'inline-block';
                    } else {
                        countElement.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error loading notification count:', error);
            });
    }

    // Load notifications from API
    function loadNotifications() {
        axios.get('{{ route('api.notifications.list') }}', {
            params: {
                per_page: 5
            }
        })
            .then(response => {
                const notificationsList = document.getElementById('notifications-list');
                if (notificationsList) {
                    if (response.data.data && response.data.data.length > 0) {
                        let html = '';
                        response.data.data.forEach(notification => {
                            const isUnread = !notification.read_at;
                            const notificationClass = isUnread ? 'bg-label-primary' : '';

                            html += `
                                <li>
                                    <a class="dropdown-item ${notificationClass}" href="javascript:void(0);">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar avatar-xs">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        <i class="bx bx-bell"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">${notification.data.title || 'Notification'}</h6>
                                                <p class="mb-0">${notification.data.body || 'New notification'}</p>
                                                <small class="text-muted">${notification.formatted_date}</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            `;
                        });
                        notificationsList.innerHTML = html;
                    } else {
                        notificationsList.innerHTML = `
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <p class="text-center mb-0">No notifications found</p>
                                </a>
                            </li>
                        `;
                    }
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                const notificationsList = document.getElementById('notifications-list');
                if (notificationsList) {
                    notificationsList.innerHTML = `
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);">
                                <p class="text-center mb-0">Error loading notifications</p>
                            </a>
                        </li>
                    `;
                }
            });
    }

    // Initialize notifications on page load and refresh every 30 seconds
    document.addEventListener('DOMContentLoaded', function() {
        loadNotificationCount();
        loadNotifications();

        // Refresh notifications every 30 seconds
        setInterval(function() {
            loadNotificationCount();
            loadNotifications();
        }, 30000); // 30 seconds
    });

    // Mark all as read button
    document.getElementById('markAllAsReadBtn')?.addEventListener('click', function(e) {
        e.preventDefault();

        fetch('{{ route('notifications.mark-all-as-read') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the notification UI after marking as read
                loadNotificationCount();
                loadNotifications();
            }
        })
        .catch(error => {
            console.error('Error marking all as read:', error);
        });
    });
</script>
