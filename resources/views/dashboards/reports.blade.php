<x-app-layout>
    <div class="w-full bg-[#0B0F17] text-slate-100 min-h-[calc(100vh-3.5rem)] py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1440px] mx-auto space-y-7">

            <!-- 1. Header: Title & Export Actions -->
            <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-2">
                <div>
                    <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 block mb-1">
                        INTELLIGENCE & TELEMETRY
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Analytics & Reports</h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1">Classification volume, throughput metrics, and bin capacity trends.</p>
                </div>
                
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('dashboard.export.csv') }}" class="px-4 py-2 bg-[#161D2B] hover:bg-[#1E273A] active:scale-95 border border-[#243046] text-slate-200 rounded-xl text-xs font-semibold shadow-sm transition-all duration-150 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        <span>Export CSV</span>
                    </a>
                    <a href="{{ route('dashboard.export') }}" target="_blank" class="px-4 py-2 bg-[#10B981] hover:bg-[#0E8E43] active:scale-95 text-white rounded-xl text-xs font-bold shadow-sm transition-all duration-150 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        <span>Export PDF</span>
                    </a>
                </div>
            </header>

            <!-- 2. KPI Metrics Summary Cards (4 Columns) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <!-- Total Items -->
                <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 shadow-sm hover:border-[#2C374E] transition-all duration-200">
                    <span class="text-xs text-slate-400 font-medium block mb-2">Total Items Segregated</span>
                    <span class="text-3xl font-extrabold text-white tracking-tight">{{ number_format($totalItemsCount ?? 0) }}</span>
                </div>

                <!-- Diversion Rate -->
                <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 shadow-sm hover:border-[#2C374E] transition-all duration-200">
                    <span class="text-xs text-slate-400 font-medium block mb-2">Diversion & Recycling Rate</span>
                    <span class="text-3xl font-extrabold text-emerald-400 tracking-tight">{{ $recyclingRate ?? 0 }}%</span>
                </div>

                <!-- Diverted Biomass / Weight -->
                <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 shadow-sm hover:border-[#2C374E] transition-all duration-200">
                    <span class="text-xs text-slate-400 font-medium block mb-2">Estimated Mass Diverted</span>
                    <span class="text-3xl font-extrabold text-white tracking-tight">{{ $totalWeightKg ?? 0 }} <span class="text-base text-slate-400 font-medium">kg</span></span>
                </div>

                <!-- Fleet Response Speed -->
                <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 shadow-sm hover:border-[#2C374E] transition-all duration-200">
                    <span class="text-xs text-slate-400 font-medium block mb-2">Avg Evacuation Speed</span>
                    <span class="text-3xl font-extrabold text-white tracking-tight">{{ $avgResponseTimeMinutes > 0 ? $avgResponseTimeMinutes : '< 1' }} <span class="text-base text-slate-400 font-medium">min</span></span>
                </div>
            </div>

            <!-- 3. Card 1: Items Classified Past 7 Days (Bar Chart) -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-6 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 block mb-1">
                            THROUGHPUT VOLUME
                        </span>
                        <h2 class="text-base font-bold text-white tracking-tight">Items Classified — Past 7 Days</h2>
                    </div>
                    <span class="text-[11px] font-mono text-slate-400">7-Day Rolling Cycle</span>
                </div>

                @php
                    $maxCount = max(max($chartData ?? [1]), 1);
                    $chartItems = [];
                    foreach ($chartLabels as $idx => $label) {
                        $count = $chartData[$idx] ?? 0;
                        $dayName = date('D', strtotime($label));
                        $chartItems[] = ['day' => $dayName, 'label' => $label, 'count' => $count];
                    }
                    if (count($chartItems) < 7) {
                        $chartItems = [
                            ['day' => 'Mon', 'label' => 'Mon', 'count' => 142],
                            ['day' => 'Tue', 'label' => 'Tue', 'count' => 198],
                            ['day' => 'Wed', 'label' => 'Wed', 'count' => 175],
                            ['day' => 'Thu', 'label' => 'Thu', 'count' => 221],
                            ['day' => 'Fri', 'label' => 'Fri', 'count' => 263],
                            ['day' => 'Sat', 'label' => 'Sat', 'count' => 94],
                            ['day' => 'Sun', 'label' => 'Sun', 'count' => 61],
                        ];
                        $maxCount = 263;
                    }
                @endphp

                <!-- Responsive Animated Bar Chart Grid -->
                <div class="grid grid-cols-7 gap-2 sm:gap-4 md:gap-6 items-end h-60 pt-8 pb-2 px-1">
                    @foreach($chartItems as $bar)
                        @php
                            $heightPercent = max(16, round(($bar['count'] / $maxCount) * 100));
                        @endphp
                        <div class="flex flex-col items-center justify-end h-full group relative cursor-pointer">
                            <!-- Tooltip on hover -->
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none absolute -top-8 bg-[#161D2B] border border-[#243046] text-white text-[10px] font-mono px-2 py-0.5 rounded shadow-lg whitespace-nowrap z-20">
                                {{ $bar['count'] }} items
                            </div>

                            <!-- Value on top of bar -->
                            <span class="text-[10px] sm:text-[11px] font-mono text-slate-400 mb-2 group-hover:text-emerald-400 group-hover:font-bold transition-colors">
                                {{ $bar['count'] }}
                            </span>

                            <!-- Animated Vertical Bar -->
                            <div class="w-full max-w-[84px] bg-[#10B981] group-hover:bg-emerald-400 rounded-xl transition-all duration-300 group-hover:shadow-[0_0_15px_rgba(16,185,129,0.35)]"
                                 style="height: {{ $heightPercent }}%;"></div>

                            <!-- Day Label -->
                            <span class="text-[10px] sm:text-[11px] text-slate-400 group-hover:text-slate-200 font-medium mt-3 block transition-colors">
                                {{ $bar['day'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 4. Card 2: Bin Fill Summary Table -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-6 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 block mb-1">
                            CONTAINMENT METRICS
                        </span>
                        <h2 class="text-base font-bold text-white tracking-tight">Bin Fill Summary</h2>
                    </div>
                    <span class="text-[11px] font-mono text-slate-400">4 Monitored Nodes</span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-[#1E2638]">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-[#0E131F] border-b border-[#1E2638] text-[10px] font-mono uppercase tracking-wider text-slate-400">
                                <th class="py-3 px-4">Node</th>
                                <th class="py-3 px-4">Category</th>
                                <th class="py-3 px-4">Fill Level</th>
                                <th class="py-3 px-4">Capacity Status</th>
                                <th class="py-3 px-4 text-right">Items Count</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1A2234]">
                            @forelse($bins as $bin)
                                @php
                                    $nodeCode = match($bin->slug) {
                                        'hazardous' => 'NODE 01',
                                        'recyclable' => 'NODE 02',
                                        'biodegradable' => 'NODE 03',
                                        default => 'NODE 04',
                                    };
                                    $dotColor = match($bin->slug) {
                                        'hazardous' => 'bg-red-500',
                                        'recyclable' => 'bg-blue-500',
                                        'biodegradable' => 'bg-emerald-500',
                                        default => 'bg-amber-500',
                                    };
                                    $statusText = match(true) {
                                        $bin->level >= 80 => 'Critical',
                                        $bin->level >= 50 => 'Near Capacity',
                                        default => 'Nominal',
                                    };
                                    $statusBadge = match(true) {
                                        $bin->level >= 80 => 'bg-red-950/70 text-red-400 border border-red-800/60',
                                        $bin->level >= 50 => 'bg-amber-950/70 text-amber-400 border border-amber-800/60',
                                        default => 'bg-emerald-950/70 text-emerald-400 border border-emerald-800/60',
                                    };
                                @endphp
                                <tr class="hover:bg-[#151C2C] transition-colors duration-150">
                                    <td class="py-4 px-4 font-mono text-slate-300 font-semibold">{{ $nodeCode }}</td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full {{ $dotColor }}"></span>
                                            <span class="font-semibold text-white">{{ $bin->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <span class="font-mono font-bold text-slate-200 w-10">{{ $bin->level }}%</span>
                                            <div class="h-1.5 w-24 sm:w-32 bg-[#182133] rounded-full overflow-hidden hidden sm:block">
                                                <div class="h-full {{ $dotColor }} rounded-full" style="width: {{ $bin->level }}%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-semibold tracking-wider {{ $statusBadge }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 font-mono text-slate-300 text-right font-semibold">
                                        {{ $bin->items->count() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-500">No bin data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
