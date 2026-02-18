// --- jQuery (Required for DataTables & legacy plugins)
import $ from 'jquery';
window.$ = window.jQuery = $;

// --- Axios (Modern AJAX)
import axios from 'axios';
window.axios = axios;
window.axios.defaults.withCredentials = true;
window.axios.defaults.withXSRFToken = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// --- Bootstrap 5 (Bundle includes Popper.js internally)
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle';
window.bootstrap = bootstrap;

// --- SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// --- Shared Components ---
import ThemeTabler from '../assets/tabler/js/ThemeTabler.js';
import '../assets/tabler/js/CustomSweetAlerts.js';
import '../assets/tabler/js/Notification.js';
import '../assets/tabler/js/FormHandlerAjax.js';

window.loadHugeRTE = function (selector, config = {}) {
    return import('hugerte').then((module) => {
        const hugerte = module.default;

        // Dynamic dark mode detection function
        const isDarkMode = () => {
            // Prioritize HTML element attribute (SSR source of truth)
            const htmlTheme = document.documentElement.getAttribute('data-bs-theme');
            if (htmlTheme === 'dark') return true;
            if (htmlTheme === 'light') return false;

            // Fallback to LocalStorage
            const theme = localStorage.getItem("tabler-theme");
            if (theme === 'dark') return true;
            if (theme === 'light') return false;
            // Auto mode - check system preference
            return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        };

        const currentDarkMode = isDarkMode();

        // Import appropriate skin based on current theme
        const skinImport = currentDarkMode
            ? import('hugerte/skins/ui/oxide-dark/skin.min.css')
            : import('hugerte/skins/ui/oxide/skin.min.css');

        // Import appropriate content CSS
        const contentCssImport = currentDarkMode
            ? import('hugerte/skins/content/dark/content.min.css?inline')
            : import('hugerte/skins/content/default/content.min.css?inline');

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
            contentCssImport
        ]).then(([, , , , , , , , , , , , , , skinModule, contentCssModule]) => {
            window.hugerte = hugerte;
            window.hugerteContentCss = contentCssModule.default;

            // Build config with dark mode support
            const editorConfig = {
                selector: selector,
                skin: false,
                content_css: false,
                content_style: window.hugerteContentCss + (config.content_style || ''),
                ...config
            };

            if (selector) {
                hugerte.init(editorConfig);
            }

            // Listen for theme changes and reinitialize if needed
            window.addEventListener('storage', (e) => {
                if (e.key === 'tabler-theme') {
                    const newDarkMode = isDarkMode();
                    if (newDarkMode !== currentDarkMode) {
                        // Theme changed, reinitialize HugeRTE
                        if (hugerte.get(selector)) {
                            const currentContent = hugerte.get(selector).getContent();
                            hugerte.remove(selector);
                            window.loadHugeRTE(selector, config).then((editor) => {
                                if (editor && editor.get(selector)) {
                                    editor.get(selector).setContent(currentContent);
                                }
                            });
                        }
                    }
                }
            });

            return hugerte;
        });
    });
};

window.loadDataTables = function () {
    if (window.DataTablesLoaded) return Promise.resolve(window.CustomDataTables);

    return import('datatables.net-bs5').then(() => {
        return import('../assets/tabler/js/CustomDataTables.js').then(({ default: CustomDataTables }) => {
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
    return import('../assets/tabler/js/GlobalSearch.js').then(({ GlobalSearch }) => {
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

window.loadSelect2 = async function () {
    if (window.jQuery.fn.select2) return window.jQuery.fn.select2;

    // Load Select2 library and CSS with Bootstrap 5 theme
    await import('select2/dist/css/select2.min.css');
    await import('select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css');
    const select2 = (await import('select2')).default;

    // Initialize Select2 on jQuery
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

window.initToastEditor = function (selector, config = {}) {
    import('@toast-ui/editor').then(({ Editor }) => {
        new Editor({
            el: document.querySelector(selector),
            ...config
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    // Initializing for both Admin and Sys (Unified)
    window.themeTabler = new ThemeTabler('sys'); // Default to sys mode/standard
    window.themeTabler.initSettingsPanel();

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

    window.initFilePond = function () {
        const elements = document.querySelectorAll('.filepond-input');
        if (elements.length > 0) {
            window.loadFilePond().then((FilePond) => {
                elements.forEach(el => {
                    if (FilePond.find(el)) return; // Skip if already initialized

                    const config = {
                        storeAsFile: true,
                        allowMultiple: el.multiple || el.dataset.allowMultiple === 'true' || false,
                        required: el.required || false,
                        credits: false,
                        labelIdle: el.dataset.labelIdle || 'Drag & Drop files or <span class="filepond--label-action">Browse</span>',
                        acceptedFileTypes: el.dataset.acceptedFileTypes ? el.dataset.acceptedFileTypes.split(',') : (el.accept ? el.accept.split(',') : null),
                        ... (el.dataset.filepondConfig ? JSON.parse(el.dataset.filepondConfig) : {})
                    };

                    FilePond.create(el, config);
                });
            });
        }
    };

    window.initOfflineSelect2();
    window.initFilePond();


    if (document.querySelector('#global-search-input') || document.getElementById('globalSearchModal')) {
        window.loadGlobalSearch().then((GlobalSearch) => {
            window.globalSearch = new GlobalSearch();
        });
    }
});

// --- Dark Mode Toggle (Global) ---
window.toggleTheme = function (mode) {
    // 1. Set Attribute
    document.documentElement.setAttribute('data-bs-theme', mode);

    // 2. Save to LocalStorage
    localStorage.setItem('tabler-theme', mode);

    // 3. Sync with ThemeTabler if available
    if (window.themeTabler) {
        window.themeTabler.refresh();
    }

    // 4. Persist to Server
    if (window.axios) {
        axios.post('/theme/save', {
            mode: 'sys', // Default context, might need adjustment for Admin but 'sys' config usually shared for theme
            theme: mode
        }).catch(err => console.error('Failed to save theme preference', err));
    }
};
