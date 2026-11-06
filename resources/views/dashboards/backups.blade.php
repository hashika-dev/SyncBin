<x-app-layout>
    <div class="relative isolate w-full min-h-[calc(100vh-3.5rem)] bg-gradient-to-br from-emerald-100/60 via-slate-100 to-teal-100/40 dark:from-[#030906] dark:via-[#06120D] dark:to-[#040C09] text-slate-800 dark:text-slate-100 py-6 sm:py-8 px-4 sm:px-6 lg:px-8 selection:bg-emerald-500 selection:text-white transition-colors duration-300 overflow-hidden"
         x-data="{ creating: false }">

        <!-- Backlight -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden select-none z-0">
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[850px] h-[550px] bg-emerald-400/35 dark:bg-emerald-500/20 blur-[130px] rounded-full"></div>
            <div class="absolute top-1/3 -left-32 w-[650px] h-[650px] bg-teal-400/30 dark:bg-teal-600/15 blur-[140px] rounded-full"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#059669_1.2px,transparent_1.2px)] dark:bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:26px_26px] opacity-25 dark:opacity-[0.08]"></div>
        </div>

        <div class="max-w-[1200px] mx-auto space-y-6 sm:space-y-7 relative z-10">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-white/50 dark:border-white/10">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight drop-shadow-sm">
                            Database Backups
                        </h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/70 dark:bg-cyan-500/15 border border-white/80 dark:border-cyan-400/30 text-cyan-800 dark:text-cyan-300 backdrop-blur-xl font-mono uppercase">
                            SuperAdmin Only
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Automatic daily copies of the EcoSync database, plus one before every deploy and restore
                    </p>
                </div>

                <form method="POST" action="{{ route('dashboard.backups.store') }}" @submit="creating = true">
                    @csrf
                    <button type="submit" :disabled="creating"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 active:scale-95 text-white rounded-2xl text-xs font-semibold shadow-sm transition-all flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" :class="creating ? 'animate-spin' : ''"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"/></svg>
                        <span x-text="creating ? 'Backing up…' : 'Back up now'">Back up now</span>
                    </button>
                </form>
            </div>

            @if (session('status'))
                <div class="p-3.5 bg-emerald-500/15 border border-emerald-400/30 rounded-2xl text-emerald-800 dark:text-emerald-300 text-xs font-semibold backdrop-blur-xl">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-3.5 bg-rose-500/15 border border-rose-400/30 rounded-2xl text-rose-800 dark:text-rose-300 text-xs font-semibold backdrop-blur-xl">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Policy summary -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @php
                    $latest = $backups[0] ?? null;
                    $cards = [
                        ['label' => 'Latest backup', 'value' => $latest ? $latest['created_at']->diffForHumans() : 'None yet'],
                        ['label' => 'Schedule', 'value' => "Daily at {$dailyAt} ({$timezone})"],
                        ['label' => 'Retention', 'value' => "{$keepDays} days, newest {$keepMin} always kept"],
                    ];
                @endphp
                @foreach ($cards as $card)
                    <div class="rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl">
                        <span class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $card['label'] }}</span>
                        <span class="block mt-1 text-sm font-bold text-slate-900 dark:text-white">{{ $card['value'] }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Backup list -->
            <div class="relative rounded-3xl p-6 sm:p-7 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl overflow-hidden">
                <div class="mb-5 pb-4 border-b border-white/50 dark:border-white/10 flex flex-wrap items-center justify-between gap-2">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Stored backups</h2>
                    <span class="text-[11px] font-mono text-slate-500 dark:text-slate-400 break-all">{{ $directory }}</span>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-white/70 dark:border-white/10 bg-white/40 dark:bg-black/30">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white/60 dark:bg-white/[0.04] border-b border-white/60 dark:border-white/10 text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <th class="py-3.5 px-4 font-semibold">File</th>
                                <th class="py-3.5 px-4 font-semibold">Type</th>
                                <th class="py-3.5 px-4 font-semibold">Created</th>
                                <th class="py-3.5 px-4 font-semibold text-right">Size</th>
                                <th class="py-3.5 px-4 font-semibold text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/50 dark:divide-white/10">
                            @forelse ($backups as $backup)
                                @php
                                    $badge = match ($backup['reason']) {
                                        'daily' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-300',
                                        'manual' => 'bg-sky-100 text-sky-800 dark:bg-sky-500/15 dark:text-sky-300',
                                        'predeploy' => 'bg-violet-100 text-violet-800 dark:bg-violet-500/15 dark:text-violet-300',
                                        default => 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-300',
                                    };
                                    $label = match ($backup['reason']) {
                                        'predeploy' => 'Before deploy',
                                        'prerestore' => 'Before restore',
                                        default => ucfirst($backup['reason']),
                                    };
                                @endphp
                                <tr class="hover:bg-white/40 dark:hover:bg-white/[0.03]">
                                    <td class="py-3 px-4 font-mono text-slate-800 dark:text-slate-200 whitespace-nowrap">{{ $backup['name'] }}</td>
                                    <td class="py-3 px-4"><span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $badge }}">{{ $label }}</span></td>
                                    <td class="py-3 px-4 whitespace-nowrap text-slate-600 dark:text-slate-400">{{ $backup['created_at']->format('M d, Y H:i:s') }}</td>
                                    <td class="py-3 px-4 text-right font-mono text-slate-600 dark:text-slate-400">{{ number_format($backup['size'] / 1024, 1) }} KB</td>
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('dashboard.backups.download', $backup['name']) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/80 dark:bg-white/10 hover:bg-white dark:hover:bg-white/20 border border-white/80 dark:border-white/15 font-semibold text-slate-700 dark:text-slate-200 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                            Download
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 px-4 text-center text-slate-500 dark:text-slate-400">
                                        No backups yet. Press <strong>Back up now</strong> to make the first one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <p class="mt-4 text-[11px] text-slate-500 dark:text-slate-400">
                    Downloads ask for your password again because a backup contains every account's password hash.
                    Restoring is done from the server shell: <code class="font-mono">php artisan db:restore &lt;file&gt;</code> (see docs/BACKUP_RESTORE.md).
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
