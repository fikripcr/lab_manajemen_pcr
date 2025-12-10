// resources/js/api.js
// Centralized API helper without exposing route names in JavaScript
// Using axios to maintain compatibility with existing code
// Routes are defined in a mapping that can be populated server-side if needed later
const routes = {
    notifications: {
        unreadCount: '/sys/notifications/unread-count',
        dropdownData: '/sys/notifications/dropdown-data',
        markAllAsRead: '/sys/notifications/mark-all-as-read',
        index: '/sys/notifications',
        markAsRead: '/sys/notifications/mark-as-read/'  // + id
    },
    globalSearch: '/global-search'
};

// Function to get CSRF token
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

export const api = {
    notifications: {
        unreadCount: () => window.axios.get(routes.notifications.unreadCount),
        dropdownData: () => window.axios.get(routes.notifications.dropdownData),
        markAllAsRead: () => window.axios.post(routes.notifications.markAllAsRead, {}, {
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
            }
        }),
        index: () => window.axios.get(routes.notifications.index),
        markAsRead: (id) => window.axios.get(`${routes.notifications.markAsRead}${id}`, {
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
            }
        })
    },

    globalSearch: (query) => {
        return window.axios.get(`${routes.globalSearch}?q=${encodeURIComponent(query)}`);
    }
};