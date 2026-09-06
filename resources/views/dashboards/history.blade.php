<x-app-layout>
    <div class="w-full bg-[#0B0F17] text-slate-100 min-h-[calc(100vh-3.5rem)] py-8 px-4 sm:px-6 lg:px-8"
         x-data="{ confirmClearModal: false }">
        <div class="max-w-[1440px] mx-auto space-y-6">

            <!-- 1. Header & Actions -->
            <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-2">
                <div>
                    <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 block mb-1">
                        AUDIT TRAIL CONSOLE
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Activity History</h1>
                    <p class="text-xs text-slate-400 mt-1">Verified telemetry records, segregation event timeline, and fleet response log.</p>
                </div>

                @if(Auth::user()->isSuperAdmin())
                <div class="flex items-center gap-3">
                    <button @click="confirmClearModal = true" type="button" class="px-4 py-2 bg-rose-950/40 hover:bg-rose-900/60 active:scale-95 border border-rose-800/60 text-rose-300 rounded-xl text-xs font-semibold shadow-sm transition-all duration-150 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        <span>Clear History</span>
                    </button>
                </div>
                @endif
            </header>

            <!-- Status Alerts -->
            @if (session('status'))
                <div class="p-3.5 bg-emerald-950/60 border border-emerald-800/80 rounded-xl flex items-center gap-3 text-emerald-400 text-xs font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- 2. Filters & Export Card -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 sm:p-6 shadow-sm">
                <form action="{{ route('dashboard.history') }}" method="GET" id="historyFilterForm" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search Item -->
                        <div>
                            <label for="search" class="block text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 mb-1.5">SEARCH ITEM</label>
                            <input type="text" name="search" id="search" placeholder="Item name..." value="{{ request('search') }}"
                                   @keydown.enter="$el.form.submit()"
                                   class="w-full px-3.5 py-2.5 bg-[#0E131F] border border-[#1E2638] rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 outline-none text-xs text-slate-200 placeholder:text-slate-500 transition-all">
                        </div>

                        <!-- Bin Category -->
                        <div>
                            <label for="bin" class="block text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 mb-1.5">BIN CATEGORY</label>
                            <select name="bin" id="bin" @change="$el.form.submit()" 
                                    class="w-full px-3.5 py-2.5 bg-[#0E131F] border border-[#1E2638] rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 outline-none text-xs text-slate-200 transition-all">
                                <option value="">All Categories</option>
                                @foreach($bins as $b)
                                    <option value="{{ $b->slug }}" {{ request('bin') == $b->slug ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- From Date -->
                        <div>
                            <label for="from_date" class="block text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 mb-1.5">FROM DATE</label>
                            <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}"
                                   @change="$el.form.submit()"
                                   class="w-full px-3.5 py-2.5 bg-[#0E131F] border border-[#1E2638] rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 outline-none text-xs font-mono text-slate-200 transition-all">
                        </div>

                        <!-- To Date -->
                        <div>
                            <label for="to_date" class="block text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 mb-1.5">TO DATE</label>
                            <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}"
                                   @change="$el.form.submit()"
                                   class="w-full px-3.5 py-2.5 bg-[#0E131F] border border-[#1E2638] rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 outline-none text-xs font-mono text-slate-200 transition-all">
                        </div>
                    </div>

                    <!-- Quick Range Shortcuts & Filter Actions Row -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 border-t border-[#1A2234]">
                        <!-- Quick Range Buttons -->
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1">
                            <span class="text-[10px] font-mono uppercase text-slate-400 mr-1 shrink-0">Quick:</span>
                            <a href="{{ route('dashboard.history', array_merge(request()->except(['quick_range', 'page', 'from_date', 'to_date']), ['quick_range' => 'today'])) }}"
                               class="px-2.5 py-1 rounded-lg text-[11px] font-medium transition-colors shrink-0 {{ request('quick_range') === 'today' ? 'bg-[#1C2638] text-white border border-[#2B3A52]' : 'text-slate-400 hover:text-white bg-[#0E131F]' }}">
                                Today
                            </a>
                            <a href="{{ route('dashboard.history', array_merge(request()->except(['quick_range', 'page', 'from_date', 'to_date']), ['quick_range' => '7days'])) }}"
                               class="px-2.5 py-1 rounded-lg text-[11px] font-medium transition-colors shrink-0 {{ request('quick_range') === '7days' ? 'bg-[#1C2638] text-white border border-[#2B3A52]' : 'text-slate-400 hover:text-white bg-[#0E131F]' }}">
                                Past 7 Days
                            </a>
                            <a href="{{ route('dashboard.history', array_merge(request()->except(['quick_range', 'page', 'from_date', 'to_date']), ['quick_range' => '30days'])) }}"
                               class="px-2.5 py-1 rounded-lg text-[11px] font-medium transition-colors shrink-0 {{ request('quick_range') === '30days' ? 'bg-[#1C2638] text-white border border-[#2B3A52]' : 'text-slate-400 hover:text-white bg-[#0E131F]' }}">
                                Past 30 Days
                            </a>
                            @if(request()->hasAny(['search', 'bin', 'from_date', 'to_date', 'quick_range']))
                                <a href="{{ route('dashboard.history') }}" class="px-2.5 py-1 rounded-lg text-[11px] font-medium text-amber-400 hover:text-amber-300 hover:bg-amber-950/30 transition-colors shrink-0 flex items-center gap-1">
                                    <span>&times; Clear</span>
                                </a>
                            @endif
                        </div>

                        <!-- Export Buttons -->
                        <div class="flex items-center gap-2.5 shrink-0">
                            <a href="{{ route('dashboard.export.csv', request()->all()) }}" class="px-3.5 py-1.5 bg-[#161D2B] hover:bg-[#1E273A] active:scale-95 border border-[#243046] text-slate-200 rounded-xl text-xs font-semibold shadow-sm transition-all duration-150 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                <span>Export CSV</span>
                            </a>
                            <a href="{{ route('dashboard.export', request()->all()) }}" target="_blank" class="px-3.5 py-1.5 bg-[#10B981] hover:bg-[#0E8E43] active:scale-95 text-white rounded-xl text-xs font-bold shadow-sm transition-all duration-150 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <span>Export PDF</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 3. Card 2: Classified Item Stream (Telemetry Ingestion Logs) -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-6 sm:p-8 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 block mb-1">
                            CLASSIFIED ITEM STREAM
                        </span>
                        <h2 class="text-base font-bold text-white tracking-tight">Telemetry Ingestion Logs</h2>
                    </div>
                    <span class="text-[11px] font-mono text-slate-400">{{ $logs->total() }} Events</span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-[#1E2638]">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-[#0E131F] border-b border-[#1E2638] text-[10px] font-mono uppercase tracking-wider text-slate-400">
                                <th class="py-3 px-4">ITEM</th>
                                <th class="py-3 px-4">CATEGORY</th>
                                <th class="py-3 px-4">WEIGHT</th>
                                <th class="py-3 px-4 text-right">TIME</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1A2234]">
                            @forelse($logs as $log)
                                @php
                                    $categoryColor = match($log->bin?->slug) {
                                        'biodegradable' => 'text-emerald-400',
                                        'recyclable' => 'text-blue-400',
                                        'hazardous' => 'text-red-400',
                                        default => 'text-amber-400',
                                    };
                                    $dotColor = match($log->bin?->slug) {
                                        'biodegradable' => 'bg-emerald-500',
                                        'recyclable' => 'bg-blue-500',
                                        'hazardous' => 'bg-red-500',
                                        default => 'bg-amber-500',
                                    };
                                @endphp
                                <tr class="hover:bg-[#151C2C] transition-colors duration-150">
                                    <td class="py-3.5 px-4 font-semibold text-slate-200">
                                        <div class="flex items-center gap-2">
                                            @if($log->icon)
                                                <span class="text-sm">{{ $log->icon }}</span>
                                            @endif
                                            <span>{{ $log->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-medium {{ $categoryColor }}">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                            <span>{{ $log->bin ? $log->bin->name : 'Unassigned' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-slate-400">
                                        {{ $log->weight ?? '~45g' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-slate-500 text-right">
                                        {{ $log->created_at ? $log->created_at->diffForHumans() : 'Just now' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-500">No activity logs recorded matching criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="pt-5 mt-4 border-t border-[#1E2638]">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

            <!-- 4. Card 3: Maintenance Dispatch (Evacuation Audit Trail) -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-6 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 block mb-1">
                            MAINTENANCE DISPATCH
                        </span>
                        <h2 class="text-base font-bold text-white tracking-tight">Evacuation Audit Trail</h2>
                    </div>

                    <span class="px-3 py-1 bg-[#161D2B] border border-[#243046] text-slate-300 text-[11px] font-mono rounded-lg">
                        Avg Fleet Response: <strong class="text-emerald-400">{{ $avgResponseTimeMinutes > 0 ? $avgResponseTimeMinutes . ' mins' : 'Immediate' }}</strong>
                    </span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-[#1E2638]">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-[#0E131F] border-b border-[#1E2638] text-[10px] font-mono uppercase tracking-wider text-slate-400">
                                <th class="py-3 px-4">BIN NODE</th>
                                <th class="py-3 px-4">OPERATOR</th>
                                <th class="py-3 px-4">PRE-CLEAR FILL</th>
                                <th class="py-3 px-4">RESPONSE SPEED</th>
                                <th class="py-3 px-4 text-right">CLEARED AT</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1A2234]">
                            @forelse($clearanceLogs as $clearance)
                                @php
                                    $fillBadgeColor = match(true) {
                                        $clearance->level_before_clearance >= 80 => 'bg-red-950/60 text-red-400 border border-red-800/60',
                                        $clearance->level_before_clearance >= 50 => 'bg-amber-950/60 text-amber-400 border border-amber-800/60',
                                        default => 'bg-emerald-950/60 text-emerald-400 border border-emerald-800/60',
                                    };
                                @endphp
                                <tr class="hover:bg-[#151C2C] transition-colors duration-150">
                                    <td class="py-3.5 px-4 font-semibold text-white">
                                        {{ $clearance->bin ? $clearance->bin->name : 'Waste Storage' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-slate-400">
                                        {{ $clearance->cleared_by_email }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold {{ $fillBadgeColor }}">
                                            {{ $clearance->level_before_clearance }}%
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-emerald-400 font-medium">
                                        {{ $clearance->response_time_minutes ? $clearance->response_time_minutes . ' min' : 'Immediate' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-slate-400 text-right">
                                        {{ $clearance->cleared_at ? $clearance->cleared_at->format('M d, Y · H:i') : 'Just now' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-500">No bin clearance events recorded.</td>
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
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            <div class="bg-[#111622] border border-red-900/60 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4"
                 @click.outside="confirmClearModal = false">
                <div class="w-12 h-12 rounded-full bg-red-950/60 border border-red-800/80 flex items-center justify-center text-red-400 mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                </div>
                <div class="text-center">
                    <h3 class="text-base font-bold text-white">Reset Activity Audit Trail?</h3>
                    <p class="text-xs text-slate-400 mt-1">This action will delete all recorded waste classifications and reset bin fill levels to 0%. This cannot be undone.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button" @click="confirmClearModal = false" class="py-2.5 px-4 bg-[#161D2B] hover:bg-[#1E273A] text-slate-300 rounded-xl text-xs font-semibold transition-colors">
                        Cancel
                    </button>
                    <form method="POST" action="{{ route('dashboard.history.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-500 text-white rounded-xl text-xs font-bold transition-colors shadow-sm">
                            Yes, Clear
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
