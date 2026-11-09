<?php

namespace Tests\Feature;

use App\Models\Bin;
use App\Models\BinClearanceLog;
use App\Models\User;
use App\Models\WasteItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BinAutoClearTest extends TestCase
{
    use RefreshDatabase;

    protected Bin $bin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bin = Bin::create([
            'id' => 1,
            'slug' => 'biodegradable',
            'name' => 'Biodegradable',
            'subtitle' => 'Food & Organic',
            'color' => 'emerald',
            'level' => 90,
            'status' => 'Critical',
            'alert_triggered_at' => Carbon::parse('2026-10-07 10:00:00'),
        ]);

        WasteItem::create([
            'bin_id' => $this->bin->id,
            'name' => 'Banana Peel',
            'icon' => '🍌',
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /** Send the same reading several times, like the ESP32 does every 15 seconds. */
    private function sendReadings(int $percent, int $times = 3): void
    {
        for ($i = 0; $i < $times; $i++) {
            $this->postJson('/api/bins/telemetry', ['bin_id' => 1, 'fullness_percent' => $percent])
                ->assertStatus(200);
        }
    }

    public function test_one_zero_reading_does_not_empty_the_gauge(): void
    {
        $this->sendReadings(0, 2);

        $this->bin->refresh();
        $this->assertEquals(90, $this->bin->level);
        $this->assertNull($this->bin->zero_level_since);

        // A real reading in between starts the count over
        $this->sendReadings(85, 1);
        $this->sendReadings(0, 2);
        $this->assertEquals(85, $this->bin->fresh()->level);
    }

    public function test_zero_level_telemetry_initializes_debounce_without_premature_clearance(): void
    {
        Carbon::setTestNow('2026-10-07 10:20:00');

        $this->sendReadings(0);

        $this->bin->refresh();
        $this->assertEquals(0, $this->bin->level);
        $this->assertNotNull($this->bin->zero_level_since);
        $this->assertEquals(90, $this->bin->pre_empty_level);
        $this->assertNotNull($this->bin->alert_triggered_at); // Preserved for SLA response calculation
        $this->assertEquals(1, $this->bin->items()->count()); // Items not yet deleted
        $this->assertEquals(0, BinClearanceLog::count()); // No log yet
    }

    public function test_sustained_zero_level_for_five_minutes_triggers_automatic_clearance(): void
    {
        Carbon::setTestNow('2026-10-07 10:20:00');

        // Bin reads empty (three readings in a row)
        $this->sendReadings(0);

        // Advance time by 5 minutes and 1 second
        Carbon::setTestNow('2026-10-07 10:25:01');

        // Subsequent telemetry reading
        $this->postJson('/api/bins/telemetry', [
            'bin_id' => 1,
            'fullness_percent' => 0,
        ]);

        $this->bin->refresh();

        // Items leave the bin but stay in History and Reports
        $this->assertEquals(0, $this->bin->items()->count());
        $this->assertEquals(1, $this->bin->allItems()->count());
        $this->assertNotNull(WasteItem::first()->emptied_at);
        $this->assertNull($this->bin->alert_triggered_at);
        $this->assertEquals('Empty', $this->bin->status);

        // Clearance log should be recorded
        $this->assertEquals(1, BinClearanceLog::count());
        $log = BinClearanceLog::first();
        $this->assertEquals('System (IoT Sensor Auto-Detect)', $log->cleared_by_email);
        $this->assertEquals('sensor_auto', $log->clearance_type);
        $this->assertEquals(90, $log->level_before_clearance);
        $this->assertEquals(25, $log->response_time_minutes); // 10:00 to 10:25
        $this->assertNull($log->confirmed_at);
        $this->assertNotNull($this->bin->pendingClearance());

        // Advancing time further with continued 0% should not duplicate logs
        Carbon::setTestNow('2026-10-07 10:26:00');
        $this->postJson('/api/bins/telemetry', [
            'bin_id' => 1,
            'fullness_percent' => 0,
        ]);
        $this->assertEquals(1, BinClearanceLog::count());
    }

    public function test_supervisor_manual_override_clears_bin_and_logs_supervisor_email(): void
    {
        $user = User::factory()->create([
            'email' => 'supervisor@school.edu',
            'role' => 'admin',
        ]);

        Carbon::setTestNow('2026-10-07 10:15:00');

        $response = $this->actingAs($user)->postJson("/api/bins/{$this->bin->slug}/empty");

        $response->assertStatus(200);

        $this->bin->refresh();
        $this->assertEquals(0, $this->bin->level);
        $this->assertEquals(0, $this->bin->items()->count());
        $this->assertNull($this->bin->alert_triggered_at);

        $this->assertEquals(1, BinClearanceLog::count());
        $log = BinClearanceLog::first();
        $this->assertEquals('supervisor@school.edu', $log->cleared_by_email);
        $this->assertEquals('manual', $log->clearance_type);
        $this->assertEquals(90, $log->level_before_clearance);
        $this->assertEquals(15, $log->response_time_minutes); // 10:00 to 10:15
        $this->assertNotNull($log->confirmed_at);
        $this->assertEquals(1, $this->bin->allItems()->count()); // scan kept for History
    }

    public function test_admin_confirms_a_sensor_detected_emptying_without_a_second_record(): void
    {
        $user = User::factory()->create(['email' => 'supervisor@school.edu', 'role' => 'admin']);

        Carbon::setTestNow('2026-10-07 10:20:00');
        $this->sendReadings(0);
        Carbon::setTestNow('2026-10-07 10:25:01');
        $this->sendReadings(0, 1);

        $response = $this->actingAs($user)->postJson("/api/bins/{$this->bin->slug}/empty");
        $response->assertStatus(200)->assertJson(['awaiting_confirmation' => false]);

        $this->assertEquals(1, BinClearanceLog::count());
        $log = BinClearanceLog::first();
        $this->assertEquals('sensor_auto', $log->clearance_type);
        $this->assertEquals($user->id, $log->user_id);
        $this->assertEquals('supervisor@school.edu', $log->cleared_by_email);
        $this->assertNotNull($log->confirmed_at);
        $this->assertNull($this->bin->fresh()->pendingClearance());
    }

    public function test_bin_that_refills_before_confirmation_stops_asking(): void
    {
        Carbon::setTestNow('2026-10-07 10:20:00');
        $this->sendReadings(0);
        Carbon::setTestNow('2026-10-07 10:25:01');
        $this->sendReadings(0, 1);
        $this->assertNotNull($this->bin->fresh()->pendingClearance());

        $this->sendReadings(20, 1);
        $this->assertNull($this->bin->fresh()->pendingClearance());
        $this->assertNull(BinClearanceLog::first()->confirmed_at); // stays unconfirmed in History
    }

    public function test_never_filled_bin_is_not_logged_as_emptied(): void
    {
        $empty = Bin::create([
            'slug' => 'hazardous', 'name' => 'Hazardous', 'subtitle' => 'Toxic', 'color' => 'red',
            'level' => 0, 'status' => 'Empty',
        ]);

        Carbon::setTestNow('2026-10-07 10:20:00');
        $this->postJson('/api/bins/telemetry', ['bin_id' => $empty->id, 'fullness_percent' => 0]);
        Carbon::setTestNow('2026-10-07 10:30:00');
        $this->postJson('/api/bins/telemetry', ['bin_id' => $empty->id, 'fullness_percent' => 0]);

        $this->assertEquals(0, BinClearanceLog::where('bin_id', $empty->id)->count());
    }

    public function test_recent_activity_lists_scans_and_emptyings(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user)->postJson("/api/bins/{$this->bin->slug}/empty")->assertStatus(200);

        $rows = $this->actingAs($user)->getJson('/api/bins/activity')->assertStatus(200)->json();

        $this->assertEqualsCanonicalizing(['scan', 'emptied'], array_column($rows, 'kind'));
        $emptied = collect($rows)->firstWhere('kind', 'emptied');
        $this->assertEquals('Biodegradable bin emptied', $emptied['name']);
        $this->assertEquals('by admin', $emptied['detail']);
    }
}
