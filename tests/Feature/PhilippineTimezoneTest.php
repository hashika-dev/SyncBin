<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PhilippineTimezoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_app_runs_on_philippine_time(): void
    {
        $this->assertSame('Asia/Manila', config('app.timezone'));
        $this->assertSame('+08:00', now()->format('P'));
    }

    public function test_migration_moves_old_utc_times_forward_eight_hours_and_back(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);
        DB::table('users')->where('id', $user->id)->update([
            'created_at' => '2026-11-06 15:46:52',
            'updated_at' => '2026-11-06 23:30:00',
        ]);

        $migration = require database_path('migrations/2026_11_06_000001_shift_stored_timestamps_to_philippine_time.php');

        $migration->up();
        $row = DB::table('users')->find($user->id);
        $this->assertSame('2026-11-06 23:46:52', $row->created_at);
        $this->assertSame('2026-11-07 07:30:00', $row->updated_at);
        $this->assertNull($row->email_verified_at);

        $migration->down();
        $this->assertSame('2026-11-06 15:46:52', DB::table('users')->find($user->id)->created_at);
    }
}
