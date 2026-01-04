// CRITICAL: Load GlobalInit FIRST to ensure axios, jQuery, Bootstrap are available
import './components/GlobalInit.js';

// Tabler Core
// import '@tabler/core/dist/js/tabler.esm.js';
// import '@tabler/core/dist/css/tabler.css';
// import '@tabler/core/dist/css/tabler-vendors.css'; // Good to have for plugins

// Tabler Icon
// import '@tabler/icons-webfont/dist/tabler-icons.css';


// Pickr (Color Picker)
import Pickr from '@simonwep/pickr';
import '@simonwep/pickr/dist/themes/nano.min.css'; // 'classic', 'monolith', 'nano'
window.Pickr = Pickr;

// import '../css/sys.css';



// --- Notification Manager (direct import - needs to load on every page)
import './components/Notification.js';

// Import FormFeatures to make loadFormFeatures available
import './components/FormFeatures.js';

// Import HoverDropdown for desktop hover interactions
import './components/HoverDropdown.js';

// Import HugeRTE (Tabler's Editor) and expose globally
// Import HugeRTE (Tabler's Editor) and expose globally
import hugerte from 'hugerte';

// Bundle HugeRTE Assets to prevent 404s
import 'hugerte/themes/silver/theme';
import 'hugerte/icons/default/icons';
import 'hugerte/models/dom/model';

// Bundle HugeRTE Plugins
import 'hugerte/plugins/lists/plugin';
import 'hugerte/plugins/link/plugin';
import 'hugerte/plugins/image/plugin';
import 'hugerte/plugins/anchor/plugin';
import 'hugerte/plugins/searchreplace/plugin';
import 'hugerte/plugins/code/plugin';
import 'hugerte/plugins/fullscreen/plugin';
import 'hugerte/plugins/insertdatetime/plugin';
import 'hugerte/plugins/media/plugin';
import 'hugerte/plugins/table/plugin';
import 'hugerte/plugins/wordcount/plugin';

// Bundle HugeRTE Skins (prevents 404s)
import 'hugerte/skins/ui/oxide/skin.min.css';
import contentCss from 'hugerte/skins/content/default/content.min.css?inline';

window.hugerte = hugerte;
window.hugerteContentCss = contentCss;

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
