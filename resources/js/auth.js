// --- Bootstrap 5 JS (Tabler Dependency)
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// --- Popper.js (Required by Bootstrap for dropdowns) ---
import { createPopper } from '@popperjs/core';
window.Popper = { createPopper };



// --- Axios (For HTTP requests) ---
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// --- SweetAlert2 ---
import Swal from 'sweetalert2';
window.Swal = Swal;

// --- Theme Management System ---
import ThemeTabler from './custom/ThemeTabler.js';

// --- Auth Specific Logic ---
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Theme Manager (live preview only)
    const themeManager = new ThemeTabler('auth');
    themeManager.initSettingsPanel();
});