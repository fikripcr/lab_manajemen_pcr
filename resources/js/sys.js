// --- jQuery (Required for DataTables & legacy plugins)
import $ from 'jquery';
window.$ = window.jQuery = $;

// --- Axios (Modern AJAX)
import axios from 'axios';
window.axios = axios;
window.axios.defaults.withCredentials = true;
window.axios.defaults.withXSRFToken = true;

// --- Popper.js (Bootstrap 5 Dependency)
import { createPopper } from '@popperjs/core';
window.Popper = { createPopper };

// --- Bootstrap 5
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// --- SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// --- Shared Components ---
import './components/CustomSweetAlerts.js';
import './components/FormHandlerAjax.js';

import ThemeManager from './components/ThemeManager.js';
import './components/Notification.js';

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

window.loadDataTables = function () {
    if (window.DataTablesLoaded) return Promise.resolve(window.CustomDataTables);

    return import('datatables.net-bs5').then(() => {
        return import('./components/CustomDataTables.js').then(({ default: CustomDataTables }) => {
            window.CustomDataTables = CustomDataTables;
            window.DataTablesLoaded = true;
            return CustomDataTables;
        });
    });
};

window.loadApexCharts = function () {
    if (window.ApexCharts) return Promise.resolve(window.ApexCharts);

    return import('apexcharts').then(({ default: ApexCharts }) => {
        window.ApexCharts = ApexCharts;
        return ApexCharts;
    });
};

window.loadGlobalSearch = function () {
    return import('./components/GlobalSearch.js').then(({ GlobalSearch }) => {
        if (!window.GlobalSearch) {
            window.GlobalSearch = GlobalSearch;
        }
        return GlobalSearch;
    });
};

window.loadFlatpickr = async function () {
    if (window.flatpickr) return window.flatpickr;

    const flatpickr = (await import('flatpickr')).default;
    await import('flatpickr/dist/flatpickr.min.css');
    window.flatpickr = flatpickr;
    return flatpickr;
};

window.loadChoices = async function () {
    if (window.Choices) return window.Choices;

    const Choices = (await import('choices.js')).default;
    window.Choices = Choices;
    return Choices;
};

window.loadFilePond = async function () {
    if (window.FilePond) return window.FilePond;

    const FilePond = await import('filepond');
    await import('filepond/dist/filepond.min.css');

    const FilePondPluginImagePreview = (await import('filepond-plugin-image-preview')).default;
    await import('filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css');

    FilePond.registerPlugin(FilePondPluginImagePreview);
    window.FilePond = FilePond;
    return FilePond;
};

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
    themeManager.initSettingsPanel();


    if (document.querySelector('#global-search-input') || document.getElementById('globalSearchModal')) {
        window.loadGlobalSearch().then((GlobalSearch) => {
            window.globalSearch = new GlobalSearch();
        });
    }
});
