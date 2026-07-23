<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinClearanceLog extends Model
{
    protected $fillable = [
        'bin_id',
        'user_id',
        'cleared_by_email',
        'level_before_clearance',
        'alert_triggered_at',
        'cleared_at',
        'response_time_minutes',
    ];

    protected $casts = [
        'alert_triggered_at' => 'datetime',
        'cleared_at' => 'datetime',
    ];

    /**
     * Get the bin associated with the clearance log.
     */
    public function bin()
    {
        return $this->belongsTo(Bin::class);
    }

    /**
     * Get the user who cleared the bin.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
