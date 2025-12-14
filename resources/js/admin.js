// Helpers must be loaded first
import '../assets/admin/vendor/js/helpers.min.js';

// Config
import '../assets/admin/js/config.js';

// Global Dependencies (NPM - jQuery, Bootstrap, Axios, SweetAlert2)
import './components/GlobalInit.js';

// Menu
import '../assets/admin/vendor/js/menu.min.js';

// Main Template JS
import '../assets/admin/js/main.js';

// Example: Github buttons
import '../assets/admin/libs/github-buttons/buttons.min.js';

// Feature Components
import './components/FormFeatures.js';

// --- Notification Manager (direct import - needs to load on every page)
import './components/Notification.js';

// --- Global Search (lazy loading)
window.loadGlobalSearch = function () {
    return import('./components/GlobalSearch.js').then(({ GlobalSearch }) => {
        if (!window.GlobalSearch) {
            window.GlobalSearch = GlobalSearch;
        }
        return GlobalSearch;
    });
};

// Initialize global search only when DOM is ready and if needed
document.addEventListener('DOMContentLoaded', () => {
    // Check if global search elements exist on the page
    if (document.querySelector('#global-search-input') || document.getElementById('globalSearchModal')) {
        window.loadGlobalSearch().then((GlobalSearch) => {
            window.globalSearch = new GlobalSearch();
        });
    }
});