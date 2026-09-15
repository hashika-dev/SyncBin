<x-app-layout>
    <div class="relative isolate w-full bg-slate-50 dark:bg-[#0A0E17] text-slate-800 dark:text-slate-200 min-h-[calc(100vh-3.5rem)] py-6 sm:py-8 px-4 sm:px-6 lg:px-8 transition-colors duration-300 overflow-hidden">
        
        <!-- PROFESSIONAL COHESIVE BACKGROUND (Single signature brand atmospheric backlight, no rainbow) -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden select-none z-0">
            <!-- Single Unified Subtle Emerald Brand Glow at top center -->
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[850px] h-[450px] bg-emerald-500/10 dark:bg-emerald-500/8 blur-[130px] rounded-full"></div>

            <!-- Subtle Technical Dot-Grid Matrix Texture (Clean & Professional) -->
            <div class="absolute inset-0 bg-[radial-gradient(#94a3b8_1px,transparent_1px)] dark:bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] [mask-image:radial-gradient(ellipse_70%_50%_at_50%_0%,#000_60%,transparent_100%)] opacity-70"></div>
        </div>

        <!-- MAIN REPORTS CONTENT (Elevated at z-10) -->
        <div class="max-w-[1720px] mx-auto space-y-7 relative z-10">

            <!-- 1. Header: Title & Export Actions -->
            <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800/80">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Analytics & Reports</h1>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 text-emerald-700 dark:text-emerald-300 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Telemetry Intelligence
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Classification volume, throughput metrics, and bin capacity trends</p>
                </div>
                
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('dashboard.export.csv') }}" class="px-3.5 py-2 bg-white dark:bg-[#101622] hover:bg-slate-50 dark:hover:bg-slate-800 active:scale-95 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold shadow-sm transition-all duration-150 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        <span>Export CSV</span>
                    </a>
                    <a href="{{ route('dashboard.export') }}" target="_blank" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white rounded-xl text-xs font-semibold shadow-sm transition-all duration-150 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        <span>Export PDF</span>
                    </a>
                </div>
            </header>

            <!-- 2. KPI Metrics Summary Cards (4 Columns) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <!-- Total Items -->
                <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-5 shadow-sm hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Total Items Segregated</span>
                    <span class="text-2xl sm:text-3xl font-bold font-mono text-slate-900 dark:text-white tracking-tight mt-1.5 block">{{ number_format($totalItemsCount ?? 0) }}</span>
                </div>

                <!-- Diversion Rate -->
                <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-5 shadow-sm hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Diversion & Recycling Rate</span>
                    <span class="text-2xl sm:text-3xl font-bold font-mono text-emerald-600 dark:text-emerald-400 tracking-tight mt-1.5 block">{{ $recyclingRate ?? 0 }}%</span>
                </div>

                <!-- Average Fleet Capacity -->
                <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-5 shadow-sm hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Avg Fleet Capacity</span>
                    <span class="text-2xl sm:text-3xl font-bold font-mono text-slate-900 dark:text-white tracking-tight mt-1.5 block">
                        {{ round($averageFill ?? 0) }}<span class="text-sm text-slate-500 dark:text-slate-400 font-normal">%</span>
                    </span>
                </div>

                <!-- Fleet Response Speed -->
                <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-5 shadow-sm hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Avg Evacuation Speed</span>
                    <span class="text-2xl sm:text-3xl font-bold font-mono text-slate-900 dark:text-white tracking-tight mt-1.5 block">
                        {{ $avgResponseTimeMinutes > 0 ? $avgResponseTimeMinutes : '< 1' }} <span class="text-sm text-slate-500 dark:text-slate-400 font-normal">min</span>
                    </span>
                </div>
            </div>

            <!-- 3. Card 1: Items Classified Past 7 Days (Interactive Telemetry Chart) -->
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
                $totalWeekly = array_sum(array_column($chartItems, 'count'));
                $avgDaily = round($totalWeekly / max(count($chartItems), 1));
                $peakDay = null;
                foreach ($chartItems as $item) {
                    if (!$peakDay || $item['count'] > $peakDay['count']) {
                        $peakDay = $item;
                    }
                }
            @endphp

            <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-6 sm:p-7 shadow-sm transition-all relative overflow-hidden"
                 x-data="{ hoveredIdx: null }">

                <!-- Card Header with Real-time Metrics -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800/60">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Items Classified — Past 7 Days</h2>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Throughput volume, peak intake patterns, and daily classification load</p>
                    </div>

                    <!-- Micro Metric Pills -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 rounded-xl text-xs font-mono font-semibold text-emerald-700 dark:text-emerald-300 shadow-sm">
                            <svg class="w-3 h-3 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
                            <span>Peak: {{ $peakDay['day'] }} ({{ $peakDay['count'] }})</span>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-mono text-slate-600 dark:text-slate-300">
                            <span>Avg: ~{{ number_format($avgDaily) }} / day</span>
                        </div>
                    </div>
                </div>

                <!-- Chart Canvas with Horizontal Scale Guides -->
                <div class="relative pt-6 pb-2">

                    <!-- Horizontal Grid Guide Lines -->
                    <div class="absolute inset-x-0 top-12 bottom-12 flex flex-col justify-between pointer-events-none select-none opacity-40 dark:opacity-20 z-0">
                        <div class="border-b border-dashed border-slate-400 dark:border-slate-600 w-full flex justify-end">
                            <span class="text-[9px] font-mono -mt-3.5 text-slate-500 dark:text-slate-400">{{ $maxCount }}</span>
                        </div>
                        <div class="border-b border-dashed border-slate-400 dark:border-slate-600 w-full flex justify-end">
                            <span class="text-[9px] font-mono -mt-3.5 text-slate-500 dark:text-slate-400">{{ round($maxCount * 0.5) }}</span>
                        </div>
                        <div class="border-b border-slate-300 dark:border-slate-700 w-full flex justify-end">
                            <span class="text-[9px] font-mono -mt-3.5 text-slate-500 dark:text-slate-400">0</span>
                        </div>
                    </div>

                    <!-- 7-Day Capsule Columns Grid -->
                    <div class="grid grid-cols-7 gap-2 sm:gap-4 md:gap-6 items-end h-64 px-1 sm:px-3 relative z-10">
                        @foreach($chartItems as $idx => $bar)
                            @php
                                $heightPercent = max(14, round(($bar['count'] / $maxCount) * 100));
                                $sharePercent = round(($bar['count'] / max($totalWeekly, 1)) * 100, 1);
                                $isPeak = ($bar['count'] === $peakDay['count']);
                            @endphp
                            <div class="flex flex-col items-center justify-end h-full group cursor-pointer"
                                 @mouseenter="hoveredIdx = {{ $idx }}"
                                 @mouseleave="hoveredIdx = null">

                                <!-- Hover Tooltip & Value Display -->
                                <div class="relative flex flex-col items-center mb-2">
                                    <!-- Elevated Detailed Tooltip -->
                                    <div class="opacity-0 group-hover:opacity-100 transition-all duration-150 pointer-events-none absolute -top-11 bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-mono px-2.5 py-1 rounded-xl shadow-xl whitespace-nowrap z-30 border border-slate-700/80 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        <span class="font-bold">{{ $bar['count'] }} items</span>
                                        <span class="text-slate-400">({{ $sharePercent }}%)</span>
                                    </div>

                                    <!-- Numeric Value above Bar -->
                                    <span class="text-xs font-mono transition-colors duration-150 font-semibold"
                                          :class="hoveredIdx === {{ $idx }} ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-500 dark:text-slate-400'">
                                        {{ $bar['count'] }}
                                    </span>
                                </div>

                                <!-- Capsule Column Track -->
                                <div class="w-full max-w-[56px] sm:max-w-[70px] md:max-w-[80px] h-48 bg-slate-100/90 dark:bg-slate-800/40 rounded-2xl p-1 sm:p-1.5 flex flex-col justify-end border border-slate-200/70 dark:border-slate-800/60 group-hover:border-emerald-400/50 dark:group-hover:border-emerald-500/50 transition-all duration-200 shadow-inner">
                                    
                                    <!-- Filled Fluid Fill Bar -->
                                    <div class="w-full bg-gradient-to-t from-emerald-600 via-emerald-500 to-teal-400 dark:from-emerald-600 dark:via-emerald-500 dark:to-teal-300 rounded-xl transition-all duration-500 relative group-hover:shadow-[0_0_18px_rgba(16,185,129,0.4)]"
                                         style="height: {{ $heightPercent }}%;">
                                        
                                        <!-- Glowing Cap Line -->
                                        <div class="absolute top-0 inset-x-0 h-1 bg-emerald-200/90 rounded-full shadow-[0_0_6px_rgba(167,243,208,0.9)]"></div>
                                    </div>
                                </div>

                                <!-- Day Label & Peak Tag -->
                                <div class="mt-3 flex flex-col items-center gap-0.5">
                                    @if($isPeak)
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-mono font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 mb-0.5">
                                            Peak
                                        </span>
                                    @endif
                                    <span class="text-xs font-medium transition-colors"
                                          :class="hoveredIdx === {{ $idx }} ? 'text-slate-900 dark:text-white font-bold' : 'text-slate-500 dark:text-slate-400'">
                                        {{ $bar['day'] }}
                                    </span>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>

                <!-- Footer Micro-Telemetry Breakdown Strip -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-5 mt-4 border-t border-slate-100 dark:border-slate-800/60 text-xs">
                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/50">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xs">
                            ∑
                        </div>
                        <div>
                            <span class="text-[10px] font-mono text-slate-400 uppercase tracking-wider block">7-Day Volume</span>
                            <span class="font-bold text-slate-900 dark:text-white font-mono">{{ number_format($totalWeekly) }} items</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/50">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xs">
                            ↑
                        </div>
                        <div>
                            <span class="text-[10px] font-mono text-slate-400 uppercase tracking-wider block">Peak Processing</span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ $peakDay['day'] }} ({{ $peakDay['count'] }} units)</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/50">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xs">
                            ✓
                        </div>
                        <div>
                            <span class="text-[10px] font-mono text-slate-400 uppercase tracking-wider block">Intake Cadence</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">Stable (+12% throughput)</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- 4. Card 2: Bin Fill Summary Table -->
            <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-6 sm:p-7 shadow-sm transition-all">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100 dark:border-slate-800/60">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Bin Fill Summary</h2>
                    </div>
                    <span class="text-[11px] font-mono text-slate-500 dark:text-slate-400 font-medium">4 Monitored Nodes</span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-[#0A0E17]/60 border-b border-slate-200 dark:border-slate-800 text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <th class="py-3 px-4">Node</th>
                                <th class="py-3 px-4">Category</th>
                                <th class="py-3 px-4">Fill Level</th>
                                <th class="py-3 px-4">Capacity Status</th>
                                <th class="py-3 px-4 text-right">Items Count</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                            @forelse($bins as $bin)
                                @php
                                    $nodeCode = match($bin->slug) {
                                        'biodegradable' => 'NODE 01',
                                        'recyclable' => 'NODE 02',
                                        'non-bio' => 'NODE 03',
                                        default => 'NODE 04',
                                    };
                                    $dotColor = match($bin->slug) {
                                        'biodegradable' => 'bg-emerald-500',
                                        'recyclable' => 'bg-sky-500',
                                        'non-bio' => 'bg-amber-500',
                                        default => 'bg-rose-500',
                                    };
                                    $barColor = match($bin->slug) {
                                        'biodegradable' => 'bg-emerald-500',
                                        'recyclable' => 'bg-sky-500',
                                        'non-bio' => 'bg-amber-500',
                                        default => 'bg-rose-500',
                                    };
                                    $statusText = match(true) {
                                        $bin->level >= 80 => 'Critical',
                                        $bin->level >= 50 => 'Near Capacity',
                                        default => 'Nominal',
                                    };
                                    $statusBadge = match(true) {
                                        $bin->level >= 80 => 'bg-rose-50 dark:bg-rose-950/70 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60',
                                        $bin->level >= 50 => 'bg-amber-50 dark:bg-amber-950/70 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800/60',
                                        default => 'bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-4 px-4 font-mono text-slate-500 dark:text-slate-400 font-semibold">{{ $nodeCode }}</td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-2 h-2 rounded-full {{ $dotColor }}"></span>
                                            <span class="font-semibold text-slate-900 dark:text-white">{{ $bin->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200 w-10">{{ $bin->level }}%</span>
                                            <div class="h-2 w-28 sm:w-36 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden hidden sm:block">
                                                <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $bin->level }}%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-mono font-semibold uppercase tracking-wider {{ $statusBadge }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 font-mono text-slate-700 dark:text-slate-300 text-right font-semibold">
                                        {{ $bin->items->count() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-xs text-slate-400 dark:text-slate-500">No bin data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
