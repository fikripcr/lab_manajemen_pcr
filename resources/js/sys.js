import './components/GlobalInit.js';

import Pickr from '@simonwep/pickr';
import '@simonwep/pickr/dist/themes/nano.min.css';
window.Pickr = Pickr;

import ThemeManager from './components/ThemeManager.js';
import ThemeSettings from './components/ThemeSettings.js';
import './components/Notification.js';
import './components/FormFeatures.js';
import './components/HoverDropdown.js';

// HugeRTE - Lazy loaded to reduce initial bundle size
window.loadHugeRTE = function (selector, config = {}) {
    return import('hugerte').then((module) => {
        const hugerte = module.default;
        return Promise.all([
            import('hugerte/themes/silver/theme'),
            import('hugerte/icons/default/icons'),
            import('hugerte/models/dom/model'),
            import('hugerte/plugins/lists/plugin'),
            import('hugerte/plugins/link/plugin'),
            import('hugerte/plugins/image/plugin'),
            import('hugerte/plugins/anchor/plugin'),
            import('hugerte/plugins/searchreplace/plugin'),
            import('hugerte/plugins/code/plugin'),
            import('hugerte/plugins/fullscreen/plugin'),
            import('hugerte/plugins/insertdatetime/plugin'),
            import('hugerte/plugins/media/plugin'),
            import('hugerte/plugins/table/plugin'),
            import('hugerte/plugins/wordcount/plugin'),
            import('hugerte/skins/ui/oxide/skin.min.css'),
            import('hugerte/skins/content/default/content.min.css?inline')
        ]).then(([, , , , , , , , , , , , , , , contentCssModule]) => {
            window.hugerte = hugerte;
            window.hugerteContentCss = contentCssModule.default;
            if (selector) {
                hugerte.init({
                    selector: selector,
                    ...config
                });
            }

            return hugerte;
        });
    });
};

// Global Search
window.loadGlobalSearch = function () {
    return import('./components/GlobalSearch.js').then(({ GlobalSearch }) => {
        if (!window.GlobalSearch) {
            window.GlobalSearch = GlobalSearch;
        }
        return GlobalSearch;
    });
};

// TOAST UI Editor
window.initToastEditor = function (selector, config = {}) {
    import('@toast-ui/editor').then(({ Editor }) => {
        new Editor({
            el: document.querySelector(selector),
            ...config
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    const themeManager = new ThemeManager('sys');
    themeManager.loadTheme();
    if (document.getElementById('offcanvasSettings')) {
        const themeSettings = new ThemeSettings(themeManager);
        themeSettings.init();
    }


    if (document.querySelector('#global-search-input') || document.getElementById('globalSearchModal')) {
        window.loadGlobalSearch().then((GlobalSearch) => {
            window.globalSearch = new GlobalSearch();
        });
    }
});
