<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Jedinstveno razrešavanje URL-a za medije.
 *
 * - putanje koje počinju sa "img/" su statički asseti u public/ (seed/UI ikonice)
 *   -> vraćamo asset('img/...')
 * - sve ostalo je uploadovano na "public" disk (storage/app/public/...)
 *   -> vraćamo Storage::url(...)  (zahteva `php artisan storage:link`)
 */
class Media
{
    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return str_starts_with($path, 'img/')
            ? asset($path)
            : Storage::url($path);
    }
}
