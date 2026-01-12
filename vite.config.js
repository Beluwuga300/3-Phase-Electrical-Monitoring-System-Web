import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'; // jika Anda pakai tailwind

export default defineConfig({
    plugins: [
        laravel({
            // Pastikan dashboard.js ada di sini
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboard.js'],
            refresh: true,
        }),
        tailwindcss(), // jika Anda pakai tailwind
    ],
});
