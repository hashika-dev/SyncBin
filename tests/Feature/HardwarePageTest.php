<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HardwarePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_hardware_page_lists_the_real_ecosync_hardware(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);

        $this->actingAs($user)->get(route('dashboard.hardware'))
            ->assertOk()
            ->assertSee('4 Controllers')
            ->assertSee('13 Parts')
            ->assertSee('Raspberry Pi 4B')
            ->assertSee('Arduino Uno (Arm)')
            ->assertSee('Arduino Uno (Gantry)')
            ->assertSee('PCA9685 servo driver')
            ->assertSee('TB6600 stepper driver')
            ->assertSee('NEMA 17 stepper motor')
            ->assertSee('HC-SR04 ultrasonic sensor x4')
            ->assertSee('Pi Camera Module 3');
    }

    public function test_hardware_page_has_no_hardcoded_fake_status(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);

        $this->actingAs($user)->get(route('dashboard.hardware'))
            ->assertOk()
            ->assertDontSee('10 Devices')
            ->assertDontSee('OV2640')
            ->assertDontSee('Shredder')
            ->assertDontSee('5.03V')
            ->assertDontSee('Link 100%')
            ->assertDontSee('>ONLINE</span>', false)
            ->assertSee('@esp32-telemetry.window', false);
    }

    public function test_live_monitor_sensor_card_follows_the_esp32(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('All stream sensors calibrated')
            ->assertSee('/api/hardware/esp32-telemetry', false);

        // Admins can read the ESP32 status the card relies on.
        $this->actingAs($user)->getJson('/api/hardware/esp32-telemetry')
            ->assertOk()
            ->assertJson(['status' => 'offline']);
    }

    public function test_admins_cannot_open_the_hardware_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get(route('dashboard.hardware'))->assertForbidden();
    }
}
