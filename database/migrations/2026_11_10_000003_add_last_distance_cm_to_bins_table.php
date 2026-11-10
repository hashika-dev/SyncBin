<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Keep the last raw distance each ultrasonic sensor measured, so the Live
 * Monitor can show the real reading instead of one worked back from the fill %.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bins', function (Blueprint $table) {
            $table->decimal('last_distance_cm', 6, 1)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bins', function (Blueprint $table) {
            $table->dropColumn('last_distance_cm');
        });
    }
};
