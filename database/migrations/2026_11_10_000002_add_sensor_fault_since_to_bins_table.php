<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * When a bin's ultrasonic sensor gets no echo (unplugged, loose wire), the ESP32
 * sends 0 cm. Instead of reading that as 100% full, the app keeps the last level
 * and records when the sensor stopped reading, so the dashboard can say so.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bins', function (Blueprint $table) {
            $table->timestamp('sensor_fault_since')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bins', function (Blueprint $table) {
            $table->dropColumn('sensor_fault_since');
        });
    }
};
