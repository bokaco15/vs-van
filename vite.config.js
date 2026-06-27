import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            // Ulazne tačke:
            // - javni sajt (redizajn): home.css + home.js (nav/akordeoni/filter) + reservation.js (kalendar)
            // - admin: admin.css (dopuna Bootstrap-u) + admin.js (zajednička AJAX/Toastr logika)
            input: [
                'resources/css/home.css',
                'resources/js/home.js',
                'resources/js/reservation.js',
                'resources/css/admin.css',
                'resources/js/admin.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});