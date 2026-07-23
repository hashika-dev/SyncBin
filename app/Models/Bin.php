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
    ];

    protected $casts = [
        'last_emptied_at' => 'datetime',
        'alert_triggered_at' => 'datetime',
    ];

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
