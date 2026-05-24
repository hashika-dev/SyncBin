<div x-data="binModal()" class="flex min-h-screen bg-gradient-to-br from-rose-50 via-white to-orange-50/50">
    <style>[x-cloak] { display: none !important; }</style>
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-72 bg-white/80 backdrop-blur-xl border-r border-rose-100 flex flex-col z-50">
        <!-- Navigation -->
        <nav class="flex-1 px-6 py-12 space-y-3">
            <a href="#" class="flex items-center gap-4 px-5 py-4 bg-rose-500 text-white rounded-2xl font-black shadow-lg shadow-rose-200 transition-all hover:scale-[1.02]">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                Dashboard
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-4 text-rose-900/60 hover:bg-rose-50 hover:text-rose-900 rounded-2xl font-bold transition-all group">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-40 group-hover:opacity-100"><path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0Z"/><path d="M12 8v4l3 3"/></svg>
                Activity History
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-4 text-rose-900/60 hover:bg-rose-50 hover:text-rose-900 rounded-2xl font-bold transition-all group">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-40 group-hover:opacity-100"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>
                Reports
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-4 text-rose-900/60 hover:bg-rose-50 hover:text-rose-900 rounded-2xl font-bold transition-all group">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-40 group-hover:opacity-100"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                Settings
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-8 bg-rose-50/50 border-t border-rose-100">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-white border border-rose-200 flex items-center justify-center font-black text-rose-600 shadow-sm">AD</div>
                <div class="overflow-hidden">
                    <span class="block font-black truncate text-sm text-rose-950 uppercase tracking-tight">admin@wastesync.com</span>
                    <span class="inline-block px-2 py-0.5 rounded-lg text-[10px] bg-rose-600 text-white font-black uppercase tracking-widest mt-1">Admin</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 py-4 bg-white text-rose-600 border border-rose-200 rounded-2xl font-black text-sm hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-72 p-16">
        <!-- Header -->
        <header class="flex items-center justify-between mb-16">
            <div>
                <h1 class="text-5xl font-black text-rose-950 tracking-tighter">Admin Dashboard</h1>
                <p class="text-rose-600 mt-3 font-bold text-xl opacity-80">
                    Welcome back, <span class="font-black">admin@wastesync.com</span> 
                    <span class="mx-3 opacity-30">|</span> 
                    Real-time waste bin monitoring
                </p>
            </div>
            <button class="flex items-center gap-3 px-8 py-4 bg-emerald-500 text-white rounded-2xl font-black shadow-xl shadow-emerald-200 hover:bg-emerald-600 hover:-translate-y-1 transition-all active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
                Export Report
            </button>
        </header>

        <!-- Hero Bin Panel -->
        <div class="bg-white/70 backdrop-blur-xl rounded-[4rem] shadow-2xl shadow-rose-200/20 border border-white p-16 mb-12">
            <div class="text-center mb-20">
                <h3 class="text-xs font-black uppercase tracking-[0.5em] text-rose-300 mb-3">Live Status Overview</h3>
                <h2 class="text-4xl font-black text-rose-950 tracking-tight">System Bin Management</h2>
            </div>

            <div class="grid grid-cols-3 gap-16">
                <!-- Bio-Degradable Bin -->
                <div class="flex flex-col items-center">
                    <div class="relative w-full aspect-[4/5] bg-emerald-50/30 rounded-[3rem] border-2 border-emerald-100 p-10 flex flex-col justify-end overflow-hidden group hover:border-emerald-300 transition-all duration-700 shadow-sm hover:shadow-2xl">
                        <!-- Fill Level -->
                        <div class="absolute bottom-0 left-0 w-full bg-emerald-400/20 transition-all duration-1000 ease-out" :style="'height: ' + bins.biodegradable.level + '%'">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-emerald-400 shadow-[0_0_20px_rgba(52,211,153,0.6)]"></div>
                        </div>

                        <!-- Bin Icon & Label -->
                        <div class="relative z-10 flex flex-col items-center gap-8">
                            <div class="w-28 h-28 bg-white rounded-[2rem] shadow-xl flex items-center justify-center text-emerald-500 group-hover:scale-110 group-hover:rotate-3 transition-all duration-700 border border-emerald-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C10.9 14.36 12 15 12 15"/></svg>
                            </div>
                            <div class="text-center">
                                <span class="inline-block px-5 py-2 bg-rose-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-5 shadow-lg shadow-rose-200" x-show="bins.biodegradable.level >= 75">Attention Required</span>
                                <span class="inline-block px-5 py-2 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-5 shadow-lg shadow-emerald-200" x-show="bins.biodegradable.level < 75">Optimal</span>
                                <h4 class="text-xl font-black text-emerald-900 tracking-tight uppercase">Biodegradable</h4>
                            </div>
                        </div>

                        <!-- Capacity Data -->
                        <div class="absolute top-10 right-10 text-right">
                            <span class="block text-5xl font-black text-emerald-600 tracking-tighter" x-text="bins.biodegradable.level + '%'"></span>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-emerald-400 mt-1" x-text="bins.biodegradable.status"></span>
                        </div>
                    </div>
                    
                    <div class="mt-8 w-full space-y-4">
                        <div class="flex justify-between items-center px-6 py-4 bg-white/50 rounded-3xl border border-emerald-100">
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400">Status Update</span>
                            <span class="text-xs font-bold text-emerald-700" x-text="bins.biodegradable.lastEmptied"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <button @click="openModal('biodegradable')" class="py-4 bg-white border border-emerald-100 rounded-2xl text-xs font-black text-emerald-600 hover:bg-emerald-50 transition-all">Details</button>
                            <button @click="emptyBinDirect('biodegradable')" class="py-4 bg-emerald-500 text-white rounded-2xl text-xs font-black hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100">Empty Bin</button>
                        </div>
                    </div>
                </div>

                <!-- Non-Bio Degradable Bin -->
                <div class="flex flex-col items-center">
                    <div class="relative w-full aspect-[4/5] bg-orange-50/30 rounded-[3rem] border-2 border-orange-100 p-10 flex flex-col justify-end overflow-hidden group hover:border-orange-300 transition-all duration-700 shadow-sm hover:shadow-2xl">
                        <!-- Fill Level -->
                        <div class="absolute bottom-0 left-0 w-full bg-orange-400/20 transition-all duration-1000 ease-out" :style="'height: ' + bins['non-bio'].level + '%'">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-orange-400 shadow-[0_0_20px_rgba(251,146,60,0.6)]"></div>
                        </div>

                        <!-- Bin Icon & Label -->
                        <div class="relative z-10 flex flex-col items-center gap-8">
                            <div class="w-28 h-28 bg-white rounded-[2rem] shadow-xl flex items-center justify-center text-orange-500 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-700 border border-orange-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </div>
                            <div class="text-center">
                                <span class="inline-block px-5 py-2 bg-rose-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-5 shadow-lg shadow-rose-200" x-show="bins['non-bio'].level >= 75">Attention Required</span>
                                <span class="inline-block px-5 py-2 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-5 shadow-lg shadow-emerald-200" x-show="bins['non-bio'].level < 75">Optimal</span>
                                <h4 class="text-xl font-black text-orange-900 tracking-tight uppercase">Non-Bio</h4>
                            </div>
                        </div>

                        <!-- Capacity Data -->
                        <div class="absolute top-10 right-10 text-right">
                            <span class="block text-5xl font-black text-orange-600 tracking-tighter" x-text="bins['non-bio'].level + '%'"></span>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-orange-400 mt-1" x-text="bins['non-bio'].status"></span>
                        </div>
                    </div>
                    
                    <div class="mt-8 w-full space-y-4">
                        <div class="flex justify-between items-center px-6 py-4 bg-white/50 rounded-3xl border border-orange-100">
                            <span class="text-[10px] font-black uppercase tracking-widest text-orange-400">Status Update</span>
                            <span class="text-xs font-bold text-orange-700" x-text="bins['non-bio'].lastEmptied"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <button @click="openModal('non-bio')" class="py-4 bg-white border border-orange-100 rounded-2xl text-xs font-black text-orange-600 hover:bg-orange-50 transition-all">Details</button>
                            <button @click="emptyBinDirect('non-bio')" class="py-4 bg-orange-400 text-white rounded-2xl text-xs font-black hover:bg-orange-500 transition-all shadow-lg shadow-orange-100">Empty Bin</button>
                        </div>
                    </div>
                </div>

                <!-- Recyclable Bin -->
                <div class="flex flex-col items-center">
                    <div class="relative w-full aspect-[4/5] bg-sky-50/30 rounded-[3rem] border-2 border-sky-100 p-10 flex flex-col justify-end overflow-hidden group hover:border-sky-300 transition-all duration-700 shadow-sm hover:shadow-2xl">
                        <!-- Fill Level -->
                        <div class="absolute bottom-0 left-0 w-full bg-sky-400/20 transition-all duration-1000 ease-out" :style="'height: ' + bins.recyclable.level + '%'">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-sky-400 shadow-[0_0_20px_rgba(56,189,248,0.6)]"></div>
                        </div>

                        <!-- Bin Icon & Label -->
                        <div class="relative z-10 flex flex-col items-center gap-8">
                            <div class="w-28 h-28 bg-white rounded-[2rem] shadow-xl flex items-center justify-center text-sky-500 group-hover:scale-110 transition-all duration-700 border border-sky-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                            </div>
                            <div class="text-center">
                                <span class="inline-block px-5 py-2 bg-rose-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-5 shadow-lg shadow-rose-200" x-show="bins.recyclable.level >= 85">Critical Full</span>
                                <span class="inline-block px-5 py-2 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-5 shadow-lg shadow-emerald-200" x-show="bins.recyclable.level < 85">Optimal</span>
                                <h4 class="text-xl font-black text-sky-900 tracking-tight uppercase">Recyclable</h4>
                            </div>
                        </div>

                        <!-- Capacity Data -->
                        <div class="absolute top-10 right-10 text-right">
                            <span class="block text-5xl font-black text-sky-600 tracking-tighter" x-text="bins.recyclable.level + '%'"></span>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-sky-400 mt-1" x-text="bins.recyclable.status"></span>
                        </div>
                    </div>
                    
                    <div class="mt-8 w-full space-y-4">
                        <div class="flex justify-between items-center px-6 py-4 bg-white/50 rounded-3xl border border-sky-100">
                            <span class="text-[10px] font-black uppercase tracking-widest text-sky-400">Status Update</span>
                            <span class="text-xs font-bold text-sky-700" x-text="bins.recyclable.lastEmptied"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <button @click="openModal('recyclable')" class="py-4 bg-white border border-sky-100 rounded-2xl text-xs font-black text-sky-600 hover:bg-sky-50 transition-all">Details</button>
                            <button @click="emptyBinDirect('recyclable')" class="py-4 bg-sky-500 text-white rounded-2xl text-xs font-black hover:bg-sky-600 transition-all shadow-lg shadow-sky-100">Empty Bin</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pb-20">
            <!-- Card 1: AI Sorting Data -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[3rem] shadow-xl shadow-rose-200/10 border border-white p-10 flex flex-col">
                <div class="mb-10">
                    <h3 class="text-2xl font-black text-rose-950 tracking-tight">AI Analytics</h3>
                    <p class="text-[10px] font-black text-rose-300 uppercase tracking-[0.3em] mt-2">Daily Performance</p>
                </div>

                <div class="bg-rose-50/50 rounded-[2.5rem] p-8 text-center border border-rose-100 mb-10">
                    <span class="block text-6xl font-black text-rose-600 tracking-tighter" x-text="totalProcessed"></span>
                    <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-rose-400 mt-3">Items Processed Today</span>
                </div>

                <div class="space-y-8 flex-1">
                    <!-- Bio -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-rose-900/60">Biodegradable</span>
                            </div>
                            <span class="text-sm font-black text-rose-950"><span x-text="bins.biodegradable.items.length"></span> items</span>
                        </div>
                        <div class="h-2 w-full bg-rose-50 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-400 rounded-full transition-all duration-500" :style="'width: ' + (bins.biodegradable.items.length / totalProcessed * 100 || 0) + '%'"></div>
                        </div>
                    </div>
                    <!-- Recyclable -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-sky-400"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-rose-900/60">Recyclable</span>
                            </div>
                            <span class="text-sm font-black text-rose-950"><span x-text="bins.recyclable.items.length"></span> items</span>
                        </div>
                        <div class="h-2 w-full bg-rose-50 rounded-full overflow-hidden">
                            <div class="h-full bg-sky-400 rounded-full transition-all duration-500" :style="'width: ' + (bins.recyclable.items.length / totalProcessed * 100 || 0) + '%'"></div>
                        </div>
                    </div>
                    <!-- Non-Bio -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-orange-400"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-rose-900/60">Non-Bio</span>
                            </div>
                            <span class="text-sm font-black text-rose-950"><span x-text="bins['non-bio'].items.length"></span> items</span>
                        </div>
                        <div class="h-2 w-full bg-rose-50 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-400 rounded-full transition-all duration-500" :style="'width: ' + (bins['non-bio'].items.length / totalProcessed * 100 || 0) + '%'"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Recent Activity -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[3rem] shadow-xl shadow-rose-200/10 border border-white p-10 flex flex-col">
                <div class="mb-10">
                    <h3 class="text-2xl font-black text-rose-950 tracking-tight">Recent Activity</h3>
                    <p class="text-[10px] font-black text-rose-300 uppercase tracking-[0.3em] mt-2">Latest Operations</p>
                </div>

                <div class="flex-1 flex flex-col">
                    <button class="w-full p-6 bg-rose-50/50 border border-rose-100 rounded-3xl flex items-center justify-between group hover:bg-rose-500 hover:border-rose-500 transition-all duration-500">
                        <span class="text-sm font-black text-rose-900 group-hover:text-white transition-colors uppercase tracking-widest">Access Logs</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-rose-300 group-hover:text-white transition-all"><path d="m6 9 6 6 6-6"/></svg>
                    </button>

                    <div class="flex-1 flex items-center justify-center text-center p-12">
                        <div class="space-y-4">
                            <div class="w-16 h-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto text-rose-200">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            </div>
                            <p class="text-sm text-rose-300 italic font-bold leading-relaxed max-w-[200px] mx-auto">
                                Expanding the activity logs will reveal detailed classification timestamps.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: System Alerts -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[3rem] shadow-xl shadow-rose-200/10 border border-white p-10 flex flex-col">
                <div class="mb-10">
                    <h3 class="text-2xl font-black text-rose-950 tracking-tight">System Alerts</h3>
                    <p class="text-[10px] font-black text-rose-600 uppercase tracking-[0.3em] mt-2">Critical Notifications</p>
                </div>

                <div class="flex-1 flex flex-col">
                    <div class="bg-rose-500 rounded-[2.5rem] p-8 flex-1 flex flex-col shadow-2xl shadow-rose-200">
                        <div class="flex gap-5 mb-8">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white flex-shrink-0 border border-white/20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-white leading-tight uppercase tracking-tight">System Status</h4>
                                <p class="text-xs font-bold text-rose-100 mt-1 opacity-80 italic" x-text="bins.recyclable.level >= 85 ? 'Recyclable Full — evacuation required' : 'All systems operating normally'"></p>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <button class="w-full py-5 bg-white text-rose-600 rounded-3xl font-black flex items-center justify-center gap-3 shadow-xl hover:scale-[1.03] transition-all active:scale-95 uppercase text-xs tracking-[0.2em]">
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
             class="bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full p-10 relative overflow-hidden border border-rose-100"
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
                            'text-sky-600': activeBin.color === 'sky'
                        }" 
                        x-text="activeBin.name"></h2>
                    <p class="text-xs font-black text-rose-300 uppercase tracking-widest mt-1" x-text="activeBin.subtitle"></p>
                </div>
                <button @click="isOpen = false" class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center hover:bg-rose-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <!-- Fill Level Indicator -->
            <div class="bg-rose-50/30 rounded-3xl p-6 border border-rose-50 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-rose-900/40">Fill Level</span>
                    <span class="text-sm font-black" 
                          :class="{
                            'text-emerald-600': activeBin.color === 'emerald',
                            'text-orange-600': activeBin.color === 'orange',
                            'text-sky-600': activeBin.color === 'sky'
                          }" 
                          x-text="activeBin.level + '% — ' + activeBin.status"></span>
                </div>
                <div class="h-3 w-full bg-white rounded-full overflow-hidden border border-rose-100">
                    <div class="h-full transition-all duration-1000" 
                         :class="{
                            'bg-emerald-500': activeBin.color === 'emerald',
                            'bg-orange-400': activeBin.color === 'orange',
                            'bg-sky-500': activeBin.color === 'sky'
                         }" 
                         :style="'width: ' + activeBin.level + '%'"></div>
                </div>
            </div>

            <!-- Contents List -->
            <div class="mb-8">
                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-rose-300 mb-5">Contents (<span x-text="activeBin.items.length"></span> items detected)</h3>
                <div class="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                    <template x-for="(item, index) in activeBin.items" :key="index">
                        <div class="flex items-center justify-between p-4 bg-white border rounded-2xl transition-all" 
                             :class="{
                                'border-emerald-100 hover:border-emerald-300': activeBin.color === 'emerald',
                                'border-orange-100 hover:border-orange-300': activeBin.color === 'orange',
                                'border-sky-100 hover:border-sky-300': activeBin.color === 'sky'
                             }"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-x-4"
                             x-transition:enter-end="opacity-100 translate-x-0">
                            <div class="flex items-center gap-4">
                                <span class="text-2xl" x-text="item.icon"></span>
                                <span class="text-sm font-bold text-rose-950" x-text="item.name"></span>
                            </div>
                            <span class="text-xs font-black text-rose-500" x-text="'~' + item.weight"></span>
                        </div>
                    </template>
                    <div x-show="activeBin.items.length === 0" class="text-center py-8 bg-rose-50/20 rounded-2xl border border-dashed border-rose-100">
                        <p class="text-xs font-bold text-rose-300 uppercase tracking-widest italic">Bin is currently empty</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                <button @click="emptyBin()" 
                        class="py-4 border-2 border-rose-100 text-rose-300 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all active:scale-95">
                    Empty Bin
                </button>
                <button @click="simulateScan()" 
                        class="py-4 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-lg transition-all active:scale-95"
                        :class="{
                            'bg-emerald-500 shadow-emerald-100 hover:bg-emerald-600': activeBin.color === 'emerald',
                            'bg-orange-400 shadow-orange-100 hover:bg-orange-500': activeBin.color === 'orange',
                            'bg-sky-500 shadow-sky-100 hover:bg-sky-600': activeBin.color === 'sky'
                        }">
                    Simulate Scan
                </button>
            </div>

            <!-- Modal Footer -->
            <div class="bg-rose-50/50 rounded-2xl p-4 flex items-center gap-3 border border-rose-100">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-rose-400 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <span class="text-xs font-bold text-rose-900/60">Last emptied: <span x-text="activeBin.lastEmptied"></span></span>
            </div>
        </div>
    </div>

    <script>
        function binModal() {
            return {
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
                    biodegradable: {
                        name: 'Biodegradable',
                        subtitle: 'Organic Waste Bin',
                        color: 'emerald',
                        level: 75,
                        status: 'High',
                        lastEmptied: '2 hours ago',
                        items: [
                            { name: 'Banana Peel', icon: '🍌', weight: '45g' },
                            { name: 'Apple Core', icon: '🍎', weight: '30g' },
                            { name: 'Carrot Top', icon: '🥕', weight: '15g' }
                        ],
                        mockItems: [
                            { name: 'Apple Core', icon: '🍎', weight: '30g' },
                            { name: 'Orange Peel', icon: '🍊', weight: '20g' },
                            { name: 'Lettuce Scrap', icon: '🥬', weight: '15g' },
                            { name: 'Coffee Grounds', icon: '☕', weight: '40g' }
                        ]
                    },
                    'non-bio': {
                        name: 'Non-Biodegradable',
                        subtitle: 'General Waste Bin',
                        color: 'orange',
                        level: 45,
                        status: 'Stable',
                        lastEmptied: '5 hours ago',
                        items: [
                            { name: 'Plastic Wrap', icon: '🍬', weight: '10g' },
                            { name: 'Styrofoam Piece', icon: '📦', weight: '25g' },
                            { name: 'Broken Glass', icon: '🍷', weight: '60g' }
                        ],
                        mockItems: [
                            { name: 'Packaging Film', icon: '🛍️', weight: '15g' },
                            { name: 'Plastic Cutlery', icon: '🍴', weight: '12g' },
                            { name: 'Chip Bag', icon: '🍿', weight: '8g' },
                            { name: 'Mask', icon: '😷', weight: '5g' }
                        ]
                    },
                    recyclable: {
                        name: 'Recyclable',
                        subtitle: 'Recoverable Waste Bin',
                        color: 'sky',
                        level: 90,
                        status: 'Critical',
                        lastEmptied: '45 minutes ago',
                        items: [
                            { name: 'Plastic Bottle', icon: '🍼', weight: '120g' },
                            { name: 'Paper Box', icon: '📄', weight: '200g' },
                            { name: 'Aluminum Can', icon: '🥫', weight: '50g' }
                        ],
                        mockItems: [
                            { name: 'Glass Jar', icon: '🫙', weight: '85g' },
                            { name: 'Magazine', icon: '📖', weight: '110g' },
                            { name: 'Soda Can', icon: '🥤', weight: '45g' },
                            { name: 'Cardboard Tube', icon: '🧻', weight: '30g' }
                        ]
                    }
                },
                get totalProcessed() {
                    return Object.values(this.bins).reduce((acc, bin) => acc + bin.items.length, 0);
                },
                openModal(binKey) {
                    this.activeKey = binKey;
                    this.activeBin = this.bins[binKey];
                    this.isOpen = true;
                },
                emptyBin() {
                    const bin = this.bins[this.activeKey];
                    bin.level = 0;
                    bin.items = [];
                    bin.lastEmptied = 'Just now';
                    this.updateStatus(bin);
                },
                emptyBinDirect(binKey) {
                    const bin = this.bins[binKey];
                    bin.level = 0;
                    bin.items = [];
                    bin.lastEmptied = 'Just now';
                    this.updateStatus(bin);
                },
                simulateScan() {
                    const bin = this.bins[this.activeKey];
                    
                    // Add random mock item
                    const randomItem = bin.mockItems[Math.floor(Math.random() * bin.mockItems.length)];
                    bin.items.unshift({...randomItem}); // Add to start of list

                    // Increase level by 5-15%
                    const increase = Math.floor(Math.random() * 11) + 5;
                    bin.level = Math.min(100, bin.level + increase);
                    
                    this.updateStatus(bin);
                },
                updateStatus(bin) {
                    if (bin.level === 0) bin.status = 'Empty';
                    else if (bin.level < 30) bin.status = 'Low';
                    else if (bin.level < 60) bin.status = 'Stable';
                    else if (bin.level < 85) bin.status = 'High';
                    else bin.status = 'Critical';
                }
            }
        }
    </script>
</div>
