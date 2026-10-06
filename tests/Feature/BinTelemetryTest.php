<?php

namespace Tests\Feature;

use App\Models\Bin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BinTelemetryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default bins
        Bin::create([
            'id' => 1,
            'slug' => 'hazardous',
            'name' => 'Hazardous',
            'subtitle' => 'Chemicals & Batteries',
            'color' => 'rose',
            'level' => 10,
            'status' => 'Low',
        ]);

        Bin::create([
            'id' => 2,
            'slug' => 'recyclable',
            'name' => 'Recyclable',
            'subtitle' => 'Plastics & Cans',
            'color' => 'sky',
            'level' => 20,
            'status' => 'Low',
        ]);
    }

    public function test_esp32_can_send_telemetry_payload_by_numeric_id(): void
    {
        $payload = [
            'bin_id' => 1,
            'distance_mm' => 250,
            'fullness_percent' => 75,
        ];

        $response = $this->postJson('/api/bins/telemetry', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'bin' => [
                    'id' => 1,
                    'slug' => 'hazardous',
                    'level' => 75,
                    'status' => 'High',
                    'distance_mm' => 250,
                ],
            ]);

        $this->assertDatabaseHas('bins', [
            'id' => 1,
            'level' => 75,
            'status' => 'High',
        ]);
    }

    public function test_esp32_can_send_telemetry_to_hardware_alias(): void
    {
        $payload = [
            'bin_id' => 2,
            'distance_mm' => 120,
            'fullness_percent' => 90,
        ];

        $response = $this->postJson('/api/hardware/telemetry', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'bin' => [
                    'id' => 2,
                    'level' => 90,
                    'status' => 'Critical',
                ],
            ]);

        $this->assertDatabaseHas('bins', [
            'id' => 2,
            'level' => 90,
            'status' => 'Critical',
        ]);
    }

    public function test_esp32_can_send_telemetry_by_slug(): void
    {
        $payload = [
            'bin_id' => 'hazardous',
            'distance_mm' => 450,
            'fullness_percent' => 0,
        ];

        $response = $this->postJson('/api/bins/telemetry', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'bin' => [
                    'id' => 1,
                    'slug' => 'hazardous',
                    'level' => 0,
                    'status' => 'Empty',
                ],
            ]);

        $this->assertDatabaseHas('bins', [
            'id' => 1,
            'level' => 0,
            'status' => 'Empty',
        ]);
    }

    public function test_validation_fails_if_bin_id_is_missing(): void
    {
        $response = $this->postJson('/api/bins/telemetry', [
            'distance_mm' => 250,
            'fullness_percent' => 50,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['bin_id']);
    }

    public function test_returns_404_if_bin_does_not_exist(): void
    {
        $response = $this->postJson('/api/bins/telemetry', [
            'bin_id' => 9999,
            'distance_mm' => 250,
            'fullness_percent' => 50,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
            ]);
    }

    public function test_critical_alert_email_template_renders(): void
    {
        $bin = Bin::first();
        $html = view('emails.critical-alert', ['bin' => $bin])->render();
        $this->assertStringContainsString('EcoSync Telemetry Alert', $html);
        $this->assertStringContainsString('Hazardous', $html);
    }
}
