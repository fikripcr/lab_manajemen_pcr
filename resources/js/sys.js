import './global.js';

// --- Global Search (lazy loading)
window.loadGlobalSearch = function() {
    return import('./components/GlobalSearch.js').then(({ GlobalSearch }) => {
        if (!window.GlobalSearch) {
            window.GlobalSearch = GlobalSearch;
        }
        return GlobalSearch;
    });
};

// --- Notification Manager (lazy loading)
window.loadNotificationManager = function() {
    return import('./components/Notification.js').then(({ NotificationManager }) => {
        if (!window.NotificationManager) {
            window.NotificationManager = NotificationManager;
        }
        return NotificationManager;
    });
};

// --- TOAST UI Editor (dynamic import)
window.initToastEditor = function(selector, config = {}) {
    import('@toast-ui/editor').then(({ Editor }) => {
        new Editor({
            el: document.querySelector(selector),
            ...config
        });
    });
};

// Initialize global search and notifications only when DOM is ready and if they're needed on the page
document.addEventListener('DOMContentLoaded', () => {
    // Check if global search elements exist on the page
    if (document.querySelector('#global-search-input') || document.getElementById('globalSearchModal')) {
        window.loadGlobalSearch().then((GlobalSearch) => {
            window.globalSearch = new GlobalSearch();
        });
    }

    // Initialize notification manager if notification elements exist on the page
    // The dropdown is always present in sys layout, so this should always run
    if (document.querySelector('.dropdown-notification') || document.getElementById('notification-count')) {
        // Load notification manager (no longer requires appRoutes)
        window.loadNotificationManager().then((NotificationManager) => {
            window.notificationManager = new NotificationManager();
        });
    }
});
