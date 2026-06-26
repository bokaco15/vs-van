import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            // Ulazne tačke:
            // - javni sajt: style.css (postojeći dizajn) + script.js (animacije/akordeoni) + reservation.js (kalendar)
            // - admin: admin.css (dopuna Bootstrap-u) + admin.js (zajednička AJAX/Toastr logika)
            input: [
                'resources/css/style.css',
                'resources/js/script.js',
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