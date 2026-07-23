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
            $table->timestamp('alert_triggered_at')->nullable()->after('last_emptied_at');
        });

        Schema::create('bin_clearance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bin_id')->constrained('bins')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('cleared_by_email');
            $table->integer('level_before_clearance');
            $table->timestamp('alert_triggered_at')->nullable();
            $table->timestamp('cleared_at');
            $table->integer('response_time_minutes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bin_clearance_logs');

        Schema::table('bins', function (Blueprint $table) {
            $table->dropColumn('alert_triggered_at');
        });
    }
};
