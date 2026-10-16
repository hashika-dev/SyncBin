<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bins', function (Blueprint $table) {
            $table->timestamp('zero_level_since')->nullable()->after('alert_triggered_at');
            $table->integer('pre_empty_level')->default(0)->after('zero_level_since');
        });

        Schema::table('bin_clearance_logs', function (Blueprint $table) {
            $table->string('clearance_type')->default('manual')->after('cleared_by_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bin_clearance_logs', function (Blueprint $table) {
            $table->dropColumn('clearance_type');
        });

        Schema::table('bins', function (Blueprint $table) {
            $table->dropColumn(['zero_level_since', 'pre_empty_level']);
        });
    }
};
