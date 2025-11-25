// More efficient notification loading using API
function loadNotificationsApi() {
    axios.get(window.appRoutes.notificationsDropdownData)
        .then(function(response) {
            const data = response.data;
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
        .catch(function(error) {
            console.error('Error loading notifications via API:', error);
            const notificationsList = document.getElementById('notifications-list');
            notificationsList.innerHTML = '<li><a class="dropdown-item" href="javascript:void(0);"><p class="text-center mb-0">Failed to load notifications</p></a></li>';
        });
}

// Load initial notification count when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Only load notification count if the element exists
    // and we're not on a notification-specific page
    const path = window.location.pathname;
    const countElement = document.getElementById('notification-count');
    if (countElement &&
        !path.includes('/notifications') &&
        !path.includes('/api/') &&
        !path.includes('/unread-count')) {

        axios.get(window.appRoutes.notificationsUnreadCount)
            .then(function(response) {
                if (countElement) {
                    const count = response.data.data.count;
                    countElement.textContent = count;
                    // Show/hide badge based on count
                    if (count > 0) {
                        countElement.style.display = 'inline-block';
                    } else {
                        countElement.style.display = 'none';
                    }
                }
            })
            .catch(function(error) {
                console.error('Error loading initial notification count:', error);
            });
    }

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
                    axios.post(window.appRoutes.notificationsMarkAllAsRead)
                    .then(function(response) {
                        if (response.data.status === 'success') {
                            Swal.fire({
                                title: 'Sukses!',
                                text: response.data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Update notification count
                                const countElement = document.getElementById('notification-count');
                                if (countElement) {
                                    countElement.textContent = '0';
                                    // Hide badge when count is 0
                                    countElement.style.display = 'none';
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
                                text: response.data.message || 'Gagal menandai notifikasi sebagai telah dibaca',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(function(error) {
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

// Function to periodically refresh notification count
function updateNotificationCount() {
    const countElement = document.getElementById('notification-count');
    if (countElement) {
        axios.get(window.appRoutes.notificationsUnreadCount)
            .then(function(response) {
                if (countElement) {
                    const count = response.data.data.count;
                    countElement.textContent = count;
                    // Show/hide badge based on count
                    if (count > 0) {
                        countElement.style.display = 'inline-block';
                    } else {
                        countElement.style.display = 'none';
                    }
                }
            })
            .catch(function(error) {
                console.error('Error updating notification count:', error);
            });
    }
}
