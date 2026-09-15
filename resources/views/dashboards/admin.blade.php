@php
    $definitions = [
        'biodegradable' => [
            'name' => 'Biodegradable', 
            'subtitle' => 'Organic & food waste', 
            'color' => 'emerald',
            'dot' => 'bg-emerald-500',
            'bar' => 'bg-emerald-500',
            'text' => 'text-emerald-600 dark:text-emerald-400',
        ],
        'recyclable' => [
            'name' => 'Recyclable', 
            'subtitle' => 'Plastics, paper, glass, cans', 
            'color' => 'blue',
            'dot' => 'bg-sky-500',
            'bar' => 'bg-sky-500',
            'text' => 'text-sky-600 dark:text-sky-400',
        ],
        'non-bio' => [
            'name' => 'Non-Biodegradable', 
            'subtitle' => 'Residual & non-recyclable', 
            'color' => 'amber',
            'dot' => 'bg-amber-500',
            'bar' => 'bg-amber-500',
            'text' => 'text-amber-600 dark:text-amber-400',
        ],
        'hazardous' => [
            'name' => 'Hazardous', 
            'subtitle' => 'Chemicals, electronics, batteries', 
            'color' => 'rose',
            'dot' => 'bg-rose-500',
            'bar' => 'bg-rose-500',
            'text' => 'text-rose-600 dark:text-rose-400',
        ],
    ];
    $initialBinsData = [];
    foreach ($definitions as $slug => $def) {
        $binModel = isset($bins) && isset($bins[$slug]) ? $bins[$slug] : null;
        $initialBinsData[$slug] = [
            'name' => $def['name'],
            'subtitle' => $def['subtitle'],
            'color' => $def['color'],
            'level' => $binModel ? (int)$binModel->level : 0,
            'status' => $binModel ? $binModel->status : 'Empty',
            'lastEmptied' => $binModel && $binModel->last_emptied_at ? $binModel->last_emptied_at->diffForHumans() : 'Never',
            'items' => $binModel ? $binModel->items->take(15)->map(function($i) {
                return [
                    'id' => $i->id,
                    'name' => $i->name,
                    'created_at' => $i->created_at ? $i->created_at->toISOString() : null,
                ];
            })->values()->all() : [],
        ];
    }
@endphp

<div x-data="binModal(@js($initialBinsData))" class="relative isolate w-full bg-slate-50 dark:bg-[#0A0E17] text-slate-800 dark:text-slate-200 min-h-[calc(100vh-3.5rem)] py-6 sm:py-8 px-4 sm:px-6 lg:px-8 transition-colors duration-300 overflow-hidden">
    
    <!-- PROFESSIONAL COHESIVE BACKGROUND (Single signature brand atmospheric backlight, no rainbow) -->
    <div class="pointer-events-none absolute inset-0 overflow-hidden select-none z-0">
        <!-- Single Unified Subtle Emerald Brand Glow at top center -->
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[850px] h-[450px] bg-emerald-500/10 dark:bg-emerald-500/8 blur-[130px] rounded-full"></div>

        <!-- Subtle Technical Dot-Grid Matrix Texture (Clean & Professional) -->
        <div class="absolute inset-0 bg-[radial-gradient(#94a3b8_1px,transparent_1px)] dark:bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] [mask-image:radial-gradient(ellipse_70%_50%_at_50%_0%,#000_60%,transparent_100%)] opacity-70"></div>
    </div>

    <!-- MAIN DASHBOARD CONTENT (Elevated at z-10) -->
    <div class="max-w-[1720px] mx-auto space-y-7 relative z-10">

        <!-- 1. Clean Professional Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800/80">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Overview</h1>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 text-emerald-700 dark:text-emerald-300 shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live Telemetry
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Real-time monitoring across 4 waste streams</p>
            </div>
            
            <div class="flex items-center gap-2 text-xs font-mono text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-900/80 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>System Operational</span>
            </div>
        </div>

        <!-- 2. Professional Metric Row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            
            <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-5 shadow-sm hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Items Today</span>
                <span class="text-2xl sm:text-3xl font-bold font-mono text-slate-900 dark:text-white tracking-tight mt-1.5 block" x-text="totalProcessed">0</span>
            </div>

            <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-5 shadow-sm hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Active Streams</span>
                <span class="text-2xl sm:text-3xl font-bold font-mono text-slate-900 dark:text-white tracking-tight mt-1.5 block">4 / 4</span>
            </div>

            <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-5 shadow-sm hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Alerts (&ge;80%)</span>
                <span class="text-2xl sm:text-3xl font-bold font-mono tracking-tight mt-1.5 block transition-colors"
                      :class="criticalAlertsCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-300'"
                      x-text="criticalAlertsCount">0</span>
            </div>

            <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-5 shadow-sm hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Average Fill</span>
                <span class="text-2xl sm:text-3xl font-bold font-mono text-slate-900 dark:text-white tracking-tight mt-1.5 block" x-text="avgFillLevel + '%'">0%</span>
            </div>

        </div>

        <!-- 3. Main Content: 4 Bin Squares (Left) + Activity Ledger (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- 4 Bins in High-Tech Industrial Telemetry Cards (2x2 Grid) -->
            <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-5">

                <!-- 1. Biodegradable -->
                <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 hover:border-slate-300 dark:hover:border-slate-700 shadow-sm hover:shadow-md rounded-2xl p-6 flex flex-col justify-between transition-all duration-200 group relative overflow-hidden">
                    <div>
                        <!-- Header & Live Status -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white leading-tight">Biodegradable</h2>
                                    <span class="text-[10px] font-mono text-slate-400 block mt-0.5">NODE 01 &middot; Organic Waste</span>
                                </div>
                            </div>
                            <!-- Status Badges -->
                            <div>
                                <span x-show="bins.biodegradable.level >= 80" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                    CRITICAL
                                </span>
                                <span x-show="bins.biodegradable.level < 80" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-mono text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            </div>
                        </div>

                        <!-- Capacity Readout -->
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Containment Fill</span>
                            <div class="flex items-baseline gap-0.5">
                                <span class="text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight" x-text="bins.biodegradable.level">0</span>
                                <span class="text-sm font-mono font-bold text-slate-400 dark:text-slate-500">%</span>
                            </div>
                        </div>

                        <!-- Industrial Capsule Telemetry Gauge -->
                        <div class="w-full h-5 sm:h-6 bg-slate-100/90 dark:bg-slate-800/50 rounded-xl p-1 relative overflow-hidden border border-slate-200/70 dark:border-slate-800/60 shadow-inner flex items-center">
                            <!-- Background Scale Ticks -->
                            <div class="absolute inset-0 flex justify-between px-4 pointer-events-none opacity-30 dark:opacity-20 z-0">
                                <div class="w-px h-full bg-slate-400"></div>
                                <div class="w-px h-full bg-slate-400"></div>
                                <div class="w-px h-full bg-slate-400"></div>
                            </div>
                            <!-- Fluid Fill Bar -->
                            <div class="h-full bg-gradient-to-r from-emerald-600 via-emerald-500 to-teal-400 rounded-lg transition-all duration-700 relative z-10 group-hover:shadow-[0_0_12px_rgba(16,185,129,0.4)]"
                                 :style="'width: ' + Math.max(bins.biodegradable.level, 3) + '%'">
                                <!-- Glowing Edge Cap -->
                                <div class="absolute right-0 top-0 bottom-0 w-1.5 bg-emerald-200/90 rounded-r-lg shadow-[0_0_8px_rgba(167,243,208,0.9)]"></div>
                            </div>
                        </div>

                        <!-- Scale Indicators -->
                        <div class="flex items-center justify-between text-[10px] font-mono text-slate-400 dark:text-slate-500 px-0.5 mt-1.5">
                            <span>0%</span>
                            <span>50%</span>
                            <span class="text-rose-500/80 font-semibold">80% Alert</span>
                            <span>100%</span>
                        </div>

                        <!-- Telemetry Metadata Row -->
                        <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mt-4 pt-3.5 border-t border-slate-100 dark:border-slate-800/60">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                <span><strong class="font-bold text-slate-800 dark:text-slate-200" x-text="bins.biodegradable.items ? bins.biodegradable.items.length : 0">0</strong> items</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[11px] font-mono">
                                <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>Emptied <strong class="text-slate-700 dark:text-slate-300 font-semibold" x-text="bins.biodegradable.lastEmptied">Never</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Controls -->
                    <div class="grid grid-cols-2 gap-2.5 mt-5 pt-3.5 border-t border-slate-100 dark:border-slate-800/60">
                        <button @click="openModal('biodegradable')" type="button"
                                class="py-2.5 px-3 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 dark:bg-slate-800/70 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-all text-center shadow-sm flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <span>Details</span>
                        </button>
                        <button @click="emptyBinDirect('biodegradable')" :disabled="loadingAction !== null" type="button"
                                class="py-2.5 px-3 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-rose-600 dark:hover:text-rose-400 bg-slate-100 dark:bg-slate-800/70 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-all shadow-sm flex items-center justify-center gap-1.5"
                                :class="loadingAction ? 'opacity-60 cursor-not-allowed' : 'active:scale-95'">
                            <svg x-show="loadingAction === 'biodegradable-empty'" class="animate-spin h-3.5 w-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span x-text="loadingAction === 'biodegradable-empty' ? 'Emptying...' : 'Empty'"></span>
                        </button>
                    </div>
                </div>

                <!-- 2. Recyclable -->
                <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 hover:border-slate-300 dark:hover:border-slate-700 shadow-sm hover:shadow-md rounded-2xl p-6 flex flex-col justify-between transition-all duration-200 group relative overflow-hidden">
                    <div>
                        <!-- Header & Live Status -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-sky-500 animate-pulse"></span>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white leading-tight">Recyclable</h2>
                                    <span class="text-[10px] font-mono text-slate-400 block mt-0.5">NODE 02 &middot; Plastics, Paper, Glass</span>
                                </div>
                            </div>
                            <!-- Status Badges -->
                            <div>
                                <span x-show="bins.recyclable.level >= 80" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                    CRITICAL
                                </span>
                                <span x-show="bins.recyclable.level < 80" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-mono text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                    Active
                                </span>
                            </div>
                        </div>

                        <!-- Capacity Readout -->
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Containment Fill</span>
                            <div class="flex items-baseline gap-0.5">
                                <span class="text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight" x-text="bins.recyclable.level">0</span>
                                <span class="text-sm font-mono font-bold text-slate-400 dark:text-slate-500">%</span>
                            </div>
                        </div>

                        <!-- Industrial Capsule Telemetry Gauge -->
                        <div class="w-full h-5 sm:h-6 bg-slate-100/90 dark:bg-slate-800/50 rounded-xl p-1 relative overflow-hidden border border-slate-200/70 dark:border-slate-800/60 shadow-inner flex items-center">
                            <!-- Background Scale Ticks -->
                            <div class="absolute inset-0 flex justify-between px-4 pointer-events-none opacity-30 dark:opacity-20 z-0">
                                <div class="w-px h-full bg-slate-400"></div>
                                <div class="w-px h-full bg-slate-400"></div>
                                <div class="w-px h-full bg-slate-400"></div>
                            </div>
                            <!-- Fluid Fill Bar -->
                            <div class="h-full bg-gradient-to-r from-blue-600 via-sky-500 to-cyan-400 rounded-lg transition-all duration-700 relative z-10 group-hover:shadow-[0_0_12px_rgba(56,189,248,0.4)]"
                                 :style="'width: ' + Math.max(bins.recyclable.level, 3) + '%'">
                                <!-- Glowing Edge Cap -->
                                <div class="absolute right-0 top-0 bottom-0 w-1.5 bg-sky-200/90 rounded-r-lg shadow-[0_0_8px_rgba(186,230,253,0.9)]"></div>
                            </div>
                        </div>

                        <!-- Scale Indicators -->
                        <div class="flex items-center justify-between text-[10px] font-mono text-slate-400 dark:text-slate-500 px-0.5 mt-1.5">
                            <span>0%</span>
                            <span>50%</span>
                            <span class="text-rose-500/80 font-semibold">80% Alert</span>
                            <span>100%</span>
                        </div>

                        <!-- Telemetry Metadata Row -->
                        <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mt-4 pt-3.5 border-t border-slate-100 dark:border-slate-800/60">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                <span><strong class="font-bold text-slate-800 dark:text-slate-200" x-text="bins.recyclable.items ? bins.recyclable.items.length : 0">0</strong> items</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[11px] font-mono">
                                <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>Emptied <strong class="text-slate-700 dark:text-slate-300 font-semibold" x-text="bins.recyclable.lastEmptied">Never</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Controls -->
                    <div class="grid grid-cols-2 gap-2.5 mt-5 pt-3.5 border-t border-slate-100 dark:border-slate-800/60">
                        <button @click="openModal('recyclable')" type="button"
                                class="py-2.5 px-3 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 dark:bg-slate-800/70 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-all text-center shadow-sm flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <span>Details</span>
                        </button>
                        <button @click="emptyBinDirect('recyclable')" :disabled="loadingAction !== null" type="button"
                                class="py-2.5 px-3 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-rose-600 dark:hover:text-rose-400 bg-slate-100 dark:bg-slate-800/70 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-all shadow-sm flex items-center justify-center gap-1.5"
                                :class="loadingAction ? 'opacity-60 cursor-not-allowed' : 'active:scale-95'">
                            <svg x-show="loadingAction === 'recyclable-empty'" class="animate-spin h-3.5 w-3.5 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span x-text="loadingAction === 'recyclable-empty' ? 'Emptying...' : 'Empty'"></span>
                        </button>
                    </div>
                </div>

                <!-- 3. Non-Biodegradable -->
                <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 hover:border-slate-300 dark:hover:border-slate-700 shadow-sm hover:shadow-md rounded-2xl p-6 flex flex-col justify-between transition-all duration-200 group relative overflow-hidden">
                    <div>
                        <!-- Header & Live Status -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white leading-tight">Non-Bio</h2>
                                    <span class="text-[10px] font-mono text-slate-400 block mt-0.5">NODE 03 &middot; Residual Waste</span>
                                </div>
                            </div>
                            <!-- Status Badges -->
                            <div>
                                <span x-show="bins['non-bio'].level >= 80" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                    CRITICAL
                                </span>
                                <span x-show="bins['non-bio'].level < 80" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-mono text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Active
                                </span>
                            </div>
                        </div>

                        <!-- Capacity Readout -->
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Containment Fill</span>
                            <div class="flex items-baseline gap-0.5">
                                <span class="text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight" x-text="bins['non-bio'].level">0</span>
                                <span class="text-sm font-mono font-bold text-slate-400 dark:text-slate-500">%</span>
                            </div>
                        </div>

                        <!-- Industrial Capsule Telemetry Gauge -->
                        <div class="w-full h-5 sm:h-6 bg-slate-100/90 dark:bg-slate-800/50 rounded-xl p-1 relative overflow-hidden border border-slate-200/70 dark:border-slate-800/60 shadow-inner flex items-center">
                            <!-- Background Scale Ticks -->
                            <div class="absolute inset-0 flex justify-between px-4 pointer-events-none opacity-30 dark:opacity-20 z-0">
                                <div class="w-px h-full bg-slate-400"></div>
                                <div class="w-px h-full bg-slate-400"></div>
                                <div class="w-px h-full bg-slate-400"></div>
                            </div>
                            <!-- Fluid Fill Bar -->
                            <div class="h-full bg-gradient-to-r from-amber-600 via-amber-500 to-yellow-400 rounded-lg transition-all duration-700 relative z-10 group-hover:shadow-[0_0_12px_rgba(251,191,36,0.4)]"
                                 :style="'width: ' + Math.max(bins['non-bio'].level, 3) + '%'">
                                <!-- Glowing Edge Cap -->
                                <div class="absolute right-0 top-0 bottom-0 w-1.5 bg-amber-200/90 rounded-r-lg shadow-[0_0_8px_rgba(253,230,138,0.9)]"></div>
                            </div>
                        </div>

                        <!-- Scale Indicators -->
                        <div class="flex items-center justify-between text-[10px] font-mono text-slate-400 dark:text-slate-500 px-0.5 mt-1.5">
                            <span>0%</span>
                            <span>50%</span>
                            <span class="text-rose-500/80 font-semibold">80% Alert</span>
                            <span>100%</span>
                        </div>

                        <!-- Telemetry Metadata Row -->
                        <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mt-4 pt-3.5 border-t border-slate-100 dark:border-slate-800/60">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                <span><strong class="font-bold text-slate-800 dark:text-slate-200" x-text="bins['non-bio'].items ? bins['non-bio'].items.length : 0">0</strong> items</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[11px] font-mono">
                                <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>Emptied <strong class="text-slate-700 dark:text-slate-300 font-semibold" x-text="bins['non-bio'].lastEmptied">Never</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Controls -->
                    <div class="grid grid-cols-2 gap-2.5 mt-5 pt-3.5 border-t border-slate-100 dark:border-slate-800/60">
                        <button @click="openModal('non-bio')" type="button"
                                class="py-2.5 px-3 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 dark:bg-slate-800/70 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-all text-center shadow-sm flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <span>Details</span>
                        </button>
                        <button @click="emptyBinDirect('non-bio')" :disabled="loadingAction !== null" type="button"
                                class="py-2.5 px-3 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-rose-600 dark:hover:text-rose-400 bg-slate-100 dark:bg-slate-800/70 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-all shadow-sm flex items-center justify-center gap-1.5"
                                :class="loadingAction ? 'opacity-60 cursor-not-allowed' : 'active:scale-95'">
                            <svg x-show="loadingAction === 'non-bio-empty'" class="animate-spin h-3.5 w-3.5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span x-text="loadingAction === 'non-bio-empty' ? 'Emptying...' : 'Empty'"></span>
                        </button>
                    </div>
                </div>

                <!-- 4. Hazardous -->
                <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 hover:border-slate-300 dark:hover:border-slate-700 shadow-sm hover:shadow-md rounded-2xl p-6 flex flex-col justify-between transition-all duration-200 group relative overflow-hidden">
                    <div>
                        <!-- Header & Live Status -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900 dark:text-white leading-tight">Hazardous</h2>
                                    <span class="text-[10px] font-mono text-slate-400 block mt-0.5">NODE 04 &middot; Regulated / E-Waste</span>
                                </div>
                            </div>
                            <!-- Status Badges -->
                            <div>
                                <span x-show="bins.hazardous.level >= 80" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                    CRITICAL
                                </span>
                                <span x-show="bins.hazardous.level < 80" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-mono text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Active
                                </span>
                            </div>
                        </div>

                        <!-- Capacity Readout -->
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Containment Fill</span>
                            <div class="flex items-baseline gap-0.5">
                                <span class="text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tight" x-text="bins.hazardous.level">0</span>
                                <span class="text-sm font-mono font-bold text-slate-400 dark:text-slate-500">%</span>
                            </div>
                        </div>

                        <!-- Industrial Capsule Telemetry Gauge -->
                        <div class="w-full h-5 sm:h-6 bg-slate-100/90 dark:bg-slate-800/50 rounded-xl p-1 relative overflow-hidden border border-slate-200/70 dark:border-slate-800/60 shadow-inner flex items-center">
                            <!-- Background Scale Ticks -->
                            <div class="absolute inset-0 flex justify-between px-4 pointer-events-none opacity-30 dark:opacity-20 z-0">
                                <div class="w-px h-full bg-slate-400"></div>
                                <div class="w-px h-full bg-slate-400"></div>
                                <div class="w-px h-full bg-slate-400"></div>
                            </div>
                            <!-- Fluid Fill Bar -->
                            <div class="h-full bg-gradient-to-r from-rose-600 via-rose-500 to-red-400 rounded-lg transition-all duration-700 relative z-10 group-hover:shadow-[0_0_12px_rgba(244,63,94,0.4)]"
                                 :style="'width: ' + Math.max(bins.hazardous.level, 3) + '%'">
                                <!-- Glowing Edge Cap -->
                                <div class="absolute right-0 top-0 bottom-0 w-1.5 bg-rose-200/90 rounded-r-lg shadow-[0_0_8px_rgba(254,205,211,0.9)]"></div>
                            </div>
                        </div>

                        <!-- Scale Indicators -->
                        <div class="flex items-center justify-between text-[10px] font-mono text-slate-400 dark:text-slate-500 px-0.5 mt-1.5">
                            <span>0%</span>
                            <span>50%</span>
                            <span class="text-rose-500/80 font-semibold">80% Alert</span>
                            <span>100%</span>
                        </div>

                        <!-- Telemetry Metadata Row -->
                        <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mt-4 pt-3.5 border-t border-slate-100 dark:border-slate-800/60">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                <span><strong class="font-bold text-slate-800 dark:text-slate-200" x-text="bins.hazardous.items ? bins.hazardous.items.length : 0">0</strong> items</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[11px] font-mono">
                                <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>Emptied <strong class="text-slate-700 dark:text-slate-300 font-semibold" x-text="bins.hazardous.lastEmptied">Never</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Controls -->
                    <div class="grid grid-cols-2 gap-2.5 mt-5 pt-3.5 border-t border-slate-100 dark:border-slate-800/60">
                        <button @click="openModal('hazardous')" type="button"
                                class="py-2.5 px-3 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 dark:bg-slate-800/70 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-all text-center shadow-sm flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <span>Details</span>
                        </button>
                        <button @click="emptyBinDirect('hazardous')" :disabled="loadingAction !== null" type="button"
                                class="py-2.5 px-3 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-rose-600 dark:hover:text-rose-400 bg-slate-100 dark:bg-slate-800/70 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-all shadow-sm flex items-center justify-center gap-1.5"
                                :class="loadingAction ? 'opacity-60 cursor-not-allowed' : 'active:scale-95'">
                            <svg x-show="loadingAction === 'hazardous-empty'" class="animate-spin h-3.5 w-3.5 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span x-text="loadingAction === 'hazardous-empty' ? 'Emptying...' : 'Empty'"></span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Right Column: Professional Activity Ledger -->
            <div class="lg:col-span-4 bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 shadow-sm rounded-2xl p-6 flex flex-col justify-between min-h-[460px] transition-all">
                <div>
                    <div class="flex items-center justify-between pb-3.5 mb-3.5 border-b border-slate-100 dark:border-slate-800/60">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Recent Activity</h2>
                        </div>
                        <span class="text-[11px] font-mono text-slate-500 dark:text-slate-400 font-semibold" x-text="recentActivity.length + ' logged'"></span>
                    </div>

                    <div class="space-y-1.5">
                        <template x-for="item in recentActivity" :key="item.id">
                            <div class="flex items-center justify-between text-xs py-2 px-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                    <span class="w-1.5 h-1.5 rounded-full shrink-0"
                                          :class="{
                                              'bg-emerald-500': item.binName === 'Biodegradable',
                                              'bg-sky-500': item.binName === 'Recyclable',
                                              'bg-amber-500': item.binName === 'Non-Biodegradable',
                                              'bg-rose-500': item.binName === 'Hazardous'
                                          }"></span>
                                    <span class="text-slate-800 dark:text-slate-200 font-medium truncate" x-text="item.name"></span>
                                </div>
                                <span class="text-[11px] font-mono text-slate-400 dark:text-slate-500 shrink-0" x-text="formatTimeAgo(item.created_at)"></span>
                            </div>
                        </template>

                        <div x-show="recentActivity.length === 0" class="py-12 text-center text-xs text-slate-400 dark:text-slate-500">
                            No recent activity
                        </div>
                    </div>
                </div>

                <div class="pt-3.5 mt-4 border-t border-slate-100 dark:border-slate-800/60">
                    <a href="{{ route('dashboard.history') }}" class="text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors flex items-center justify-between">
                        <span>View complete history</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- Minimal Details Modal -->
    <div x-show="isOpen" 
         @keydown.escape.window="isOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 dark:bg-black/75 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 transition-colors"
             @click.outside="isOpen = false">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="activeBin.name"></h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5" x-text="activeBin.subtitle"></p>
                </div>
                <button @click="isOpen = false" type="button" class="text-slate-400 hover:text-slate-700 dark:hover:text-white p-1 transition-colors" aria-label="Close modal">&times;</button>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-3 text-center">
                <div class="bg-slate-50 dark:bg-slate-800/40 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-wider block font-medium">Fill Level</span>
                    <span class="text-2xl font-bold font-mono text-slate-900 dark:text-white mt-0.5 block" x-text="activeBin.level + '%'"></span>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/40 p-3 rounded-xl border border-slate-200 dark:border-slate-800/60">
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-wider block font-medium">Items In Bin</span>
                    <span class="text-2xl font-bold font-mono text-slate-900 dark:text-white mt-0.5 block" x-text="activeBin.items ? activeBin.items.length : 0"></span>
                </div>
            </div>

            <!-- Stored Items Breakdown -->
            <div>
                <span class="text-xs text-slate-500 dark:text-slate-400 block mb-2 font-medium">Stored Items</span>
                <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1">
                    <template x-for="item in activeBin.items" :key="item.id">
                        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800/50 flex items-center justify-between text-xs">
                            <span class="text-slate-800 dark:text-slate-200 font-medium" x-text="item.name"></span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-mono text-[10px] uppercase font-semibold">Classified</span>
                        </div>
                    </template>
                    <div x-show="!activeBin.items || activeBin.items.length === 0" class="text-xs text-slate-400 dark:text-slate-500 py-3 text-center">
                        Bin is empty
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="grid grid-cols-2 gap-2.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                <button type="button" @click="simulateScan()" :disabled="loadingAction !== null"
                        class="py-2.5 px-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-semibold transition-all shadow-sm flex items-center justify-center gap-1.5"
                        :class="loadingAction ? 'opacity-60 cursor-not-allowed' : 'active:scale-95'">
                    <svg x-show="loadingAction === 'simulate-scan'" class="animate-spin h-3.5 w-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span x-text="loadingAction === 'simulate-scan' ? 'Simulating...' : 'Simulate Scan'"></span>
                </button>
                <button type="button" @click="emptyBin()" :disabled="loadingAction !== null"
                        class="py-2.5 px-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold transition-all border border-slate-200 dark:border-slate-700/60 flex items-center justify-center gap-1.5"
                        :class="loadingAction ? 'opacity-60 cursor-not-allowed' : 'active:scale-95'">
                    <svg x-show="loadingAction === 'modal-empty'" class="animate-spin h-3.5 w-3.5 text-slate-600 dark:text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span x-text="loadingAction === 'modal-empty' ? 'Emptying...' : 'Empty Bin'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Minimalist Toast -->
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed bottom-5 right-5 z-50 flex items-center gap-2.5 px-4 py-3 rounded-xl shadow-xl border text-xs font-semibold bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 transition-colors"
         x-cloak>
        <span class="w-2 h-2 rounded-full" :class="toast.type === 'error' ? 'bg-rose-500' : 'bg-emerald-500'"></span>
        <span x-text="toast.message"></span>
    </div>

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
                    const now = new Date();
                    const diffMs = now - date;
                    const diffMins = Math.floor(diffMs / 60000);
                    const diffHours = Math.floor(diffMs / 3600000);
                    
                    if (diffMins < 1) return 'Just now';
                    if (diffMins < 60) return `${diffMins}m ago`;
                    if (diffHours < 24) return `${diffHours}h ago`;
                    return date.toLocaleDateString();
                },
                formatTimeAgo(timestamp) {
                    if (!timestamp) return '1m ago';
                    const date = new Date(timestamp);
                    const now = new Date();
                    const diffMins = Math.floor((now - date) / 60000);
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
                    return allItems.sort((a, b) => new Date(b.created_at || b.id) - new Date(a.created_at || a.id)).slice(0, 8);
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
                        this.bins[this.activeKey].lastEmptied = 'Just now';
                        this.activeBin = this.bins[this.activeKey];
                        this.showToast(`${this.activeBin.name} emptied`);
                    } catch (error) {
                        console.error("Error emptying bin:", error);
                        this.showToast('Failed to empty bin', 'error');
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
                        this.bins[binKey].lastEmptied = 'Just now';
                        this.showToast(`${this.bins[binKey].name} emptied`);
                    } catch (error) {
                        console.error("Error emptying bin:", error);
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
                        this.showToast(`Logged scan in ${this.activeBin.name}`);
                    } catch (error) {
                        console.error("Error simulating scan:", error);
                        this.showToast('Simulation failed', 'error');
                    } finally {
                        this.loadingAction = null;
                    }
                }
            }
        }
    </script>
</div>
