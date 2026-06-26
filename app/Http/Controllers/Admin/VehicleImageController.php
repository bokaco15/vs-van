<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Services\ImageService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleImageController extends Controller
{
    use ApiResponse;

    /** Upload jedne ili više slika za vozilo (konverzija u WebP). */
    public function store(Request $request, Vehicle $vehicle, ImageService $images): JsonResponse
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // do 5MB
        ], [], ['images.*' => 'slika']);

        $start = (int) $vehicle->images()->max('sort_order');
        $created = [];

        foreach ($request->file('images') as $i => $file) {
            $paths = $images->storeWebp($file, 'vehicles');

            $image = $vehicle->images()->create([
                'path' => $paths['path'],
                'thumb_path' => $paths['thumb_path'],
                'sort_order' => $start + $i + 1,
                // Prva slika ikad postaje naslovna.
                'is_cover' => $vehicle->images()->count() === 0 && $i === 0,
            ]);

            $created[] = [
                'id' => $image->id,
                'thumb_url' => $image->thumb_url,
                'is_cover' => $image->is_cover,
            ];
        }

        return $this->ok('Slike su dodate.', ['images' => $created], 201);
    }

    /** Postavi sliku kao naslovnu (cover). */
    public function cover(VehicleImage $image): JsonResponse
    {
        $image->vehicle->images()->update(['is_cover' => false]);
        $image->update(['is_cover' => true]);

        return $this->ok('Naslovna slika je postavljena.');
    }

    /** Sačuvaj redosled slika. */
    public function sort(Request $request, Vehicle $vehicle): JsonResponse
    {
        foreach ($request->input('ids', []) as $position => $id) {
            $vehicle->images()->whereKey($id)->update(['sort_order' => $position]);
        }

        return $this->ok('Redosled slika je sačuvan.');
    }

    public function destroy(VehicleImage $image, ImageService $images): JsonResponse
    {
        $images->delete($image->path, $image->thumb_path);
        $wasCover = $image->is_cover;
        $vehicle = $image->vehicle;
        $image->delete();

        // Ako je obrisana naslovna, postavi prvu preostalu kao novu naslovnu.
        if ($wasCover && ($next = $vehicle->images()->orderBy('sort_order')->first())) {
            $next->update(['is_cover' => true]);
        }

        return $this->ok('Slika je obrisana.');
    }
}
