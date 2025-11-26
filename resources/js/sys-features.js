// Import flatpickr
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

// Import filepond
import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';

// Import filepond image preview plugin
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

// Register the plugin
FilePond.registerPlugin(FilePondPluginImagePreview);

// Import lodash
import _ from 'lodash';

// Make these libraries available globally
window.flatpickr = flatpickr;
window.FilePond = FilePond;
window._ = _;