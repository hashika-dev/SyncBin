<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteItem extends Model
{
    protected $fillable = [
        'bin_id',
        'name',
        'icon',
        'weight',
        'image_path',
        'ai_confidence',
        'detection_label',
        'bounding_box',
    ];

    /**
     * Get the bin this waste item belongs to.
     */
    public function bin()
    {
        return $this->belongsTo(Bin::class);
    }
}
