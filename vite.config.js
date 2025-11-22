import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 'resources/js/admin.js',
                // 'resources/js/auth.js',
                // 'resources/js/guest.js',
                'resources/js/sys.js',
            ],
            refresh: true,
        }),
    ],
});
