<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_is_locked_after_five_failed_logins(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'wrong-password']);
        }

        // Even the correct password is refused while locked out.
        $response = $this->post('/login', ['email' => $user->email, 'password' => 'password']);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString(
            'Too many login attempts',
            session('errors')->first('email')
        );
    }

    public function test_seeder_uses_default_password_outside_production(): void
    {
        $this->app->make(DatabaseSeeder::class)->run();

        $superadmin = User::where('email', 'superadmin@ecosync.com')->first();
        $this->assertTrue(Hash::check('password', $superadmin->password));
    }

    public function test_seeder_never_uses_default_password_in_production(): void
    {
        $this->app['env'] = 'production';
        config(['app.seed_passwords' => [
            'admin' => 'Admin-Strong-Pass-123',
            'superadmin' => 'Super-Strong-Pass-456',
        ]]);

        $this->app->make(DatabaseSeeder::class)->run();

        foreach (['admin@ecosync.com', 'admin@wastesync.com'] as $email) {
            $user = User::where('email', $email)->first();
            $this->assertFalse(Hash::check('password', $user->password));
            $this->assertTrue(Hash::check('Admin-Strong-Pass-123', $user->password));
        }
        foreach (['superadmin@ecosync.com', 'superadmin@wastesync.com'] as $email) {
            $user = User::where('email', $email)->first();
            $this->assertFalse(Hash::check('password', $user->password));
            $this->assertTrue(Hash::check('Super-Strong-Pass-456', $user->password));
        }
    }

    public function test_production_seeder_replaces_existing_default_password(): void
    {
        User::factory()->create([
            'email' => 'superadmin@ecosync.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);
        $this->app['env'] = 'production';
        config(['app.seed_passwords' => ['admin' => 'Admin-Strong-Pass-123', 'superadmin' => 'Super-Strong-Pass-456']]);

        $this->app->make(DatabaseSeeder::class)->run();

        $user = User::where('email', 'superadmin@ecosync.com')->first();
        $this->assertTrue(Hash::check('Super-Strong-Pass-456', $user->password));
    }

    public function test_production_seeder_keeps_a_password_the_owner_changed(): void
    {
        User::factory()->create([
            'email' => 'superadmin@ecosync.com',
            'password' => Hash::make('Owner-Chosen-Pass-789'),
            'role' => 'superadmin',
        ]);
        $this->app['env'] = 'production';
        config(['app.seed_passwords' => ['admin' => 'Admin-Strong-Pass-123', 'superadmin' => 'Super-Strong-Pass-456']]);

        $this->app->make(DatabaseSeeder::class)->run();

        $user = User::where('email', 'superadmin@ecosync.com')->first();
        $this->assertTrue(Hash::check('Owner-Chosen-Pass-789', $user->password));
    }

    public function test_placeholder_is_not_recreated_once_a_real_account_exists(): void
    {
        // Owner changed superadmin@wastesync.com to their real email.
        User::factory()->create(['email' => 'owner@example.com', 'role' => 'superadmin']);

        $this->app->make(DatabaseSeeder::class)->run();

        $this->assertDatabaseMissing('users', ['email' => 'superadmin@wastesync.com']);
        $this->assertDatabaseMissing('users', ['email' => 'superadmin@ecosync.com']);
        // Admin has no real account yet, so its placeholders are still created.
        $this->assertDatabaseHas('users', ['email' => 'admin@wastesync.com']);
        $this->assertDatabaseHas('users', ['email' => 'admin@ecosync.com']);
    }

    public function test_existing_placeholder_is_kept_when_a_real_account_exists(): void
    {
        User::factory()->create(['email' => 'superadmin@ecosync.com', 'role' => 'superadmin']);
        User::factory()->create(['email' => 'owner@example.com', 'role' => 'superadmin']);

        $this->app->make(DatabaseSeeder::class)->run();

        $this->assertDatabaseHas('users', ['email' => 'superadmin@ecosync.com']);
        $this->assertDatabaseMissing('users', ['email' => 'superadmin@wastesync.com']);
    }

    public function test_seeder_does_not_refill_an_emptied_bin(): void
    {
        $this->app->make(DatabaseSeeder::class)->run();

        $bin = \App\Models\Bin::where('slug', 'recyclable')->first();
        $bin->items()->delete();
        $bin->update(['level' => 0, 'status' => 'Empty']);

        // Next deploy runs the seeder again.
        $this->app->make(DatabaseSeeder::class)->run();

        $bin->refresh();
        $this->assertSame(0, $bin->level);
        $this->assertSame('Empty', $bin->status);
        $this->assertSame(0, $bin->items()->count());
    }

    public function test_seeder_creates_missing_bins_with_demo_items(): void
    {
        $this->app->make(DatabaseSeeder::class)->run();

        $this->assertSame(4, \App\Models\Bin::count());
        $this->assertSame(3, \App\Models\Bin::where('slug', 'hazardous')->first()->items()->count());
    }

    public function test_production_seeder_skips_accounts_when_passwords_are_not_set(): void
    {
        $this->app['env'] = 'production';
        config(['app.seed_passwords' => ['admin' => null, 'superadmin' => null]]);

        $this->app->make(DatabaseSeeder::class)->run();

        $this->assertSame(0, User::count());
    }
}
