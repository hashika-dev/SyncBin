<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('esp32_telemetry', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->default('NODE-01');
            $table->string('mac_address')->nullable()->default('A4:CF:12:6E:3B:10');
            $table->string('firmware')->nullable()->default('EcoSync-v2.1.4');
            $table->string('protocol')->nullable()->default('UART / Serial (115200)');
            $table->string('bus_status')->nullable()->default('1k Nominal');
            $table->string('ip_address')->nullable();
            $table->integer('wifi_rssi')->nullable();
            $table->bigInteger('free_heap_bytes')->nullable();
            $table->string('uptime')->nullable();
            $table->string('status')->default('online');
            $table->timestamps();

            $table->index('device_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('esp32_telemetry');
    }
};
