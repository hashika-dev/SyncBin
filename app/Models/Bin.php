<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bin extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'subtitle',
        'color',
        'level',
        'status',
        'last_emptied_at',
        'alert_triggered_at',
        'zero_level_since',
        'pre_empty_level',
    ];

    protected $casts = [
        'last_emptied_at' => 'datetime',
        'alert_triggered_at' => 'datetime',
        'zero_level_since' => 'datetime',
    ];

    protected $appends = [
        'chamber_height_cm',
        'chamber_volume_liters',
        'dimensions',
    ];

    /**
     * Get the physical dimensions for this bin stream.
     */
    public function getDimensionsAttribute(): array
    {
        return config("bins.dimensions.{$this->slug}", [
            'height_cm'     => (float) config('bins.default_depth_cm', 60.0),
            'length_cm'     => null,
            'width_cm'      => null,
            'volume_liters' => null,
            'is_custom'     => false,
        ]);
    }

    /**
     * Get the chamber depth/height in centimeters.
     */
    public function getChamberHeightCmAttribute(): float
    {
        return (float) (config("bins.dimensions.{$this->slug}.height_cm") ?? config('bins.default_depth_cm', 60.0));
    }

    /**
     * Get the chamber volume in Liters.
     */
    public function getChamberVolumeLitersAttribute(): ?float
    {
        return config("bins.dimensions.{$this->slug}.volume_liters");
    }

    /**
     * Get the waste items for this bin.
     */
    public function items()
    {
        return $this->hasMany(WasteItem::class);
    }

    /**
     * Get the clearance logs for this bin.
     */
    public function clearanceLogs()
    {
        return $this->hasMany(BinClearanceLog::class);
    }
}
