<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Someone who knows only the password must not get past the 2FA code
 * by typing another page address directly (before, only /dashboard checked it).
 */
class TwoFactorEverywhereTest extends TestCase
{
    use RefreshDatabase;

    private const PAGES = [
        '/dashboard',
        '/dashboard/reports',
        '/dashboard/history',
        '/dashboard/export/csv',
        '/dashboard/hardware',
        '/dashboard/backups',
        '/profile',
        '/2fa/setup',
        '/confirm-password',
    ];

    private function superadminWith2fa(): User
    {
        $user = User::factory()->create(['role' => 'superadmin']);
        $user->createTwoFactorAuth();
        $user->enableTwoFactorAuth();

        return $user->fresh();
    }

    public function test_password_alone_cannot_open_any_page(): void
    {
        $user = $this->superadminWith2fa();
        $this->assertTrue($user->hasTwoFactorEnabled());

        foreach (self::PAGES as $page) {
            $this->actingAs($user)->get($page)->assertRedirect(route('2fa.confirm'));
        }
    }

    public function test_password_alone_cannot_use_actions_or_device_status(): void
    {
        $user = $this->superadminWith2fa();

        $this->actingAs($user)->post(route('dashboard.backups.store'))->assertRedirect(route('2fa.confirm'));
        $this->actingAs($user)->post('/2fa/disable')->assertRedirect(route('2fa.confirm'));
        $this->actingAs($user)->getJson('/api/bins')->assertForbidden();
        $this->actingAs($user)->getJson('/api/hardware/esp32-telemetry')->assertForbidden();
        $this->actingAs($user)->getJson('/api/hardware/rpi-telemetry')->assertForbidden();
        $this->assertTrue($user->fresh()->hasTwoFactorEnabled(), '2FA must not be disabled without the code');
    }

    public function test_the_code_page_itself_stays_reachable(): void
    {
        $this->actingAs($this->superadminWith2fa())->get(route('2fa.confirm'))->assertOk();
    }

    public function test_after_entering_the_code_every_page_opens(): void
    {
        $user = $this->superadminWith2fa();

        $this->actingAs($user)
            ->post('/2fa/confirm', ['code' => $user->makeTwoFactorCode()])
            ->assertRedirect();

        foreach (self::PAGES as $page) {
            $this->assertNotSame(
                route('2fa.confirm'),
                $this->get($page)->headers->get('Location'),
                "{$page} still asks for the code after it was entered"
            );
        }
        $this->getJson('/api/bins')->assertOk();
    }

    public function test_accounts_without_2fa_are_not_affected(): void
    {
        $user = User::factory()->create(['role' => 'superadmin']);

        $this->actingAs($user)->get('/dashboard/reports')->assertOk();
        $this->actingAs($user)->get('/dashboard/backups')->assertOk();
    }
}
