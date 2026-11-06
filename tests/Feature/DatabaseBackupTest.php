<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\DatabaseBackupService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * Uses a real SQLite file (not :memory:) because VACUUM INTO cannot run
 * inside the transaction that RefreshDatabase wraps around each test.
 */
class DatabaseBackupTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ecosync-backup-test-'.uniqid();
        File::ensureDirectoryExists($this->dir);
        touch($this->dir.'/database.sqlite');

        config([
            'database.connections.sqlite.database' => $this->dir.'/database.sqlite',
            'backup.path' => null,
        ]);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--force' => true]);
    }

    protected function tearDown(): void
    {
        DB::disconnect();
        File::deleteDirectory($this->dir);

        parent::tearDown();
    }

    private function superadmin(): User
    {
        return User::factory()->create(['role' => 'superadmin']);
    }

    private function backups(): DatabaseBackupService
    {
        return app(DatabaseBackupService::class);
    }

    public function test_backup_command_writes_a_valid_copy_next_to_the_database(): void
    {
        User::factory()->create(['email' => 'kept@example.com']);

        $this->artisan('db:backup', ['--reason' => 'daily'])->assertSuccessful();

        $all = $this->backups()->all();
        $this->assertCount(1, $all);
        $this->assertSame('daily', $all[0]['reason']);
        $this->assertSame($this->dir.DIRECTORY_SEPARATOR.'backups', $this->backups()->directory());

        $copy = new \PDO('sqlite:'.$this->backups()->find($all[0]['name']));
        $this->assertSame(1, (int) $copy->query("select count(*) from users where email = 'kept@example.com'")->fetchColumn());
    }

    public function test_prune_deletes_old_backups_but_keeps_the_newest_ones(): void
    {
        config(['backup.keep_days' => 7, 'backup.keep_min' => 3]);
        $dir = $this->backups()->directory();
        File::ensureDirectoryExists($dir);

        foreach ([1, 2, 3, 20, 30] as $daysAgo) {
            $name = 'ecosync-'.now()->subDays($daysAgo)->format('Y-m-d_His').'-daily.sqlite';
            File::put($dir.'/'.$name, 'x');
        }

        $deleted = $this->backups()->prune();

        $this->assertCount(2, $deleted);
        $this->assertCount(3, $this->backups()->all());

        // Old backups survive when they are among the newest keep_min.
        config(['backup.keep_min' => 5]);
        File::put($dir.'/ecosync-'.now()->subDays(40)->format('Y-m-d_His').'-daily.sqlite', 'x');
        $this->assertSame([], $this->backups()->prune());
    }

    public function test_restore_brings_back_old_data_and_keeps_a_safety_copy(): void
    {
        User::factory()->create(['email' => 'before@example.com']);
        $backup = $this->backups()->create('manual');

        User::where('email', 'before@example.com')->delete();
        User::factory()->create(['email' => 'after@example.com']);

        $this->artisan('db:restore', ['file' => $backup['name'], '--force' => true])->assertSuccessful();

        $this->assertTrue(User::where('email', 'before@example.com')->exists());
        $this->assertFalse(User::where('email', 'after@example.com')->exists());

        $reasons = array_column($this->backups()->all(), 'reason');
        $this->assertContains('prerestore', $reasons);
    }

    public function test_invalid_backup_names_are_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->backups()->find('../database.sqlite');
    }

    public function test_only_superadmins_can_open_the_backups_page(): void
    {
        $this->get(route('dashboard.backups'))->assertRedirect(route('login'));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get(route('dashboard.backups'))->assertForbidden();
        $this->actingAs($admin)->post(route('dashboard.backups.store'))->assertForbidden();

        $this->actingAs($this->superadmin())
            ->get(route('dashboard.backups'))
            ->assertOk()
            ->assertSee('Database Backups')
            ->assertSee('No backups yet');
    }

    public function test_superadmin_can_create_a_backup_from_the_page(): void
    {
        $this->actingAs($this->superadmin())
            ->post(route('dashboard.backups.store'))
            ->assertRedirect(route('dashboard.backups'))
            ->assertSessionHas('status');

        $this->assertSame('manual', $this->backups()->all()[0]['reason']);
    }

    public function test_back_up_now_is_not_blocked_by_other_dashboard_requests(): void
    {
        $user = $this->superadmin();

        // The live dashboard polls /api/bins; this must not use up the backup limit.
        for ($i = 0; $i < 10; $i++) {
            $this->actingAs($user)->getJson(route('bins.index'))->assertOk();
        }

        $this->actingAs($user)->post(route('dashboard.backups.store'))->assertRedirect(route('dashboard.backups'));

        // Still limited on its own: 5 per minute.
        for ($i = 0; $i < 4; $i++) {
            sleep(1); // backup names are unique per second
            $this->actingAs($user)->post(route('dashboard.backups.store'))->assertRedirect();
        }
        $this->actingAs($user)->post(route('dashboard.backups.store'))->assertStatus(429);
    }

    public function test_download_requires_password_confirmation(): void
    {
        $backup = $this->backups()->create('manual');
        $user = $this->superadmin();
        $url = route('dashboard.backups.download', $backup['name']);

        $this->actingAs($user)->get($url)->assertRedirect(route('password.confirm'));

        $this->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get($url)
            ->assertOk()
            ->assertDownload($backup['name']);
    }

    public function test_download_of_unknown_or_malicious_file_returns_404(): void
    {
        $this->actingAs($this->superadmin())
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get('/dashboard/backups/..%2F..%2F.env/download')
            ->assertNotFound();

        $this->actingAs($this->superadmin())
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('dashboard.backups.download', 'ecosync-2020-01-01_000000-daily.sqlite'))
            ->assertNotFound();
    }

    public function test_page_shows_whether_the_scheduler_is_running(): void
    {
        $user = $this->superadmin();

        $this->actingAs($user)->get(route('dashboard.backups'))->assertSee('NOT running');

        $this->artisan('schedule:run')->assertSuccessful();

        $this->actingAs($user)->get(route('dashboard.backups'))
            ->assertSee('Running (checked')
            ->assertDontSee('NOT running');
    }
}
