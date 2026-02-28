// --- jQuery (Required for DataTables & legacy plugins)
import $ from 'jquery';
window.$ = window.jQuery = $;


// --- Axios (Modern AJAX)
import axios from 'axios';
// Only expose axios globally if truly needed by legacy code
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

// --- SortableJS
import Sortable from 'sortablejs';
window.Sortable = Sortable;

// --- SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// --- Shared Components ---
import ThemeTabler from '../assets/tabler/js/ThemeTabler.js';
import '../assets/tabler/js/CustomSweetAlerts.js';
import '../assets/tabler/js/Notification.js';
import '../assets/tabler/js/FormHandlerAjax.js';

// --- Module Helpers ---
import './helpers/pemutu-workspace.js';
import './helpers/pemutu-indikator.js';
import './helpers/projects-kanban.js';
import './helpers/hr-pegawai.js';


/**
 * Load HugeRTE Editor with error handling and dynamic skin loading
 * @param {string} selector - CSS selector for the textarea
 * @param {object} config - Additional configuration options
 * @returns {Promise} - Resolves with hugerte instance
 */
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
                sandbox_iframes: false,
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
    }).catch((error) => {
        console.error('Failed to load HugeRTE editor:', error);
        // Fallback: Show user-friendly error message
        if (selector) {
            const el = document.querySelector(selector);
            if (el) {
                el.style.border = '2px solid #d63939';
                el.style.padding = '1rem';
                el.innerHTML = '<p style="color: #d63939;">Editor failed to load. Please refresh the page or contact support.</p>';
            }
        }
        throw error;
    });
};

/**
 * Load DataTables with error handling
 * @returns {Promise} - Resolves with CustomDataTables instance
 */
window.loadDataTables = function () {
    if (window.DataTablesLoaded) return Promise.resolve(window.CustomDataTables);

    return import('datatables.net-bs5')
        .then(() => import('../assets/tabler/js/CustomDataTables.js'))
        .then(({ default: CustomDataTables }) => {
            window.CustomDataTables = CustomDataTables;
            window.DataTablesLoaded = true;
            return CustomDataTables;
        })
        .catch((error) => {
            console.error('Failed to load DataTables:', error);
            // Provide fallback error message
            console.warn('DataTables features will be unavailable. Please check your network connection.');
            throw error;
        });
};

/**
 * Load ApexCharts with error handling
 * @returns {Promise} - Resolves with ApexCharts instance
 */
window.loadApexCharts = function () {
    if (window.ApexCharts) return Promise.resolve(window.ApexCharts);

    return import('apexcharts')
        .then(({ default: ApexCharts }) => {
            window.ApexCharts = ApexCharts;
            return ApexCharts;
        })
        .catch((error) => {
            console.error('Failed to load ApexCharts:', error);
            console.warn('Chart features will be unavailable. Please check your network connection.');
            throw error;
        });
};

/**
 * Load Global Search module with error handling
 * @returns {Promise} - Resolves with GlobalSearch class
 */
window.loadGlobalSearch = function () {
    return import('../assets/tabler/js/GlobalSearch.js')
        .then(({ GlobalSearch }) => {
            if (!window.GlobalSearch) {
                window.GlobalSearch = GlobalSearch;
            }
            return GlobalSearch;
        })
        .catch((error) => {
            console.error('Failed to load GlobalSearch:', error);
            throw error;
        });
};

/**
 * Load Flatpickr with error handling
 * @returns {Promise} - Resolves with flatpickr instance
 */
window.loadFlatpickr = async function () {
    if (window.flatpickr) return window.flatpickr;

    try {
        const flatpickr = (await import('flatpickr')).default;
        await import('flatpickr/dist/flatpickr.min.css');
        window.flatpickr = flatpickr;
        return flatpickr;
    } catch (error) {
        console.error('Failed to load Flatpickr:', error);
        throw error;
    }
};

/**
 * Load Select2 with Bootstrap 5 theme and error handling
 * @returns {Promise} - Resolves with Select2 jQuery plugin
 */
window.loadSelect2 = async function () {
    if (window.jQuery.fn.select2) return window.jQuery.fn.select2;

    try {
        // Load Select2 library and CSS with Bootstrap 5 theme
        await import('select2/dist/css/select2.min.css');
        await import('select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css');
        const select2 = (await import('select2')).default;

        // Initialize Select2 on jQuery
        select2($);

        return window.jQuery.fn.select2;
    } catch (error) {
        console.error('Failed to load Select2:', error);
        throw error;
    }
};

/**
 * Load FilePond with plugins and error handling
 * @returns {Promise} - Resolves with FilePond instance
 */
window.loadFilePond = async function () {
    if (window.FilePond) return window.FilePond;

    try {
        const FilePond = await import('filepond');
        await import('filepond/dist/filepond.min.css');

        const FilePondPluginImagePreview = (await import('filepond-plugin-image-preview')).default;
        await import('filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css');

        const FilePondPluginImageCrop = (await import('filepond-plugin-image-crop')).default;
        const FilePondPluginImageTransform = (await import('filepond-plugin-image-transform')).default;
        const FilePondPluginFileValidateType = (await import('filepond-plugin-file-validate-type')).default;
        const FilePondPluginFileValidateSize = (await import('filepond-plugin-file-validate-size')).default;

        FilePond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImagePreview,
            FilePondPluginImageCrop,
            FilePondPluginImageTransform
        );
        window.FilePond = FilePond;
        return FilePond;
    } catch (error) {
        console.error('Failed to load FilePond:', error);
        throw error;
    }
};

/**
 * Initialize Toast UI Editor
 * @param {string} selector - CSS selector for the editor container
 * @param {object} config - Additional configuration options
 */
window.initToastEditor = function (selector, config = {}) {
    import('@toast-ui/editor')
        .then(({ Editor }) => {
            new Editor({
                el: document.querySelector(selector),
                ...config
            });
        })
        .catch((error) => {
            console.error('Failed to load Toast Editor:', error);
        });
};

document.addEventListener('DOMContentLoaded', () => {
    // Initializing for Unified Tabler Layout
    window.themeTabler = new ThemeTabler('tabler');
    window.themeTabler.initSettingsPanel();

    window.initOfflineSelect2 = function () {
        const elements = document.querySelectorAll('.select2-offline');
        if (elements.length > 0) {
            window.loadSelect2().then(() => {
                $('.select2-offline').each(function () {
                    const $el = $(this);
                    if ($el.data('select2')) return;

                    const $modal = $el.closest('.modal');
                    const config = {
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: $el.data('placeholder') || 'Select option',
                        allowClear: true
                    };

                    if ($modal.length) {
                        config.dropdownParent = $modal;
                    }

                    $el.select2(config);

                    $el.on('select2:select select2:unselect', function (e) {
                        this.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                });
            }).catch((error) => {
                console.error('Failed to initialize Select2:', error);
            });
        }
    };

    window.initFlatpickr = function () {
        const elements = document.querySelectorAll('.flatpickr-input');
        if (elements.length > 0) {
            window.loadFlatpickr().then((fp) => {
                elements.forEach(el => {
                    if (el._flatpickr) return; // Skip if already initialized

                    const config = {
                        dateFormat: "Y-m-d",
                        allowInput: true,
                        altInput: true,
                        altFormat: "j F Y", // e.g. 15 February 2026
                        ... (el.dataset.flatpickrConfig ? JSON.parse(el.dataset.flatpickrConfig) : {})
                    };

                    // Handle specific types from data attributes
                    if (el.dataset.flatpickrType === 'time') {
                        config.enableTime = true;
                        config.noCalendar = true;
                        config.dateFormat = "H:i";
                        config.time_24hr = true;
                        config.altInput = true;
                        config.altFormat = "H:i";
                    } else if (el.dataset.flatpickrEnableTime === 'true') {
                        config.enableTime = true;
                        config.dateFormat = "Y-m-d H:i";
                        config.altFormat = "j F Y H:i";
                    }

                    if (el.dataset.flatpickrMode) {
                        config.mode = el.dataset.flatpickrMode;
                    }

                    fp(el, config);
                });
            }).catch((error) => {
                console.error('Failed to initialize Flatpickr:', error);
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
            }).catch((error) => {
                console.error('Failed to initialize FilePond:', error);
            });
        }
    };

    window.initOfflineSelect2();
    window.initFlatpickr();
    window.initFilePond();

    if (document.querySelector('#global-search-input') || document.getElementById('globalSearchModal')) {
        window.loadGlobalSearch().then((GlobalSearch) => {
            window.globalSearch = new GlobalSearch();
        }).catch((error) => {
            console.error('Failed to initialize GlobalSearch:', error);
        });
    }
});

/**
 * Toggle theme with server-first approach
 * Sends request to server first, then updates UI only on success
 * @param {string} mode - 'light' or 'dark'
 */
window.toggleTheme = function (mode) {
    const previousMode = document.documentElement.getAttribute('data-bs-theme');

    // Optimistic UI update (for better UX, but we'll rollback if server fails)
    document.documentElement.setAttribute('data-bs-theme', mode);

    // Sync with ThemeTabler if available
    if (window.themeTabler) {
        window.themeTabler.refresh();
    }

    // Persist to Server FIRST (server is source of truth)
    if (window.axios) {
        axios.post('/theme/save', {
            mode: 'tabler',
            theme: mode
        })
            .then(() => {
                // Server confirmed - save to localStorage
                localStorage.setItem('tabler-theme', mode);
            })
            .catch((error) => {
                console.error('Failed to save theme preference, rolling back...', error);
                // Rollback: Restore previous theme
                document.documentElement.setAttribute('data-bs-theme', previousMode);
                localStorage.setItem('tabler-theme', previousMode);

                if (window.themeTabler) {
                    window.themeTabler.refresh();
                }

                // Notify user of the failure
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Theme Sync Failed',
                        text: 'Unable to save theme preference. Your local view has been restored.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
    } else {
        // No axios available - just update locally
        localStorage.setItem('tabler-theme', mode);
        console.warn('Axios not available, theme preference not saved to server');
    }
};

