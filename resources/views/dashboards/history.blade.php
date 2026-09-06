<x-app-layout>
    <div class="w-full bg-[#0B0F17] text-slate-100 min-h-[calc(100vh-3.5rem)] py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1440px] mx-auto space-y-6">

            <!-- 1. Header -->
            <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-2">
                <div>
                    <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 block mb-1">
                        AUDIT TRAIL CONSOLE
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Activity History</h1>
                    <p class="text-xs text-slate-400 mt-1">Verified telemetry records and segregation audit history</p>
                </div>
            </header>

            <!-- 2. Filters Card -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 sm:p-6 shadow-sm">
                <form action="{{ route('dashboard.history') }}" method="GET" id="historyFilterForm" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search Item -->
                        <div>
                            <label for="search" class="block text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 mb-1.5">SEARCH ITEM</label>
                            <input type="text" name="search" id="search" placeholder="Item name..." value="{{ request('search') }}"
                                   @keydown.enter="$el.form.submit()"
                                   class="w-full px-3.5 py-2.5 bg-[#0E131F] border border-[#1E2638] rounded-xl focus:border-emerald-500 outline-none text-xs text-slate-200 placeholder:text-slate-500">
                        </div>

                        <!-- Bin Category -->
                        <div>
                            <label for="bin" class="block text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 mb-1.5">BIN CATEGORY</label>
                            <select name="bin" id="bin" @change="$el.form.submit()" 
                                    class="w-full px-3.5 py-2.5 bg-[#0E131F] border border-[#1E2638] rounded-xl focus:border-emerald-500 outline-none text-xs text-slate-200">
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
                                   class="w-full px-3.5 py-2.5 bg-[#0E131F] border border-[#1E2638] rounded-xl focus:border-emerald-500 outline-none text-xs font-mono text-slate-200">
                        </div>

                        <!-- To Date -->
                        <div>
                            <label for="to_date" class="block text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 mb-1.5">TO DATE</label>
                            <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}"
                                   @change="$el.form.submit()"
                                   class="w-full px-3.5 py-2.5 bg-[#0E131F] border border-[#1E2638] rounded-xl focus:border-emerald-500 outline-none text-xs font-mono text-slate-200">
                        </div>
                    </div>

                    <!-- Filter Actions Row -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('dashboard.export.csv', request()->all()) }}" class="px-4 py-2 bg-[#161D2B] hover:bg-[#1E273A] border border-[#243046] text-slate-200 rounded-xl text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                            <span>Export CSV</span>
                        </a>
                        <a href="{{ route('dashboard.export', request()->all()) }}" target="_blank" class="px-4 py-2 bg-[#10B981] hover:bg-[#0E8E43] text-white rounded-xl text-xs font-bold shadow-sm transition-colors flex items-center gap-1.5">
                            <span>Export PDF</span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- 3. Card 2: Classified Item Stream (Telemetry Ingestion Logs) -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-6 sm:p-8 shadow-sm">
                <div class="mb-5">
                    <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 block mb-1">
                        CLASSIFIED ITEM STREAM
                    </span>
                    <h2 class="text-base font-bold text-white tracking-tight">Telemetry Ingestion Logs</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#1E2638] text-[10px] font-mono uppercase tracking-wider text-slate-400">
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
                                @endphp
                                <tr class="hover:bg-[#151C2C] transition-colors">
                                    <td class="py-3.5 px-4 font-semibold text-slate-200">
                                        {{ $log->name }}
                                    </td>
                                    <td class="py-3.5 px-4 font-medium {{ $categoryColor }}">
                                        {{ $log->bin ? $log->bin->name : 'Unassigned' }}
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

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#1E2638] text-[10px] font-mono uppercase tracking-wider text-slate-400">
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
                                <tr class="hover:bg-[#151C2C] transition-colors">
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
    </div>
</x-app-layout>
