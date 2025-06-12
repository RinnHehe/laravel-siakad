import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
    ],
    server: {
        port: 5176,
        strictPort: false,
        host: '127.0.0.1',
        hmr: {
            host: '127.0.0.1'
        },
    },
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
