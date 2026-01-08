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

// --- SweetAlert2 ---
import Swal from 'sweetalert2';
window.Swal = Swal;

// --- Theme Management System ---
import ThemeManager from './custom/ThemeManager.js';

// --- Auth Specific Logic ---
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Theme Manager
    const themeManager = new ThemeManager('auth');
    themeManager.loadTheme();
    themeManager.initSettingsPanel();
});