<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;

/**
 * Creates, lists, prunes and restores SQLite backups.
 *
 * Backups are written with SQLite's VACUUM INTO, which produces a clean,
 * consistent copy even while the app is serving requests.
 */
class DatabaseBackupService
{
    /** Only names produced by this service are accepted (blocks path traversal). */
    public const NAME_PATTERN = '/^ecosync-\d{4}-\d{2}-\d{2}_\d{6}-(daily|manual|predeploy|prerestore)\.sqlite$/';

    public const REASONS = ['daily', 'manual', 'predeploy', 'prerestore'];

    public function directory(): string
    {
        $configured = config('backup.path');

        if ($configured) {
            return rtrim($configured, '/\\');
        }

        $database = config('database.connections.sqlite.database');

        // An in-memory or missing path falls back to storage/app/backups.
        if (! $database || $database === ':memory:') {
            return storage_path('app/backups');
        }

        return dirname($database).DIRECTORY_SEPARATOR.'backups';
    }

    /**
     * Write a new backup and return its details.
     *
     * @return array{name: string, size: int, created_at: Carbon, reason: string}
     */
    public function create(string $reason = 'manual'): array
    {
        if (! in_array($reason, self::REASONS, true)) {
            throw new InvalidArgumentException("Unknown backup reason [{$reason}].");
        }

        if (DB::connection()->getDriverName() !== 'sqlite') {
            throw new RuntimeException('Backups currently support the SQLite database only.');
        }

        File::ensureDirectoryExists($this->directory(), 0750);

        $name = sprintf('ecosync-%s-%s.sqlite', now()->format('Y-m-d_His'), $reason);
        $path = $this->path($name);

        if (File::exists($path)) {
            throw new RuntimeException('A backup with this name already exists, try again in a second.');
        }

        DB::statement('VACUUM INTO ?', [$path]);

        $this->assertHealthy($path);

        Log::channel(config('backup.log_channel'))->info('Database backup created', [
            'file' => $name,
            'reason' => $reason,
            'size_bytes' => File::size($path),
            'user_id' => auth()->id(),
        ]);

        return $this->describe($path);
    }

    /**
     * All backups, newest first.
     *
     * @return array<int, array{name: string, size: int, created_at: Carbon, reason: string}>
     */
    public function all(): array
    {
        if (! File::isDirectory($this->directory())) {
            return [];
        }

        return collect(File::files($this->directory()))
            ->filter(fn ($file) => preg_match(self::NAME_PATTERN, $file->getFilename()))
            ->map(fn ($file) => $this->describe($file->getPathname()))
            ->sortByDesc('name')
            ->values()
            ->all();
    }

    /**
     * Delete backups older than the retention window, always keeping the newest few.
     *
     * @return array<int, string> names of deleted backups
     */
    public function prune(): array
    {
        $cutoff = now()->subDays((int) config('backup.keep_days'));
        $keepMin = (int) config('backup.keep_min');

        $deleted = [];

        foreach (array_slice($this->all(), $keepMin) as $backup) {
            if ($backup['created_at']->lt($cutoff)) {
                File::delete($this->path($backup['name']));
                $deleted[] = $backup['name'];
            }
        }

        if ($deleted) {
            Log::channel(config('backup.log_channel'))->info('Old database backups pruned', [
                'files' => $deleted,
            ]);
        }

        return $deleted;
    }

    /**
     * Full path of an existing backup, after validating its name.
     */
    public function find(string $name): string
    {
        if (! preg_match(self::NAME_PATTERN, $name)) {
            throw new InvalidArgumentException('Invalid backup name.');
        }

        $path = $this->path($name);

        if (! File::exists($path)) {
            throw new InvalidArgumentException("Backup [{$name}] was not found.");
        }

        return $path;
    }

    /**
     * Replace the live database with a backup. A safety backup is taken first.
     *
     * @return string name of the safety backup taken before restoring
     */
    public function restore(string $name): string
    {
        $source = $this->find($name);
        $this->assertHealthy($source);

        $target = config('database.connections.sqlite.database');

        if (! $target || $target === ':memory:') {
            throw new RuntimeException('The live database is not a file, nothing to restore into.');
        }

        $safety = $this->create('prerestore');

        DB::disconnect();

        $temp = $target.'.restoring';
        File::copy($source, $temp);

        if (! rename($temp, $target)) {
            File::delete($temp);
            throw new RuntimeException('Could not replace the database file.');
        }

        // Remove leftover journal files that belong to the old database.
        File::delete([$target.'-wal', $target.'-shm', $target.'-journal']);

        DB::reconnect();

        Log::channel(config('backup.log_channel'))->warning('Database restored from backup', [
            'restored_from' => $name,
            'safety_backup' => $safety['name'],
        ]);

        return $safety['name'];
    }

    private function path(string $name): string
    {
        return $this->directory().DIRECTORY_SEPARATOR.$name;
    }

    /**
     * @return array{name: string, size: int, created_at: Carbon, reason: string}
     */
    private function describe(string $path): array
    {
        $name = basename($path);
        preg_match('/^ecosync-(\d{4}-\d{2}-\d{2}_\d{6})-(\w+)\.sqlite$/', $name, $m);

        return [
            'name' => $name,
            'size' => File::size($path),
            'created_at' => Carbon::createFromFormat('Y-m-d_His', $m[1]),
            'reason' => $m[2],
        ];
    }

    /**
     * Open the file read-only and run SQLite's integrity check.
     */
    private function assertHealthy(string $path): void
    {
        $pdo = new \PDO('sqlite:'.$path, null, null, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        $result = $pdo->query('PRAGMA integrity_check')->fetchColumn();
        $pdo = null;

        if ($result !== 'ok') {
            throw new RuntimeException('Backup ['.basename($path)."] failed the integrity check: {$result}");
        }
    }
}
