import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    cacheDir: '/private/tmp/ticket-vite-cache',
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/ticket-chat.js'],
            refresh: true,
        }),
    ],
});
