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
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'css/[name].[hash].[ext]';
                    }

                    // For assets from node_modules (libraries added via npm)
                    if (assetInfo.name.includes('node_modules')) {
                        return 'assets/vendor/[name].[hash].[ext]';
                    }

                    return 'assets/[name].[hash].[ext]';
                },
                chunkFileNames: 'assets/vendor/[name].[hash].js', // Bundled vendor chunks
                entryFileNames: 'assets/[name].[hash].js', // Main entry files
            }
        }
    },
    // Enable code splitting for better performance
    optimizeDeps: {
        include: [
            // Include specific libraries from node_modules if needed for pre-bundling
            // 'package-name',  // Add specific packages here if needed
        ]
    }
});
