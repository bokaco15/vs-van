<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    protected $fillable = ['name', 'icon_path', 'sort_order'];

    protected $casts = ['sort_order' => 'integer'];

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class)->withPivot('value')->withTimestamps();
    }

    /** Javni URL ikonice (ako postoji). */
    public function getIconUrlAttribute(): ?string
    {
        return Media::url($this->icon_path);
    }
}
