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
                    <!-- Station health: worked out from the Pi and ESP32 heartbeats, never assumed -->
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border shadow-[0_4px_16px_rgba(16,185,129,0.15)] backdrop-blur-xl"
                          :class="{
                              ok: 'bg-white/70 dark:bg-emerald-500/15 border-white/80 dark:border-emerald-400/30 text-emerald-800 dark:text-emerald-300',
                              warn: 'bg-amber-50 dark:bg-amber-500/15 border-amber-300 dark:border-amber-400/30 text-amber-800 dark:text-amber-300',
                              loading: 'bg-white/70 dark:bg-white/10 border-white/80 dark:border-white/15 text-slate-600 dark:text-slate-300'
                          }[stationHealth.tone]"
                          role="status">
                        <span class="inline-flex rounded-full h-2 w-2"
                              :class="{ ok: 'bg-emerald-500', warn: 'bg-amber-500', loading: 'bg-slate-400' }[stationHealth.tone]"></span>
                        <span x-text="stationHealth.text">Checking station…</span>
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Live bin levels and sorting activity for the four waste streams
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-xs font-mono text-emerald-800 dark:text-emerald-300 bg-white/70 dark:bg-emerald-950/50 px-3.5 py-1.5 rounded-2xl border border-white/80 dark:border-emerald-500/30 shadow-[0_4px_16px_rgba(0,0,0,0.05)] backdrop-blur-xl">
                    <!-- Refresh ring: drains over the 4s poll, restarts on every successful refresh -->
                    <svg class="w-4 h-4 -rotate-90 shrink-0" viewBox="0 0 16 16" aria-hidden="true">
                        <circle cx="8" cy="8" r="6" fill="none" stroke-width="2" class="stroke-emerald-500/25"></circle>
                        <circle cx="8" cy="8" r="6" fill="none" stroke-width="2" stroke-linecap="round" stroke-dasharray="37.7"
                                class="es-poll" :class="syncError ? 'stroke-amber-500' : 'stroke-emerald-500'"
                                :style="syncError ? 'animation: none' : 'animation-name: ' + (pollTick % 2 ? 'es-poll-a' : 'es-poll-b')"></circle>
                    </svg>
                    <span :class="syncError ? 'text-amber-700 dark:text-amber-300' : ''"
                          x-text="syncError ? 'Connection lost, retrying' : 'Live, refreshes every 4s'">Live, refreshes every 4s</span>
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
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Items in bins</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/15 border border-emerald-400/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-[0_0_12px_rgba(16,185,129,0.2)]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight block" x-text="totalProcessed">0</span>
                    <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold block mt-1">Since each bin was last emptied</span>
                </div>
            </div>

            <!-- Metric 2: Active Streams -->
            <div class="relative rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] hover:border-sky-400/50 transition-all flex flex-col justify-between overflow-hidden group">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-sky-400/40 to-transparent pointer-events-none"></div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Bin sensors</span>
                    <div class="w-8 h-8 rounded-xl bg-sky-500/15 border border-sky-400/40 flex items-center justify-center text-sky-600 dark:text-sky-400 shadow-[0_0_12px_rgba(56,189,248,0.2)]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="20" height="8" x="2" y="2" rx="2"/><rect width="20" height="8" x="2" y="14" rx="2"/>
                            <line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight block"
                          x-text="sensorStatus === 'live' ? '4 / 4' : (sensorStatus === 'loading' ? '…' : '0 / 4')">…</span>
                    <span class="text-[11px] font-semibold block mt-1"
                          :class="sensorStatus === 'live' ? 'text-sky-600 dark:text-sky-400' : (sensorStatus === 'stale' ? 'text-amber-600 dark:text-amber-400' : (sensorStatus === 'loading' ? 'text-slate-500' : 'text-rose-600 dark:text-rose-400'))"
                          x-text="{ live: 'All 4 sensors reporting', stale: 'ESP32 is late to report', loading: 'Checking sensors…' }[sensorStatus] ?? 'Offline: the ESP32 is not reporting'">Checking sensors…</span>
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
                          x-text="criticalAlertsCount > 0 ? (criticalAlertsCount === 1 ? '1 bin needs emptying' : criticalAlertsCount + ' bins need emptying') : 'All bins below 80%'"></span>
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

        <!-- Sorting station strip: replays each newly detected sort (camera -> arm -> bin) -->
        <section aria-labelledby="station-strip-title"
                 class="relative rounded-3xl p-5 sm:p-6 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] overflow-hidden">
            <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1 mb-2">
                <h2 id="station-strip-title" class="text-sm font-bold text-slate-900 dark:text-white">Sorting station</h2>
                <p class="text-xs text-slate-600 dark:text-slate-400 min-w-0" aria-live="polite">
                    <span x-show="station.phase !== 'idle'" x-cloak>
                        Sorting <strong class="font-semibold text-slate-900 dark:text-white" x-text="station.item"></strong>
                        into <span class="font-semibold" x-text="station.key ? bins[station.key].name : ''"></span>
                    </span>
                    <span x-show="station.phase === 'idle' && recentActivity.length > 0">
                        Last sorted: <strong class="font-semibold text-slate-900 dark:text-white" x-text="recentActivity[0]?.name"></strong>
                        into <span class="font-semibold" x-text="recentActivity[0]?.binName"></span>,
                        <span x-text="recentActivity[0] ? formatTimeAgo(recentActivity[0].created_at).toLowerCase() : ''"></span>
                    </span>
                    <span x-show="station.phase === 'idle' && recentActivity.length === 0" x-cloak>Waiting for the first scan</span>
                </p>
            </div>

            @php
                // Station geometry (SVG user units, viewBox 600 x 150). Labels are HTML placed by
                // percentage so they stay readable at phone widths.
                $stationHome = 50;
                $stationBins = [];
                foreach (array_keys($definitions) as $i => $slug) {
                    $stationBins[$slug] = [
                        'cx' => 165 + $i * 125,
                        'hex' => ['emerald' => '#10b981', 'sky' => '#0ea5e9', 'amber' => '#f59e0b', 'rose' => '#f43f5e'][$definitions[$slug]['color']],
                        'short' => ['biodegradable' => 'Bio', 'recyclable' => 'Recycle', 'non-bio' => 'Non-bio', 'hazardous' => 'Hazard'][$slug],
                    ];
                }
            @endphp
            <div class="mx-auto max-w-3xl">
                <div class="relative">
                    <svg viewBox="0 0 600 150" class="w-full h-auto block" role="img"
                         aria-label="Sorting station: camera at the scan point, arm carriage on the gantry rail, and the four bins with their fill levels">
                        <!-- Gantry rail -->
                        <rect x="20" y="20" width="560" height="8" rx="4" class="fill-slate-200 dark:fill-slate-800 stroke-slate-300 dark:stroke-slate-600"></rect>

                        <!-- Scan point: camera over the intake platform -->
                        <rect x="{{ $stationHome - 22 }}" y="128" width="44" height="6" rx="3" class="fill-slate-300 dark:fill-slate-700"></rect>
                        <rect x="{{ $stationHome - 16 }}" y="96" width="32" height="20" rx="4" class="fill-white dark:fill-slate-900 stroke-slate-400 dark:stroke-slate-500"></rect>
                        <circle cx="{{ $stationHome }}" cy="106" r="5.5" class="fill-slate-100 dark:fill-slate-950" stroke="#10b981" stroke-width="2"></circle>

                        <!-- Four bins with live fill levels -->
                        @foreach ($stationBins as $slug => $b)
                            <rect x="{{ $b['cx'] - 56 }}" y="78" width="112" height="62" rx="6" class="fill-white/80 dark:fill-slate-950/70"
                                  stroke="{{ $b['hex'] }}" :stroke-width="station.at === '{{ $slug }}' && ['drop', 'release'].includes(station.phase) ? 3 : 1.5"></rect>
                            <rect x="{{ $b['cx'] - 51 }}" y="83" width="102" height="52" rx="3" fill="{{ $b['hex'] }}" fill-opacity="0.3" class="es-fill"
                                  :style="'transform: scaleY(' + (levelOf('{{ $slug }}') / 100) + ')'"></rect>
                        @endforeach

                        <!-- Carriage + arm: rides the rail to the target bin -->
                        <g class="es-carriage" :style="'transform: translateX(' + stationX + 'px)'">
                            <rect x="-24" y="14" width="48" height="20" rx="4" class="fill-slate-700 dark:fill-slate-300 stroke-slate-800 dark:stroke-slate-100"></rect>
                            <g class="es-arm" :style="'transform: translateY(' + (['pick', 'drop'].includes(station.phase) ? 22 : 0) + 'px)'">
                                <line x1="0" y1="34" x2="0" y2="52" stroke-width="4" stroke-linecap="round" class="stroke-slate-500 dark:stroke-slate-400"></line>
                                <path d="M-9 52 H9 M-9 52 V62 M9 52 V62" fill="none" stroke-width="3" stroke-linecap="round" class="stroke-slate-500 dark:stroke-slate-400"></path>
                                <rect x="-6" y="56" width="12" height="15" rx="2" class="es-item"
                                      :fill="streamHex[station.key] || '#94a3b8'"
                                      :style="'opacity:' + (['lift', 'travel', 'drop'].includes(station.phase) ? 1 : 0)"></rect>
                            </g>
                        </g>
                    </svg>
                    <!-- Fill % over each bin -->
                    @foreach ($stationBins as $slug => $b)
                        <span class="absolute -translate-x-1/2 -translate-y-1/2 text-sm sm:text-lg font-bold tabular-nums text-slate-900 dark:text-white pointer-events-none"
                              style="left: {{ round($b['cx'] / 6, 3) }}%; top: 72.7%;"
                              x-text="Math.round(shown['{{ $slug }}'] ?? levelOf('{{ $slug }}')) + '%'"></span>
                    @endforeach
                </div>
                <!-- Names under the drawing -->
                <div class="relative h-5 mt-1 text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-300">
                    <span class="absolute -translate-x-1/2 text-slate-500 dark:text-slate-400" style="left: {{ round($stationHome / 6, 3) }}%;">Scan</span>
                    @foreach ($stationBins as $slug => $b)
                        <span class="absolute -translate-x-1/2 whitespace-nowrap" style="left: {{ round($b['cx'] / 6, 3) }}%;">{{ $b['short'] }}</span>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- 3. MAIN SECTION: 4 SMART BINS (Left 8 Cols) + RECENT ACTIVITY LEDGER (Right 4 Cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- 4 Bins Telemetry Cards (2x2 Grid, Left 8 Cols) -->
            <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-5">

                @foreach ($definitions as $slug => $def)
                <div class="relative rounded-3xl p-6 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] hover:border-emerald-400/50 transition-colors duration-200 flex flex-col justify-between overflow-hidden group"
                     :class="crossed['{{ $slug }}'] ? 'es-cross' : ''">
                    
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
                                <span x-show="levelOf('{{ $slug }}') >= 80"
                                      class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-rose-500/20 text-rose-700 dark:text-rose-400 border border-rose-400/40 shadow-[0_0_12px_rgba(244,63,94,0.3)] backdrop-blur-md" x-cloak>
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    CRITICAL
                                </span>
                                <span x-show="levelOf('{{ $slug }}') < 80"
                                      class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-mono font-semibold text-slate-700 dark:text-slate-300 bg-white/70 dark:bg-white/10 border border-white/80 dark:border-white/15 shadow-sm backdrop-blur-md">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $def['dot'] }}"></span>
                                    <span x-text="levelOf('{{ $slug }}') >= 50 ? 'Near Capacity' : 'Healthy'">Healthy</span>
                                </span>
                            </div>
                        </div>

                        <!-- Capacity Number Readout -->
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Bin level</span>
                            <div class="flex items-baseline gap-0.5">
                                <span class="text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight tabular-nums"
                                      x-text="Math.round(shown['{{ $slug }}'] ?? levelOf('{{ $slug }}'))">0</span>
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
                            <div class="h-full bg-gradient-to-r {{ $def['bar'] }} rounded-xl es-bar relative z-10 shadow-[0_0_12px_rgba(16,185,129,0.35)]"
                                 :style="'width: ' + Math.max(levelOf('{{ $slug }}'), 3) + '%'">
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
                                <span><strong class="font-bold text-slate-900 dark:text-white inline-block" :class="bumped['{{ $slug }}'] ? 'es-bump' : ''"
                                              x-text="pendingEmpty?.key === '{{ $slug }}' ? 0 : (bins['{{ $slug }}'].items ? bins['{{ $slug }}'].items.length : 0)">0</strong> items</span>
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

                        <!-- Empty Bin Direct Button (Supervisor Override) -->
                        <button @click="requestEmpty('{{ $slug }}')"
                                :disabled="loadingAction !== null || pendingEmpty?.key === '{{ $slug }}'"
                                type="button"
                                title="Mark this bin as emptied (you can undo for 5 seconds)"
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
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Recent Activity</h2>
                        </div>
                        <span class="text-[11px] font-mono text-emerald-700 dark:text-emerald-400 font-semibold" 
                              x-text="recentActivity.length + ' logged'"></span>
                    </div>

                    <!-- Items List -->
                    <div class="space-y-2">
                        <template x-for="item in recentActivity" :key="item.id">
                            <div class="relative overflow-hidden flex items-center justify-between text-xs py-2.5 px-3 rounded-2xl bg-white/60 dark:bg-white/[0.04] hover:bg-white/80 dark:hover:bg-white/[0.08] border border-white/70 dark:border-white/10 transition-colors shadow-sm backdrop-blur-md"
                                 :class="fresh[item.id] ? 'es-row-new' : ''"
                                 :style="'--es-flash: ' + (streamHex[item.binKey] || '#94a3b8')">
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
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-wider block font-semibold">Items</span>
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
                        This bin is empty
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
                <button type="button" @click="requestEmpty(activeKey)" :disabled="loadingAction !== null || pendingEmpty?.key === activeKey"
                        title="Mark this bin as emptied (you can undo for 5 seconds)"
                        class="py-2.5 px-3 bg-rose-500/15 hover:bg-rose-500/25 text-rose-700 dark:text-rose-300 border border-rose-400/40 dark:border-rose-500/35 rounded-2xl text-xs font-semibold transition-all flex items-center justify-center gap-1.5 active:scale-95 shadow-sm">
                    <svg x-show="loadingAction === 'modal-empty'" class="animate-spin h-3.5 w-3.5 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" x-cloak><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span x-text="loadingAction === 'modal-empty' ? 'Emptying...' : 'Empty bin'"></span>
                </button>
            </div>

            <!-- Instant Test Alert Button -->
            <div class="pt-2">
                <button type="button" @click="testAlertEmail()" :disabled="loadingAction !== null"
                        title="Dispatch a live test 80% capacity alert email to verify Gmail delivery"
                        class="w-full py-2 px-3 bg-amber-500/15 hover:bg-amber-500/25 text-amber-800 dark:text-amber-300 border border-amber-400/40 rounded-2xl text-[11px] font-semibold transition-all flex items-center justify-center gap-1.5 active:scale-95 shadow-sm">
                    <svg x-show="loadingAction === 'test-alert'" class="animate-spin h-3 w-3 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" x-cloak><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span>⚡ Test 80% Alert Email to Gmail</span>
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
        <span class="w-2.5 h-2.5 rounded-full" :class="toast.type === 'error' ? 'bg-rose-500' : 'bg-emerald-500'"></span>
        <span x-text="toast.message"></span>
    </div>

    <!-- PENDING EMPTY: 5-second window to undo before the bin is actually emptied -->
    <div x-show="pendingEmpty"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         role="status" aria-live="polite"
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 w-[min(92vw,26rem)] rounded-2xl shadow-2xl border bg-white dark:bg-[#0D1814] border-slate-200 dark:border-emerald-500/40 overflow-hidden"
         x-cloak>
        <div class="flex items-center justify-between gap-4 px-4 py-3 text-sm">
            <span class="text-slate-800 dark:text-slate-100">
                Emptying <strong class="font-semibold" x-text="pendingEmpty ? bins[pendingEmpty.key].name : ''"></strong>
                <span x-show="!pendingEmpty?.committing">in <span class="tabular-nums" x-text="pendingEmpty?.remaining"></span>s</span>
            </span>
            <button type="button" @click="undoEmpty()" :disabled="!!(pendingEmpty && pendingEmpty.committing)"
                    x-text="pendingEmpty?.committing ? 'Emptying…' : 'Undo'"
                    class="disabled:opacity-60 disabled:cursor-default shrink-0 px-3 py-1.5 rounded-xl text-sm font-semibold text-emerald-800 dark:text-emerald-300 bg-emerald-500/15 hover:bg-emerald-500/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                Undo
            </button>
        </div>
        <!-- Countdown line: shrinks over the 5s window -->
        <div class="h-1 bg-slate-100 dark:bg-white/10">
            <div class="h-full bg-rose-500 origin-left es-countdown"
                 :style="'animation-name: ' + ((pendingEmpty?.run || 0) % 2 ? 'es-countdown-a' : 'es-countdown-b')"></div>
        </div>
    </div>

    <style>
        /* Motion for the Live Monitor. Every movement is tied to a real event
           (new scan, level change, empty, refresh) and is removed for reduced motion. */
        .es-bar { transition: width 700ms cubic-bezier(.16, 1, .3, 1); }
        .es-fill { transform-box: fill-box; transform-origin: 50% 100%; transition: transform 700ms cubic-bezier(.16, 1, .3, 1); }
        .es-carriage { transition: transform 900ms cubic-bezier(.65, 0, .35, 1); }
        .es-arm { transition: transform 380ms cubic-bezier(.16, 1, .3, 1); }
        .es-item { transition: opacity 200ms ease-out; }

        .es-poll { stroke-dashoffset: 0; animation-duration: 4s; animation-timing-function: linear; animation-fill-mode: forwards; }
        @keyframes es-poll-a { from { stroke-dashoffset: 0; } to { stroke-dashoffset: 37.7; } }
        @keyframes es-poll-b { from { stroke-dashoffset: 0; } to { stroke-dashoffset: 37.7; } }

        .es-countdown { animation-duration: 5s; animation-timing-function: linear; animation-fill-mode: forwards; }
        @keyframes es-countdown-a { from { transform: scaleX(1); } to { transform: scaleX(0); } }
        @keyframes es-countdown-b { from { transform: scaleX(1); } to { transform: scaleX(0); } }

        /* New scan: the row arrives with a wash of its bin's color */
        .es-row-new { animation: es-row-in 420ms cubic-bezier(.16, 1, .3, 1); }
        .es-row-new::after { content: ''; position: absolute; inset: 0; background: var(--es-flash); opacity: 0; pointer-events: none; animation: es-flash 1600ms ease-out; }
        @keyframes es-row-in { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: none; } }
        @keyframes es-flash { 0% { opacity: .28; } 100% { opacity: 0; } }

        /* Item count on the bin card ticks up once */
        .es-bump { animation: es-bump 500ms cubic-bezier(.16, 1, .3, 1); }
        @keyframes es-bump { 0% { transform: scale(1); } 35% { transform: scale(1.35); } 100% { transform: scale(1); } }

        /* A bin crossing 80%: one ring ripples out from the card, once */
        .es-cross { animation: es-cross 1400ms ease-out; }
        @keyframes es-cross { 0% { box-shadow: 0 0 0 0 rgba(244, 63, 94, .55); } 100% { box-shadow: 0 0 0 14px rgba(244, 63, 94, 0); } }

        @media (prefers-reduced-motion: reduce) {
            .es-bar, .es-fill, .es-carriage, .es-arm { transition-duration: 1ms; }
            .es-row-new, .es-bump, .es-cross { animation: none; }
            .es-row-new::after { animation-duration: 1600ms; } /* keep the color cue, drop the movement */
            .es-poll { animation: none !important; }
        }
    </style>

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
                sensorStatus: 'loading',
                piStatus: 'loading',

                // ---- Motion state (every animation below reacts to a real event) ----
                reducedMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
                streamHex: { biodegradable: '#10b981', recyclable: '#0ea5e9', 'non-bio': '#f59e0b', hazardous: '#f43f5e' },
                binX: { biodegradable: 165, recyclable: 290, 'non-bio': 415, hazardous: 540 }, // must match $stationBins
                shown: {},          // displayed % per bin, eased toward the real level
                fresh: {},          // item ids that just arrived (row flash)
                bumped: {},         // bins whose item count just went up
                crossed: {},        // bins that just crossed 80%
                knownIds: null,     // item ids already on screen
                pollTick: 0,
                syncError: false,
                pendingEmpty: null, // { key, remaining, run, interval }
                emptyRuns: 0,
                station: { phase: 'idle', at: null, item: '', key: null },
                sortQueue: [],
                tweening: false,

                levelOf(key) {
                    if (this.pendingEmpty && this.pendingEmpty.key === key) return 0;
                    return this.bins[key] ? (this.bins[key].level || 0) : 0;
                },
                get stationX() {
                    return this.station.at ? this.binX[this.station.at] : 50;
                },
                flag(map, id, ms) {
                    map[id] = true;
                    setTimeout(() => { delete map[id]; }, ms);
                },
                // Ease each displayed % toward its real level (exponential ease-out).
                tweenLevels() {
                    if (this.reducedMotion) {
                        Object.keys(this.bins).forEach(k => { this.shown[k] = this.levelOf(k); });
                        return;
                    }
                    if (this.tweening) return;
                    this.tweening = true;
                    const step = () => {
                        let moving = false;
                        Object.keys(this.bins).forEach(k => {
                            const target = this.levelOf(k);
                            const cur = this.shown[k] ?? target;
                            const diff = target - cur;
                            if (Math.abs(diff) < 0.4) {
                                this.shown[k] = target;
                            } else {
                                this.shown[k] = cur + diff * 0.14;
                                moving = true;
                            }
                        });
                        if (moving) requestAnimationFrame(step); else this.tweening = false;
                    };
                    requestAnimationFrame(step);
                },
                // Compare a bin's fresh data with what is on screen and fire the matching cues.
                ingest(key, data) {
                    const bin = this.bins[key];
                    const prevLevel = bin.level || 0;
                    const items = data.items || [];
                    if (prevLevel < 80 && data.level >= 80) this.flag(this.crossed, key, 1400);

                    if (this.knownIds) {
                        const arrivals = items.filter(i => !this.knownIds.has(i.id));
                        arrivals.forEach(i => { this.flag(this.fresh, i.id, 1700); this.queueSort(key, i.name); });
                        if (arrivals.length) this.flag(this.bumped, key, 500);
                    }
                    items.forEach(i => this.knownIds && this.knownIds.add(i.id));

                    bin.level = data.level;
                    bin.status = data.status;
                    bin.items = items;
                },
                // Station strip: replay one sort at a time (queue capped so it never lags far behind).
                queueSort(key, name) {
                    this.sortQueue.push({ key, name });
                    if (this.sortQueue.length > 3) this.sortQueue.splice(0, this.sortQueue.length - 3);
                    if (this.station.phase === 'idle') this.playNextSort();
                },
                async playNextSort() {
                    const job = this.sortQueue.shift();
                    if (!job) return;
                    const wait = ms => new Promise(r => setTimeout(r, this.reducedMotion ? Math.min(ms, 300) : ms));
                    Object.assign(this.station, { phase: 'pick', at: null, item: job.name, key: job.key });
                    await wait(450);
                    this.station.phase = 'lift';    await wait(350);
                    this.station.phase = 'travel';  this.station.at = job.key; await wait(950);
                    this.station.phase = 'drop';    await wait(450);
                    this.station.phase = 'release'; await wait(350);
                    this.station.phase = 'return';  this.station.at = null; await wait(950);
                    Object.assign(this.station, { phase: 'idle', key: null });
                    this.playNextSort();
                },

                // Overall station health from real heartbeats (Pi and ESP32 both report every few seconds).
                get stationHealth() {
                    if (this.piStatus === 'loading' || this.sensorStatus === 'loading') {
                        return { tone: 'loading', text: 'Checking station…' };
                    }
                    const issues = [];
                    if (this.piStatus !== 'live') issues.push(this.piStatus === 'stale' ? 'Pi is late to report' : 'Pi offline');
                    if (this.sensorStatus !== 'live') issues.push(this.sensorStatus === 'stale' ? 'bin sensors late' : 'bin sensors offline');
                    if (issues.length === 0) return { tone: 'ok', text: 'Station online' };
                    const text = issues.join(' · ');
                    return { tone: 'warn', text: text.charAt(0).toUpperCase() + text.slice(1) };
                },
                async fetchPiStatus() {
                    try {
                        const response = await axios.get('/api/hardware/rpi-telemetry');
                        this.piStatus = response.data.status;
                    } catch (e) {
                        this.piStatus = 'offline';
                    }
                },
                async fetchSensorStatus() {
                    // The 4 ultrasonic sensors are read by the ESP32; its telemetry tells us if they report.
                    try {
                        const response = await axios.get('/api/hardware/esp32-telemetry');
                        this.sensorStatus = response.data.status;
                    } catch (e) {
                        this.sensorStatus = 'offline';
                    }
                },
                init() {
                    Object.keys(this.bins).forEach(k => { this.shown[k] = this.bins[k].level || 0; });
                    // The first refresh records what is already there; only later arrivals animate.
                    this.fetchBins();
                    // Leaving the page during the undo window still empties the bin.
                    window.addEventListener('pagehide', () => this.flushPendingEmpty());
                    this.fetchSensorStatus();
                    this.fetchPiStatus();
                    setInterval(() => {
                        this.fetchBins();
                        this.fetchSensorStatus();
                        this.fetchPiStatus();
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
                        const firstLoad = this.knownIds === null;
                        if (firstLoad) this.knownIds = new Set();
                        Object.keys(this.bins).forEach(key => {
                            if (data[key]) {
                                if (this.bins[key].level > 0 && data[key].level === 0 && this.pendingEmpty?.key !== key) {
                                    this.showToast(`${data[key].name} reads 0%: the sensor detected it was emptied`, 'success');
                                }
                                this.ingest(key, firstLoad ? { ...data[key], items: [] } : data[key]);
                                if (firstLoad) {
                                    this.bins[key].items = data[key].items || [];
                                    this.bins[key].items.forEach(i => this.knownIds.add(i.id));
                                }
                                this.bins[key].lastEmptied = this.formatLastEmptied(data[key].last_emptied_at);
                            }
                        });
                        if (this.isOpen && this.activeKey && this.bins[this.activeKey]) {
                            this.activeBin = this.bins[this.activeKey];
                        }
                        this.syncError = false;
                        this.pollTick++;
                        this.tweenLevels();
                    } catch (error) {
                        this.syncError = true;
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
                    return Object.keys(this.bins).filter(k => this.pendingEmpty?.key !== k && (this.levelOf(k) >= 80 || this.bins[k].status === 'Critical')).length;
                },
                get avgFillLevel() {
                    const levels = Object.keys(this.bins).map(k => this.levelOf(k));
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
                                    binName: bin.name,
                                    binKey: key
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
                // Empty with a 5-second undo window. The bar drains right away; the server
                // only empties the bin (and deletes its items) when the window closes.
                requestEmpty(binKey) {
                    if (!binKey || this.loadingAction) return;
                    if (this.pendingEmpty) this.commitEmpty(); // a second Empty confirms the first
                    this.isOpen = false;
                    // Compare by run number: Alpine wraps pendingEmpty in a proxy, so object identity is unreliable.
                    const run = ++this.emptyRuns;
                    const interval = setInterval(() => {
                        if (!this.pendingEmpty || this.pendingEmpty.run !== run) return clearInterval(interval);
                        this.pendingEmpty.remaining--;
                        if (this.pendingEmpty.remaining <= 0) this.commitEmpty();
                    }, 1000);
                    this.pendingEmpty = { key: binKey, remaining: 5, run, interval };
                    this.tweenLevels();
                },
                undoEmpty() {
                    if (!this.pendingEmpty || this.pendingEmpty.committing) return;
                    clearInterval(this.pendingEmpty.interval);
                    const name = this.bins[this.pendingEmpty.key].name;
                    this.pendingEmpty = null;
                    this.tweenLevels();
                    this.showToast(`${name} was not emptied`);
                },
                async commitEmpty() {
                    const pending = this.pendingEmpty;
                    if (!pending) return;
                    clearInterval(pending.interval);
                    pending.committing = true; // too late to undo once the request is on its way
                    const binKey = pending.key;
                    this.loadingAction = `${binKey}-empty`;
                    try {
                        const response = await axios.post(`/api/bins/${binKey}/empty`);
                        const updatedBin = response.data;
                        this.bins[binKey].level = updatedBin.level;
                        this.bins[binKey].items = updatedBin.items || [];
                        this.bins[binKey].status = updatedBin.status;
                        this.bins[binKey].lastEmptied = 'Just now';
                        this.showToast(`${this.bins[binKey].name} emptied`);
                    } catch (error) {
                        this.showToast(`Failed to empty ${this.bins[binKey].name}`, 'error');
                    } finally {
                        if (this.pendingEmpty && this.pendingEmpty.run === pending.run) this.pendingEmpty = null;
                        this.loadingAction = null;
                        this.tweenLevels();
                    }
                },
                flushPendingEmpty() {
                    if (!this.pendingEmpty) return;
                    const body = new FormData();
                    body.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
                    navigator.sendBeacon(`/api/bins/${this.pendingEmpty.key}/empty`, body);
                    clearInterval(this.pendingEmpty.interval);
                    this.pendingEmpty = null;
                },
                async simulateScan() {
                    if (this.loadingAction) return;
                    this.loadingAction = 'simulate-scan';
                    try {
                        const response = await axios.post(`/api/bins/${this.activeKey}/scan`);
                        const updatedBin = response.data;
                        this.ingest(this.activeKey, updatedBin);
                        this.activeBin = this.bins[this.activeKey];
                        this.tweenLevels();

                        if (updatedBin.alert_dispatched) {
                            if (updatedBin.alert_feedback && updatedBin.alert_feedback.delivered) {
                                const via = (updatedBin.alert_feedback.channels || []).join(' & ') || 'Email';
                                this.showToast(`⚠️ ${this.activeBin.name} reached ${updatedBin.level}%! Alert sent to ${updatedBin.alert_feedback.recipient} (${via})`, 'success');
                            } else {
                                const err = updatedBin.alert_feedback?.brevo?.error || updatedBin.alert_feedback?.mailer?.error || 'Check Brevo/Mail config in Railway';
                                this.showToast(`⚠️ ${this.activeBin.name} at ${updatedBin.level}%! Alert failed: ${err}`, 'error');
                            }
                        } else {
                            this.showToast(`Logged item into ${this.activeBin.name} (${updatedBin.level}%)`);
                        }
                    } catch (error) {
                        this.showToast('Simulation failed', 'error');
                    } finally {
                        this.loadingAction = null;
                    }
                },
                async testAlertEmail() {
                    if (this.loadingAction) return;
                    this.loadingAction = 'test-alert';
                    try {
                        const response = await axios.post('/api/bins/test-alert-email');
                        const data = response.data;
                        if (data.success) {
                            const channels = (data.channels || []).join(' & ') || 'Email';
                            this.showToast(`✅ Critical alert test sent to ${data.recipient} via ${channels}!`, 'success');
                        } else {
                            const error = data.details?.brevo?.error || data.details?.mailer?.error || 'Check Brevo/Mail config in Railway';
                            this.showToast(`❌ Test alert failed: ${error}`, 'error');
                        }
                    } catch (error) {
                        const msg = error.response?.data?.message || error.response?.data?.error || error.message;
                        this.showToast(`❌ Test alert error: ${msg}`, 'error');
                    } finally {
                        this.loadingAction = null;
                    }
                }
            };
        }
    </script>
</div>
