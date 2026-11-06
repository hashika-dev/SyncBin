<?php

namespace App\Http\Controllers;

use App\Services\DatabaseBackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function __construct(private DatabaseBackupService $backups) {}

    public function index(): View
    {
        return view('dashboards.backups', [
            'backups' => $this->backups->all(),
            'directory' => $this->backups->directory(),
            'keepDays' => config('backup.keep_days'),
            'keepMin' => config('backup.keep_min'),
            'dailyAt' => config('backup.daily_at'),
            'timezone' => config('backup.timezone'),
            'schedulerLastRun' => $this->schedulerLastRun(),
        ]);
    }

    public function store(): RedirectResponse
    {
        try {
            $backup = $this->backups->create('manual');
            $this->backups->prune();
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('dashboard.backups')
                ->with('error', 'Backup failed. Check the server log for details.');
        }

        return redirect()->route('dashboard.backups')
            ->with('status', "Backup created: {$backup['name']}");
    }

    public function download(Request $request, string $file): BinaryFileResponse
    {
        try {
            $path = $this->backups->find($file);
        } catch (InvalidArgumentException) {
            abort(404);
        }

        Log::channel(config('backup.log_channel'))->notice('Database backup downloaded', [
            'file' => $file,
            'user_id' => $request->user()->id,
            'ip' => $request->ip(),
        ]);

        return response()->download($path, $file, [
            'Content-Type' => 'application/vnd.sqlite3',
        ]);
    }

    private function schedulerLastRun(): ?Carbon
    {
        $timestamp = Cache::get('scheduler:last_run');

        return $timestamp ? Carbon::createFromTimestamp($timestamp) : null;
    }
}
