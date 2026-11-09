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
        'zero_reading_count',
        'sensor_fault_since',
    ];

    protected $casts = [
        'last_emptied_at' => 'datetime',
        'alert_triggered_at' => 'datetime',
        'zero_level_since' => 'datetime',
        'sensor_fault_since' => 'datetime',
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
     * Items in the bin right now (scanned since it was last emptied).
     */
    public function items()
    {
        return $this->hasMany(WasteItem::class)->whereNull('emptied_at');
    }

    /**
     * Every item ever sorted into this bin, including ones already emptied out.
     */
    public function allItems()
    {
        return $this->hasMany(WasteItem::class);
    }

    /**
     * The sensor-detected emptying an admin has not confirmed yet, while the bin
     * still reads 0%. Once the bin fills again it stays unconfirmed in History.
     */
    public function pendingClearance(): ?BinClearanceLog
    {
        if ((int) $this->level !== 0) {
            return null;
        }

        $latest = $this->clearanceLogs()->latest('cleared_at')->latest('id')->first();

        return $latest && $latest->clearance_type === 'sensor_auto' && $latest->confirmed_at === null
            ? $latest
            : null;
    }

    /**
     * Get the clearance logs for this bin.
     */
    public function clearanceLogs()
    {
        return $this->hasMany(BinClearanceLog::class);
    }
}
