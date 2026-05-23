<div class="flex min-h-screen -mt-[65px] -ml-[32px] -mr-[32px]">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-72 bg-[#9B1C1C] text-white flex flex-col z-50 shadow-2xl">
        <!-- Top Logo -->
        <div class="p-6 flex items-center gap-3">
            <div class="bg-white/10 p-2 rounded-lg">
                <x-application-logo class="w-8 h-8 text-white" />
            </div>
            <div class="leading-tight">
                <span class="block font-bold text-lg tracking-tight">SyncBin</span>
                <span class="block text-[10px] uppercase tracking-[0.2em] opacity-70">Admin System</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="#" class="flex items-center gap-3 px-4 py-3 bg-white/10 rounded-xl font-bold transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-white/60 hover:bg-white/5 hover:text-white rounded-xl font-bold transition-all group">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-50 group-hover:opacity-100"><path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0Z"/><path d="M12 8v4l3 3"/></svg>
                Activity History
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-white/60 hover:bg-white/5 hover:text-white rounded-xl font-bold transition-all group">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-50 group-hover:opacity-100"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>
                Reports
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-white/60 hover:bg-white/5 hover:text-white rounded-xl font-bold transition-all group">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-50 group-hover:opacity-100"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                Settings
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-6 bg-black/20 border-t border-white/5">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-11 h-11 rounded-2xl bg-white/10 flex items-center justify-center font-black text-white border border-white/10">AD</div>
                <div class="overflow-hidden">
                    <span class="block font-bold truncate text-sm text-white">admin@wastesync.com</span>
                    <span class="inline-flex px-2 py-0.5 rounded-lg text-[10px] bg-emerald-500/20 text-emerald-300 font-black uppercase tracking-widest border border-emerald-500/20">Admin</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-white text-[#9B1C1C] rounded-2xl font-black text-sm hover:shadow-xl hover:scale-[1.02] transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-72 p-12 bg-gray-50 min-h-screen">
        <!-- Header -->
        <header class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">Admin Dashboard</h1>
                <p class="text-gray-500 mt-2 font-bold text-lg">Welcome back, <span class="text-[#9B1C1C]">admin@wastesync.com</span> - <span class="opacity-60">Real-time waste bin monitoring</span></p>
            </div>
            <button class="flex items-center gap-3 px-8 py-4 bg-emerald-600 text-white rounded-2xl font-black shadow-2xl shadow-emerald-200 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
                Export Report to PDF
            </button>
        </header>

        <!-- Hero Bin Panel -->
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 border border-gray-100 p-12">
            <div class="text-center mb-16">
                <h3 class="text-sm font-black uppercase tracking-[0.4em] text-gray-400 mb-2">System Overview</h3>
                <h2 class="text-3xl font-black text-gray-900">Live Bin Management</h2>
            </div>

            <div class="grid grid-cols-3 gap-12">
                <!-- Bio-Degradable Bin -->
                <div class="flex flex-col items-center">
                    <div class="relative w-full aspect-[4/5] bg-gray-50 rounded-[2.5rem] border-2 border-emerald-50 p-8 flex flex-col justify-end overflow-hidden group hover:border-emerald-200 transition-all duration-500 shadow-sm hover:shadow-xl">
                        <!-- Fill Level -->
                        <div class="absolute bottom-0 left-0 w-full bg-emerald-500/10 transition-all duration-1000 ease-out" style="height: 75%;">
                            <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>
                        </div>

                        <!-- Bin Icon & Label -->
                        <div class="relative z-10 flex flex-col items-center gap-6">
                            <div class="w-24 h-24 bg-white rounded-3xl shadow-xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform duration-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C10.9 14.36 12 15 12 15"/></svg>
                            </div>
                            <div class="text-center">
                                <span class="inline-block px-4 py-1.5 bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full mb-4 shadow-lg">High</span>
                                <h4 class="text-lg font-black text-gray-900 tracking-tight uppercase">Biodegradable</h4>
                            </div>
                        </div>

                        <!-- Capacity Data -->
                        <div class="absolute top-8 right-8 text-right">
                            <span class="block text-4xl font-black text-emerald-700">75%</span>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-emerald-600/50">Capacity</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 w-full space-y-4">
                        <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-2xl border border-gray-100">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Last Emptied</span>
                            <span class="text-xs font-bold text-gray-700">2h 14m ago</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="py-3 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all">View Details</button>
                            <button class="py-3 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100">Log Collection</button>
                        </div>
                    </div>
                </div>

                <!-- Non-Bio Degradable Bin -->
                <div class="flex flex-col items-center">
                    <div class="relative w-full aspect-[4/5] bg-gray-50 rounded-[2.5rem] border-2 border-orange-50 p-8 flex flex-col justify-end overflow-hidden group hover:border-orange-200 transition-all duration-500 shadow-sm hover:shadow-xl">
                        <!-- Fill Level -->
                        <div class="absolute bottom-0 left-0 w-full bg-orange-500/10 transition-all duration-1000 ease-out" style="height: 45%;">
                            <div class="absolute top-0 left-0 w-full h-1 bg-orange-500 shadow-[0_0_15px_rgba(249,115,22,0.5)]"></div>
                        </div>

                        <!-- Bin Icon & Label -->
                        <div class="relative z-10 flex flex-col items-center gap-6">
                            <div class="w-24 h-24 bg-white rounded-3xl shadow-xl flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform duration-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </div>
                            <div class="text-center">
                                <span class="inline-block px-4 py-1.5 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full mb-4 shadow-lg">Normal</span>
                                <h4 class="text-lg font-black text-gray-900 tracking-tight uppercase">Non-Biodegradable</h4>
                            </div>
                        </div>

                        <!-- Capacity Data -->
                        <div class="absolute top-8 right-8 text-right">
                            <span class="block text-4xl font-black text-orange-700">45%</span>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-orange-600/50">Capacity</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 w-full space-y-4">
                        <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-2xl border border-gray-100">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Last Emptied</span>
                            <span class="text-xs font-bold text-gray-700">5h 42m ago</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="py-3 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all">View Details</button>
                            <button class="py-3 bg-orange-500 text-white rounded-xl text-xs font-bold hover:bg-orange-600 transition-all shadow-lg shadow-orange-100">Log Collection</button>
                        </div>
                    </div>
                </div>

                <!-- Recyclable Bin -->
                <div class="flex flex-col items-center">
                    <div class="relative w-full aspect-[4/5] bg-gray-50 rounded-[2.5rem] border-2 border-sky-50 p-8 flex flex-col justify-end overflow-hidden group hover:border-sky-200 transition-all duration-500 shadow-sm hover:shadow-xl">
                        <!-- Fill Level -->
                        <div class="absolute bottom-0 left-0 w-full bg-sky-500/10 transition-all duration-1000 ease-out" style="height: 90%;">
                            <div class="absolute top-0 left-0 w-full h-1 bg-sky-500 shadow-[0_0_15px_rgba(14,165,233,0.5)]"></div>
                        </div>

                        <!-- Bin Icon & Label -->
                        <div class="relative z-10 flex flex-col items-center gap-6">
                            <div class="w-24 h-24 bg-white rounded-3xl shadow-xl flex items-center justify-center text-sky-600 group-hover:scale-110 transition-transform duration-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 11V7a5 5 0 0 1 10 0v4"/><rect width="18" height="12" x="3" y="11" rx="2"/><circle cx="12" cy="16" r="1"/></svg>
                            </div>
                            <div class="text-center">
                                <span class="inline-block px-4 py-1.5 bg-[#9B1C1C] text-white text-[10px] font-black uppercase tracking-widest rounded-full mb-4 shadow-lg">Full</span>
                                <h4 class="text-lg font-black text-gray-900 tracking-tight uppercase">Recyclable</h4>
                            </div>
                        </div>

                        <!-- Capacity Data -->
                        <div class="absolute top-8 right-8 text-right">
                            <span class="block text-4xl font-black text-sky-700">90%</span>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-sky-600/50">Capacity</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 w-full space-y-4">
                        <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-2xl border border-gray-100">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Last Emptied</span>
                            <span class="text-xs font-bold text-gray-700">45m ago</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="py-3 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all">View Details</button>
                            <button class="py-3 bg-sky-600 text-white rounded-xl text-xs font-bold hover:bg-sky-700 transition-all shadow-lg shadow-sky-100">Log Collection</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>