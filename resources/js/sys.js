// Import legacy sys vendor files to bundle them together
import './sys/vendor/helpers.min.js';
import './sys/config.js';
import './sys/vendor/menu.min.js';
import './sys/main.js';

// --- Notification Manager (direct import - needs to load on every page)
import './components/Notification.js';

// Import global dependencies
import './global.js';

// Import FormFeatures to make loadFormFeatures available
import './components/FormFeatures.js';

// --- Global Search (lazy loading)
window.loadGlobalSearch = function () {
    return import('./components/GlobalSearch.js').then(({ GlobalSearch }) => {
        if (!window.GlobalSearch) {
            window.GlobalSearch = GlobalSearch;
        }
        return GlobalSearch;
    });
};

// --- TOAST UI Editor (dynamic import)
window.initToastEditor = function (selector, config = {}) {
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
});
