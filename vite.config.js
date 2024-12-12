import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/sass/app.scss', 'resources/js/app.js'],
            input: ['resources/css/app.css', 'resources/js/app.js','resources/vendor/js/app.js','resources/vendor/libs/app.js'],
            refresh: true,
            
        }),
    ],
});

