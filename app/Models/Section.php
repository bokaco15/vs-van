<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Section extends Model
{
    protected $fillable = ['key', 'value', 'group', 'label'];

    /**
     * Pomoćnik: vrati vrednost sekcije po ključu (sa keširanjem i fallback-om).
     * Koristi se u Blade prikazima: Section::get('hero_title', 'Podrazumevano').
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $all = Cache::rememberForever('sections.map', function () {
            return static::query()->pluck('value', 'key')->all();
        });

        return $all[$key] ?? $default;
    }

    /** Očisti keš kada se sekcije izmene (poziva se iz admina). */
    protected static function booted(): void
    {
        $flush = fn () => Cache::forget('sections.map');
        static::saved($flush);
        static::deleted($flush);
    }
}
