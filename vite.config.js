import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: 'localhost',
        port: 3000,
    },
    plugins: [
        laravel({
            input: [
                // CSS Entry Points
                'resources/css/sys.css',

                // JS Entry Points
                'resources/js/admin.js',
                'resources/js/sys.js',

                // Legacy 'sys' scripts that must be loaded manually
                'resources/js/sys/main.js',
                'resources/js/sys/vendor/menu.min.js',
                'resources/js/sys/vendor/helpers.min.js',
                'resources/js/sys/config.js'
            ],
            refresh: true,
        }),
    ],
});
