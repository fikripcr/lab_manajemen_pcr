// --- jQuery (Required for DataTables & legacy plugins)
import $ from 'jquery';
window.$ = window.jQuery = $;

// --- Axios (Modern AJAX)
import axios from 'axios';
window.axios = axios;
window.axios.defaults.withCredentials = true;
window.axios.defaults.withXSRFToken = true;

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

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
import ThemeTabler from './custom/ThemeTabler.js';
import './custom/CustomSweetAlerts.js';
import './custom/Notification.js';
import './custom/FormHandlerAjax.js';

// --- Feature Loaders (Text Editor, DataTables, Charts, Global Search) ---
// Note: We use the exact same loaders as Sys to ensure consistency

window.loadHugeRTE = function (selector, config = {}) {
    return import('hugerte').then((module) => {
        const hugerte = module.default;
        const isDarkMode = localStorage.getItem("tabler-theme") === 'dark';
        const skinImport = isDarkMode
            ? import('hugerte/skins/ui/oxide-dark/skin.min.css')
            : import('hugerte/skins/ui/oxide/skin.min.css');

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
            skinImport,
            import('hugerte/skins/content/default/content.min.css?inline')
        ]).then(([, , , , , , , , , , , , , , skinModule, contentCssModule]) => {
            window.hugerte = hugerte;
            window.hugerteContentCss = contentCssModule.default;
            if (selector) {
                hugerte.init({
                    selector: selector,
                    skin: false,
                    content_css: false,
                    content_style: window.hugerteContentCss + (config.content_style || ''),
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
        return import('./custom/CustomDataTables.js').then(({ default: CustomDataTables }) => {
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
    return import('./custom/GlobalSearch.js').then(({ GlobalSearch }) => {
        if (!window.GlobalSearch) { window.GlobalSearch = GlobalSearch; }
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

window.loadSelect2 = async function () {
    if (window.jQuery.fn.select2) return window.jQuery.fn.select2;
    await import('select2/dist/css/select2.min.css');
    await import('select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css');
    const select2 = (await import('select2')).default;
    select2($);
    return window.jQuery.fn.select2;
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

document.addEventListener('DOMContentLoaded', () => {
    /**
     * UNIFIED THEME SYNC:
     * We initialize with 'sys' mode even in Admin to ensure settings panel syncs globally.
     */
    const themeManager = new ThemeTabler('sys');
    themeManager.initSettingsPanel();

    // Initialize Global Search if present
    if (document.querySelector('#global-search-input') || document.getElementById('globalSearchModal')) {
        window.loadGlobalSearch().then((GlobalSearch) => {
            window.globalSearch = new GlobalSearch();
        });
    }

    // Initialize Offline Select2 (same as sys)
    window.initOfflineSelect2 = function () {
        const elements = document.querySelectorAll('.select2-offline');
        if (elements.length > 0) {
            window.loadSelect2().then(() => {
                $('.select2-offline').each(function () {
                    const $el = $(this);
                    if ($el.data('select2')) return;
                    $el.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: $el.data('placeholder') || 'Select option',
                        allowClear: true
                    });
                    $el.on('select2:select select2:unselect', function (e) {
                        this.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                });
            });
        }
    };
    window.initOfflineSelect2();
});
