// --- Bootstrap 5 JS (Tabler Dependency)
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// --- Popper.js (Required by Bootstrap for dropdowns) ---
import { createPopper } from '@popperjs/core';
window.Popper = { createPopper };

// --- Pickr (Color Picker) ---
import Pickr from '@simonwep/pickr';
import '@simonwep/pickr/dist/themes/nano.min.css';
window.Pickr = Pickr;

// --- Axios (For HTTP requests) ---
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// --- SweetAlert2 ---
import Swal from 'sweetalert2';
window.Swal = Swal;

// --- Theme Management System ---
import ThemeManager from './components/ThemeManager.js';
import ThemeSettings from './components/ThemeSettings.js';
import FormPositioner from './components/FormPositioner.js';

// --- Auth Specific Logic ---
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Theme Manager
    const themeManager = new ThemeManager('auth');
    themeManager.loadTheme();

    // Initialize Form Positioning (for cover/illustration layouts)
    const formPositioner = new FormPositioner(themeManager);
    formPositioner.init();

    // Initialize Settings Panel (if present)
    if (document.getElementById('offcanvasSettings')) {
        const themeSettings = new ThemeSettings(themeManager);
        themeSettings.init();
    }
});