<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_update_initiates_otp_verification(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'StrongP@ssw0rd123',
                'password_confirmation' => 'StrongP@ssw0rd123',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('password.verify-change'));

        $this->assertNotNull(session('pending_password_change'));
    }

    public function test_password_can_be_updated_with_valid_otp(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'StrongP@ssw0rd123',
                'password_confirmation' => 'StrongP@ssw0rd123',
            ]);

        $pending = session('pending_password_change');
        $this->assertNotNull($pending);
        $code = $pending['code'];

        $response = $this
            ->actingAs($user)
            ->post('/password/confirm-change', [
                'code' => $code,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHas('status', 'password-updated');

        $this->assertTrue(Hash::check('StrongP@ssw0rd123', $user->refresh()->password));
        $this->assertNull(session('pending_password_change'));
    }

    public function test_password_cannot_be_updated_with_invalid_otp(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'StrongP@ssw0rd123',
                'password_confirmation' => 'StrongP@ssw0rd123',
            ]);

        $response = $this
            ->actingAs($user)
            ->post('/password/confirm-change', [
                'code' => '999999',
            ]);

        $response->assertSessionHasErrors('code');
        $this->assertFalse(Hash::check('StrongP@ssw0rd123', $user->refresh()->password));
    }

    public function test_correct_password_must_be_provided_to_update_password(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'wrong-password',
                'password' => 'StrongP@ssw0rd123',
                'password_confirmation' => 'StrongP@ssw0rd123',
            ]);

        $response
            ->assertSessionHasErrorsIn('updatePassword', 'current_password')
            ->assertRedirect('/profile');
    }

    public function test_password_cannot_be_updated_with_demo_email(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@wastesync.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'StrongP@ssw0rd123',
                'password_confirmation' => 'StrongP@ssw0rd123',
            ]);

        $response
            ->assertSessionHasErrorsIn('updatePassword', 'current_password')
            ->assertRedirect('/profile');
    }
}
