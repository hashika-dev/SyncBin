<div x-data="binModal()" class="flex flex-col lg:flex-row min-h-screen w-full bg-gradient-to-br from-rose-50 via-white to-orange-50/50 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950 text-gray-900 dark:text-zinc-100 transition-colors duration-300">
    <style>[x-cloak] { display: none !important; }</style>
    <!-- Sidebar -->
    @include('layouts.sidebar', ['active' => 'dashboard'])

    <!-- Main Content -->
    <main class="flex-1 w-full min-w-0 ml-0 lg:ml-72 p-4 sm:p-6 lg:p-10 xl:p-12">
        <!-- Header -->
        <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 sm:mb-12">
            <div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-rose-950 dark:text-zinc-100 tracking-tighter">Admin Dashboard</h1>
                <p class="text-rose-600 dark:text-rose-400 mt-2 font-bold text-sm sm:text-base lg:text-lg opacity-80">
                    Welcome back, <span class="font-black">{{ Auth::user()->email }}</span> 
                    <span class="hidden sm:inline mx-2 opacity-30">|</span> 
                    <span class="block sm:inline">Real-time waste bin monitoring</span>
                </p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button @click="openCameraModal()" class="w-full sm:w-auto flex items-center justify-center gap-2.5 px-6 py-3.5 bg-rose-600 text-white rounded-2xl font-black shadow-lg shadow-rose-200 dark:shadow-none hover:bg-rose-700 hover:-translate-y-0.5 transition-all active:scale-95 text-sm shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                    AI Camera Simulator
                </button>
                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('dashboard.export') }}" target="_blank" class="w-full sm:w-auto flex items-center justify-center gap-3 px-6 py-3.5 bg-emerald-500 text-white rounded-2xl font-black shadow-lg shadow-emerald-200 dark:shadow-none hover:bg-emerald-600 hover:-translate-y-0.5 transition-all active:scale-95 text-sm shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
                    Export Report
                </a>
                @endif
            </div>
        </header>

        <!-- Hero Bin Panel -->
        <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-3xl sm:rounded-[2.5rem] lg:rounded-[3.5rem] shadow-xl dark:shadow-none border border-white dark:border-zinc-800 p-5 sm:p-8 lg:p-12 mb-8 sm:mb-12 transition-colors duration-300">
            <div class="text-center mb-8 sm:mb-14">
                <h3 class="text-[10px] sm:text-xs font-black uppercase tracking-[0.3em] sm:tracking-[0.5em] text-rose-400 dark:text-zinc-500 mb-2">Live Status Overview</h3>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-rose-950 dark:text-zinc-100 tracking-tight">System Bin Management</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <!-- Hazardous Bin -->
                <div class="flex flex-col items-center">
                    <div class="relative w-full aspect-[4/5] min-h-[280px] bg-red-50/30 dark:bg-red-950/10 rounded-3xl lg:rounded-[2.5rem] border-2 border-red-100 dark:border-red-900/30 p-6 sm:p-8 flex flex-col justify-end overflow-hidden group hover:border-red-300 dark:hover:border-red-800 transition-all duration-700 shadow-sm hover:shadow-2xl">
                        <!-- Fill Level -->
                        <div class="absolute bottom-0 left-0 w-full bg-red-400/20 transition-all duration-1000 ease-out" :style="'height: ' + bins.hazardous.level + '%'">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-red-400 shadow-[0_0_20px_rgba(239,68,68,0.6)]"></div>
                        </div>

                        <!-- Bin Icon & Label -->
                        <div class="relative z-10 flex flex-col items-center gap-4 sm:gap-6">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 lg:w-28 lg:h-28 bg-white dark:bg-zinc-800 rounded-2xl lg:rounded-[2rem] shadow-xl dark:shadow-none flex items-center justify-center text-red-500 group-hover:scale-110 group-hover:rotate-3 transition-all duration-700 border border-red-50 dark:border-zinc-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sm:w-14 sm:h-14 lucide lucide-skull"><path d="M9 10h.01"/><path d="M15 10h.01"/><path d="M12 2a8 8 0 0 0-8 8v1a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4v-1a8 8 0 0 0-8-8z"/><path d="M9 14v3a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-3"/></svg>
                            </div>
                            <div class="text-center">
                                <span class="inline-block px-4 py-1.5 bg-rose-500 text-white text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-3 shadow-md shadow-rose-200 dark:shadow-none" x-show="bins.hazardous.level >= 75">Attention Required</span>
                                <span class="inline-block px-4 py-1.5 bg-red-500 text-white text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-3 shadow-md shadow-red-200 dark:shadow-none" x-show="bins.hazardous.level < 75">Optimal</span>
                                <h4 class="text-lg sm:text-xl font-black text-red-900 dark:text-red-300 tracking-tight uppercase">Hazardous</h4>
                            </div>
                        </div>

                        <!-- Capacity Data -->
                        <div class="absolute top-6 right-6 sm:top-8 sm:right-8 text-right">
                            <span class="block text-3xl sm:text-4xl lg:text-5xl font-black text-red-600 dark:text-red-400 tracking-tighter" x-text="bins.hazardous.level + '%'"></span>
                            <span class="block text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-red-400 dark:text-red-500 mt-0.5" x-text="bins.hazardous.status"></span>
                        </div>
                    </div>
                    
                    <div class="mt-6 w-full space-y-3">
                        <div class="flex justify-between items-center px-5 py-3 bg-white/50 dark:bg-zinc-800/50 rounded-2xl border border-red-100 dark:border-zinc-800">
                            <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-red-400 dark:text-red-500">Status Update</span>
                            <span class="text-xs font-bold text-red-700 dark:text-red-400" x-text="bins.hazardous.lastEmptied"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <button @click="openModal('hazardous')" class="py-3 bg-white dark:bg-zinc-800 border border-red-100 dark:border-zinc-700 rounded-xl text-xs font-black text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-zinc-700 transition-all">Details</button>
                            <button @click="emptyBinDirect('hazardous')" class="py-3 bg-red-500 text-white rounded-xl text-xs font-black hover:bg-red-600 transition-all shadow-md shadow-red-100 dark:shadow-none">Empty Bin</button>
                        </div>
                    </div>
                </div>

                <!-- Recyclable Bin -->
                <div class="flex flex-col items-center">
                    <div class="relative w-full aspect-[4/5] min-h-[280px] bg-sky-50/30 dark:bg-sky-950/10 rounded-3xl lg:rounded-[2.5rem] border-2 border-sky-100 dark:border-sky-900/30 p-6 sm:p-8 flex flex-col justify-end overflow-hidden group hover:border-sky-300 dark:hover:border-sky-800 transition-all duration-700 shadow-sm hover:shadow-2xl">
                        <!-- Fill Level -->
                        <div class="absolute bottom-0 left-0 w-full bg-sky-400/20 transition-all duration-1000 ease-out" :style="'height: ' + bins.recyclable.level + '%'">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-sky-400 shadow-[0_0_20px_rgba(56,189,248,0.6)]"></div>
                        </div>

                        <!-- Bin Icon & Label -->
                        <div class="relative z-10 flex flex-col items-center gap-4 sm:gap-6">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 lg:w-28 lg:h-28 bg-white dark:bg-zinc-800 rounded-2xl lg:rounded-[2rem] shadow-xl dark:shadow-none flex items-center justify-center text-sky-500 group-hover:scale-110 transition-all duration-700 border border-sky-50 dark:border-zinc-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="sm:w-14 sm:h-14"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                            </div>
                            <div class="text-center">
                                <span class="inline-block px-4 py-1.5 bg-rose-600 text-white text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-3 shadow-md shadow-rose-200 dark:shadow-none" x-show="bins.recyclable.level >= 85">Critical Full</span>
                                <span class="inline-block px-4 py-1.5 bg-emerald-500 text-white text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-3 shadow-md shadow-emerald-200 dark:shadow-none" x-show="bins.recyclable.level < 85">Optimal</span>
                                <h4 class="text-lg sm:text-xl font-black text-sky-900 dark:text-sky-300 tracking-tight uppercase">Recyclable</h4>
                            </div>
                        </div>

                        <!-- Capacity Data -->
                        <div class="absolute top-6 right-6 sm:top-8 sm:right-8 text-right">
                            <span class="block text-3xl sm:text-4xl lg:text-5xl font-black text-sky-600 dark:text-sky-400 tracking-tighter" x-text="bins.recyclable.level + '%'"></span>
                            <span class="block text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-sky-400 dark:text-sky-500 mt-0.5" x-text="bins.recyclable.status"></span>
                        </div>
                    </div>
                    
                    <div class="mt-6 w-full space-y-3">
                        <div class="flex justify-between items-center px-5 py-3 bg-white/50 dark:bg-zinc-800/50 rounded-2xl border border-sky-100 dark:border-zinc-800">
                            <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-sky-400 dark:text-sky-500">Status Update</span>
                            <span class="text-xs font-bold text-sky-700 dark:text-sky-400" x-text="bins.recyclable.lastEmptied"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <button @click="openModal('recyclable')" class="py-3 bg-white dark:bg-zinc-800 border border-sky-100 dark:border-zinc-700 rounded-xl text-xs font-black text-sky-600 dark:text-sky-400 hover:bg-sky-50 dark:hover:bg-zinc-700 transition-all">Details</button>
                            <button @click="emptyBinDirect('recyclable')" class="py-3 bg-sky-500 text-white rounded-xl text-xs font-black hover:bg-sky-600 transition-all shadow-md shadow-sky-100 dark:shadow-none">Empty Bin</button>
                        </div>
                    </div>
                </div>

                <!-- Non-Bio Degradable Bin -->
                <div class="flex flex-col items-center">
                    <div class="relative w-full aspect-[4/5] min-h-[280px] bg-orange-50/30 dark:bg-orange-950/10 rounded-3xl lg:rounded-[2.5rem] border-2 border-orange-100 dark:border-orange-900/30 p-6 sm:p-8 flex flex-col justify-end overflow-hidden group hover:border-orange-300 dark:hover:border-orange-800 transition-all duration-700 shadow-sm hover:shadow-2xl">
                        <!-- Fill Level -->
                        <div class="absolute bottom-0 left-0 w-full bg-orange-400/20 transition-all duration-1000 ease-out" :style="'height: ' + bins['non-bio'].level + '%'">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-orange-400 shadow-[0_0_20px_rgba(251,146,60,0.6)]"></div>
                        </div>

                        <!-- Bin Icon & Label -->
                        <div class="relative z-10 flex flex-col items-center gap-4 sm:gap-6">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 lg:w-28 lg:h-28 bg-white dark:bg-zinc-800 rounded-2xl lg:rounded-[2rem] shadow-xl dark:shadow-none flex items-center justify-center text-orange-500 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-700 border border-orange-50 dark:border-zinc-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sm:w-14 sm:h-14"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </div>
                            <div class="text-center">
                                <span class="inline-block px-4 py-1.5 bg-rose-500 text-white text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-3 shadow-md shadow-rose-200 dark:shadow-none" x-show="bins['non-bio'].level >= 75">Attention Required</span>
                                <span class="inline-block px-4 py-1.5 bg-emerald-500 text-white text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-3 shadow-md shadow-emerald-200 dark:shadow-none" x-show="bins['non-bio'].level < 75">Optimal</span>
                                <h4 class="text-lg sm:text-xl font-black text-orange-900 dark:text-orange-300 tracking-tight uppercase">Non-Bio</h4>
                            </div>
                        </div>

                        <!-- Capacity Data -->
                        <div class="absolute top-6 right-6 sm:top-8 sm:right-8 text-right">
                            <span class="block text-3xl sm:text-4xl lg:text-5xl font-black text-orange-600 dark:text-orange-400 tracking-tighter" x-text="bins['non-bio'].level + '%'"></span>
                            <span class="block text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-orange-400 dark:text-orange-500 mt-0.5" x-text="bins['non-bio'].status"></span>
                        </div>
                    </div>
                    
                    <div class="mt-6 w-full space-y-3">
                        <div class="flex justify-between items-center px-5 py-3 bg-white/50 dark:bg-zinc-800/50 rounded-2xl border border-orange-100 dark:border-zinc-800">
                            <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-orange-400 dark:text-orange-500">Status Update</span>
                            <span class="text-xs font-bold text-orange-700 dark:text-orange-400" x-text="bins['non-bio'].lastEmptied"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <button @click="openModal('non-bio')" class="py-3 bg-white dark:bg-zinc-800 border border-orange-100 dark:border-zinc-700 rounded-xl text-xs font-black text-orange-600 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-zinc-700 transition-all">Details</button>
                            <button @click="emptyBinDirect('non-bio')" class="py-3 bg-orange-400 text-white rounded-xl text-xs font-black hover:bg-orange-500 transition-all shadow-md shadow-orange-100 dark:shadow-none">Empty Bin</button>
                        </div>
                    </div>
                </div>

                <!-- Bio-Degradable Bin -->
                <div class="flex flex-col items-center">
                    <div class="relative w-full aspect-[4/5] min-h-[280px] bg-emerald-50/30 dark:bg-emerald-950/10 rounded-3xl lg:rounded-[2.5rem] border-2 border-emerald-100 dark:border-emerald-900/30 p-6 sm:p-8 flex flex-col justify-end overflow-hidden group hover:border-emerald-300 dark:hover:border-emerald-800 transition-all duration-700 shadow-sm hover:shadow-2xl">
                        <!-- Fill Level -->
                        <div class="absolute bottom-0 left-0 w-full bg-emerald-400/20 transition-all duration-1000 ease-out" :style="'height: ' + bins.biodegradable.level + '%'">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-emerald-400 shadow-[0_0_20px_rgba(52,211,153,0.6)]"></div>
                        </div>

                        <!-- Bin Icon & Label -->
                        <div class="relative z-10 flex flex-col items-center gap-4 sm:gap-6">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 lg:w-28 lg:h-28 bg-white dark:bg-zinc-800 rounded-2xl lg:rounded-[2rem] shadow-xl dark:shadow-none flex items-center justify-center text-emerald-500 group-hover:scale-110 group-hover:rotate-3 transition-all duration-700 border border-emerald-50 dark:border-zinc-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sm:w-14 sm:h-14"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C10.9 14.36 12 15 12 15"/></svg>
                            </div>
                            <div class="text-center">
                                <span class="inline-block px-4 py-1.5 bg-rose-500 text-white text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-3 shadow-md shadow-rose-200 dark:shadow-none" x-show="bins.biodegradable.level >= 75">Attention Required</span>
                                <span class="inline-block px-4 py-1.5 bg-emerald-500 text-white text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-3 shadow-md shadow-emerald-200 dark:shadow-none" x-show="bins.biodegradable.level < 75">Optimal</span>
                                <h4 class="text-lg sm:text-xl font-black text-emerald-900 dark:text-emerald-300 tracking-tight uppercase">Biodegradable</h4>
                            </div>
                        </div>

                        <!-- Capacity Data -->
                        <div class="absolute top-6 right-6 sm:top-8 sm:right-8 text-right">
                            <span class="block text-3xl sm:text-4xl lg:text-5xl font-black text-emerald-600 dark:text-emerald-400 tracking-tighter" x-text="bins.biodegradable.level + '%'"></span>
                            <span class="block text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-emerald-400 dark:text-emerald-500 mt-0.5" x-text="bins.biodegradable.status"></span>
                        </div>
                    </div>
                    
                    <div class="mt-6 w-full space-y-3">
                        <div class="flex justify-between items-center px-5 py-3 bg-white/50 dark:bg-zinc-800/50 rounded-2xl border border-emerald-100 dark:border-zinc-800">
                            <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-emerald-400 dark:text-emerald-500">Status Update</span>
                            <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400" x-text="bins.biodegradable.lastEmptied"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <button @click="openModal('biodegradable')" class="py-3 bg-white dark:bg-zinc-800 border border-emerald-100 dark:border-zinc-700 rounded-xl text-xs font-black text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-zinc-700 transition-all">Details</button>
                            <button @click="emptyBinDirect('biodegradable')" class="py-3 bg-emerald-500 text-white rounded-xl text-xs font-black hover:bg-emerald-600 transition-all shadow-md shadow-emerald-100 dark:shadow-none">Empty Bin</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 pb-16 sm:pb-20">
            <!-- Card 1: AI Sorting Data -->
            <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl rounded-3xl lg:rounded-[2.5rem] shadow-xl shadow-rose-200/10 dark:shadow-none border border-white dark:border-zinc-800 p-6 sm:p-8 flex flex-col transition-colors duration-300">
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-xl sm:text-2xl font-black text-rose-950 dark:text-zinc-100 tracking-tight">AI Analytics</h3>
                    <p class="text-[9px] sm:text-[10px] font-black text-rose-400 dark:text-rose-400 uppercase tracking-[0.3em] mt-1">Daily Performance</p>
                </div>

                <div class="bg-rose-50/50 dark:bg-zinc-800/50 rounded-2xl lg:rounded-[2rem] p-6 text-center border border-rose-100 dark:border-zinc-700 mb-6 sm:mb-8 transition-colors duration-300">
                    <span class="block text-4xl sm:text-5xl lg:text-6xl font-black text-rose-600 dark:text-rose-400 tracking-tighter" x-text="totalProcessed"></span>
                    <span class="block text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] text-rose-400 dark:text-zinc-500 mt-2">Items Processed Today</span>
                </div>

                <div class="space-y-6 flex-1">
                    <!-- Hazard -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-red-400"></div>
                                <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-rose-900/60 dark:text-zinc-400">Hazardous</span>
                            </div>
                            <span class="text-xs sm:text-sm font-black text-rose-950 dark:text-zinc-300"><span x-text="bins.hazardous.items.length"></span> items</span>
                        </div>
                        <div class="h-2 w-full bg-rose-50 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="h-full bg-red-400 rounded-full transition-all duration-500" :style="'width: ' + (bins.hazardous.items.length / totalProcessed * 100 || 0) + '%'"></div>
                        </div>
                    </div>
                    <!-- Recyclable -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-sky-400"></div>
                                <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-rose-900/60 dark:text-zinc-400">Recyclable</span>
                            </div>
                            <span class="text-xs sm:text-sm font-black text-rose-950 dark:text-zinc-300"><span x-text="bins.recyclable.items.length"></span> items</span>
                        </div>
                        <div class="h-2 w-full bg-rose-50 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="h-full bg-sky-400 rounded-full transition-all duration-500" :style="'width: ' + (bins.recyclable.items.length / totalProcessed * 100 || 0) + '%'"></div>
                        </div>
                    </div>
                    <!-- Non-Bio -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-orange-400"></div>
                                <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-rose-900/60 dark:text-zinc-400">Non-Bio</span>
                            </div>
                            <span class="text-xs sm:text-sm font-black text-rose-950 dark:text-zinc-300"><span x-text="bins['non-bio'].items.length"></span> items</span>
                        </div>
                        <div class="h-2 w-full bg-rose-50 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-400 rounded-full transition-all duration-500" :style="'width: ' + (bins['non-bio'].items.length / totalProcessed * 100 || 0) + '%'"></div>
                        </div>
                    </div>
                    <!-- Bio -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-rose-900/60 dark:text-zinc-400">Biodegradable</span>
                            </div>
                            <span class="text-sm font-black text-rose-950 dark:text-zinc-300"><span x-text="bins.biodegradable.items.length"></span> items</span>
                        </div>
                        <div class="h-2 w-full bg-rose-50 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-400 rounded-full transition-all duration-500" :style="'width: ' + (bins.biodegradable.items.length / totalProcessed * 100 || 0) + '%'"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Recent Activity -->
            <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl rounded-[3rem] shadow-xl shadow-rose-200/10 dark:shadow-none border border-white dark:border-zinc-800 p-10 flex flex-col transition-colors duration-300">
                <div class="mb-10">
                    <h3 class="text-2xl font-black text-rose-950 dark:text-zinc-100 tracking-tight">Recent Activity</h3>
                    <p class="text-[10px] font-black text-rose-300 dark:text-rose-400 uppercase tracking-[0.3em] mt-2">Latest Operations</p>
                </div>

                <div class="flex-1 flex flex-col">
                    <a href="{{ route('dashboard.history') }}" class="w-full p-6 bg-rose-50/50 dark:bg-zinc-800/50 border border-rose-100 dark:border-zinc-700 rounded-3xl flex items-center justify-between group hover:bg-rose-500 hover:border-rose-500 hover:text-white transition-all duration-500">
                        <span class="text-sm font-black text-rose-900 dark:text-zinc-300 group-hover:text-white transition-colors uppercase tracking-widest">Access Logs</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-rose-300 group-hover:text-white transition-all"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>

                    <div class="flex-1 flex flex-col justify-center gap-4 py-8">
                        <template x-for="(activity, index) in recentActivity" :key="index">
                            <div class="flex items-center justify-between p-4 bg-rose-50/20 dark:bg-zinc-800/30 border border-rose-100/50 dark:border-zinc-800 rounded-2xl transition-all hover:scale-[1.02] duration-300">
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl" x-text="activity.icon"></span>
                                    <div>
                                        <span class="block text-xs font-black text-rose-950 dark:text-zinc-100" x-text="activity.name"></span>
                                        <span class="block text-[9px] font-black uppercase tracking-wider mt-0.5" 
                                              :class="{
                                                  'text-red-500': activity.binColor === 'red',
                                                  'text-sky-500': activity.binColor === 'sky',
                                                  'text-emerald-500': activity.binColor === 'emerald',
                                                  'text-orange-500': activity.binColor === 'orange'
                                              }"
                                              x-text="activity.binName"></span>
                                    </div>
                                </div>
                                <span class="text-xs font-black text-rose-500" x-text="activity.weight"></span>
                            </div>
                        </template>

                        <div x-show="recentActivity.length === 0" class="text-center py-6">
                            <div class="w-12 h-12 bg-rose-50 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-3 text-rose-200">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            </div>
                            <p class="text-xs text-rose-300 dark:text-rose-450 italic font-bold max-w-[180px] mx-auto">
                                No recent activity. Start scanning items to see real-time log data.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: System Alerts -->
            <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl rounded-[3rem] shadow-xl shadow-rose-200/10 dark:shadow-none border border-white dark:border-zinc-800 p-10 flex flex-col transition-colors duration-300">
                <div class="mb-10">
                    <h3 class="text-2xl font-black text-rose-950 dark:text-zinc-100 tracking-tight">System Alerts</h3>
                    <p class="text-[10px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-[0.3em] mt-2">Critical Notifications</p>
                </div>

                <div class="flex-1 flex flex-col">
                    <div class="rounded-[2.5rem] p-8 flex-1 flex flex-col justify-between transition-all duration-500"
                         :class="Object.values(bins).some(bin => bin.level >= 85) ? 'bg-red-500 shadow-xl shadow-red-200 dark:shadow-none animate-pulse' : 'bg-emerald-500 shadow-xl shadow-emerald-100 dark:shadow-none'">
                        <div class="flex gap-5 mb-8">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white flex-shrink-0 border border-white/20">
                                <template x-if="Object.values(bins).some(bin => bin.level >= 85)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                                </template>
                                <template x-if="!Object.values(bins).some(bin => bin.level >= 85)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                </template>
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-white leading-tight uppercase tracking-tight">System Status</h4>
                                <p class="text-xs font-bold text-white/95 mt-1 opacity-90 italic" 
                                   x-text="Object.values(bins).some(bin => bin.level >= 85) ? 'Critical: Bin(s) Full - Admin email alert dispatched.' : 'All systems operating normally'"></p>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <button class="w-full py-5 bg-white dark:bg-zinc-800 text-rose-600 dark:text-rose-400 rounded-3xl font-black flex items-center justify-center gap-3 shadow-xl dark:shadow-none hover:scale-[1.03] transition-all active:scale-95 uppercase text-xs tracking-[0.2em] border dark:border-zinc-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                Contact Support
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Overlay -->
    <div x-show="isOpen" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        
        <!-- Modal Container -->
        <div @click.away="isOpen = false" 
             class="bg-white dark:bg-zinc-900 rounded-[2.5rem] shadow-2xl dark:shadow-none max-w-md w-full p-10 relative overflow-hidden border border-rose-100 dark:border-zinc-800 transition-colors duration-300"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-3xl font-black tracking-tight uppercase" 
                        :class="{
                            'text-emerald-600': activeBin.color === 'emerald',
                            'text-orange-600': activeBin.color === 'orange',
                            'text-sky-600': activeBin.color === 'sky',
                            'text-red-600': activeBin.color === 'red'
                        }" 
                        x-text="activeBin.name"></h2>
                    <p class="text-xs font-black text-rose-300 dark:text-zinc-500 uppercase tracking-widest mt-1" x-text="activeBin.subtitle"></p>
                </div>
                <button @click="isOpen = false" class="w-10 h-10 bg-rose-50 dark:bg-zinc-800 text-rose-500 dark:text-rose-400 rounded-xl flex items-center justify-center hover:bg-rose-100 dark:hover:bg-zinc-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <!-- Fill Level Indicator -->
            <div class="bg-rose-50/30 dark:bg-zinc-800/30 rounded-3xl p-6 border border-rose-50 dark:border-zinc-800 mb-8 transition-colors duration-300">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-rose-900/40 dark:text-zinc-500">Fill Level</span>
                    <span class="text-sm font-black" 
                          :class="{
                            'text-emerald-600': activeBin.color === 'emerald',
                            'text-orange-600': activeBin.color === 'orange',
                            'text-sky-600': activeBin.color === 'sky',
                            'text-red-600': activeBin.color === 'red'
                          }" 
                          x-text="activeBin.level + '% — ' + activeBin.status"></span>
                </div>
                <div class="h-3 w-full bg-white dark:bg-zinc-800 rounded-full overflow-hidden border border-rose-100 dark:border-zinc-700">
                    <div class="h-full transition-all duration-1000" 
                         :class="{
                            'bg-emerald-500': activeBin.color === 'emerald',
                            'bg-orange-400': activeBin.color === 'orange',
                            'bg-sky-500': activeBin.color === 'sky',
                            'bg-red-500': activeBin.color === 'red'
                         }" 
                         :style="'width: ' + activeBin.level + '%'"></div>
                </div>
            </div>

            <!-- Contents List -->
            <div class="mb-8">
                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-rose-300 dark:text-zinc-500 mb-5">Contents (<span x-text="activeBin.items.length"></span> items detected)</h3>
                <div class="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                    <template x-for="(item, index) in activeBin.items" :key="index">
                        <div class="flex items-center justify-between p-4 bg-white dark:bg-zinc-800/50 border rounded-2xl transition-all" 
                             :class="{
                                'border-emerald-100 dark:border-emerald-950 hover:border-emerald-300 dark:hover:border-emerald-800': activeBin.color === 'emerald',
                                'border-orange-100 dark:border-orange-950 hover:border-orange-300 dark:hover:border-orange-800': activeBin.color === 'orange',
                                'border-sky-100 dark:border-sky-950 hover:border-sky-300 dark:hover:border-sky-800': activeBin.color === 'sky',
                                'border-red-100 dark:border-red-950 hover:border-red-300 dark:hover:border-red-800': activeBin.color === 'red'
                             }"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-x-4"
                             x-transition:enter-end="opacity-100 translate-x-0">
                            <div class="flex items-center gap-4">
                                <span class="text-2xl" x-text="item.icon"></span>
                                <span class="text-sm font-bold text-rose-950 dark:text-zinc-100" x-text="item.name"></span>
                            </div>
                            <span class="text-xs font-black text-rose-500 dark:text-rose-400" x-text="'~' + item.weight"></span>
                        </div>
                    </template>
                    <div x-show="activeBin.items.length === 0" class="text-center py-8 bg-rose-50/20 dark:bg-zinc-800/10 rounded-2xl border border-dashed border-rose-100 dark:border-zinc-800">
                        <p class="text-xs font-bold text-rose-300 dark:text-zinc-500 uppercase tracking-widest italic">Bin is currently empty</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                <button @click="emptyBin()" 
                        class="py-4 border-2 border-rose-100 dark:border-zinc-800 text-rose-350 dark:text-zinc-500 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all active:scale-95">
                    Empty Bin
                </button>
                <button @click="simulateScan()" 
                        class="py-4 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-lg transition-all active:scale-95"
                        :class="{
                            'bg-emerald-500 shadow-emerald-100 dark:shadow-none hover:bg-emerald-600': activeBin.color === 'emerald',
                            'bg-orange-400 shadow-orange-100 dark:shadow-none hover:bg-orange-500': activeBin.color === 'orange',
                            'bg-sky-500 shadow-sky-100 dark:shadow-none hover:bg-sky-600': activeBin.color === 'sky',
                            'bg-red-500 shadow-red-100 dark:shadow-none hover:bg-red-600': activeBin.color === 'red'
                        }">
                    Simulate Scan
                </button>
            </div>

            <!-- Modal Footer -->
            <div class="bg-rose-50/50 dark:bg-zinc-800/50 rounded-2xl p-4 flex items-center gap-3 border border-rose-100 dark:border-zinc-800 transition-colors duration-300">
                <div class="w-8 h-8 bg-white dark:bg-zinc-800 rounded-lg flex items-center justify-center text-rose-400 dark:text-zinc-500 shadow-sm dark:shadow-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <span class="text-xs font-bold text-rose-900/60 dark:text-zinc-400">Last emptied: <span x-text="activeBin.lastEmptied"></span></span>
            </div>
        </div>
    </div>

    <!-- AI Camera Simulator Modal -->
    <div x-show="cameraModalOpen" 
         class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-black/60 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        
        <div @click.away="cameraModalOpen = false" 
             class="bg-white dark:bg-zinc-900 rounded-[2.5rem] shadow-2xl max-w-2xl w-full p-6 sm:p-10 relative overflow-hidden border border-rose-100 dark:border-zinc-800 transition-all">
            
            <!-- Header -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <div class="flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-rose-500 mb-1">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                        AI Camera Vision Module
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-rose-950 dark:text-zinc-100 tracking-tight">Camera & YOLO Simulator</h2>
                </div>
                <button @click="cameraModalOpen = false" class="w-10 h-10 bg-rose-50 dark:bg-zinc-800 text-rose-500 rounded-xl flex items-center justify-center hover:bg-rose-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <!-- Body Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Left: Controls & Presets -->
                <div class="space-y-4">
                    <label class="block text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-400">1. Select Waste Sample Preset</label>
                    <div class="space-y-2">
                        <template x-for="(preset, key) in aiPresetDetails" :key="key">
                            <button @click="aiSelectedPreset = key; aiCustomFile = null; aiPreviewUrl = ''"
                                    class="w-full flex items-center justify-between p-3 rounded-2xl border text-left transition-all"
                                    :class="aiSelectedPreset === key && !aiCustomFile ? 'bg-rose-500 text-white border-rose-500 shadow-md shadow-rose-200' : 'bg-gray-50 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border-gray-200 dark:border-zinc-700 hover:border-rose-300'">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl" x-text="preset.icon"></span>
                                    <div>
                                        <span class="block text-xs font-black" x-text="preset.name"></span>
                                        <span class="block text-[9px] uppercase font-bold tracking-widest opacity-80" x-text="'Target: ' + preset.slug"></span>
                                    </div>
                                </div>
                                <span class="text-[10px] font-black px-2 py-1 bg-white/20 rounded-lg" x-text="preset.confidence + '%'"></span>
                            </button>
                        </template>
                    </div>

                    <div class="pt-2">
                        <label class="block text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-400 mb-2">OR Upload Custom Waste Photo</label>
                        <input type="file" @change="handleFileUpload($event)" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-rose-500 file:text-white hover:file:bg-rose-600 cursor-pointer">
                    </div>
                </div>

                <!-- Right: AI Vision Preview Frame -->
                <div class="flex flex-col">
                    <label class="block text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-400 mb-2">2. Camera Vision Detection Canvas</label>
                    <div class="relative flex-1 bg-zinc-950 rounded-3xl border-2 border-zinc-800 p-4 flex flex-col items-center justify-center overflow-hidden min-h-[220px]">
                        <!-- Camera Grid Lines -->
                        <div class="absolute inset-0 bg-[radial-gradient(#3f3f46_1px,transparent_1px)] [background-size:16px_16px] opacity-30"></div>
                        
                        <!-- Simulated Image Container -->
                        <div class="relative w-full h-full flex items-center justify-center">
                            <template x-if="aiPreviewUrl">
                                <img :src="aiPreviewUrl" class="max-h-48 object-contain rounded-xl shadow-lg">
                            </template>
                            <template x-if="!aiPreviewUrl">
                                <div class="flex flex-col items-center text-center p-6">
                                    <span class="text-6xl mb-2 animate-bounce" x-text="aiPresetDetails[aiSelectedPreset].icon"></span>
                                    <span class="text-sm font-black text-white" x-text="aiPresetDetails[aiSelectedPreset].name"></span>
                                </div>
                            </template>

                            <!-- Drawn YOLO Bounding Box Overlay -->
                            <div class="absolute inset-4 border-2 border-emerald-400 bg-emerald-500/10 rounded-2xl flex flex-col justify-between p-2 shadow-[0_0_20px_rgba(52,211,153,0.4)] pointer-events-none animate-pulse">
                                <div class="self-start px-2 py-0.5 bg-emerald-500 text-black text-[9px] font-black uppercase tracking-wider rounded">
                                    YOLOv8: <span x-text="aiCustomFile ? 'Custom Object' : aiPresetDetails[aiSelectedPreset].name"></span> (<span x-text="aiCustomFile ? '95.0%' : aiPresetDetails[aiSelectedPreset].confidence + '%'"></span>)
                                </div>
                                <div class="self-end text-[9px] font-mono text-emerald-400 font-bold bg-black/60 px-1.5 py-0.5 rounded">
                                    Conf: <span x-text="aiCustomFile ? '0.95' : (aiPresetDetails[aiSelectedPreset].confidence / 100).toFixed(2)"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Target Routing Badge -->
                    <div class="mt-4 p-3 bg-rose-50/50 dark:bg-zinc-800/50 border border-rose-100 dark:border-zinc-800 rounded-2xl flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Auto Route To:</span>
                        <span class="text-xs font-black text-rose-600 dark:text-rose-400 uppercase" x-text="aiCustomFile ? 'Auto-Classifier' : aiPresetDetails[aiSelectedPreset].slug"></span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button @click="executeCameraScan()" 
                    :disabled="aiProcessing"
                    class="w-full py-4 bg-rose-600 text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-rose-200 dark:shadow-none hover:bg-rose-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                <svg x-show="!aiProcessing" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                <svg x-show="aiProcessing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span x-text="aiProcessing ? 'Processing YOLO Vision...' : 'Trigger AI Camera Scan'"></span>
            </button>
        </div>
    </div>

    <script>
        function binModal() {
            return {
                sidebarOpen: false,
                isOpen: false,
                cameraModalOpen: false,
                aiProcessing: false,
                aiSelectedPreset: 'plastic_bottle',
                aiCustomFile: null,
                aiPreviewUrl: '',
                aiPresetDetails: {
                    'plastic_bottle': { name: 'Plastic Water Bottle', slug: 'recyclable', icon: '🍼', confidence: 96.4, weight: '120g' },
                    'used_battery': { name: 'Used Battery', slug: 'hazardous', icon: '🔋', confidence: 98.2, weight: '80g' },
                    'banana_peel': { name: 'Banana Peel', slug: 'biodegradable', icon: '🍌', confidence: 94.7, weight: '45g' },
                    'soda_can': { name: 'Aluminum Soda Can', slug: 'recyclable', icon: '🥤', confidence: 95.8, weight: '45g' },
                    'styrofoam': { name: 'Styrofoam Piece', slug: 'non-bio', icon: '📦', confidence: 92.1, weight: '25g' }
                },
                openCameraModal() {
                    this.cameraModalOpen = true;
                },
                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.aiCustomFile = file;
                        this.aiPreviewUrl = URL.createObjectURL(file);
                    }
                },
                async executeCameraScan() {
                    this.aiProcessing = true;
                    try {
                        const formData = new FormData();
                        const preset = this.aiPresetDetails[this.aiSelectedPreset];
                        
                        if (this.aiCustomFile) {
                            formData.append('image', this.aiCustomFile);
                            formData.append('item_name', 'Custom Scanned Item');
                        } else {
                            formData.append('item_name', preset.name);
                            formData.append('bin_slug', preset.slug);
                            formData.append('confidence', preset.confidence);
                            formData.append('weight', preset.weight);
                        }
                        
                        await axios.post('/api/bins/camera-scan', formData, {
                            headers: { 'Content-Type': 'multipart/form-data' }
                        });
                        
                        await this.fetchBins();
                        this.aiProcessing = false;
                        this.cameraModalOpen = false;
                        this.aiCustomFile = null;
                        this.aiPreviewUrl = '';
                    } catch (error) {
                        console.error("Camera scan error:", error);
                        this.aiProcessing = false;
                    }
                },
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
