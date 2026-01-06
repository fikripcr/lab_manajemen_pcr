import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: 'localhost',
        port: 3010,
    },
    plugins: [
        laravel({
            input: [
                // CSS Entry Points
                'resources/css/sys.css',
                'resources/assets/sys/css/custom-datatable.css',
                'resources/css/admin.css',
                'resources/css/auth.css',
                'resources/css/guest.css',

                // JS Entry Points
                'resources/js/admin.js',
                'resources/js/sys.js',
                'resources/js/auth.js',
                'resources/js/guest.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('bootstrap') || id.includes('@popperjs')) {
                        return 'vendor-bootstrap';
                    }

                    if (id.includes('datatables')) {
                        return 'vendor-datatables';
                    }

                    if (id.includes('chart') || id.includes('apexcharts')) {
                        return 'vendor-charts';
                    }

                    if (id.includes('pickr') || id.includes('choices') || id.includes('flatpickr')) {
                        return 'vendor-forms';
                    }

                    if (id.includes('hugerte') || id.includes('tinymce')) {
                        return 'vendor-editor';
                    }

                    if (id.includes('axios') || id.includes('sweetalert2')) {
                        return 'vendor-utils';
                    }

                    if (id.includes('node_modules')) {
                        return 'vendor-other';
                    }
                }
            }
        }
    }
});
