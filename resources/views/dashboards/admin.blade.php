@php
    $definitions = [
        'biodegradable' => [
            'name' => 'Biodegradable',
            'node' => 'NODE 01',
            'subtitle' => 'Organic & food waste',
            'color' => 'emerald',
            'dot' => 'bg-emerald-500',
            'bar' => 'from-emerald-500 via-emerald-400 to-teal-300',
            'glow' => 'rgba(16,185,129,0.4)',
            'iconBg' => 'bg-emerald-500/15 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border-emerald-400/30',
        ],
        'recyclable' => [
            'name' => 'Recyclable',
            'node' => 'NODE 02',
            'subtitle' => 'Plastics, paper, glass, cans',
            'color' => 'sky',
            'dot' => 'bg-sky-500',
            'bar' => 'from-sky-500 via-sky-400 to-cyan-300',
            'glow' => 'rgba(56,189,248,0.4)',
            'iconBg' => 'bg-sky-500/15 dark:bg-sky-500/20 text-sky-600 dark:text-sky-400 border-sky-400/30',
        ],
        'non-bio' => [
            'name' => 'Non-Biodegradable',
            'node' => 'NODE 03',
            'subtitle' => 'Residual & non-recyclable',
            'color' => 'amber',
            'dot' => 'bg-amber-500',
            'bar' => 'from-amber-500 via-amber-400 to-yellow-300',
            'glow' => 'rgba(251,191,36,0.4)',
            'iconBg' => 'bg-amber-500/15 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 border-amber-400/30',
        ],
        'hazardous' => [
            'name' => 'Hazardous',
            'node' => 'NODE 04',
            'subtitle' => 'Chemicals, electronics, batteries',
            'color' => 'rose',
            'dot' => 'bg-rose-500',
            'bar' => 'from-rose-500 via-rose-400 to-red-400',
            'glow' => 'rgba(244,63,94,0.4)',
            'iconBg' => 'bg-rose-500/15 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 border-rose-400/30',
        ],
    ];

    $initialBinsData = [];
    foreach ($definitions as $slug => $def) {
        $binModel = isset($bins) && isset($bins[$slug]) ? $bins[$slug] : null;
        $initialBinsData[$slug] = [
            'name' => $def['name'],
            'node' => $def['node'],
            'subtitle' => $def['subtitle'],
            'color' => $def['color'],
            'level' => $binModel ? (int)$binModel->level : 0,
            'status' => $binModel ? $binModel->status : 'Empty',
            'lastEmptied' => $binModel && $binModel->last_emptied_at ? $binModel->last_emptied_at->diffForHumans() : 'Never',
            'items' => $binModel ? $binModel->items->take(20)->map(function($i) {
                return [
                    'id' => $i->id,
                    'name' => $i->name,
                    'created_at' => $i->created_at ? $i->created_at->toISOString() : null,
                ];
            })->values()->all() : [],
        ];
    }
@endphp

<div x-data="binModal(@js($initialBinsData))" class="relative isolate w-full min-h-[calc(100vh-3.5rem)] bg-gradient-to-br from-emerald-100/60 via-slate-100 to-teal-100/40 dark:from-[#030906] dark:via-[#06120D] dark:to-[#040C09] text-slate-800 dark:text-slate-100 py-6 sm:py-8 px-4 sm:px-6 lg:px-8 selection:bg-emerald-500 selection:text-white transition-colors duration-300 overflow-hidden">
    
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

    <!-- MAIN DASHBOARD CONTENT -->
    <div class="max-w-[1720px] mx-auto space-y-6 sm:space-y-7 relative z-10">

        <!-- 1. Header with Translucent Pill & Glassmorphic Title -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-white/50 dark:border-white/10">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight drop-shadow-sm">
                        Station overview
                    </h1>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/70 dark:bg-emerald-500/15 border border-white/80 dark:border-emerald-400/30 text-emerald-800 dark:text-emerald-300 shadow-[0_4px_16px_rgba(16,185,129,0.15)] backdrop-blur-xl">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        All systems operational &bull; Live Telemetry
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Continuous optical monitoring and capacity management across 4 waste streams
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-xs font-mono text-emerald-800 dark:text-emerald-300 bg-white/70 dark:bg-emerald-950/50 px-3.5 py-1.5 rounded-2xl border border-white/80 dark:border-emerald-500/30 shadow-[0_4px_16px_rgba(0,0,0,0.05)] backdrop-blur-xl">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Polling Active (4s)</span>
                </div>
            </div>
        </div>

        <!-- 2. The 4 Facility Metric Cards (True Frosted Glass) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            
            <!-- Metric 1: Items Processed -->
            <div class="relative rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] hover:border-emerald-400/50 transition-all flex flex-col justify-between overflow-hidden group">
                <!-- Top Specular Glass Reflection Line -->
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>
                
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Items Processed</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/15 border border-emerald-400/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-[0_0_12px_rgba(16,185,129,0.2)]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight block" x-text="totalProcessed">0</span>
                    <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold block mt-1">Across 4 containment nodes</span>
                </div>
            </div>

            <!-- Metric 2: Active Streams -->
            <div class="relative rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] hover:border-sky-400/50 transition-all flex flex-col justify-between overflow-hidden group">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-sky-400/40 to-transparent pointer-events-none"></div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Active Streams</span>
                    <div class="w-8 h-8 rounded-xl bg-sky-500/15 border border-sky-400/40 flex items-center justify-center text-sky-600 dark:text-sky-400 shadow-[0_0_12px_rgba(56,189,248,0.2)]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="20" height="8" x="2" y="2" rx="2"/><rect width="20" height="8" x="2" y="14" rx="2"/>
                            <line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight block">4 / 4</span>
                    <span class="text-[11px] text-sky-600 dark:text-sky-400 font-semibold block mt-1">All stream sensors calibrated</span>
                </div>
            </div>

            <!-- Metric 3: Critical Alerts -->
            <div class="relative rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] hover:border-rose-400/50 transition-all flex flex-col justify-between overflow-hidden group">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-rose-400/40 to-transparent pointer-events-none"></div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Alerts (&ge;80%)</span>
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center"
                         :class="criticalAlertsCount > 0 ? 'bg-rose-500/20 border border-rose-400/40 text-rose-600 dark:text-rose-400 shadow-[0_0_12px_rgba(244,63,94,0.3)]' : 'bg-slate-500/10 border border-slate-300 dark:border-white/10 text-slate-400'">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-extrabold font-mono tracking-tight block"
                          :class="criticalAlertsCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-white'"
                          x-text="criticalAlertsCount">0</span>
                    <span class="text-[11px] font-semibold block mt-1"
                          :class="criticalAlertsCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500 dark:text-slate-400'"
                          x-text="criticalAlertsCount > 0 ? 'Emptying required immediately' : 'Normal containment levels'"></span>
                </div>
            </div>

            <!-- Metric 4: Average Capacity -->
            <div class="relative rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] hover:border-amber-400/50 transition-all flex flex-col justify-between overflow-hidden group">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-amber-400/40 to-transparent pointer-events-none"></div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Average Capacity</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-500/15 border border-amber-400/40 flex items-center justify-center text-amber-600 dark:text-amber-400 shadow-[0_0_12px_rgba(251,191,36,0.2)]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight block" x-text="avgFillLevel + '%'">0%</span>
                    <!-- Frosted progress bar -->
                    <div class="w-full h-2 rounded-full bg-slate-200/80 dark:bg-black/50 mt-2 overflow-hidden border border-white/50 dark:border-white/10">
                        <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-700 shadow-[0_0_10px_rgba(16,185,129,0.5)]"
                             :style="'width: ' + avgFillLevel + '%'"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- 3. MAIN SECTION: 4 SMART BINS (Left 8 Cols) + RECENT ACTIVITY LEDGER (Right 4 Cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- 4 Bins Telemetry Cards (2x2 Grid, Left 8 Cols) -->
            <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-5">

                @foreach ($definitions as $slug => $def)
                <div class="relative rounded-3xl p-6 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] hover:border-emerald-400/50 transition-all duration-200 flex flex-col justify-between overflow-hidden group">
                    
                    <!-- Top Specular Edge Highlight -->
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                    <!-- Ambient Glow Behind Bin Card -->
                    <div class="absolute -right-16 -top-16 w-44 h-44 rounded-full blur-3xl opacity-30 dark:opacity-20 pointer-events-none transition-opacity duration-300 group-hover:opacity-50"
                         style="background: {{ $def['glow'] }};"></div>

                    <div class="relative z-10">
                        <!-- Card Header: Title, Node, & Status -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl flex items-center justify-center p-2.5 border shadow-[0_4px_16px_rgba(0,0,0,0.06)] backdrop-blur-md {{ $def['iconBg'] }}">
                                    @if ($slug === 'biodegradable')
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                                    @elseif ($slug === 'recyclable')
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/><path d="M11 19h8.2a1.8 1.8 0 0 0 1.564-.91 1.776 1.776 0 0 0-.007-1.779L16.8 9.5"/><path d="m14 13-3-4-3 4"/></svg>
                                    @elseif ($slug === 'non-bio')
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    @elseif ($slug === 'hazardous')
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white leading-tight">
                                        {{ $def['name'] }}
                                    </h2>
                                    <span class="text-[10px] font-mono font-semibold text-emerald-700 dark:text-emerald-400 block mt-0.5">
                                        {{ $def['node'] }} &bull; {{ $def['subtitle'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Live Status Badge -->
                            <div>
                                <span x-show="bins['{{ $slug }}'].level >= 80" 
                                      class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-rose-500/20 text-rose-700 dark:text-rose-400 border border-rose-400/40 shadow-[0_0_12px_rgba(244,63,94,0.3)] backdrop-blur-md" x-cloak>
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                    CRITICAL
                                </span>
                                <span x-show="bins['{{ $slug }}'].level < 80" 
                                      class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-mono font-semibold text-slate-700 dark:text-slate-300 bg-white/70 dark:bg-white/10 border border-white/80 dark:border-white/15 shadow-sm backdrop-blur-md">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $def['dot'] }}"></span>
                                    <span x-text="bins['{{ $slug }}'].level >= 50 ? 'Near Capacity' : 'Healthy'">Healthy</span>
                                </span>
                            </div>
                        </div>

                        <!-- Capacity Number Readout -->
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Containment Fill</span>
                            <div class="flex items-baseline gap-0.5">
                                <span class="text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight" 
                                      x-text="bins['{{ $slug }}'].level">0</span>
                                <span class="text-sm font-mono font-bold text-slate-500 dark:text-slate-400">%</span>
                            </div>
                        </div>

                        <!-- Frosted Capsule Telemetry Gauge -->
                        <div class="w-full h-6 bg-slate-900/5 dark:bg-black/50 rounded-2xl p-1 relative overflow-hidden border border-white/80 dark:border-white/10 shadow-inner flex items-center backdrop-blur-md">
                            <!-- Background Scale Ticks -->
                            <div class="absolute inset-0 flex justify-between px-4 pointer-events-none opacity-25 z-0">
                                <div class="w-px h-full bg-slate-500 dark:bg-white"></div>
                                <div class="w-px h-full bg-slate-500 dark:bg-white"></div>
                                <div class="w-px h-full bg-slate-500 dark:bg-white"></div>
                            </div>
                            <!-- Fluid Fill Bar -->
                            <div class="h-full bg-gradient-to-r {{ $def['bar'] }} rounded-xl transition-all duration-700 relative z-10 shadow-[0_0_12px_rgba(16,185,129,0.35)]"
                                 :style="'width: ' + Math.max(bins['{{ $slug }}'].level, 3) + '%'">
                                <!-- Glowing Edge Cap -->
                                <div class="absolute right-0 top-0 bottom-0 w-2 bg-white/95 rounded-r-xl shadow-[0_0_10px_rgba(255,255,255,0.9)]"></div>
                            </div>
                        </div>

                        <!-- Scale Indicators -->
                        <div class="flex items-center justify-between text-[10px] font-mono text-slate-500 dark:text-slate-400 px-0.5 mt-1.5">
                            <span>0%</span>
                            <span>50%</span>
                            <span class="text-rose-600 dark:text-rose-400 font-semibold">80% Alert</span>
                            <span>100%</span>
                        </div>

                        <!-- Telemetry Metadata Row -->
                        <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400 mt-4 pt-3.5 border-t border-white/50 dark:border-white/10">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                                </svg>
                                <span><strong class="font-bold text-slate-900 dark:text-white" x-text="bins['{{ $slug }}'].items ? bins['{{ $slug }}'].items.length : 0">0</strong> items</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[11px] font-mono">
                                <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>Emptied <strong class="text-slate-800 dark:text-slate-200 font-semibold" x-text="bins['{{ $slug }}'].lastEmptied">Never</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Controls (Frosted Glass Buttons: Details & Empty) -->
                    <div class="grid grid-cols-2 gap-2.5 mt-5 pt-3.5 border-t border-white/50 dark:border-white/10 relative z-10">
                        <!-- Details Button -->
                        <button @click="openModal('{{ $slug }}')" 
                                type="button"
                                class="py-2.5 px-3 text-xs font-semibold text-slate-800 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white bg-white/70 dark:bg-white/10 hover:bg-white/90 dark:hover:bg-white/20 border border-white/80 dark:border-white/15 rounded-2xl transition-all shadow-[0_2px_8px_rgba(0,0,0,0.04)] backdrop-blur-md flex items-center justify-center gap-1.5 active:scale-95">
                            <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <span>Details</span>
                        </button>

                        <!-- Empty Bin Direct Button -->
                        <button @click="emptyBinDirect('{{ $slug }}')" 
                                :disabled="loadingAction !== null" 
                                type="button"
                                class="py-2.5 px-3 text-xs font-semibold text-rose-700 dark:text-rose-300 hover:text-rose-800 dark:hover:text-rose-200 bg-rose-500/15 hover:bg-rose-500/25 border border-rose-400/40 dark:border-rose-500/35 rounded-2xl transition-all shadow-[0_2px_8px_rgba(244,63,94,0.08)] backdrop-blur-md flex items-center justify-center gap-1.5 active:scale-95"
                                :class="loadingAction ? 'opacity-60 cursor-not-allowed' : ''">
                            <svg x-show="loadingAction === '{{ $slug }}-empty'" class="animate-spin h-3.5 w-3.5 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" x-cloak><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <svg x-show="loadingAction !== '{{ $slug }}-empty'" class="w-3.5 h-3.5 text-rose-600 dark:text-rose-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            <span x-text="loadingAction === '{{ $slug }}-empty' ? 'Emptying...' : 'Empty'">Empty</span>
                        </button>
                    </div>

                </div>
                @endforeach

            </div>

            <!-- Recent Activity Ledger (Right 4 Cols, Frosted Glass) -->
            <div class="lg:col-span-4 relative rounded-3xl p-6 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] flex flex-col justify-between min-h-[480px] transition-all overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                <div>
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-3.5 mb-3.5 border-b border-white/50 dark:border-white/10">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Recent Activity</h2>
                        </div>
                        <span class="text-[11px] font-mono text-emerald-700 dark:text-emerald-400 font-semibold" 
                              x-text="recentActivity.length + ' logged'"></span>
                    </div>

                    <!-- Items List -->
                    <div class="space-y-2">
                        <template x-for="item in recentActivity" :key="item.id">
                            <div class="flex items-center justify-between text-xs py-2.5 px-3 rounded-2xl bg-white/60 dark:bg-white/[0.04] hover:bg-white/80 dark:hover:bg-white/[0.08] border border-white/70 dark:border-white/10 transition-colors shadow-sm backdrop-blur-md">
                                <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                    <span class="w-2 h-2 rounded-full shrink-0 shadow-sm"
                                          :class="{
                                              'bg-emerald-500 shadow-[0_0_8px_#10b981]': item.binName === 'Biodegradable',
                                              'bg-sky-500 shadow-[0_0_8px_#38bdf8]': item.binName === 'Recyclable',
                                              'bg-amber-500 shadow-[0_0_8px_#fbbf24]': item.binName === 'Non-Biodegradable',
                                              'bg-rose-500 shadow-[0_0_8px_#f43f5e]': item.binName === 'Hazardous'
                                          }"></span>
                                    <span class="text-slate-800 dark:text-slate-100 font-semibold truncate" x-text="item.name"></span>
                                </div>
                                <span class="text-[11px] font-mono text-slate-500 dark:text-slate-400 shrink-0" x-text="formatTimeAgo(item.created_at)"></span>
                            </div>
                        </template>

                        <div x-show="recentActivity.length === 0" class="py-16 text-center text-xs text-slate-400 dark:text-slate-500 space-y-1" x-cloak>
                            <svg class="w-8 h-8 mx-auto text-slate-400 dark:text-slate-600 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="10"/><path d="M8 12h8"/>
                            </svg>
                            <p>No recent sorting activity recorded</p>
                        </div>
                    </div>
                </div>

                <div class="pt-3.5 mt-4 border-t border-white/50 dark:border-white/10">
                    <a href="{{ route('dashboard.history') }}" class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-300 transition-colors flex items-center justify-between group">
                        <span>View complete audit history</span>
                        <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- DETAILS INSPECTION MODAL (Deep Frosted Acrylic) -->
    <div x-show="isOpen" 
         @keydown.escape.window="isOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 dark:bg-black/80 backdrop-blur-xl"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        <div class="relative bg-white/90 dark:bg-[#0B1713]/90 border border-white/90 dark:border-emerald-500/35 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-5 text-slate-800 dark:text-slate-100 backdrop-blur-2xl overflow-hidden"
             @click.outside="isOpen = false">
            
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

            <div class="flex items-center justify-between pb-3.5 border-b border-slate-200/80 dark:border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-500/15 border border-emerald-400/40 text-emerald-700 dark:text-emerald-300 flex items-center justify-center font-bold text-sm shadow-sm">
                        <span x-text="activeBin.name.charAt(0)"></span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="activeBin.name"></h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400" x-text="activeBin.subtitle"></p>
                    </div>
                </div>
                <button @click="isOpen = false" type="button" class="text-slate-400 hover:text-slate-700 dark:hover:text-white p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">&times;</button>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-3 text-center">
                <div class="bg-white/60 dark:bg-white/[0.04] p-3.5 rounded-2xl border border-white/80 dark:border-white/10 shadow-sm">
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-wider block font-semibold">Fill Level</span>
                    <span class="text-2xl font-bold font-mono text-slate-900 dark:text-white mt-1 block" x-text="activeBin.level + '%'"></span>
                </div>
                <div class="bg-white/60 dark:bg-white/[0.04] p-3.5 rounded-2xl border border-white/80 dark:border-white/10 shadow-sm">
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-wider block font-semibold">Items Containment</span>
                    <span class="text-2xl font-bold font-mono text-slate-900 dark:text-white mt-1 block" x-text="activeBin.items ? activeBin.items.length : 0"></span>
                </div>
            </div>

            <!-- Stored Items Breakdown -->
            <div>
                <span class="text-xs text-slate-600 dark:text-slate-400 block mb-2 font-medium">Stored Items</span>
                <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1">
                    <template x-for="item in activeBin.items" :key="item.id">
                        <div class="p-2.5 rounded-2xl bg-white/60 dark:bg-white/[0.03] border border-white/70 dark:border-white/5 flex items-center justify-between text-xs shadow-sm">
                            <span class="text-slate-800 dark:text-slate-200 font-medium" x-text="item.name"></span>
                            <span class="text-emerald-700 dark:text-emerald-400 font-mono text-[10px] uppercase font-semibold">Classified</span>
                        </div>
                    </template>
                    <div x-show="!activeBin.items || activeBin.items.length === 0" class="text-xs text-slate-400 dark:text-slate-500 py-6 text-center" x-cloak>
                        Containment bin is empty
                    </div>
                </div>
            </div>

            <!-- Actions (Simulate Scan & Empty Bin) -->
            <div class="grid grid-cols-2 gap-2.5 pt-3 border-t border-slate-200/80 dark:border-white/10">
                <button type="button" @click="simulateScan()" :disabled="loadingAction !== null"
                        class="py-2.5 px-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-xs font-semibold transition-all shadow-md flex items-center justify-center gap-1.5 active:scale-95">
                    <svg x-show="loadingAction === 'simulate-scan'" class="animate-spin h-3.5 w-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" x-cloak><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span x-text="loadingAction === 'simulate-scan' ? 'Simulating...' : 'Simulate Scan'"></span>
                </button>
                <button type="button" @click="emptyBin()" :disabled="loadingAction !== null"
                        class="py-2.5 px-3 bg-rose-500/15 hover:bg-rose-500/25 text-rose-700 dark:text-rose-300 border border-rose-400/40 dark:border-rose-500/35 rounded-2xl text-xs font-semibold transition-all flex items-center justify-center gap-1.5 active:scale-95 shadow-sm">
                    <svg x-show="loadingAction === 'modal-empty'" class="animate-spin h-3.5 w-3.5 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" x-cloak><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span x-text="loadingAction === 'modal-empty' ? 'Emptying...' : 'Empty Bin'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- FLOATING TOAST FEEDBACK -->
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-3"
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-2xl shadow-2xl border text-xs font-semibold bg-white/90 dark:bg-[#0D1814]/90 border-white/80 dark:border-emerald-500/40 text-slate-900 dark:text-emerald-300 backdrop-blur-xl"
         x-cloak>
        <span class="w-2.5 h-2.5 rounded-full" :class="toast.type === 'error' ? 'bg-rose-500 animate-ping' : 'bg-emerald-500'"></span>
        <span x-text="toast.message"></span>
    </div>

    <!-- ALPINE ENGINE SCRIPT -->
    <script>
        function binModal(initialData = null) {
            return {
                isOpen: false,
                activeKey: '',
                loadingAction: null,
                toast: {
                    show: false,
                    message: '',
                    type: 'success',
                    timer: null
                },
                activeBin: {
                    name: '',
                    subtitle: '',
                    color: 'emerald',
                    level: 0,
                    status: '',
                    lastEmptied: '',
                    items: []
                },
                bins: initialData || {
                    hazardous: { name: 'Hazardous', subtitle: 'Chemicals & batteries', color: 'rose', level: 0, status: 'Empty', lastEmptied: 'Never', items: [] },
                    recyclable: { name: 'Recyclable', subtitle: 'Plastics, paper, cans', color: 'blue', level: 0, status: 'Empty', lastEmptied: 'Never', items: [] },
                    biodegradable: { name: 'Biodegradable', subtitle: 'Organic & food waste', color: 'emerald', level: 0, status: 'Empty', lastEmptied: 'Never', items: [] },
                    'non-bio': { name: 'Non-Biodegradable', subtitle: 'Residual waste', color: 'amber', level: 0, status: 'Empty', lastEmptied: 'Never', items: [] }
                },
                init() {
                    if (!initialData) {
                        this.fetchBins();
                    }
                    setInterval(() => {
                        this.fetchBins();
                    }, 4000);
                },
                showToast(message, type = 'success') {
                    if (this.toast.timer) clearTimeout(this.toast.timer);
                    this.toast.message = message;
                    this.toast.type = type;
                    this.toast.show = true;
                    this.toast.timer = setTimeout(() => {
                        this.toast.show = false;
                    }, 3000);
                },
                async fetchBins() {
                    try {
                        const response = await axios.get('/api/bins');
                        const data = response.data;
                        Object.keys(this.bins).forEach(key => {
                            if (data[key]) {
                                this.bins[key].level = data[key].level;
                                this.bins[key].status = data[key].status;
                                this.bins[key].items = data[key].items || [];
                                this.bins[key].lastEmptied = this.formatLastEmptied(data[key].last_emptied_at);
                            }
                        });
                        if (this.isOpen && this.activeKey && this.bins[this.activeKey]) {
                            this.activeBin = this.bins[this.activeKey];
                        }
                    } catch (error) {
                        console.error("Background sync error:", error);
                    }
                },
                formatLastEmptied(timestamp) {
                    if (!timestamp) return 'Never';
                    const date = new Date(timestamp);
                    const diffMs = (new Date()) - date;
                    const diffMins = Math.floor(diffMs / 60000);
                    if (diffMins < 1) return 'Just now';
                    if (diffMins < 60) return `${diffMins}m ago`;
                    return `${Math.floor(diffMins / 60)}h ago`;
                },
                formatTimeAgo(timestamp) {
                    if (!timestamp) return '1m ago';
                    const date = new Date(timestamp);
                    const diffMins = Math.floor(((new Date()) - date) / 60000);
                    if (diffMins < 1) return 'Just now';
                    if (diffMins < 60) return `${diffMins}m ago`;
                    const diffHours = Math.floor(diffMins / 60);
                    if (diffHours < 24) return `${diffHours}h ago`;
                    return `${Math.floor(diffHours / 24)}d ago`;
                },
                get totalProcessed() {
                    return Object.values(this.bins).reduce((acc, bin) => acc + (bin.items ? bin.items.length : 0), 0);
                },
                get criticalAlertsCount() {
                    return Object.values(this.bins).filter(bin => bin.level >= 80 || bin.status === 'Critical').length;
                },
                get avgFillLevel() {
                    const levels = Object.values(this.bins).map(b => b.level || 0);
                    if (levels.length === 0) return 0;
                    return Math.round(levels.reduce((a, b) => a + b, 0) / levels.length);
                },
                get recentActivity() {
                    let allItems = [];
                    Object.keys(this.bins).forEach(key => {
                        const bin = this.bins[key];
                        if (bin.items) {
                            bin.items.forEach(item => {
                                allItems.push({
                                    id: item.id,
                                    name: item.name,
                                    created_at: item.created_at,
                                    binName: bin.name
                                });
                            });
                        }
                    });
                    return allItems.sort((a, b) => new Date(b.created_at || b.id) - new Date(a.created_at || a.id)).slice(0, 10);
                },
                openModal(binKey) {
                    this.activeKey = binKey;
                    this.activeBin = this.bins[binKey];
                    this.isOpen = true;
                },
                async emptyBin() {
                    if (this.loadingAction) return;
                    this.loadingAction = 'modal-empty';
                    try {
                        const response = await axios.post(`/api/bins/${this.activeKey}/empty`);
                        const updatedBin = response.data;
                        this.bins[this.activeKey].level = updatedBin.level;
                        this.bins[this.activeKey].items = updatedBin.items || [];
                        this.bins[this.activeKey].status = updatedBin.status;
                        this.lastEmptied = 'Just now';
                        this.activeBin = this.bins[this.activeKey];
                        this.showToast(`${this.activeBin.name} containment emptied`);
                    } catch (error) {
                        this.showToast('Failed to empty containment', 'error');
                    } finally {
                        this.loadingAction = null;
                    }
                },
                async emptyBinDirect(binKey) {
                    if (this.loadingAction) return;
                    this.loadingAction = `${binKey}-empty`;
                    try {
                        const response = await axios.post(`/api/bins/${binKey}/empty`);
                        const updatedBin = response.data;
                        this.bins[binKey].level = updatedBin.level;
                        this.bins[binKey].items = updatedBin.items || [];
                        this.bins[binKey].status = updatedBin.status;
                        this.lastEmptied = 'Just now';
                        this.showToast(`${this.bins[binKey].name} emptied successfully`);
                    } catch (error) {
                        this.showToast(`Failed to empty ${this.bins[binKey].name}`, 'error');
                    } finally {
                        this.loadingAction = null;
                    }
                },
                async simulateScan() {
                    if (this.loadingAction) return;
                    this.loadingAction = 'simulate-scan';
                    try {
                        const response = await axios.post(`/api/bins/${this.activeKey}/scan`);
                        const updatedBin = response.data;
                        this.bins[this.activeKey].level = updatedBin.level;
                        this.bins[this.activeKey].items = updatedBin.items || [];
                        this.bins[this.activeKey].status = updatedBin.status;
                        this.activeBin = this.bins[this.activeKey];
                        this.showToast(`Logged item into ${this.activeBin.name}`);
                    } catch (error) {
                        this.showToast('Simulation failed', 'error');
                    } finally {
                        this.loadingAction = null;
                    }
                }
            };
        }
    </script>
</div>
