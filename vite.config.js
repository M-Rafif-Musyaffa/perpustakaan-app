import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path'; // <-- Tambahkan ini

export default defineConfig({
    plugins: [
        laravel({
            // Arahkan ke file SASS dan JS utama
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            // Alias untuk folder Bootstrap
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        }
    }
});
