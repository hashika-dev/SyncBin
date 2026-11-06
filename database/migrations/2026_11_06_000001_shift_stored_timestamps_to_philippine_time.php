<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The app timezone changed from UTC to Asia/Manila (UTC+8, no daylight saving).
 * Dates already stored were written in UTC, so move them forward 8 hours once,
 * otherwise every old record would show 8 hours too early.
 *
 * Unix-number columns (sessions, cache, jobs) are timezone-free and not touched.
 */
return new class extends Migration
{
    private const COLUMNS = [
        'users' => ['email_verified_at', 'created_at', 'updated_at'],
        'password_reset_tokens' => ['created_at'],
        'failed_jobs' => ['failed_at'],
        'bins' => ['last_emptied_at', 'alert_triggered_at', 'zero_level_since', 'created_at', 'updated_at'],
        'waste_items' => ['created_at', 'updated_at'],
        'bin_clearance_logs' => ['alert_triggered_at', 'cleared_at', 'created_at', 'updated_at'],
        'rpi_telemetry' => ['created_at', 'updated_at'],
        'esp32_telemetry' => ['created_at', 'updated_at'],
        'two_factor_authentications' => ['enabled_at', 'recovery_codes_generated_at', 'created_at', 'updated_at'],
    ];

    public function up(): void
    {
        $this->shift('+8 hours');
    }

    public function down(): void
    {
        $this->shift('-8 hours');
    }

    private function shift(string $modifier): void
    {
        // EcoSync runs on SQLite; other drivers would need their own date function.
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        foreach (self::COLUMNS as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    continue;
                }

                DB::table($table)
                    ->whereNotNull($column)
                    ->update([$column => DB::raw("datetime(\"{$column}\", '{$modifier}')")]);
            }
        }
    }
};
