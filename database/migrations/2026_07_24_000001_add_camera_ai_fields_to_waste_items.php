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
        Schema::table('waste_items', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('weight');
            $table->float('ai_confidence', 5, 2)->nullable()->after('image_path');
            $table->string('detection_label')->nullable()->after('ai_confidence');
            $table->text('bounding_box')->nullable()->after('detection_label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_items', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'ai_confidence', 'detection_label', 'bounding_box']);
        });
    }
};
