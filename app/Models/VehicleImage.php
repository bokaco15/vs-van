<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleImage extends Model
{
    protected $fillable = ['vehicle_id', 'path', 'thumb_path', 'sort_order', 'is_cover'];

    protected $casts = [
        'is_cover' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /** Javni URL pune slike. */
    public function getUrlAttribute(): ?string
    {
        return Media::url($this->path);
    }

    /** Javni URL thumbnail-a. */
    public function getThumbUrlAttribute(): ?string
    {
        return Media::url($this->thumb_path);
    }
}
