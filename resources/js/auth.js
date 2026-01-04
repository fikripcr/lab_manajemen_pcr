// --- Bootstrap 5 JS (Tabler Dependency)
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// --- Tabler Core ---
import '@tabler/core/dist/js/tabler.esm.js';

// --- Pickr (Color Picker) ---
import Pickr from '@simonwep/pickr';
import '@simonwep/pickr/dist/themes/nano.min.css';
window.Pickr = Pickr;

// --- Auth Specific Logic ---
// (Will be added by specific pages or components)