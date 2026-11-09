<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Emptying a bin used to delete its scanned items, so History and Reports lost them.
 * Items now get an emptied_at time instead. Sensor-detected clearances can be
 * confirmed by an admin (confirmed_at), and a single 0% sensor reading no longer
 * drops the gauge on its own (zero_reading_count).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('waste_items', function (Blueprint $table) {
            $table->timestamp('emptied_at')->nullable()->index();
        });

        Schema::table('bin_clearance_logs', function (Blueprint $table) {
            $table->timestamp('confirmed_at')->nullable();
        });

        // Older records predate confirmation; treat them as settled so they don't ask for it now.
        DB::table('bin_clearance_logs')->update(['confirmed_at' => DB::raw('cleared_at')]);

        Schema::table('bins', function (Blueprint $table) {
            $table->unsignedTinyInteger('zero_reading_count')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('bins', function (Blueprint $table) {
            $table->dropColumn('zero_reading_count');
        });

        Schema::table('bin_clearance_logs', function (Blueprint $table) {
            $table->dropColumn('confirmed_at');
        });

        Schema::table('waste_items', function (Blueprint $table) {
            $table->dropIndex(['emptied_at']);
            $table->dropColumn('emptied_at');
        });
    }
};
