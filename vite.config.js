import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],

    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },

    // Optimisations de build
    build: {
        chunkSizeWarningLimit: 1000,
        rollupOptions: {
            output: {
                manualChunks: {
                    'vendor': [
                        'vue',
                        '@inertiajs/vue3',
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
        include: ['vue', '@inertiajs/vue3', 'lodash-es'],
    },
});