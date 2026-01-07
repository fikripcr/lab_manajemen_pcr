// --- jQuery (dibutuhkan oleh DataTables dan plugin lama)
import $ from 'jquery';
window.$ = window.jQuery = $;

// --- Axios (untuk AJAX modern)
import axios from 'axios';
window.axios = axios;
window.axios.defaults.withCredentials = true; // untuk Sanctum/session
window.axios.defaults.withXSRFToken = true

// --- Popper.js (REQUIRED by Bootstrap 5 for dropdowns, popovers, tooltips)
import { createPopper } from '@popperjs/core';
window.Popper = { createPopper }; // Expose globally for Bootstrap

// --- SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// --- Shared Components ---
import './CustomSweetAlerts.js';
import './AjaxFormHandler.js';





