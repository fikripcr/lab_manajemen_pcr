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

// --- DataTables (Core library, needs jQuery)
import 'datatables.net';

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
