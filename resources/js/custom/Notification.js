import { api } from '../api.js';

// Notification component using ES6 modules and vanilla JavaScript
export class NotificationManager {
    constructor() {
        // Cache DOM elements
        this.dom = {
            count: document.querySelectorAll('.notification-count'),
            list: document.getElementById('notifications-list'),
            markReadBtn: document.getElementById('markAllAsReadBtn'),
            dropdowns: document.querySelectorAll('.dropdown-notification')
        };

        this.state = {
            isLoading: false
        };

        this.init();
    }

    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.runInit());
        } else {
            this.runInit();
        }
    }

    runInit() {
        // Refresh DOM cache in case elements were added dyamically (though unlikely for header)
        this.refreshDomCache();

        if (this.dom.count.length > 0) {
            this.loadInitialNotificationCount();
        }

        this.setupDropdownListener();
        this.setupMarkAllAsReadListener();
    }

    refreshDomCache() {
        this.dom.count = document.querySelectorAll('.notification-count');
        this.dom.list = document.getElementById('notifications-list');
        this.dom.markReadBtn = document.getElementById('markAllAsReadBtn');
        this.dom.dropdowns = document.querySelectorAll('.dropdown-notification');
    }

    loadInitialNotificationCount() {
        // Simple check: if elements exist, fetch count. 
        // Removed fragile path checking unless specifically requested.
        this.fetchUnreadCount()
            .then(data => {
                // Std API response: { data: { count: 5 } } or similar
                const count = data?.data?.count ?? data?.count ?? 0;
                this.updateAllCounters(count);
            })
            .catch(err => console.error('Init count error:', err));
    }

    updateAllCounters(count) {
        this.dom.count.forEach(el => {
            el.textContent = count;
            // Toggle visibility
            el.style.display = count > 0 ? 'inline-block' : 'none';
        });
    }

    setupDropdownListener() {
        this.dom.dropdowns.forEach(dropdown => {
            dropdown.addEventListener('show.bs.dropdown', () => {
                this.loadNotifications();
            });
        });
    }

    setupMarkAllAsReadListener() {
        if (this.dom.markReadBtn) {
            this.dom.markReadBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.showConfirmationDialog();
            });
        }
    }

    async fetchUnreadCount() {
        // Returns axios response body
        return (await api.notifications.unreadCount()).data;
    }

    async fetchNotificationData() {
        return (await api.notifications.dropdownData()).data;
    }

    async loadNotifications() {
        if (!this.dom.list || this.state.isLoading) return;

        this.state.isLoading = true;

        try {
            const responseBody = await this.fetchNotificationData();
            const items = responseBody.data || [];

            this.dom.list.innerHTML = '';

            if (items.length > 0) {
                const fragment = document.createDocumentFragment();
                items.forEach(item => {
                    fragment.appendChild(this._createNotificationElement(item));
                });
                this.dom.list.appendChild(fragment);
            } else {
                this.dom.list.innerHTML = this._getEmptyStateHtml();
            }
        } catch (error) {
            console.error('Load Error:', error);
            this.dom.list.innerHTML = this._getErrorStateHtml();
        } finally {
            this.state.isLoading = false;
        }
    }

    _createNotificationElement(notification) {
        const div = document.createElement('div');
        div.className = 'list-group-item';
        // Use a cleaner HTML structure
        div.innerHTML = `
            <div class="row align-items-center">
                <div class="col-auto">
                    <span class="status-dot ${notification.is_unread ? 'status-dot-animated bg-red' : 'd-none'} d-block"></span>
                </div>
                <div class="col text-truncate">
                    <a href="${notification.action_url || '#'}" class="text-body d-block">${notification.title || 'Notification'}</a>
                    <div class="d-block text-muted text-truncate mt-n1">
                        ${this._truncateText(notification.body, 80)}
                    </div>
                </div>
            </div>
        `;
        return div;
    }

    _truncateText(text, limit) {
        if (!text) return 'New notification';
        return text.length > limit ? text.substring(0, limit) + '...' : text;
    }

    _getEmptyStateHtml() {
        return `<div class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col text-truncate">
                            <p class="text-center mb-0 text-muted">No notifications found</p>
                        </div>
                    </div>
                </div>`;
    }

    _getErrorStateHtml() {
        return `<div class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col text-truncate">
                            <p class="text-center mb-0 text-danger">Failed to load content</p>
                        </div>
                    </div>
                </div>`;
    }

    showConfirmationDialog() {
        showConfirmation(
            'Konfirmasi',
            'Apakah Anda yakin ingin menandai semua notifikasi sebagai telah dibaca?',
            'Ya, tandai semua'
        ).then((result) => {
            if (result.isConfirmed) this.handleMarkAllAsRead();
        });
    }

    async handleMarkAllAsRead() {
        try {
            const response = await api.notifications.markAllAsRead(); // .data handled in method? No, wait.
            // api object methods return axios response. 
            const data = response.data; // Helper usually consistent

            if (data.status === 'success' || data.success) { // Handle varied API responses
                showSuccessMessage('Sukses!', data.message || 'Marked as read').then(() => {
                    this.updateAllCounters(0);
                    if (this.dom.list) this.dom.list.innerHTML = this._getEmptyStateHtml();
                });
            } else {
                showErrorMessage('Error!', data.message || 'Failed action');
            }
        } catch (error) {
            console.error('Mark Read Error:', error);
            showErrorMessage('Error!', 'System error occurred');
        }
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    window.notificationManager = new NotificationManager();
});