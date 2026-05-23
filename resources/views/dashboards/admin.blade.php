<div class="flex min-h-screen bg-gradient-to-br from-rose-50 via-white to-orange-50/50">
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
                        <div class="absolute bottom-0 left-0 w-full bg-emerald-400/20 transition-all duration-1000 ease-out" style="height: 75%;">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-emerald-400 shadow-[0_0_20px_rgba(52,211,153,0.6)]"></div>
                        </div>

                        <!-- Bin Icon & Label -->
                        <div class="relative z-10 flex flex-col items-center gap-8">
                            <div class="w-28 h-28 bg-white rounded-[2rem] shadow-xl flex items-center justify-center text-emerald-500 group-hover:scale-110 group-hover:rotate-3 transition-all duration-700 border border-emerald-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C10.9 14.36 12 15 12 15"/></svg>
                            </div>
                            <div class="text-center">
                                <span class="inline-block px-5 py-2 bg-rose-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-5 shadow-lg shadow-rose-200">Attention Required</span>
                                <h4 class="text-xl font-black text-emerald-900 tracking-tight uppercase">Biodegradable</h4>
                            </div>
                        </div>

                        <!-- Capacity Data -->
                        <div class="absolute top-10 right-10 text-right">
                            <span class="block text-5xl font-black text-emerald-600 tracking-tighter">75%</span>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-emerald-400 mt-1">Full</span>
                        </div>
                    </div>
                    
                    <div class="mt-8 w-full space-y-4">
                        <div class="flex justify-between items-center px-6 py-4 bg-white/50 rounded-3xl border border-emerald-100">
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400">Status Update</span>
                            <span class="text-xs font-bold text-emerald-700">2h 14m ago</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="py-4 bg-white border border-emerald-100 rounded-2xl text-xs font-black text-emerald-600 hover:bg-emerald-50 transition-all">Details</button>
                            <button class="py-4 bg-emerald-500 text-white rounded-2xl text-xs font-black hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100">Empty Bin</button>
                        </div>
                    </div>
                </div>

                <!-- Non-Bio Degradable Bin -->
                <div class="flex flex-col items-center">
                    <div class="relative w-full aspect-[4/5] bg-orange-50/30 rounded-[3rem] border-2 border-orange-100 p-10 flex flex-col justify-end overflow-hidden group hover:border-orange-300 transition-all duration-700 shadow-sm hover:shadow-2xl">
                        <!-- Fill Level -->
                        <div class="absolute bottom-0 left-0 w-full bg-orange-400/20 transition-all duration-1000 ease-out" style="height: 45%;">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-orange-400 shadow-[0_0_20px_rgba(251,146,60,0.6)]"></div>
                        </div>

                        <!-- Bin Icon & Label -->
                        <div class="relative z-10 flex flex-col items-center gap-8">
                            <div class="w-28 h-28 bg-white rounded-[2rem] shadow-xl flex items-center justify-center text-orange-500 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-700 border border-orange-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </div>
                            <div class="text-center">
                                <span class="inline-block px-5 py-2 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-5 shadow-lg shadow-emerald-200">Optimal</span>
                                <h4 class="text-xl font-black text-orange-900 tracking-tight uppercase">Non-Bio</h4>
                            </div>
                        </div>

                        <!-- Capacity Data -->
                        <div class="absolute top-10 right-10 text-right">
                            <span class="block text-5xl font-black text-orange-600 tracking-tighter">45%</span>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-orange-400 mt-1">Stable</span>
                        </div>
                    </div>
                    
                    <div class="mt-8 w-full space-y-4">
                        <div class="flex justify-between items-center px-6 py-4 bg-white/50 rounded-3xl border border-orange-100">
                            <span class="text-[10px] font-black uppercase tracking-widest text-orange-400">Status Update</span>
                            <span class="text-xs font-bold text-orange-700">5h 42m ago</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="py-4 bg-white border border-orange-100 rounded-2xl text-xs font-black text-orange-600 hover:bg-orange-50 transition-all">Details</button>
                            <button class="py-4 bg-orange-400 text-white rounded-2xl text-xs font-black hover:bg-orange-500 transition-all shadow-lg shadow-orange-100">Empty Bin</button>
                        </div>
                    </div>
                </div>

                <!-- Recyclable Bin -->
                <div class="flex flex-col items-center">
                    <div class="relative w-full aspect-[4/5] bg-sky-50/30 rounded-[3rem] border-2 border-sky-100 p-10 flex flex-col justify-end overflow-hidden group hover:border-sky-300 transition-all duration-700 shadow-sm hover:shadow-2xl">
                        <!-- Fill Level -->
                        <div class="absolute bottom-0 left-0 w-full bg-sky-400/20 transition-all duration-1000 ease-out" style="height: 90%;">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-sky-400 shadow-[0_0_20px_rgba(56,189,248,0.6)]"></div>
                        </div>

                        <!-- Bin Icon & Label -->
                        <div class="relative z-10 flex flex-col items-center gap-8">
                            <div class="w-28 h-28 bg-white rounded-[2rem] shadow-xl flex items-center justify-center text-sky-500 group-hover:scale-110 transition-all duration-700 border border-sky-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                            </div>
                            <div class="text-center">
                                <span class="inline-block px-5 py-2 bg-rose-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-5 shadow-lg shadow-rose-200">Critical Full</span>
                                <h4 class="text-xl font-black text-sky-900 tracking-tight uppercase">Recyclable</h4>
                            </div>
                        </div>

                        <!-- Capacity Data -->
                        <div class="absolute top-10 right-10 text-right">
                            <span class="block text-5xl font-black text-sky-600 tracking-tighter">90%</span>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-sky-400 mt-1">Urgent</span>
                        </div>
                    </div>
                    
                    <div class="mt-8 w-full space-y-4">
                        <div class="flex justify-between items-center px-6 py-4 bg-white/50 rounded-3xl border border-sky-100">
                            <span class="text-[10px] font-black uppercase tracking-widest text-sky-400">Status Update</span>
                            <span class="text-xs font-bold text-sky-700">45m ago</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="py-4 bg-white border border-sky-100 rounded-2xl text-xs font-black text-sky-600 hover:bg-sky-50 transition-all">Details</button>
                            <button class="py-4 bg-sky-500 text-white rounded-2xl text-xs font-black hover:bg-sky-600 transition-all shadow-lg shadow-sky-100">Empty Bin</button>
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
                    <span class="block text-6xl font-black text-rose-600 tracking-tighter">47</span>
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
                            <span class="text-sm font-black text-rose-950">18 items</span>
                        </div>
                        <div class="h-2 w-full bg-rose-50 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-400 rounded-full" style="width: 38%"></div>
                        </div>
                    </div>
                    <!-- Recyclable -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-sky-400"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-rose-900/60">Recyclable</span>
                            </div>
                            <span class="text-sm font-black text-rose-950">21 items</span>
                        </div>
                        <div class="h-2 w-full bg-rose-50 rounded-full overflow-hidden">
                            <div class="h-full bg-sky-400 rounded-full" style="width: 45%"></div>
                        </div>
                    </div>
                    <!-- Non-Bio -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-orange-400"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-rose-900/60">Non-Bio</span>
                            </div>
                            <span class="text-sm font-black text-rose-950">8 items</span>
                        </div>
                        <div class="h-2 w-full bg-rose-50 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-400 rounded-full" style="width: 17%"></div>
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
                                <h4 class="text-lg font-black text-white leading-tight uppercase tracking-tight">Recyclable Full</h4>
                                <p class="text-xs font-bold text-rose-100 mt-1 opacity-80 italic">90% capacity — evacuation required</p>
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
</div>