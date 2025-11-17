<script src="{{ asset('assets-admin') }}/vendor/libs/jquery/jquery.js"></script>
<script src="{{ asset('assets-admin') }}/vendor/libs/popper/popper.js"></script>
<script src="{{ asset('assets-admin') }}/vendor/js/bootstrap.js"></script>
<script src="{{ asset('assets-admin') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="{{ asset('assets-admin') }}/vendor/js/menu.js"></script>

<script src="{{ asset('assets-admin') }}/vendor/libs/apex-charts/apexcharts.js"></script>

<script src="{{ asset('assets-admin') }}/js/main.js"></script>

<script src="{{ asset('assets-admin') }}/js/dashboards-analytics.js"></script>

<script async defer src="{{ asset('assets-admin/libs/github-buttons/buttons.js') }}"></script>
<script src="{{ asset('assets-admin/libs/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets-admin/libs/datatables/dataTables.bootstrap5.min.js') }}"></script>

<!-- Choice.js -->
<script src="{{ asset('assets-admin/libs/choicesjs/choices.min.js') }}"></script>

<!-- TinyMCE -->
<script src="{{ asset('assets-admin/js/tinymce/tinymce.min.js') }}"></script>

<!-- SweetAlert2 JS -->
<script src="{{ asset('assets-admin/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>

<!-- SweetAlert Component -->
@include('components.sweetalert')

<script>
// Load notifications asynchronously
function loadNotifications() {
    fetch('{{ route('notifications.unread-count') }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('notification-count').textContent = data.count;
        })
        .catch(error => console.error('Error loading notification count:', error));

    fetch('{{ route('notifications.index') }}')
        .then(response => response.text())
        .then(html => {
            // Parse the HTML response to extract notifications
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const notifications = doc.querySelectorAll('.notification-item'); // assuming notifications have this class

            const notificationsList = document.getElementById('notifications-list');
            if (notifications.length > 0) {
                notificationsList.innerHTML = '';
                notifications.forEach(notification => {
                    const li = document.createElement('li');
                    li.innerHTML = notification.outerHTML;
                    notificationsList.appendChild(li);
                });
            } else {
                notificationsList.innerHTML = '<li><a class="dropdown-item" href="javascript:void(0);"><p class="text-center mb-0">No notifications found</p></a></li>';
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            const notificationsList = document.getElementById('notifications-list');
            notificationsList.innerHTML = '<li><a class="dropdown-item" href="javascript:void(0);"><p class="text-center mb-0">Failed to load notifications</p></a></li>';
        });
}

// More efficient notification loading using API
function loadNotificationsApi() {
    fetch('{{ route('notifications.dropdown-data') }}')
        .then(response => response.json())
        .then(data => {
            const notificationsList = document.getElementById('notifications-list');
            if (data.data && data.data.length > 0) {
                notificationsList.innerHTML = '';
                data.data.forEach(notification => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <a class="dropdown-item notification-item" href="${notification.action_url}">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        ${notification.is_unread ? '<span class="badge rounded-pill bg-danger">NEW</span>' : ''}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${notification.title || 'Notification'}</h6>
                                    <p class="mb-0">${notification.body ? notification.body.substring(0, 80) + (notification.body.length > 80 ? '...' : '') : 'New notification'}</p>
                                    <small class="text-muted">${notification.created_at}</small>
                                </div>
                            </div>
                        </a>
                    `;
                    notificationsList.appendChild(li);
                });
            } else {
                notificationsList.innerHTML = '<li><a class="dropdown-item" href="javascript:void(0);"><p class="text-center mb-0">No notifications found</p></a></li>';
            }
        })
        .catch(error => {
            console.error('Error loading notifications via API:', error);
            const notificationsList = document.getElementById('notifications-list');
            notificationsList.innerHTML = '<li><a class="dropdown-item" href="javascript:void(0);"><p class="text-center mb-0">Failed to load notifications</p></a></li>';
        });
}

// Load initial notification count when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Load initial notification count
    fetch('{{ route('notifications.unread-count') }}')
        .then(response => response.json())
        .then(data => {
            const countElement = document.getElementById('notification-count');
            if (countElement) {
                countElement.textContent = data.count;
            }
        })
        .catch(error => console.error('Error loading initial notification count:', error));

    // Load notifications when dropdown is shown
    const dropdownElement = document.querySelector('.dropdown-notification');
    if (dropdownElement) {
        dropdownElement.addEventListener('show.bs.dropdown', function () {
            loadNotificationsApi();
        });
    }

    // Handle "Mark all as read" button
    const markAllAsReadBtn = document.getElementById('markAllAsReadBtn');
    if (markAllAsReadBtn) {
        markAllAsReadBtn.addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menandai semua notifikasi sebagai telah dibaca?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, tandai semua',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('notifications.mark-all-as-read') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Sukses!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Update notification count
                                const countElement = document.getElementById('notification-count');
                                if (countElement) {
                                    countElement.textContent = '0';
                                }
                                // Clear the notifications list
                                const notificationsList = document.getElementById('notifications-list');
                                if (notificationsList) {
                                    notificationsList.innerHTML = '<li><a class="dropdown-item" href="javascript:void(0);"><p class="text-center mb-0">No notifications found</p></a></li>';
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Gagal menandai notifikasi sebagai telah dibaca',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menandai notifikasi sebagai telah dibaca',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        });
    }
});

// Refresh notification count every minute
setInterval(function() {
    fetch('{{ route('notifications.unread-count') }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('notification-count').textContent = data.count;
        })
        .catch(error => console.error('Error updating notification count:', error));
}, 60000); // 60 seconds
</script>
