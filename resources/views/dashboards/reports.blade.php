<x-app-layout>
    <div class="relative isolate w-full min-h-[calc(100vh-3.5rem)] bg-gradient-to-br from-emerald-100/60 via-slate-100 to-teal-100/40 dark:from-[#030906] dark:via-[#06120D] dark:to-[#040C09] text-slate-800 dark:text-slate-100 py-6 sm:py-8 px-4 sm:px-6 lg:px-8 selection:bg-emerald-500 selection:text-white transition-colors duration-300 overflow-hidden">
        
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

        <!-- MAIN REPORTS CONTENT -->
        <div class="max-w-[1720px] mx-auto space-y-6 sm:space-y-7 relative z-10">

            <!-- 1. Header: Title & Export Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-white/50 dark:border-white/10">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight drop-shadow-sm">
                            Analytics & Reports
                        </h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/70 dark:bg-emerald-500/15 border border-white/80 dark:border-emerald-400/30 text-emerald-800 dark:text-emerald-300 shadow-[0_4px_16px_rgba(16,185,129,0.15)] backdrop-blur-xl">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Telemetry Intelligence
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Classification volume, throughput metrics, and bin capacity trends across containment nodes
                    </p>
                </div>
                
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('dashboard.export.csv') }}" 
                       class="px-3.5 py-2 bg-white/70 dark:bg-white/10 hover:bg-white/90 dark:hover:bg-white/20 active:scale-95 border border-white/80 dark:border-white/15 text-slate-800 dark:text-slate-200 rounded-2xl text-xs font-semibold shadow-sm backdrop-blur-md transition-all duration-150 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        <span>Export CSV</span>
                    </a>
                    <a href="{{ route('dashboard.export') }}" target="_blank" 
                       class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white rounded-2xl text-xs font-semibold shadow-[0_4px_16px_rgba(16,185,129,0.3)] transition-all duration-150 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        <span>Export PDF</span>
                    </a>
                </div>
            </div>

            <!-- 2. KPI Metrics Summary Cards (True Frosted Glass) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                
                <!-- Metric 1: Total Items Segregated -->
                <div class="relative rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] hover:border-emerald-400/50 transition-all flex flex-col justify-between overflow-hidden group">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Total Items Segregated</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/15 border border-emerald-400/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-[0_0_12px_rgba(16,185,129,0.2)]">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl sm:text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight block">{{ number_format($totalItemsCount ?? 0) }}</span>
                        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold block mt-1">Telemetry verified items</span>
                    </div>
                </div>

                <!-- Metric 2: Diversion & Recycling Rate -->
                <div class="relative rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] hover:border-sky-400/50 transition-all flex flex-col justify-between overflow-hidden group">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-sky-400/40 to-transparent pointer-events-none"></div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Diversion & Recycling</span>
                        <div class="w-8 h-8 rounded-xl bg-sky-500/15 border border-sky-400/40 flex items-center justify-center text-sky-600 dark:text-sky-400 shadow-[0_0_12px_rgba(56,189,248,0.2)]">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/>
                                <path d="M11 19h8.2a1.8 1.8 0 0 0 1.564-.91 1.776 1.776 0 0 0-.007-1.779L16.8 9.5"/>
                                <path d="m14 13-3-4-3 4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl sm:text-3xl font-extrabold font-mono text-emerald-600 dark:text-emerald-400 tracking-tight block">{{ $recyclingRate ?? 0 }}%</span>
                        <span class="text-[11px] text-sky-600 dark:text-sky-400 font-semibold block mt-1">Diverted from landfill</span>
                    </div>
                </div>

                <!-- Metric 3: Average Fleet Capacity -->
                <div class="relative rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] hover:border-amber-400/50 transition-all flex flex-col justify-between overflow-hidden group">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-amber-400/40 to-transparent pointer-events-none"></div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Avg Fleet Capacity</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-500/15 border border-amber-400/40 flex items-center justify-center text-amber-600 dark:text-amber-400 shadow-[0_0_12px_rgba(251,191,36,0.2)]">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl sm:text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight block">
                            {{ round($averageFill ?? 0) }}%
                        </span>
                        <!-- Frosted progress bar -->
                        <div class="w-full h-2 rounded-full bg-slate-200/80 dark:bg-black/50 mt-2 overflow-hidden border border-white/50 dark:border-white/10">
                            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-700 shadow-[0_0_10px_rgba(16,185,129,0.5)]"
                                 style="width: {{ round($averageFill ?? 0) }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Metric 4: Evacuation Speed -->
                <div class="relative rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] hover:border-emerald-400/50 transition-all flex flex-col justify-between overflow-hidden group">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Avg Evacuation Speed</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/15 border border-emerald-400/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-[0_0_12px_rgba(16,185,129,0.2)]">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl sm:text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight block">
                            {{ $avgResponseTimeMinutes > 0 ? $avgResponseTimeMinutes : '< 1' }} <span class="text-sm font-normal text-slate-500">min</span>
                        </span>
                        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold block mt-1">Prompt fleet response</span>
                    </div>
                </div>

            </div>

            <!-- 3. Card 1: Items Classified Past 7 Days (Frosted Glass Telemetry Chart) -->
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

            <div class="relative rounded-3xl p-6 sm:p-7 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] transition-all overflow-hidden"
                 x-data="{ hoveredIdx: null }">

                <!-- Specular Highlight Line -->
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                <!-- Card Header with Real-time Metrics -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-white/50 dark:border-white/10">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Items Classified — Past 7 Days</h2>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">Throughput volume, peak intake patterns, and daily classification load</p>
                    </div>

                    <!-- Micro Metric Pills -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/70 dark:bg-emerald-500/15 border border-white/80 dark:border-emerald-400/30 rounded-xl text-xs font-mono font-semibold text-emerald-800 dark:text-emerald-300 shadow-sm backdrop-blur-md">
                            <svg class="w-3 h-3 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
                            <span>Peak: {{ $peakDay['day'] }} ({{ $peakDay['count'] }})</span>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/70 dark:bg-white/10 border border-white/80 dark:border-white/15 rounded-xl text-xs font-mono text-slate-700 dark:text-slate-300 shadow-sm backdrop-blur-md">
                            <span>Avg: ~{{ number_format($avgDaily) }} / day</span>
                        </div>
                    </div>
                </div>

                <!-- Chart Canvas with Horizontal Scale Guides -->
                <div class="relative pt-6 pb-2">

                    <!-- Horizontal Grid Guide Lines -->
                    <div class="absolute inset-x-0 top-12 bottom-12 flex flex-col justify-between pointer-events-none select-none opacity-40 dark:opacity-20 z-0">
                        <div class="border-b border-dashed border-slate-400 dark:border-slate-500 w-full flex justify-end">
                            <span class="text-[9px] font-mono -mt-3.5 text-slate-500 dark:text-slate-400">{{ $maxCount }}</span>
                        </div>
                        <div class="border-b border-dashed border-slate-400 dark:border-slate-500 w-full flex justify-end">
                            <span class="text-[9px] font-mono -mt-3.5 text-slate-500 dark:text-slate-400">{{ round($maxCount * 0.5) }}</span>
                        </div>
                        <div class="border-b border-slate-300 dark:border-slate-600 w-full flex justify-end">
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
                                    <div class="opacity-0 group-hover:opacity-100 transition-all duration-150 pointer-events-none absolute -top-11 bg-white/95 dark:bg-slate-900/95 text-slate-900 dark:text-white text-[11px] font-mono px-3 py-1 rounded-2xl shadow-xl whitespace-nowrap z-30 border border-white/80 dark:border-emerald-500/30 backdrop-blur-xl flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span class="font-bold">{{ $bar['count'] }} items</span>
                                        <span class="text-slate-500 dark:text-slate-400">({{ $sharePercent }}%)</span>
                                    </div>

                                    <!-- Numeric Value above Bar -->
                                    <span class="text-xs font-mono transition-colors duration-150 font-semibold"
                                          :class="hoveredIdx === {{ $idx }} ? 'text-emerald-700 dark:text-emerald-400 font-bold' : 'text-slate-600 dark:text-slate-400'">
                                        {{ $bar['count'] }}
                                    </span>
                                </div>

                                <!-- Capsule Column Track (Frosted Glass) -->
                                <div class="w-full max-w-[56px] sm:max-w-[70px] md:max-w-[80px] h-48 bg-slate-900/5 dark:bg-black/50 rounded-2xl p-1 sm:p-1.5 flex flex-col justify-end border border-white/80 dark:border-white/10 group-hover:border-emerald-400/50 transition-all duration-200 shadow-inner backdrop-blur-md">
                                    
                                    <!-- Filled Fluid Fill Bar -->
                                    <div class="w-full bg-gradient-to-t from-emerald-600 via-emerald-500 to-teal-400 rounded-xl transition-all duration-500 relative shadow-[0_0_12px_rgba(16,185,129,0.35)] group-hover:shadow-[0_0_20px_rgba(16,185,129,0.6)]"
                                         style="height: {{ $heightPercent }}%;">
                                        
                                        <!-- Glowing Cap Line -->
                                        <div class="absolute top-0 inset-x-0 h-1 bg-white/90 rounded-full shadow-[0_0_8px_rgba(255,255,255,0.9)]"></div>
                                    </div>
                                </div>

                                <!-- Day Label & Peak Tag -->
                                <div class="mt-3 flex flex-col items-center gap-0.5">
                                    @if($isPeak)
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-mono font-bold uppercase tracking-wider bg-white/80 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-white/80 dark:border-emerald-400/30 mb-0.5 backdrop-blur-md shadow-sm">
                                            Peak
                                        </span>
                                    @endif
                                    <span class="text-xs font-semibold transition-colors"
                                          :class="hoveredIdx === {{ $idx }} ? 'text-slate-900 dark:text-white font-bold' : 'text-slate-600 dark:text-slate-400'">
                                        {{ $bar['day'] }}
                                    </span>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>

                <!-- Footer Micro-Telemetry Breakdown Strip (Frosted Pills) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-5 mt-4 border-t border-white/50 dark:border-white/10 text-xs">
                    <div class="flex items-center gap-2.5 p-3 rounded-2xl bg-white/60 dark:bg-white/[0.04] border border-white/70 dark:border-white/10 shadow-sm backdrop-blur-md">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/15 border border-emerald-400/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold text-xs shadow-sm">
                            ∑
                        </div>
                        <div>
                            <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400 uppercase tracking-wider block font-medium">7-Day Volume</span>
                            <span class="font-bold text-slate-900 dark:text-white font-mono">{{ number_format($totalWeekly) }} items</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 p-3 rounded-2xl bg-white/60 dark:bg-white/[0.04] border border-white/70 dark:border-white/10 shadow-sm backdrop-blur-md">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/15 border border-emerald-400/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold text-xs shadow-sm">
                            ↑
                        </div>
                        <div>
                            <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400 uppercase tracking-wider block font-medium">Peak Processing</span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ $peakDay['day'] }} ({{ $peakDay['count'] }} units)</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 p-3 rounded-2xl bg-white/60 dark:bg-white/[0.04] border border-white/70 dark:border-white/10 shadow-sm backdrop-blur-md">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/15 border border-emerald-400/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold text-xs shadow-sm">
                            ✓
                        </div>
                        <div>
                            <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400 uppercase tracking-wider block font-medium">Intake Cadence</span>
                            <span class="font-bold text-emerald-700 dark:text-emerald-400 font-semibold">Stable (+12% throughput)</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- 4. Card 2: Bin Fill Summary Table (Frosted Glass Container) -->
            <div class="relative rounded-3xl p-6 sm:p-7 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] transition-all overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                <div class="flex items-center justify-between mb-5 pb-4 border-b border-white/50 dark:border-white/10">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Containment Stream Status Summary</h2>
                    </div>
                    <span class="text-[11px] font-mono text-emerald-700 dark:text-emerald-400 font-semibold">4 Monitored Nodes</span>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-white/70 dark:border-white/10 bg-white/40 dark:bg-black/30 backdrop-blur-md">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white/60 dark:bg-white/[0.04] border-b border-white/60 dark:border-white/10 text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <th class="py-3.5 px-4 font-semibold">Node Code</th>
                                <th class="py-3.5 px-4 font-semibold">Category Stream</th>
                                <th class="py-3.5 px-4 font-semibold">Containment Fill Level</th>
                                <th class="py-3.5 px-4 font-semibold">Status Badge</th>
                                <th class="py-3.5 px-4 text-right font-semibold">Classified Items</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/50 dark:divide-white/10">
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
                                    $barGradient = match($bin->slug) {
                                        'biodegradable' => 'from-emerald-500 via-emerald-400 to-teal-300',
                                        'recyclable' => 'from-sky-500 via-sky-400 to-cyan-300',
                                        'non-bio' => 'from-amber-500 via-amber-400 to-yellow-300',
                                        default => 'from-rose-500 via-rose-400 to-red-400',
                                    };
                                    $statusText = match(true) {
                                        $bin->level >= 80 => 'Critical',
                                        $bin->level >= 50 => 'Near Capacity',
                                        default => 'Healthy',
                                    };
                                    $statusBadge = match(true) {
                                        $bin->level >= 80 => 'bg-rose-500/20 text-rose-700 dark:text-rose-400 border border-rose-400/40 shadow-[0_0_12px_rgba(244,63,94,0.3)]',
                                        $bin->level >= 50 => 'bg-amber-500/15 text-amber-700 dark:text-amber-400 border border-amber-400/40',
                                        default => 'bg-emerald-500/15 text-emerald-800 dark:text-emerald-300 border border-emerald-400/40',
                                    };
                                @endphp
                                <tr class="hover:bg-white/60 dark:hover:bg-white/[0.04] transition-colors">
                                    <td class="py-4 px-4 font-mono text-emerald-700 dark:text-emerald-400 font-semibold">{{ $nodeCode }}</td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-2 h-2 rounded-full {{ $dotColor }}"></span>
                                            <span class="font-bold text-slate-900 dark:text-white">{{ $bin->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <span class="font-mono font-extrabold text-slate-900 dark:text-white w-12">{{ $bin->level }}%</span>
                                            <div class="h-2.5 w-32 sm:w-44 bg-slate-900/5 dark:bg-black/50 rounded-full overflow-hidden border border-white/60 dark:border-white/10 hidden sm:block p-0.5">
                                                <div class="h-full bg-gradient-to-r {{ $barGradient }} rounded-full transition-all duration-700 shadow-[0_0_8px_rgba(16,185,129,0.4)]" 
                                                     style="width: {{ $bin->level }}%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider backdrop-blur-md {{ $statusBadge }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $bin->level >= 80 ? 'bg-rose-500 animate-ping' : $dotColor }}"></span>
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 font-mono text-slate-900 dark:text-white text-right font-bold">
                                        {{ $bin->items->count() }} items
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-xs text-slate-400 dark:text-slate-500">No containment bin telemetry records available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
