// Notification component using ES6 modules and vanilla JavaScript
export class NotificationManager {
    constructor() {
        this.init();
    }

    init() {
        // Load initial notification count when page loads
        document.addEventListener('DOMContentLoaded', () => {
            this.loadInitialNotificationCount();
            this.setupDropdownListener();
            this.setupMarkAllAsReadListener();
        });
    }

    loadInitialNotificationCount() {
        // Check if element exists and we're not on notification-specific pages
        const path = window.location.pathname;
        const countElement = document.getElementById('notification-count');
        
        if (countElement && !path.includes('/notifications') && 
            !path.includes('/api/') && !path.includes('/unread-count')) {
            
            this.fetchUnreadCount()
                .then(response => {
                    if (countElement) {
                        const count = response.data.count;
                        countElement.textContent = count;
                        this.toggleCountBadge(countElement, count);
                    }
                })
                .catch(error => {
                    console.error('Error loading initial notification count:', error);
                });
        }
    }

    setupDropdownListener() {
        const dropdownElement = document.querySelector('.dropdown-notification');
        if (dropdownElement) {
            dropdownElement.addEventListener('show.bs.dropdown', () => {
                this.loadNotifications();
            });
        }
    }

    setupMarkAllAsReadListener() {
        const markAllAsReadBtn = document.getElementById('markAllAsReadBtn');
        if (markAllAsReadBtn) {
            markAllAsReadBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.showConfirmationDialog();
            });
        }
    }

    async fetchUnreadCount() {
        const response = await window.axios.get(window.appRoutes.notificationsUnreadCount);
        return response.data;
    }

    async fetchNotificationData() {
        const response = await window.axios.get(window.appRoutes.notificationsDropdownData);
        return response.data;
    }

    async markAllAsRead() {
        const response = await window.axios.post(window.appRoutes.notificationsMarkAllAsRead);
        return response.data;
    }

    async loadNotifications() {
        try {
            const data = await this.fetchNotificationData();
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
        } catch (error) {
            console.error('Error loading notifications via API:', error);
            const notificationsList = document.getElementById('notifications-list');
            notificationsList.innerHTML = '<li><a class="dropdown-item" href="javascript:void(0);"><p class="text-center mb-0">Failed to load notifications</p></a></li>';
        }
    }

    showConfirmationDialog() {
        window.Swal.fire({
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
                this.handleMarkAllAsRead();
            }
        });
    }

    async handleMarkAllAsRead() {
        try {
            const response = await this.markAllAsRead();
            
            if (response.status === 'success') {
                window.Swal.fire({
                    title: 'Sukses!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Update notification count
                    const countElement = document.getElementById('notification-count');
                    if (countElement) {
                        countElement.textContent = '0';
                        this.toggleCountBadge(countElement, 0);
                    }
                    // Clear the notifications list
                    const notificationsList = document.getElementById('notifications-list');
                    if (notificationsList) {
                        notificationsList.innerHTML = '<li><a class="dropdown-item" href="javascript:void(0);"><p class="text-center mb-0">No notifications found</p></a></li>';
                    }
                });
            } else {
                window.Swal.fire({
                    title: 'Error!',
                    text: response.message || 'Gagal menandai notifikasi sebagai telah dibaca',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            window.Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat menandai notifikasi sebagai telah dibaca',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    }

    toggleCountBadge(countElement, count) {
        if (count > 0) {
            countElement.style.display = 'inline-block';
        } else {
            countElement.style.display = 'none';
        }
    }

    // Function to periodically refresh notification count
    updateNotificationCount() {
        const countElement = document.getElementById('notification-count');
        if (countElement) {
            this.fetchUnreadCount()
                .then(response => {
                    if (countElement) {
                        const count = response.data.count;
                        countElement.textContent = count;
                        this.toggleCountBadge(countElement, count);
                    }
                })
                .catch(error => {
                    console.error('Error updating notification count:', error);
                });
        }
    }
}

// Initialize notification manager when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.notificationManager = new NotificationManager();
});