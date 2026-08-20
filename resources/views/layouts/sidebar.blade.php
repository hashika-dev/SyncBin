<div x-data="{
    isDark: document.documentElement.classList.contains('dark'),
    toggleTheme() {
        this.isDark = !this.isDark;
        if (this.isDark) {
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
        }
    }
}">
    <!-- Mobile Top Header Bar (Visible on screens < lg) -->
    <div class="lg:hidden sticky top-0 z-40 w-full shrink-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 px-4 py-3 flex items-center justify-between shadow-md text-slate-900 dark:text-white transition-colors duration-300">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = !sidebarOpen" type="button" class="p-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white focus:outline-none transition-colors" aria-label="Open menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" x2="21" y1="6" y2="6"/>
                    <line x1="3" x2="21" y1="12" y2="12"/>
                    <line x1="3" x2="21" y1="18" y2="18"/>
                </svg>
            </button>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-7 h-7 object-contain">
                <div class="leading-tight">
                    <span class="text-base font-bold tracking-tight text-slate-900 dark:text-white block">EcoSync</span>
                    <span class="text-[9px] uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-semibold block">IoT Telemetry</span>
                </div>
            </a>
        </div>
        <div class="flex items-center gap-2">
            <!-- Mobile Theme Toggle Button -->
            <button @click="toggleTheme()" type="button" class="p-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Toggle Light/Dark Theme">
                <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                <svg x-show="isDark" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-sky-400"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
            </button>
            <span class="px-2.5 py-1 rounded-md text-[10px] {{ Auth::user()->isSuperAdmin() ? 'bg-cyan-600' : 'bg-emerald-600' }} text-white font-bold uppercase tracking-wider">
                {{ Auth::user()->isSuperAdmin() ? 'SuperAdmin' : 'Admin' }}
            </span>
        </div>
    </div>

    <!-- Mobile Backdrop Overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-40 lg:hidden"
         x-cloak></div>

    <!-- Sidebar Drawer (Sensoneo Industrial / Enterprise Style) -->
    <aside class="fixed inset-y-0 left-0 w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col z-50 transition-transform duration-300 ease-in-out lg:translate-x-0 text-slate-700 dark:text-slate-200 shadow-lg lg:shadow-none"
           :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'">
        <!-- Brand / Navigation Logo -->
        <div class="px-6 py-6 border-b border-slate-200 dark:border-slate-800/80 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 flex items-center justify-center p-2 shadow-inner">
                    <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white block">EcoSync</span>
                </div>
            </a>
            <!-- Close button on mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden p-1.5 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-3 space-y-1.5 overflow-y-auto">
            <div class="px-3 pt-2 pb-1 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Core Platform</div>

            <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all group {{ $active === 'dashboard' ? 'bg-emerald-600 text-white shadow-sm border border-emerald-500/40' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-slate-900 dark:hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'dashboard' ? 'text-white' : 'text-slate-500 dark:text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400' }}"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <span>Live Bin Monitor</span>
            </a>

            <a href="{{ route('dashboard.reports') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all group {{ $active === 'reports' ? 'bg-emerald-600 text-white shadow-sm border border-emerald-500/40' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-slate-900 dark:hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'reports' ? 'text-white' : 'text-slate-500 dark:text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400' }}"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>
                <span>Analytics & Reports</span>
            </a>

            <a href="{{ route('dashboard.history') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all group {{ $active === 'history' ? 'bg-emerald-600 text-white shadow-sm border border-emerald-500/40' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-slate-900 dark:hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'history' ? 'text-white' : 'text-slate-500 dark:text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400' }}"><path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0Z"/><path d="M12 8v4l3 3"/></svg>
                <span>Activity Audit Trail</span>
            </a>

            @if(auth()->user()->isSuperAdmin())
            <div class="px-3 pt-3 pb-1 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Engineering & IoT</div>
            <a href="{{ route('dashboard.hardware') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all group {{ $active === 'hardware' ? 'bg-cyan-600 text-white shadow-sm border border-cyan-500/40' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-slate-900 dark:hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'hardware' ? 'text-white' : 'text-slate-500 dark:text-slate-400 group-hover:text-cyan-600 dark:group-hover:text-cyan-400' }}"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span>Hardware Diagnostics</span>
            </a>
            @endif

            <div class="px-3 pt-3 pb-1 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Administration</div>
            <a href="{{ route('profile.edit') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all group {{ $active === 'settings' ? 'bg-emerald-600 text-white shadow-sm border border-emerald-500/40' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-slate-900 dark:hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'settings' ? 'text-white' : 'text-slate-500 dark:text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400' }}"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span>Security & Settings</span>
            </a>
        </nav>

        <!-- Theme Toggle Section -->
        <div class="px-4 py-2 border-t border-slate-200 dark:border-slate-800/80 bg-slate-50 dark:bg-slate-950/40">
            <button @click="toggleTheme()" type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-xl bg-white dark:bg-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/60 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-all group" title="Toggle Light or Dark Mode">
                <div class="flex items-center gap-2">
                    <!-- Sun Icon for Light Mode -->
                    <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                    <!-- Moon Icon for Dark Mode -->
                    <svg x-show="isDark" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-sky-400"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                    <span class="text-[11px] font-mono font-medium" x-text="isDark ? 'Theme: Dark' : 'Theme: Light'"></span>
                </div>
                <!-- Mini Toggle Switch -->
                <div class="w-8 h-4 rounded-full p-0.5 relative transition-colors" :class="isDark ? 'bg-emerald-950 border border-emerald-700' : 'bg-amber-100 border border-amber-400'">
                    <div class="w-3 h-3 rounded-full transition-transform transform" :class="isDark ? 'translate-x-4 bg-emerald-400' : 'translate-x-0 bg-amber-500'"></div>
                </div>
            </button>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 bg-slate-50 dark:bg-slate-950/80 border-t border-slate-200 dark:border-slate-800">
            <!-- User Info -->
            <div class="flex items-center gap-3 mb-3 px-1">
                <div class="w-9 h-9 rounded-lg bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 flex items-center justify-center font-bold text-xs text-emerald-600 dark:text-emerald-400 shrink-0">
                    {{ Auth::user()->isSuperAdmin() ? 'SA' : 'AD' }}
                </div>
                <div class="overflow-hidden">
                    <span class="block font-semibold truncate text-xs text-slate-900 dark:text-white">{{ Auth::user()->email }}</span>
                    <span class="inline-block px-1.5 py-0.5 rounded text-[9px] {{ Auth::user()->isSuperAdmin() ? 'bg-cyan-100 dark:bg-cyan-900/60 text-cyan-800 dark:text-cyan-300 border border-cyan-300 dark:border-cyan-700/50' : 'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700/50' }} font-bold uppercase tracking-wider mt-0.5">
                        {{ Auth::user()->isSuperAdmin() ? 'SuperAdmin' : 'Operator' }}
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 px-3 bg-white dark:bg-slate-800/80 hover:bg-rose-50 dark:hover:bg-rose-900/30 text-slate-700 dark:text-slate-300 hover:text-rose-600 dark:hover:text-rose-400 border border-slate-200 dark:border-slate-700 hover:border-rose-200 dark:hover:border-rose-800/50 rounded-lg font-semibold text-xs transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>
</div>
