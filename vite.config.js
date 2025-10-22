import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            'pdfjs-dist/build/pdf.worker.min.js': path.resolve(__dirname, 'node_modules/pdfjs-dist/build/pdf.worker.js'),
        },
    },
    build: {
        // Raise the threshold a bit to reflect intentional code-splitting below
        chunkSizeWarningLimit: 1200,
        rollupOptions: {
            output: {
                // Split heavy libs so the main app chunk stays smaller
                manualChunks: {
                    'vendor-vue': ['vue', 'vue-router'],
                    'pdf-vendors': ['pdf-lib', 'pdfjs-dist'],
                    'icons': ['@heroicons/vue'],
                },
            },
        },
    },
});