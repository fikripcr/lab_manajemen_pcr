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
});
