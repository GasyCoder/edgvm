import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    
    // Optimisations de build
    build: {
        chunkSizeWarningLimit: 1000,
        rollupOptions: {
            output: {
                manualChunks: {
                    'vendor': [
                        'alpinejs',
                    ],
                },
            },
        },
    },

    // Configuration du serveur de développement
    server: {
        host: true,
        hmr: {
            host: 'localhost',
        },
    },

    // Optimisation des dépendances
    optimizeDeps: {
        include: ['alpinejs'],
    },
});