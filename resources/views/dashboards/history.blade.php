<x-app-layout>
    <div class="relative isolate w-full bg-slate-50 dark:bg-[#0A0E17] text-slate-800 dark:text-slate-200 min-h-[calc(100vh-3.5rem)] py-6 sm:py-8 px-4 sm:px-6 lg:px-8 transition-colors duration-300 overflow-hidden"
         x-data="{ confirmClearModal: false }">
        
        <!-- PROFESSIONAL COHESIVE BACKGROUND (Single signature brand atmospheric backlight, no rainbow) -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden select-none z-0">
            <!-- Single Unified Subtle Emerald Brand Glow at top center -->
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[850px] h-[450px] bg-emerald-500/10 dark:bg-emerald-500/8 blur-[130px] rounded-full"></div>

            <!-- Subtle Technical Dot-Grid Matrix Texture (Clean & Professional) -->
            <div class="absolute inset-0 bg-[radial-gradient(#94a3b8_1px,transparent_1px)] dark:bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] [mask-image:radial-gradient(ellipse_70%_50%_at_50%_0%,#000_60%,transparent_100%)] opacity-70"></div>
        </div>

        <!-- MAIN AUDIT TRAIL CONTENT (Elevated at z-10) -->
        <div class="max-w-[1720px] mx-auto space-y-7 relative z-10">

            <!-- 1. Header & Actions -->
            <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800/80">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Activity History</h1>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 text-emerald-700 dark:text-emerald-300 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Audit Trail Console
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Verified telemetry records, segregation event timeline, and fleet response log</p>
                </div>

                @if(Auth::user()->isSuperAdmin())
                <div class="flex items-center gap-3">
                    <button @click="confirmClearModal = true" type="button" class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 active:scale-95 border border-rose-200 dark:border-rose-800/60 text-rose-700 dark:text-rose-300 rounded-xl text-xs font-semibold shadow-sm transition-all duration-150 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        <span>Clear History</span>
                    </button>
                </div>
                @endif
            </header>

            <!-- Status Alerts -->
            @if (session('status'))
                <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/80 rounded-xl flex items-center gap-3 text-emerald-700 dark:text-emerald-300 text-xs font-semibold shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- 2. Filters & Export Card -->
            <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-5 sm:p-6 shadow-sm transition-all">
                <form action="{{ route('dashboard.history') }}" method="GET" id="historyFilterForm" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search Item -->
                        <div>
                            <label for="search" class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">SEARCH ITEM</label>
                            <input type="text" name="search" id="search" placeholder="Item name..." value="{{ request('search') }}"
                                   @keydown.enter="$el.form.submit()"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-[#0A0E17] border border-slate-200 dark:border-slate-800 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 outline-none text-xs text-slate-800 dark:text-slate-200 placeholder:text-slate-400 transition-all">
                        </div>

                        <!-- Bin Category -->
                        <div>
                            <label for="bin" class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">BIN CATEGORY</label>
                            <select name="bin" id="bin" @change="$el.form.submit()" 
                                    class="w-full px-3.5 py-2 bg-slate-50 dark:bg-[#0A0E17] border border-slate-200 dark:border-slate-800 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 outline-none text-xs text-slate-800 dark:text-slate-200 transition-all">
                                <option value="">All Categories</option>
                                @foreach($bins as $b)
                                    <option value="{{ $b->slug }}" {{ request('bin') == $b->slug ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- From Date -->
                        <div>
                            <label for="from_date" class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">FROM DATE</label>
                            <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}"
                                   @change="$el.form.submit()"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-[#0A0E17] border border-slate-200 dark:border-slate-800 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 outline-none text-xs font-mono text-slate-800 dark:text-slate-200 transition-all">
                        </div>

                        <!-- To Date -->
                        <div>
                            <label for="to_date" class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">TO DATE</label>
                            <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}"
                                   @change="$el.form.submit()"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-[#0A0E17] border border-slate-200 dark:border-slate-800 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 outline-none text-xs font-mono text-slate-800 dark:text-slate-200 transition-all">
                        </div>
                    </div>

                    <!-- Quick Range Shortcuts & Filter Actions Row -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-slate-100 dark:border-slate-800/60">
                        <!-- Quick Range Buttons -->
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1">
                            <span class="text-[10px] font-mono uppercase text-slate-400 mr-1 shrink-0 font-semibold">Quick:</span>
                            <a href="{{ route('dashboard.history', array_merge(request()->except(['quick_range', 'page', 'from_date', 'to_date']), ['quick_range' => 'today'])) }}"
                               class="px-2.5 py-1 rounded-lg text-xs font-medium transition-colors shrink-0 {{ request('quick_range') === 'today' ? 'bg-slate-100 dark:bg-[#1C2638] text-slate-900 dark:text-white border border-slate-300 dark:border-[#2B3A52] font-semibold shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-slate-100/70 dark:bg-slate-800/50 hover:bg-slate-200 dark:hover:bg-slate-800' }}">
                                Today
                            </a>
                            <a href="{{ route('dashboard.history', array_merge(request()->except(['quick_range', 'page', 'from_date', 'to_date']), ['quick_range' => '7days'])) }}"
                               class="px-2.5 py-1 rounded-lg text-xs font-medium transition-colors shrink-0 {{ request('quick_range') === '7days' ? 'bg-slate-100 dark:bg-[#1C2638] text-slate-900 dark:text-white border border-slate-300 dark:border-[#2B3A52] font-semibold shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-slate-100/70 dark:bg-slate-800/50 hover:bg-slate-200 dark:hover:bg-slate-800' }}">
                                Past 7 Days
                            </a>
                            <a href="{{ route('dashboard.history', array_merge(request()->except(['quick_range', 'page', 'from_date', 'to_date']), ['quick_range' => '30days'])) }}"
                               class="px-2.5 py-1 rounded-lg text-xs font-medium transition-colors shrink-0 {{ request('quick_range') === '30days' ? 'bg-slate-100 dark:bg-[#1C2638] text-slate-900 dark:text-white border border-slate-300 dark:border-[#2B3A52] font-semibold shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-slate-100/70 dark:bg-slate-800/50 hover:bg-slate-200 dark:hover:bg-slate-800' }}">
                                Past 30 Days
                            </a>
                            @if(request()->hasAny(['search', 'bin', 'from_date', 'to_date', 'quick_range']))
                                <a href="{{ route('dashboard.history') }}" class="px-2.5 py-1 rounded-lg text-xs font-semibold text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/30 transition-colors shrink-0 flex items-center gap-1">
                                    <span>&times; Clear Filter</span>
                                </a>
                            @endif
                        </div>

                        <!-- Export Buttons -->
                        <div class="flex items-center gap-2.5 shrink-0">
                            <a href="{{ route('dashboard.export.csv', request()->all()) }}" class="px-3.5 py-1.5 bg-white dark:bg-[#101622] hover:bg-slate-50 dark:hover:bg-slate-800 active:scale-95 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold shadow-sm transition-all duration-150 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                <span>Export CSV</span>
                            </a>
                            <a href="{{ route('dashboard.export', request()->all()) }}" target="_blank" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white rounded-xl text-xs font-semibold shadow-sm transition-all duration-150 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <span>Export PDF</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 3. Card 2: Classified Item Stream (Telemetry Ingestion Logs) -->
            <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-6 sm:p-7 shadow-sm transition-all">
                <div class="mb-5 pb-4 border-b border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Telemetry Ingestion Logs</h2>
                    </div>
                    <span class="text-[11px] font-mono text-slate-500 dark:text-slate-400 font-medium">{{ $logs->total() }} Events</span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-[#0A0E17]/60 border-b border-slate-200 dark:border-slate-800 text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <th class="py-3 px-4">Item</th>
                                <th class="py-3 px-4">Category</th>
                                <th class="py-3 px-4 text-right">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                            @forelse($logs as $log)
                                @php
                                    $categoryText = match($log->bin?->slug) {
                                        'biodegradable' => 'text-emerald-600 dark:text-emerald-400',
                                        'recyclable' => 'text-sky-600 dark:text-sky-400',
                                        'hazardous' => 'text-rose-600 dark:text-rose-400',
                                        default => 'text-amber-600 dark:text-amber-400',
                                    };
                                    $dotColor = match($log->bin?->slug) {
                                        'biodegradable' => 'bg-emerald-500',
                                        'recyclable' => 'bg-sky-500',
                                        'hazardous' => 'bg-rose-500',
                                        default => 'bg-amber-500',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3.5 px-4 font-semibold text-slate-900 dark:text-slate-200">
                                        <div class="flex items-center gap-2">
                                            @if($log->icon)
                                                <span class="text-sm">{{ $log->icon }}</span>
                                            @endif
                                            <span>{{ $log->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-medium {{ $categoryText }}">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                            <span>{{ $log->bin ? $log->bin->name : 'Unassigned' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-slate-400 dark:text-slate-500 text-right">
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
                    <div class="pt-5 mt-4 border-t border-slate-100 dark:border-slate-800/60">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

            <!-- 4. Card 3: Maintenance Dispatch (Evacuation Audit Trail) -->
            <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-6 sm:p-7 shadow-sm transition-all">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 pb-4 border-b border-slate-100 dark:border-slate-800/60">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Evacuation Audit Trail</h2>
                    </div>

                    <span class="px-3 py-1 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 text-xs font-mono rounded-xl w-fit">
                        Avg Fleet Response: <strong class="text-emerald-600 dark:text-emerald-400">{{ $avgResponseTimeMinutes > 0 ? $avgResponseTimeMinutes . ' mins' : 'Immediate' }}</strong>
                    </span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-[#0A0E17]/60 border-b border-slate-200 dark:border-slate-800 text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <th class="py-3 px-4">Bin Node</th>
                                <th class="py-3 px-4">Operator</th>
                                <th class="py-3 px-4">Pre-Clear Fill</th>
                                <th class="py-3 px-4">Response Speed</th>
                                <th class="py-3 px-4 text-right">Cleared At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                            @forelse($clearanceLogs as $clearance)
                                @php
                                    $fillBadgeColor = match(true) {
                                        $clearance->level_before_clearance >= 80 => 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60',
                                        $clearance->level_before_clearance >= 50 => 'bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800/60',
                                        default => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3.5 px-4 font-semibold text-slate-900 dark:text-white">
                                        {{ $clearance->bin ? $clearance->bin->name : 'Waste Storage' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-slate-400">
                                        {{ $clearance->cleared_by_email }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold {{ $fillBadgeColor }}">
                                            {{ $clearance->level_before_clearance }}%
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-emerald-600 dark:text-emerald-400 font-medium">
                                        {{ $clearance->response_time_minutes ? $clearance->response_time_minutes . ' min' : 'Immediate' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-slate-400 dark:text-slate-500 text-right">
                                        {{ $clearance->cleared_at ? $clearance->cleared_at->format('M d, Y · H:i') : 'Just now' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-xs text-slate-400 dark:text-slate-500">No bin clearance events recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- SuperAdmin Confirmation Modal for Clear History -->
        @if(Auth::user()->isSuperAdmin())
        <div x-show="confirmClearModal" 
             @keydown.escape.window="confirmClearModal = false"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 dark:bg-black/75 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-white dark:bg-[#101622] border border-slate-200 dark:border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4"
                 @click.outside="confirmClearModal = false">
                <div class="w-12 h-12 rounded-full bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800/80 flex items-center justify-center text-rose-600 dark:text-rose-400 mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                </div>
                <div class="text-center">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Reset Activity Audit Trail?</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">This action will delete all recorded waste classifications and reset bin fill levels to 0%. This cannot be undone.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button" @click="confirmClearModal = false" class="py-2.5 px-4 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold transition-colors">
                        Cancel
                    </button>
                    <form method="POST" action="{{ route('dashboard.history.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-semibold transition-colors shadow-sm">
                            Yes, Clear
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
