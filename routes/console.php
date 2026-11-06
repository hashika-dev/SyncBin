<?php

use App\Services\DatabaseBackupService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('db:backup {--reason=manual : daily, manual or predeploy} {--no-prune : Keep old backups}', function (DatabaseBackupService $backups) {
    try {
        $backup = $backups->create($this->option('reason'));
    } catch (Throwable $e) {
        report($e);
        $this->error('Backup failed: '.$e->getMessage());

        return 1;
    }

    $this->info(sprintf('Backup created: %s (%s KB)', $backup['name'], number_format($backup['size'] / 1024, 1)));

    if (! $this->option('no-prune')) {
        foreach ($backups->prune() as $deleted) {
            $this->line("Deleted old backup: {$deleted}");
        }
    }

    return 0;
})->purpose('Back up the SQLite database to the backups folder');

Artisan::command('db:backups', function (DatabaseBackupService $backups) {
    $this->line('Folder: '.$backups->directory());

    $this->table(['File', 'Reason', 'Created', 'Size (KB)'], array_map(fn ($b) => [
        $b['name'],
        $b['reason'],
        $b['created_at']->toDateTimeString(),
        number_format($b['size'] / 1024, 1),
    ], $backups->all()));
})->purpose('List database backups');

Artisan::command('db:restore {file : Backup file name, see db:backups} {--force : Skip the confirmation question}', function (DatabaseBackupService $backups) {
    $file = $this->argument('file');

    $this->warn("This replaces the live database with {$file}.");
    $this->warn('A safety backup of the current database is taken first.');

    if (! $this->option('force') && ! $this->confirm('Continue?')) {
        $this->line('Restore cancelled.');

        return 1;
    }

    try {
        $safety = $backups->restore($file);
    } catch (Throwable $e) {
        report($e);
        $this->error('Restore failed: '.$e->getMessage());

        return 1;
    }

    $this->info("Restored from {$file}.");
    $this->line("Safety backup of the previous data: {$safety}");

    return 0;
})->purpose('Restore the SQLite database from a backup');

Schedule::command('db:backup --reason=daily')
    ->dailyAt(config('backup.daily_at'))
    ->timezone(config('backup.timezone'))
    ->withoutOverlapping();
