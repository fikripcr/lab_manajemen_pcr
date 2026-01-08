import { api } from '../api.js';

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
        const countElements = document.querySelectorAll('.notification-count');

        if (countElements.length > 0 && !path.includes('/notifications') &&
            !path.includes('/api/') && !path.includes('/unread-count')) {

            this.fetchUnreadCount()
                .then(response => {
                    // Handle different possible response structures
                    let count;
                    if (response.count !== undefined) {
                        count = response.count;
                    } else if (response.data && response.data.count !== undefined) {
                        count = response.data.count;
                    } else if (response.unread_count !== undefined) {
                        count = response.unread_count;
                    } else {
                        console.warn('Unexpected response structure for unread count:', response);
                        count = 0;
                    }

                    this.updateAllCounters(count);
                })
                .catch(error => {
                    console.error('Error loading initial notification count:', error);
                });
        }
    }

    updateAllCounters(count) {
        const countElements = document.querySelectorAll('.notification-count');
        countElements.forEach(element => {
            element.textContent = count;
            this.toggleCountBadge(element, count);
        });
    }

    setupDropdownListener() {
        const dropdownElements = document.querySelectorAll('.dropdown-notification');
        dropdownElements.forEach(dropdown => {
            dropdown.addEventListener('show.bs.dropdown', () => {
                this.loadNotifications();
            });
        });
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
        const response = await api.notifications.unreadCount();
        return response.data;
    }

    async fetchNotificationData() {
        const response = await api.notifications.dropdownData();
        return response.data;
    }

    async markAllAsRead() {
        const response = await api.notifications.markAllAsRead();
        return response.data;
    }

    async loadNotifications() {
        const notificationsList = document.getElementById('notifications-list');
        if (!notificationsList) return;

        try {
            const data = await this.fetchNotificationData();

            if (data.data && data.data.length > 0) {
                notificationsList.innerHTML = '';
                data.data.forEach(notification => {
                    const li = document.createElement('div');
                    li.className = 'list-group-item';
                    li.innerHTML = `
                        <div class="row align-items-center">
                            <div class="col-auto"><span class="status-dot ${notification.is_unread ? 'status-dot-animated bg-red' : 'd-none'} d-block"></span></div>
                            <div class="col text-truncate">
                                <a href="${notification.action_url}" class="text-body d-block">${notification.title || 'Notification'}</a>
                                <div class="d-block text-muted text-truncate mt-n1">
                                    ${notification.body ? notification.body.substring(0, 80) + (notification.body.length > 80 ? '...' : '') : 'New notification'}
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="list-group-item-actions">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                                </a>
                            </div>
                        </div>
                    `;
                    notificationsList.appendChild(li);
                });
            } else {
                notificationsList.innerHTML = '<div class="list-group-item"><div class="row align-items-center"><div class="col text-truncate"><p class="text-center mb-0 text-muted">No notifications found</p></div></div></div>';
            }
        } catch (error) {
            console.error('Error loading notifications via API:', error);
            notificationsList.innerHTML = '<div class="list-group-item"><div class="row align-items-center"><div class="col text-truncate"><p class="text-center mb-0 text-danger">Failed to load notifications</p></div></div></div>';
        }
    }

    showConfirmationDialog() {
        showConfirmation(
            'Konfirmasi',
            'Apakah Anda yakin ingin menandai semua notifikasi sebagai telah dibaca?',
            'Ya, tandai semua'
        ).then((result) => {
            if (result.isConfirmed) {
                this.handleMarkAllAsRead();
            }
        });
    }

    async handleMarkAllAsRead() {
        try {
            const response = await this.markAllAsRead();

            if (response.status === 'success') {
                showSuccessMessage('Sukses!', response.message).then(() => {
                    // Update notification count
                    this.updateAllCounters(0);

                    // Clear the notifications list
                    const notificationsList = document.getElementById('notifications-list');
                    if (notificationsList) {
                        notificationsList.innerHTML = '<div class="list-group-item"><div class="row align-items-center"><div class="col text-truncate"><p class="text-center mb-0 text-muted">No notifications found</p></div></div></div>';
                    }
                });
            } else {
                showErrorMessage('Error!', response.message || 'Gagal menandai notifikasi sebagai telah dibaca');
            }
        } catch (error) {
            console.error('Error:', error);
            showErrorMessage('Error!', 'Terjadi kesalahan saat menandai notifikasi sebagai telah dibaca');
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
        const countElements = document.querySelectorAll('.notification-count');
        if (countElements.length > 0) {
            this.fetchUnreadCount()
                .then(response => {
                    let count;
                    if (response.count !== undefined) {
                        count = response.count;
                    } else if (response.data && response.data.count !== undefined) {
                        count = response.data.count;
                    } else if (response.unread_count !== undefined) {
                        count = response.unread_count;
                    } else {
                        count = 0;
                    }

                    this.updateAllCounters(count);
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