// --- jQuery (dibutuhkan oleh DataTables dan plugin lama)
import $ from 'jquery';
window.$ = window.jQuery = $;

// --- Bootstrap 5 JS (untuk Modal, Dropdown, Tooltip, dll)
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// --- Axios (untuk AJAX modern)
import axios from 'axios';
window.axios = axios;
window.axios.defaults.withCredentials = true; // untuk Sanctum/session
window.axios.defaults.withXSRFToken = true

// --- DataTables (dynamic import – hanya load saat dipanggil)
import 'datatables.net';

// --- TinyMCE (dynamic import)
window.initTinyMCE = function(selector, config = {}) {
    import('tinymce/tinymce').then(() => {
        import('tinymce/themes/silver').then(() => {
            window.tinymce.init({
                selector: selector,
                skin: 'oxide',
                content_css: 'default',
                ...config
            });
        });
    });
};

// --- TOAST UI Editor (dynamic import)
window.initToastEditor = function(selector, config = {}) {
    import('@toast-ui/editor').then(({ Editor }) => {
        new Editor({
            el: document.querySelector(selector),
            ...config
        });
    });
};

// --- SweetAlert2 (dynamic import)
import Swal from 'sweetalert2';
window.Swal = Swal;
