<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RpiTelemetry extends Model
{
    protected $table = 'rpi_telemetry';

    protected $fillable = [
        'device_id',
        'cpu_temp',
        'cpu_load',
        'supply_voltage',
        'memory_used_mb',
        'memory_total_mb',
        'disk_used_gb',
        'disk_total_gb',
        'ip_address',
        'uptime',
        'status',
    ];

    protected $casts = [
        'cpu_temp'         => 'float',
        'cpu_load'         => 'float',
        'supply_voltage'   => 'float',
        'memory_used_mb'   => 'float',
        'memory_total_mb'  => 'float',
        'disk_used_gb'     => 'float',
        'disk_total_gb'    => 'float',
    ];

    /**
     * Get the latest telemetry record for a given device (default: rpi-primary).
     */
    public static function latest_for(string $deviceId = 'rpi-primary'): ?self
    {
        return static::where('device_id', $deviceId)->latest()->first();
    }

    /**
     * Memory usage as a percentage.
     */
    public function getMemoryPercentAttribute(): float
    {
        if (!$this->memory_total_mb || $this->memory_total_mb === 0.0) {
            return 0.0;
        }
        return round(($this->memory_used_mb / $this->memory_total_mb) * 100, 1);
    }

    /**
     * Disk usage as a percentage.
     */
    public function getDiskPercentAttribute(): float
    {
        if (!$this->disk_total_gb || $this->disk_total_gb === 0.0) {
            return 0.0;
        }
        return round(($this->disk_used_gb / $this->disk_total_gb) * 100, 1);
    }
}
