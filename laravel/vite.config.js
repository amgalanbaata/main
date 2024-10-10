import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/admin.css',
                'resources/font/font-awesome.min.css',
                'resources/js/app.js',
                'resources/js/app.min.js',
                'resources/js/vendor.min.js'
            ],
            refresh: true,
        }),
    ],
});
