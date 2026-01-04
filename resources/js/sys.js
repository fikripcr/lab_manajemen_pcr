// Tabler Core
import '@tabler/core/dist/js/tabler.esm.js';
import '@tabler/core/dist/css/tabler.css';
import '@tabler/core/dist/css/tabler-vendors.css'; // Good to have for plugins

// Tabler Icon
import '@tabler/icons-webfont/dist/tabler-icons.css';


// Pickr (Color Picker)
import Pickr from '@simonwep/pickr';
import '@simonwep/pickr/dist/themes/nano.min.css'; // 'classic', 'monolith', 'nano'
window.Pickr = Pickr;

import '../css/sys.css';
import './components/GlobalInit.js';



// --- Notification Manager (direct import - needs to load on every page)
import './components/Notification.js';

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
