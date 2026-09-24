<x-app-layout>
    <div class="relative isolate w-full min-h-[calc(100vh-3.5rem)] bg-gradient-to-br from-emerald-100/60 via-slate-100 to-teal-100/40 dark:from-[#030906] dark:via-[#06120D] dark:to-[#040C09] text-slate-800 dark:text-slate-100 py-6 sm:py-8 px-4 sm:px-6 lg:px-8 selection:bg-emerald-500 selection:text-white transition-colors duration-300 overflow-hidden"
         x-data="{ confirmClearModal: false }">
        
        <!-- VIBRANT BACKLIGHT AURORA FOR REAL GLASS REFRACTION -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden select-none z-0">
            <!-- Center Emerald Glow -->
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[850px] h-[550px] bg-emerald-400/35 dark:bg-emerald-500/20 blur-[130px] rounded-full"></div>
            <!-- Left Cyan/Teal Orb -->
            <div class="absolute top-1/3 -left-32 w-[650px] h-[650px] bg-teal-400/30 dark:bg-teal-600/15 blur-[140px] rounded-full"></div>
            <!-- Right Emerald/Sage Orb -->
            <div class="absolute -bottom-24 right-10 w-[700px] h-[550px] bg-emerald-500/30 dark:bg-emerald-600/15 blur-[140px] rounded-full"></div>
            <!-- Fine Dot Grid Matrix -->
            <div class="absolute inset-0 bg-[radial-gradient(#059669_1.2px,transparent_1.2px)] dark:bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:26px_26px] opacity-25 dark:opacity-[0.08]"></div>
        </div>

        <!-- MAIN AUDIT TRAIL CONTENT -->
        <div class="max-w-[1720px] mx-auto space-y-6 sm:space-y-7 relative z-10">

            <!-- 1. Header & Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-white/50 dark:border-white/10">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight drop-shadow-sm">
                            Activity History
                        </h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/70 dark:bg-emerald-500/15 border border-white/80 dark:border-emerald-400/30 text-emerald-800 dark:text-emerald-300 shadow-[0_4px_16px_rgba(16,185,129,0.15)] backdrop-blur-xl">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Audit Trail Console
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Verified telemetry records, segregation event timeline, and fleet response log
                    </p>
                </div>

                @if(Auth::user()->isSuperAdmin())
                <div class="flex items-center gap-3">
                    <button @click="confirmClearModal = true" type="button" 
                            class="px-3.5 py-2 bg-rose-500/15 hover:bg-rose-500/25 active:scale-95 border border-rose-400/40 dark:border-rose-500/35 text-rose-700 dark:text-rose-300 rounded-2xl text-xs font-semibold shadow-sm backdrop-blur-md transition-all flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        <span>Clear History</span>
                    </button>
                </div>
                @endif
            </div>

            <!-- Status Feedback Alerts -->
            @if (session('status'))
                <div class="p-3.5 bg-emerald-500/15 border border-emerald-400/30 rounded-2xl flex items-center gap-3 text-emerald-800 dark:text-emerald-300 text-xs font-semibold shadow-sm backdrop-blur-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- 2. Filters & Export Card (Frosted Glass) -->
            <div class="relative rounded-3xl p-5 sm:p-6 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] transition-all overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                <form action="{{ route('dashboard.history') }}" method="GET" id="historyFilterForm" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search Item -->
                        <div>
                            <label for="search" class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">SEARCH CLASSIFICATION</label>
                            <input type="text" name="search" id="search" placeholder="Item name..." value="{{ request('search') }}"
                                   @keydown.enter="$el.form.submit()"
                                   class="w-full px-3.5 py-2 bg-white/70 dark:bg-black/40 border border-white/80 dark:border-white/15 rounded-2xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 outline-none text-xs text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 backdrop-blur-md transition-all shadow-sm">
                        </div>

                        <!-- Bin Category -->
                        <div>
                            <label for="bin" class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">STREAM CATEGORY</label>
                            <select name="bin" id="bin" @change="$el.form.submit()" 
                                    class="w-full px-3.5 py-2 bg-white/70 dark:bg-black/40 border border-white/80 dark:border-white/15 rounded-2xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 outline-none text-xs text-slate-800 dark:text-slate-100 backdrop-blur-md transition-all shadow-sm">
                                <option value="" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-white">All Categories</option>
                                @foreach($bins as $b)
                                    <option value="{{ $b->slug }}" {{ request('bin') == $b->slug ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-slate-900 dark:text-white">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- From Date -->
                        <div>
                            <label for="from_date" class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">FROM DATE</label>
                            <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}"
                                   @change="$el.form.submit()"
                                   class="w-full px-3.5 py-2 bg-white/70 dark:bg-black/40 border border-white/80 dark:border-white/15 rounded-2xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 outline-none text-xs font-mono text-slate-800 dark:text-slate-100 backdrop-blur-md transition-all shadow-sm">
                        </div>

                        <!-- To Date -->
                        <div>
                            <label for="to_date" class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">TO DATE</label>
                            <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}"
                                   @change="$el.form.submit()"
                                   class="w-full px-3.5 py-2 bg-white/70 dark:bg-black/40 border border-white/80 dark:border-white/15 rounded-2xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 outline-none text-xs font-mono text-slate-800 dark:text-slate-100 backdrop-blur-md transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- Quick Range Shortcuts & Filter Actions Row -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-white/50 dark:border-white/10">
                        <!-- Quick Range Buttons -->
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1">
                            <span class="text-[10px] font-mono uppercase text-slate-500 dark:text-slate-400 mr-1 shrink-0 font-semibold">Quick:</span>
                            <a href="{{ route('dashboard.history', array_merge(request()->except(['quick_range', 'page', 'from_date', 'to_date']), ['quick_range' => 'today'])) }}"
                               class="px-3 py-1 rounded-xl text-xs font-semibold transition-all shrink-0 {{ request('quick_range') === 'today' ? 'bg-white text-emerald-800 border border-emerald-400/40 shadow-sm dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }}">
                                Today
                            </a>
                            <a href="{{ route('dashboard.history', array_merge(request()->except(['quick_range', 'page', 'from_date', 'to_date']), ['quick_range' => '7days'])) }}"
                               class="px-3 py-1 rounded-xl text-xs font-semibold transition-all shrink-0 {{ request('quick_range') === '7days' ? 'bg-white text-emerald-800 border border-emerald-400/40 shadow-sm dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }}">
                                Past 7 Days
                            </a>
                            <a href="{{ route('dashboard.history', array_merge(request()->except(['quick_range', 'page', 'from_date', 'to_date']), ['quick_range' => '30days'])) }}"
                               class="px-3 py-1 rounded-xl text-xs font-semibold transition-all shrink-0 {{ request('quick_range') === '30days' ? 'bg-white text-emerald-800 border border-emerald-400/40 shadow-sm dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }}">
                                Past 30 Days
                            </a>
                            @if(request()->hasAny(['search', 'bin', 'from_date', 'to_date', 'quick_range']))
                                <a href="{{ route('dashboard.history') }}" class="px-3 py-1 rounded-xl text-xs font-semibold text-rose-700 dark:text-rose-400 hover:bg-rose-500/10 transition-colors shrink-0 flex items-center gap-1">
                                    <span>&times; Clear Filter</span>
                                </a>
                            @endif
                        </div>

                        <!-- Export Buttons -->
                        <div class="flex items-center gap-2.5 shrink-0">
                            <a href="{{ route('dashboard.export.csv', request()->all()) }}" 
                               class="px-3.5 py-1.5 bg-white/70 dark:bg-white/10 hover:bg-white/90 dark:hover:bg-white/20 active:scale-95 border border-white/80 dark:border-white/15 text-slate-800 dark:text-slate-200 rounded-2xl text-xs font-semibold shadow-sm backdrop-blur-md transition-all duration-150 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                <span>Export CSV</span>
                            </a>
                            <a href="{{ route('dashboard.export', request()->all()) }}" target="_blank" 
                               class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white rounded-2xl text-xs font-semibold shadow-[0_4px_16px_rgba(16,185,129,0.3)] transition-all duration-150 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <span>Export PDF</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 3. Card 2: Classified Item Stream (Frosted Glass Table) -->
            <div class="relative rounded-3xl p-6 sm:p-7 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] transition-all overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                <div class="mb-5 pb-4 border-b border-white/50 dark:border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Telemetry Ingestion Stream</h2>
                    </div>
                    <span class="text-[11px] font-mono text-emerald-700 dark:text-emerald-400 font-semibold">{{ $logs->total() }} Logged Events</span>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-white/70 dark:border-white/10 bg-white/40 dark:bg-black/30 backdrop-blur-md">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white/60 dark:bg-white/[0.04] border-b border-white/60 dark:border-white/10 text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <th class="py-3.5 px-4 font-semibold">Material Item</th>
                                <th class="py-3.5 px-4 font-semibold">Assigned Category Stream</th>
                                <th class="py-3.5 px-4 text-right font-semibold">Logged Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/50 dark:divide-white/10">
                            @forelse($logs as $log)
                                @php
                                    $categoryText = match($log->bin?->slug) {
                                        'biodegradable' => 'text-emerald-700 dark:text-emerald-400',
                                        'recyclable' => 'text-sky-700 dark:text-sky-400',
                                        'hazardous' => 'text-rose-700 dark:text-rose-400',
                                        default => 'text-amber-700 dark:text-amber-400',
                                    };
                                    $dotColor = match($log->bin?->slug) {
                                        'biodegradable' => 'bg-emerald-500',
                                        'recyclable' => 'bg-sky-500',
                                        'hazardous' => 'bg-rose-500',
                                        default => 'bg-amber-500',
                                    };
                                @endphp
                                <tr class="hover:bg-white/60 dark:hover:bg-white/[0.04] transition-colors">
                                    <td class="py-4 px-4 font-semibold text-slate-900 dark:text-slate-100">
                                        <div class="flex items-center gap-2.5">
                                            @if($log->icon)
                                                <span class="text-base">{{ $log->icon }}</span>
                                            @endif
                                            <span>{{ $log->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 font-semibold {{ $categoryText }}">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full {{ $dotColor }} shadow-sm"></span>
                                            <span>{{ $log->bin ? $log->bin->name : 'Unassigned' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 font-mono text-slate-500 dark:text-slate-400 text-right">
                                        {{ $log->created_at ? $log->created_at->diffForHumans() : 'Just now' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-xs text-slate-400 dark:text-slate-500">No activity logs recorded matching criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="pt-5 mt-4 border-t border-white/50 dark:border-white/10">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

            <!-- 4. Card 3: Evacuation Audit Trail (Frosted Glass Container) -->
            <div class="relative rounded-3xl p-6 sm:p-7 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] transition-all overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 pb-4 border-b border-white/50 dark:border-white/10">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Containment Evacuation Audit Trail</h2>
                    </div>

                    <span class="px-3.5 py-1.5 bg-white/70 dark:bg-emerald-500/15 border border-white/80 dark:border-emerald-400/30 text-emerald-800 dark:text-emerald-300 text-xs font-mono rounded-2xl w-fit backdrop-blur-md shadow-sm">
                        Avg Fleet Response: <strong class="font-bold">{{ $avgResponseTimeMinutes > 0 ? $avgResponseTimeMinutes . ' mins' : 'Immediate' }}</strong>
                    </span>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-white/70 dark:border-white/10 bg-white/40 dark:bg-black/30 backdrop-blur-md">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white/60 dark:bg-white/[0.04] border-b border-white/60 dark:border-white/10 text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <th class="py-3.5 px-4 font-semibold">Containment Node</th>
                                <th class="py-3.5 px-4 font-semibold">Operator Email</th>
                                <th class="py-3.5 px-4 font-semibold">Pre-Clear Capacity</th>
                                <th class="py-3.5 px-4 font-semibold">Response Speed</th>
                                <th class="py-3.5 px-4 text-right font-semibold">Evacuated At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/50 dark:divide-white/10">
                            @forelse($clearanceLogs as $clearance)
                                @php
                                    $fillBadgeColor = match(true) {
                                        $clearance->level_before_clearance >= 80 => 'bg-rose-500/20 text-rose-700 dark:text-rose-400 border border-rose-400/40 shadow-[0_0_12px_rgba(244,63,94,0.3)]',
                                        $clearance->level_before_clearance >= 50 => 'bg-amber-500/15 text-amber-700 dark:text-amber-400 border border-amber-400/40',
                                        default => 'bg-emerald-500/15 text-emerald-800 dark:text-emerald-300 border border-emerald-400/40',
                                    };
                                @endphp
                                <tr class="hover:bg-white/60 dark:hover:bg-white/[0.04] transition-colors">
                                    <td class="py-4 px-4 font-bold text-slate-900 dark:text-white">
                                        {{ $clearance->bin ? $clearance->bin->name : 'Waste Containment' }}
                                    </td>
                                    <td class="py-4 px-4 font-mono text-slate-600 dark:text-slate-400">
                                        {{ $clearance->cleared_by_email }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase backdrop-blur-md {{ $fillBadgeColor }}">
                                            {{ $clearance->level_before_clearance }}%
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-emerald-700 dark:text-emerald-400 font-semibold font-mono">
                                        {{ $clearance->response_time_minutes ? $clearance->response_time_minutes . ' min' : 'Immediate' }}
                                    </td>
                                    <td class="py-4 px-4 font-mono text-slate-500 dark:text-slate-400 text-right">
                                        {{ $clearance->cleared_at ? $clearance->cleared_at->format('M d, Y · H:i') : 'Just now' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-xs text-slate-400 dark:text-slate-500">No containment clearance records recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- SuperAdmin Confirmation Modal for Clear History (Deep Frosted Acrylic) -->
        @if(Auth::user()->isSuperAdmin())
        <div x-show="confirmClearModal" 
             @keydown.escape.window="confirmClearModal = false"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 dark:bg-black/80 backdrop-blur-xl"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-cloak>
            <div class="relative bg-white/95 dark:bg-[#0B1713]/95 border border-white/90 dark:border-rose-500/35 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 text-slate-800 dark:text-slate-100 backdrop-blur-2xl overflow-hidden"
                 @click.outside="confirmClearModal = false">
                
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-rose-400/40 to-transparent pointer-events-none"></div>

                <div class="w-12 h-12 rounded-2xl bg-rose-500/15 border border-rose-400/40 flex items-center justify-center text-rose-600 dark:text-rose-400 mx-auto shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                </div>
                <div class="text-center">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Reset Activity Audit Trail?</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">This action will delete all recorded waste classifications and reset bin fill levels to 0%. This cannot be undone.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button" @click="confirmClearModal = false" class="py-2.5 px-4 bg-white/70 dark:bg-white/10 hover:bg-white/90 dark:hover:bg-white/20 border border-white/80 dark:border-white/15 text-slate-800 dark:text-slate-200 rounded-2xl text-xs font-semibold backdrop-blur-md transition-colors">
                        Cancel
                    </button>
                    <form method="POST" action="{{ route('dashboard.history.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-500 text-white rounded-2xl text-xs font-semibold shadow-md active:scale-95 transition-all">
                            Yes, Clear
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
