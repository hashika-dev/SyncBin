<?php

namespace Tests\Feature\Auth;

use App\Models\Bin;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SessionSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Bin::create([
            'id' => 1,
            'slug' => 'hazardous',
            'name' => 'Hazardous',
            'subtitle' => 'Chemicals & Batteries',
            'color' => 'rose',
            'level' => 50,
            'status' => 'Medium',
        ]);
    }

    /**
     * Laravel skips CSRF checks while running tests; turn them back on.
     */
    private function enforceCsrf(): void
    {
        $this->app->bind(ValidateCsrfToken::class, function ($app) {
            return new class($app, $app['encrypter']) extends ValidateCsrfToken {
                protected function runningUnitTests()
                {
                    return false;
                }
            };
        });
    }

    public function test_dashboard_action_without_csrf_token_is_rejected(): void
    {
        $this->enforceCsrf();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/bins/hazardous/empty');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'Your session expired. Please sign in again.');
        $this->assertSame(50, Bin::where('slug', 'hazardous')->value('level'));
    }

    public function test_dashboard_action_with_csrf_token_is_allowed(): void
    {
        $this->enforceCsrf();
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession(['_token' => 'test-token'])
            ->post('/api/bins/hazardous/empty', [], ['X-CSRF-TOKEN' => 'test-token']);

        $response->assertOk();
        $this->assertSame(0, Bin::where('slug', 'hazardous')->value('level'));
    }

    public function test_device_endpoint_does_not_need_csrf_token(): void
    {
        $this->enforceCsrf();

        $response = $this->postJson('/api/bins/telemetry', [
            'bin_id' => 1,
            'distance_mm' => 250,
            'fullness_percent' => 75,
        ]);

        $response->assertOk();
    }

    public function test_logout_without_csrf_token_is_rejected(): void
    {
        $this->enforceCsrf();
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout');

        $this->assertAuthenticatedAs($user);
    }

    public function test_other_sessions_are_logged_out_when_password_changes(): void
    {
        $user = User::factory()->create();

        // First request stores the current password hash in the session.
        $this->actingAs($user)->get('/profile')->assertOk();

        // The password is changed somewhere else (another device).
        $user->password = Hash::make('AnotherStr0ngPassword!');
        $user->save();

        $this->get('/profile')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_session_that_changed_the_password_stays_logged_in(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $this->actingAs($user)->get('/profile')->assertOk();

        $this->put('/password', [
            'current_password' => 'password',
            'password' => 'StrongP@ssw0rd123',
            'password_confirmation' => 'StrongP@ssw0rd123',
        ]);
        $this->post('/password/confirm-change', [
            'code' => session('pending_password_change')['code'],
        ])->assertRedirect(route('profile.edit'));

        $this->get('/profile')->assertOk();
        $this->assertAuthenticatedAs($user);
    }
}
