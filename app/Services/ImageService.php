<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

/**
 * Obrada uploadovanih slika preko Intervention Image v4.
 * Svaka slika se konvertuje u WebP i čuva u dve veličine:
 *  - puna (skalirana naniže do $fullWidth)
 *  - thumbnail (do $thumbWidth)
 *
 * Fajlovi se čuvaju na "public" disk (storage/app/public) i serviraju
 * preko `php artisan storage:link`.
 */
class ImageService
{
    private ImageManager $manager;

    public function __construct()
    {
        // GD drajver (proveren: gd + webp dostupni).
        $this->manager = new ImageManager(new Driver);
    }

    /**
     * Sačuvaj sliku kao WebP (puna + thumb) i vrati relativne putanje.
     *
     * @return array{path:string, thumb_path:string}
     */
    public function storeWebp(UploadedFile $file, string $dir = 'vehicles', int $fullWidth = 1600, int $thumbWidth = 400): array
    {
        $name = Str::uuid()->toString().'.webp';
        $path = "{$dir}/{$name}";
        $thumbPath = "{$dir}/thumbs/{$name}";

        // Puna slika (skalirana naniže) -> WebP
        $full = $this->manager->decode($file->getPathname())
            ->scaleDown(width: $fullWidth)
            ->encode(new WebpEncoder(quality: 82));
        Storage::disk('public')->put($path, (string) $full);

        // Thumbnail -> WebP
        $thumb = $this->manager->decode($file->getPathname())
            ->scaleDown(width: $thumbWidth)
            ->encode(new WebpEncoder(quality: 80));
        Storage::disk('public')->put($thumbPath, (string) $thumb);

        return ['path' => $path, 'thumb_path' => $thumbPath];
    }

    /**
     * Obriši sliku i njen thumbnail sa diska (preskače statičke public/img assete).
     */
    public function delete(?string $path, ?string $thumbPath = null): void
    {
        foreach ([$path, $thumbPath] as $p) {
            if ($p && ! str_starts_with($p, 'img/')) {
                Storage::disk('public')->delete($p);
            }
        }
    }
}
