<div x-data="binModal()" class="flex flex-col lg:flex-row min-h-screen w-full bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300">
    <style>[x-cloak] { display: none !important; }</style>
    <!-- Sidebar -->
    @include('layouts.sidebar', ['active' => 'dashboard'])

    <!-- Main Content -->
    <main class="flex-1 w-full min-w-0 ml-0 lg:ml-72 p-4 sm:p-6 lg:p-10 xl:p-12">
        <!-- Header -->
        <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-200 dark:border-slate-800">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                        {{ Auth::user()->isSuperAdmin() ? 'Fleet Telemetry Console' : 'Operator Bin Console' }}
                    </h1>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-mono font-bold uppercase tracking-wider {{ Auth::user()->isSuperAdmin() ? 'bg-cyan-100 dark:bg-cyan-950 text-cyan-700 dark:text-cyan-400 border border-cyan-300 dark:border-cyan-800' : 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800' }}">
                        {{ Auth::user()->isSuperAdmin() ? 'Super Admin' : 'Admin' }}
                    </span>
                </div>
                <p class="text-slate-500 dark:text-slate-400 mt-1.5 text-xs sm:text-sm">
                    Logged in as <strong class="text-slate-700 dark:text-slate-200">{{ Auth::user()->email }}</strong>
                </p>
            </div>
        </header>

        <!-- Sensoneo-Style Smart Bin Fleet Overview -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-8 mb-8 shadow-sm dark:shadow-xl">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Ultrasonic Level Telemetry</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">Active Bin Storage Units</h2>
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 font-mono">
                    <span>Auto-Refresh: <span class="text-emerald-600 dark:text-emerald-400 font-bold">Live Stream</span></span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">                <!-- Hazardous Bin (Node 01) -->
                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-5 flex flex-col justify-between hover:border-red-500/50 transition-all shadow-sm dark:shadow-md group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-500">NODE 01</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"
                                  :class="bins.hazardous.level >= 80 ? 'bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-400 border border-red-300 dark:border-red-800' : 'bg-slate-200 dark:bg-slate-900 text-slate-700 dark:text-slate-400 border border-slate-300 dark:border-slate-800'">
                                <span x-text="bins.hazardous.status"></span>
                            </span>
                        </div>

                        <!-- Icon & Title -->
                        <div class="flex items-center gap-3.5 mb-5">
                            <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-950/40 border border-red-300 dark:border-red-800/60 flex items-center justify-center text-red-600 dark:text-red-400 group-hover:scale-105 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-skull"><path d="M9 10h.01"/><path d="M15 10h.01"/><path d="M12 2a8 8 0 0 0-8 8v1a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4v-1a8 8 0 0 0-8-8z"/><path d="M9 14v3a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-3"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Hazardous</h3>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono block">Chemical & Toxic</span>
                            </div>
                        </div>

                        <!-- Fill Level Metric -->
                        <div class="mb-4">
                            <div class="flex items-baseline justify-between mb-1.5">
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Fill Capacity</span>
                                <span class="text-2xl font-mono font-extrabold text-red-600 dark:text-red-400" x-text="bins.hazardous.level + '%'"></span>
                            </div>
                            <div class="h-2.5 w-full bg-slate-200 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-300 dark:border-slate-800">
                                <div class="h-full bg-red-500 rounded-full transition-all duration-700" :style="'width: ' + bins.hazardous.level + '%'"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-mono pt-2 border-t border-slate-200 dark:border-slate-900">
                            <span>Last Flush:</span>
                            <span class="text-slate-800 dark:text-slate-200" x-text="bins.hazardous.lastEmptied"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-4 pt-3 border-t border-slate-200 dark:border-slate-800/80">
                        <button @click="openModal('hazardous')" class="py-2 bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 shadow-sm transition-colors">Details</button>
                        <button @click="emptyBinDirect('hazardous')" class="py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg text-xs font-semibold shadow-sm transition-colors">Empty</button>
                    </div>
                </div>

                <!-- Recyclable Bin (Node 02) -->
                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-5 flex flex-col justify-between hover:border-sky-500/50 transition-all shadow-sm dark:shadow-md group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-500">NODE 02</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"
                                  :class="bins.recyclable.level >= 80 ? 'bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-400 border border-red-300 dark:border-red-800' : 'bg-slate-200 dark:bg-slate-900 text-slate-700 dark:text-slate-400 border border-slate-300 dark:border-slate-800'">
                                <span x-text="bins.recyclable.status"></span>
                            </span>
                        </div>

                        <!-- Icon & Title -->
                        <div class="flex items-center gap-3.5 mb-5">
                            <div class="w-12 h-12 rounded-xl bg-sky-100 dark:bg-sky-950/40 border border-sky-300 dark:border-sky-800/60 flex items-center justify-center text-sky-600 dark:text-sky-400 group-hover:scale-105 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Recyclable</h3>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono block">Plastic, Paper, Can</span>
                            </div>
                        </div>

                        <!-- Fill Level Metric -->
                        <div class="mb-4">
                            <div class="flex items-baseline justify-between mb-1.5">
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Fill Capacity</span>
                                <span class="text-2xl font-mono font-extrabold text-sky-600 dark:text-sky-400" x-text="bins.recyclable.level + '%'"></span>
                            </div>
                            <div class="h-2.5 w-full bg-slate-200 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-300 dark:border-slate-800">
                                <div class="h-full bg-sky-500 rounded-full transition-all duration-700" :style="'width: ' + bins.recyclable.level + '%'"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-mono pt-2 border-t border-slate-200 dark:border-slate-900">
                            <span>Last Flush:</span>
                            <span class="text-slate-800 dark:text-slate-200" x-text="bins.recyclable.lastEmptied"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-4 pt-3 border-t border-slate-200 dark:border-slate-800/80">
                        <button @click="openModal('recyclable')" class="py-2 bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 shadow-sm transition-colors">Details</button>
                        <button @click="emptyBinDirect('recyclable')" class="py-2 bg-sky-600 hover:bg-sky-500 text-white rounded-lg text-xs font-semibold shadow-sm transition-colors">Empty</button>
                    </div>
                </div>

                <!-- Biodegradable Bin (Node 03) -->
                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-5 flex flex-col justify-between hover:border-emerald-500/50 transition-all shadow-sm dark:shadow-md group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-500">NODE 03</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"
                                  :class="bins.biodegradable.level >= 80 ? 'bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-400 border border-red-300 dark:border-red-800' : 'bg-slate-200 dark:bg-slate-900 text-slate-700 dark:text-slate-400 border border-slate-300 dark:border-slate-800'">
                                <span x-text="bins.biodegradable.status"></span>
                            </span>
                        </div>

                        <!-- Icon & Title -->
                        <div class="flex items-center gap-3.5 mb-5">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-800/60 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-105 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C10.9 14.36 12 15 12 15"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Biodegradable</h3>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono block">Organic & Food Waste</span>
                            </div>
                        </div>

                        <!-- Fill Level Metric -->
                        <div class="mb-4">
                            <div class="flex items-baseline justify-between mb-1.5">
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Fill Capacity</span>
                                <span class="text-2xl font-mono font-extrabold text-emerald-600 dark:text-emerald-400" x-text="bins.biodegradable.level + '%'"></span>
                            </div>
                            <div class="h-2.5 w-full bg-slate-200 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-300 dark:border-slate-800">
                                <div class="h-full bg-emerald-500 rounded-full transition-all duration-700" :style="'width: ' + bins.biodegradable.level + '%'"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-mono pt-2 border-t border-slate-200 dark:border-slate-900">
                            <span>Last Flush:</span>
                            <span class="text-slate-800 dark:text-slate-200" x-text="bins.biodegradable.lastEmptied"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-4 pt-3 border-t border-slate-200 dark:border-slate-800/80">
                        <button @click="openModal('biodegradable')" class="py-2 bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 shadow-sm transition-colors">Details</button>
                        <button @click="emptyBinDirect('biodegradable')" class="py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-semibold shadow-sm transition-colors">Empty</button>
                    </div>
                </div>

                <!-- Non-Bio Bin (Node 04) -->
                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-5 flex flex-col justify-between hover:border-amber-500/50 transition-all shadow-sm dark:shadow-md group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-500">NODE 04</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"
                                  :class="bins['non-bio'].level >= 80 ? 'bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-400 border border-red-300 dark:border-red-800' : 'bg-slate-200 dark:bg-slate-900 text-slate-700 dark:text-slate-400 border border-slate-300 dark:border-slate-800'">
                                <span x-text="bins['non-bio'].status"></span>
                            </span>
                        </div>

                        <!-- Icon & Title -->
                        <div class="flex items-center gap-3.5 mb-5">
                            <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-800/60 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-105 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Non-Bio</h3>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono block">General Residual Waste</span>
                            </div>
                        </div>

                        <!-- Fill Level Metric -->
                        <div class="mb-4">
                            <div class="flex items-baseline justify-between mb-1.5">
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Fill Capacity</span>
                                <span class="text-2xl font-mono font-extrabold text-amber-600 dark:text-amber-400" x-text="bins['non-bio'].level + '%'"></span>
                            </div>
                            <div class="h-2.5 w-full bg-slate-200 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-300 dark:border-slate-800">
                                <div class="h-full bg-amber-500 rounded-full transition-all duration-700" :style="'width: ' + bins['non-bio'].level + '%'"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-mono pt-2 border-t border-slate-200 dark:border-slate-900">
                            <span>Last Flush:</span>
                            <span class="text-slate-800 dark:text-slate-200" x-text="bins['non-bio'].lastEmptied"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-4 pt-3 border-t border-slate-200 dark:border-slate-800/80">
                        <button @click="openModal('non-bio')" class="py-2 bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 shadow-sm transition-colors">Details</button>
                        <button @click="emptyBinDirect('non-bio')" class="py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-lg text-xs font-semibold shadow-sm transition-colors">Empty</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Telemetry & Activity Metrics Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-12">
            <!-- Card 1: Classification Volume Summary -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 flex flex-col justify-between shadow-sm dark:shadow-lg">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200 dark:border-slate-800">
                        <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Telemetry Volume</span>
                        <span class="text-xs font-mono text-slate-500 dark:text-slate-400 font-semibold">Today</span>
                    </div>

                    <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 mb-5 text-center">
                        <span class="block text-4xl font-mono font-extrabold text-slate-900 dark:text-white tracking-tight" x-text="totalProcessed"></span>
                        <span class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-1">Total Items Segregated</span>
                    </div>

                    <div class="space-y-4">
                        <!-- Hazardous -->
                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span> Hazardous</span>
                                <span class="font-mono text-slate-800 dark:text-slate-200" x-text="bins.hazardous.items.length + ' items'"></span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-950 rounded-full overflow-hidden border border-slate-200 dark:border-slate-800">
                                <div class="h-full bg-red-500 rounded-full transition-all" :style="'width: ' + (bins.hazardous.items.length / totalProcessed * 100 || 0) + '%'"></div>
                            </div>
                        </div>
                        <!-- Recyclable -->
                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-sky-500 inline-block"></span> Recyclable</span>
                                <span class="font-mono text-slate-800 dark:text-slate-200" x-text="bins.recyclable.items.length + ' items'"></span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-950 rounded-full overflow-hidden border border-slate-200 dark:border-slate-800">
                                <div class="h-full bg-sky-500 rounded-full transition-all" :style="'width: ' + (bins.recyclable.items.length / totalProcessed * 100 || 0) + '%'"></div>
                            </div>
                        </div>
                        <!-- Biodegradable -->
                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> Biodegradable</span>
                                <span class="font-mono text-slate-800 dark:text-slate-200" x-text="bins.biodegradable.items.length + ' items'"></span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-950 rounded-full overflow-hidden border border-slate-200 dark:border-slate-800">
                                <div class="h-full bg-emerald-500 rounded-full transition-all" :style="'width: ' + (bins.biodegradable.items.length / totalProcessed * 100 || 0) + '%'"></div>
                            </div>
                        </div>
                        <!-- Non-Bio -->
                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span> Non-Bio</span>
                                <span class="font-mono text-slate-800 dark:text-slate-200" x-text="bins['non-bio'].items.length + ' items'"></span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-950 rounded-full overflow-hidden border border-slate-200 dark:border-slate-800">
                                <div class="h-full bg-amber-500 rounded-full transition-all" :style="'width: ' + (bins['non-bio'].items.length / totalProcessed * 100 || 0) + '%'"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Live Activity Stream -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 flex flex-col justify-between shadow-sm dark:shadow-lg">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200 dark:border-slate-800">
                        <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Live Item Stream</span>
                        <a href="{{ route('dashboard.history') }}" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Full Log →</a>
                    </div>

                    <div class="space-y-2.5">
                        <template x-for="(activity, index) in recentActivity" :key="index">
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800/80">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl" x-text="activity.icon"></span>
                                    <div>
                                        <span class="block text-xs font-bold text-slate-900 dark:text-white truncate max-w-[130px]" x-text="activity.name"></span>
                                        <span class="block text-[10px] font-mono font-semibold"
                                              :class="{
                                                  'text-red-600 dark:text-red-400': activity.binColor === 'red',
                                                  'text-sky-600 dark:text-sky-400': activity.binColor === 'sky',
                                                  'text-emerald-600 dark:text-emerald-400': activity.binColor === 'emerald',
                                                  'text-amber-600 dark:text-amber-400': activity.binColor === 'orange'
                                              }"
                                              x-text="activity.binName"></span>
                                    </div>
                                </div>
                                <span class="text-xs font-mono font-semibold text-slate-500 dark:text-slate-400" x-text="activity.weight"></span>
                            </div>
                        </template>

                        <div x-show="recentActivity.length === 0" class="text-center py-8 text-slate-400 dark:text-slate-500 text-xs">
                            No recent items classified yet.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Details Dialog (Sensoneo Style) -->
    <div x-show="isOpen" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        
        <div @click.away="isOpen = false" 
             class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full p-6 relative overflow-hidden border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-start mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white uppercase" x-text="activeBin.name"></h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-0.5" x-text="activeBin.subtitle"></p>
                </div>
                <button @click="isOpen = false" class="p-1.5 text-slate-400 hover:text-slate-700 dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <!-- Fill Level Indicator -->
            <div class="bg-slate-50 dark:bg-slate-950 rounded-xl p-4 border border-slate-200 dark:border-slate-800 mb-6">
                <div class="flex justify-between items-center mb-2 text-xs">
                    <span class="font-mono text-slate-500 dark:text-slate-400 uppercase font-semibold">Ultrasonic Fill</span>
                    <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400" x-text="activeBin.level + '% (' + activeBin.status + ')'"></span>
                </div>
                <div class="h-2 w-full bg-slate-200 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-300 dark:border-slate-800">
                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-700" :style="'width: ' + activeBin.level + '%'"></div>
                </div>
            </div>

            <!-- Contents List -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3 text-xs font-mono font-semibold text-slate-500 dark:text-slate-400">
                    <span>DETECTED ITEMS</span>
                    <span x-text="activeBin.items.length + ' logged'"></span>
                </div>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    <template x-for="(item, index) in activeBin.items" :key="index">
                        <div class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800/80 rounded-lg text-xs">
                            <div class="flex items-center gap-2.5">
                                <span class="text-lg" x-text="item.icon"></span>
                                <span class="font-semibold text-slate-900 dark:text-white" x-text="item.name"></span>
                            </div>
                            <span class="font-mono text-slate-500 dark:text-slate-400 text-[11px]" x-text="'~' + item.weight"></span>
                        </div>
                    </template>
                    <div x-show="activeBin.items.length === 0" class="text-center py-6 text-slate-400 dark:text-slate-500 text-xs italic">
                        Bin unit is currently empty.
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <button @click="emptyBin()" class="py-2.5 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-transparent hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider transition-colors shadow-sm">
                    Empty Bin
                </button>
                <button @click="simulateScan()" class="py-2.5 bg-emerald-600 hover:bg-emerald-500 rounded-xl text-xs font-bold text-white uppercase tracking-wider transition-colors shadow-sm">
                    Simulate Scan
                </button>
            </div>

            <!-- Footer -->
            <div class="pt-3 border-t border-slate-200 dark:border-slate-800 text-[11px] text-slate-500 dark:text-slate-400 font-mono text-center">
                Last Emptied: <span class="text-slate-800 dark:text-slate-200" x-text="activeBin.lastEmptied"></span>
            </div>
        </div>
    </div>

    <script>
        function binModal() {
            return {
                sidebarOpen: false,
                isOpen: false,
                activeKey: '',
                activeBin: {
                    name: '',
                    subtitle: '',
                    color: 'emerald',
                    level: 0,
                    status: '',
                    lastEmptied: '',
                    items: []
                },
                bins: {
                    hazardous: {
                        name: 'Hazardous',
                        subtitle: 'Toxic & Chemical Waste Bin',
                        color: 'red',
                        level: 0,
                        status: 'Empty',
                        lastEmptied: 'Never',
                        items: []
                    },
                    recyclable: {
                        name: 'Recyclable',
                        subtitle: 'Recoverable Waste Bin',
                        color: 'sky',
                        level: 0,
                        status: 'Empty',
                        lastEmptied: 'Never',
                        items: []
                    },
                    biodegradable: {
                        name: 'Biodegradable',
                        subtitle: 'Organic Waste Bin',
                        color: 'emerald',
                        level: 0,
                        status: 'Empty',
                        lastEmptied: 'Never',
                        items: []
                    },
                    'non-bio': {
                        name: 'Non-Biodegradable',
                        subtitle: 'General Waste Bin',
                        color: 'orange',
                        level: 0,
                        status: 'Empty',
                        lastEmptied: 'Never',
                        items: []
                    }
                },
                init() {
                    this.fetchBins();
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
                    } catch (error) {
                        console.error("Error fetching bin details:", error);
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
                    if (diffMins < 60) return `${diffMins} min ago`;
                    if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
                    return date.toLocaleDateString();
                },
                get totalProcessed() {
                    return Object.values(this.bins).reduce((acc, bin) => acc + bin.items.length, 0);
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
                                    icon: item.icon,
                                    weight: item.weight,
                                    created_at: item.created_at,
                                    binName: bin.name,
                                    binColor: bin.color
                                });
                            });
                        }
                    });
                    return allItems.sort((a, b) => new Date(b.created_at || b.id) - new Date(a.created_at || a.id)).slice(0, 3);
                },
                openModal(binKey) {
                    this.activeKey = binKey;
                    this.activeBin = this.bins[binKey];
                    this.isOpen = true;
                },
                async emptyBin() {
                    try {
                        const response = await axios.post(`/api/bins/${this.activeKey}/empty`);
                        const updatedBin = response.data;
                        
                        this.bins[this.activeKey].level = updatedBin.level;
                        this.bins[this.activeKey].items = updatedBin.items || [];
                        this.bins[this.activeKey].status = updatedBin.status;
                        this.bins[this.activeKey].lastEmptied = 'Just now';
                        
                        this.activeBin = this.bins[this.activeKey];
                    } catch (error) {
                        console.error("Error emptying bin:", error);
                    }
                },
                async emptyBinDirect(binKey) {
                    try {
                        const response = await axios.post(`/api/bins/${binKey}/empty`);
                        const updatedBin = response.data;
                        
                        this.bins[binKey].level = updatedBin.level;
                        this.bins[binKey].items = updatedBin.items || [];
                        this.bins[binKey].status = updatedBin.status;
                        this.bins[binKey].lastEmptied = 'Just now';
                        
                        if (this.isOpen && this.activeKey === binKey) {
                            this.activeBin = this.bins[binKey];
                        }
                    } catch (error) {
                        console.error("Error emptying bin:", error);
                    }
                },
                async simulateScan() {
                    try {
                        const response = await axios.post(`/api/bins/${this.activeKey}/scan`);
                        const updatedBin = response.data;
                        
                        this.bins[this.activeKey].level = updatedBin.level;
                        this.bins[this.activeKey].items = updatedBin.items || [];
                        this.bins[this.activeKey].status = updatedBin.status;
                        
                        this.activeBin = this.bins[this.activeKey];
                    } catch (error) {
                        console.error("Error simulating scan:", error);
                    }
                }
            }
        }
    </script>
</div>
