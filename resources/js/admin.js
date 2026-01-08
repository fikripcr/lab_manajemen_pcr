// Helpers must be loaded first
import '../assets/admin/vendor/js/helpers.min.js';

// Config
import '../assets/admin/js/config.js';

// Global Dependencies (NPM - jQuery, Bootstrap, Axios, SweetAlert2)
// Global Dependencies (NPM - jQuery, Bootstrap, Axios, SweetAlert2)
import $ from 'jquery';
window.$ = window.jQuery = $;

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import axios from 'axios';
window.axios = axios;
window.axios.defaults.withCredentials = true;
window.axios.defaults.withXSRFToken = true;

import Swal from 'sweetalert2';
window.Swal = Swal;

import { createPopper } from '@popperjs/core';
window.Popper = { createPopper };

// Menu
import '../assets/admin/vendor/js/menu.min.js';

// Main Template JS
import '../assets/admin/js/main.js';

// Example: Github buttons
import '../assets/admin/libs/github-buttons/buttons.min.js';

// Feature Components
// Feature Components - Lazy Loaders
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