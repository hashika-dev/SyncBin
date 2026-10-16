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

    public function test_zero_level_telemetry_initializes_debounce_without_premature_clearance(): void
    {
        Carbon::setTestNow('2026-10-07 10:20:00');

        $response = $this->postJson('/api/bins/telemetry', [
            'bin_id' => 1,
            'fullness_percent' => 0,
        ]);

        $response->assertStatus(200);

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

        // Initial 0% reading
        $r1 = $this->postJson('/api/bins/telemetry', [
            'bin_id' => 1,
            'fullness_percent' => 0,
        ]);

        // Advance time by 5 minutes and 1 second
        Carbon::setTestNow('2026-10-07 10:25:01');

        // Subsequent telemetry reading
        $this->postJson('/api/bins/telemetry', [
            'bin_id' => 1,
            'fullness_percent' => 0,
        ]);

        $this->bin->refresh();

        // Bin items should be cleared
        $this->assertEquals(0, $this->bin->items()->count());
        $this->assertNull($this->bin->alert_triggered_at);
        $this->assertEquals('Empty', $this->bin->status);

        // Clearance log should be recorded
        $this->assertEquals(1, BinClearanceLog::count());
        $log = BinClearanceLog::first();
        $this->assertEquals('System (IoT Sensor Auto-Detect)', $log->cleared_by_email);
        $this->assertEquals('sensor_auto', $log->clearance_type);
        $this->assertEquals(90, $log->level_before_clearance);
        $this->assertEquals(25, $log->response_time_minutes); // 10:00 to 10:25

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
    }
}
