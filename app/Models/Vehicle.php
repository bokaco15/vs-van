<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'name', 'slug', 'subtitle', 'description',
        'sort_order', 'is_recommended', 'is_featured', 'is_active',
    ];

    protected $casts = [
        'is_recommended' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /** Sve slike vozila (po redosledu). */
    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class)->orderBy('sort_order');
    }

    /** Naslovna slika (cover), uz fallback na prvu po redosledu. */
    public function coverImage(): HasOne
    {
        return $this->hasOne(VehicleImage::class)
            ->orderByDesc('is_cover')
            ->orderBy('sort_order');
    }

    /** Specifikacije (Manual, Kasko, AC...) preko pivota. */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class)
            ->withPivot('value')
            ->withTimestamps()
            ->orderBy('sort_order');
    }

    /** Zauzeti datumi. */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /** Scope: samo aktivna vozila, sortirana za prikaz. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /** Scope: filter po tipu (van/car). */
    public function scopeOfType($query, ?string $type)
    {
        return $type ? $query->where('type', $type) : $query;
    }
}
