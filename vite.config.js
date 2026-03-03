import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0', // Agar bisa diakses dari luar container
        port: 5173,      // Paksa kembali ke 5173 agar sesuai Docker Compose
        hmr: {
            host: 'localhost',
        },
    },
    resolve: {
        alias: {
            '@tabler-core': '/resources/tabler-core',
        },
    },
    optimizeDeps: {
        // Paksa Vite pre-bundle jKanban (CommonJS/UMD) agar resolvable sebagai ESM module
        include: ['jkanban'],
    },
    plugins: [
        laravel({
            input: [
                // CSS Entry Points
                'resources/tabler-core/css/tabler.css',
                'resources/css/auth.css',
                'resources/css/public.css',
                // JS Entry Points
                'resources/tabler-core/js/tabler.js',
                'resources/js/auth.js',
                'resources/js/public.js'
            ],
            refresh: true,
        }),
    ],
});
