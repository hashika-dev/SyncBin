<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rpi_telemetry', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->default('rpi-primary');    // identifier for multi-Pi setups
            $table->float('cpu_temp')->nullable();                   // °C
            $table->float('cpu_load')->nullable();                   // 0–100 %
            $table->float('supply_voltage')->nullable();             // volts
            $table->float('memory_used_mb')->nullable();             // MB used
            $table->float('memory_total_mb')->nullable();            // MB total
            $table->float('disk_used_gb')->nullable();               // GB used
            $table->float('disk_total_gb')->nullable();              // GB total
            $table->string('ip_address')->nullable();               // Pi's local IP
            $table->string('uptime')->nullable();                    // human-readable
            $table->string('status')->default('online');             // online / offline / warning
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rpi_telemetry');
    }
};
