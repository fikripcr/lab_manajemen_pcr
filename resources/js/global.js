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

// --- SweetAlert2 (dynamic import)
import Swal from 'sweetalert2';
window.Swal = Swal;


