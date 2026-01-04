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



// --- DataTables (Bootstrap 5 Styling)
import 'datatables.net-bs5';

// --- SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// --- Shared Components ---
import './CustomSweetAlerts.js';
import './AjaxFormHandler.js';

// --- Perfect Scrollbar
import PerfectScrollbar from 'perfect-scrollbar';
window.PerfectScrollbar = PerfectScrollbar;

// --- ApexCharts
import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

// --- Masonry
import Masonry from 'masonry-layout';
window.Masonry = Masonry;

// --- Highlight.js
import hljs from 'highlight.js';
window.hljs = hljs;

// --- CustomDataTables (essential for datatables component)
import CustomDataTables from './CustomDataTables.js';
window.CustomDataTables = CustomDataTables;
