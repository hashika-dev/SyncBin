<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Esp32Telemetry extends Model
{
    protected $table = 'esp32_telemetry';

    protected $fillable = [
        'device_id',
        'mac_address',
        'firmware',
        'protocol',
        'bus_status',
        'ip_address',
        'wifi_rssi',
        'free_heap_bytes',
        'uptime',
        'status',
    ];

    protected $casts = [
        'wifi_rssi'       => 'integer',
        'free_heap_bytes' => 'integer',
    ];

    /**
     * Get the latest telemetry record for a given device (default: NODE-01).
     */
    public static function latest_for(string $deviceId = 'NODE-01'): ?self
    {
        return static::where('device_id', $deviceId)->latest()->first();
    }
}
