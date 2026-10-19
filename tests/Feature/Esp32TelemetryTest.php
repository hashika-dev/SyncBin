<?php

namespace Tests\Feature;

use App\Models\Bin;
use App\Models\Esp32Telemetry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Esp32TelemetryTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'superadmin',
        ]);

        Bin::create([
            'id'       => 1,
            'slug'     => 'hazardous',
            'name'     => 'Hazardous',
            'subtitle' => 'Chemicals & Batteries',
            'color'    => 'rose',
            'level'    => 10,
            'status'   => 'Low',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_esp32_telemetry(): void
    {
        $response = $this->getJson('/api/hardware/esp32-telemetry');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_gets_offline_when_no_esp32_data(): void
    {
        $response = $this->actingAs($this->user)->getJson('/api/hardware/esp32-telemetry');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'offline',
                'data'   => null,
            ]);
    }

    public function test_esp32_can_post_hardware_telemetry(): void
    {
        $payload = [
            'device_id'       => 'NODE-01',
            'mac_address'     => 'A4:CF:12:6E:3B:10',
            'firmware'        => 'EcoSync-v2.1.4',
            'protocol'        => 'UART / Serial (115200)',
            'bus_status'      => '1k Nominal',
            'wifi_rssi'       => -58,
            'free_heap_bytes' => 284160,
            'uptime'          => '5 days, 08:14:12',
        ];

        $postResponse = $this->postJson('/api/hardware/esp32-telemetry', $payload);

        $postResponse->assertStatus(200)
            ->assertJson([
                'status'  => 'success',
                'message' => 'ESP32 telemetry updated.',
            ]);

        $this->assertDatabaseHas('esp32_telemetry', [
            'device_id'   => 'NODE-01',
            'mac_address' => 'A4:CF:12:6E:3B:10',
        ]);

        $getResponse = $this->actingAs($this->user)->getJson('/api/hardware/esp32-telemetry');

        $getResponse->assertStatus(200)
            ->assertJson([
                'status' => 'live',
                'data'   => [
                    'device_id'   => 'NODE-01',
                    'mac_address' => 'A4:CF:12:6E:3B:10',
                    'firmware'    => 'EcoSync-v2.1.4',
                ],
            ]);
    }

    public function test_stale_status_when_esp32_telemetry_older_than_30_seconds(): void
    {
        $record = Esp32Telemetry::create([
            'device_id'   => 'NODE-01',
            'mac_address' => 'A4:CF:12:6E:3B:10',
            'firmware'    => 'EcoSync-v2.1.4',
        ]);
        $record->timestamps = false;
        $record->updated_at = now()->subSeconds(45);
        $record->save();

        $response = $this->actingAs($this->user)->getJson('/api/hardware/esp32-telemetry');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'stale',
            ]);
    }

    public function test_esp32_ultrasonic_sensor_endpoint_updates_heartbeat(): void
    {
        $response = $this->post('/api/update_bins', [
            'hazardous' => 10,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('esp32_telemetry', [
            'device_id' => 'NODE-01',
        ]);

        $getResponse = $this->actingAs($this->user)->getJson('/api/hardware/esp32-telemetry');

        $getResponse->assertStatus(200)
            ->assertJson([
                'status' => 'live',
            ]);
    }
}
