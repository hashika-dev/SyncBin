<aside class="fixed inset-y-0 left-0 w-72 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border-r border-rose-100 dark:border-zinc-800 flex flex-col z-50 transition-colors duration-300">
    <!-- Brand / Navigation Logo -->
    <div class="px-8 pt-10 pb-2 flex items-center gap-3">
        <div class="text-rose-500 animate-float">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 8v12c0 1-1 2-2 2H7c-1 0-2-1-2-2V8"/>
                <path d="M3 8h18"/>
                <path d="M9 8V5c0-1 1-2 2-2h2c1 0 2 1 2 2v3"/>
            </svg>
        </div>
        <span class="text-2xl font-black tracking-tight text-rose-950 dark:text-zinc-50">SyncBin</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-6 py-12 space-y-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-5 py-4 transition-all rounded-2xl font-bold group {{ $active === 'dashboard' ? 'bg-rose-500 text-white font-black shadow-lg shadow-rose-200 dark:shadow-none' : 'text-rose-900/60 dark:text-zinc-400 hover:bg-rose-50 dark:hover:bg-zinc-800 hover:text-rose-900 dark:hover:text-zinc-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'dashboard' ? '' : 'opacity-40 group-hover:opacity-100' }}"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            Dashboard
        </a>
        <a href="{{ route('dashboard.history') }}" class="flex items-center gap-4 px-5 py-4 transition-all rounded-2xl font-bold group {{ $active === 'history' ? 'bg-rose-500 text-white font-black shadow-lg shadow-rose-200 dark:shadow-none' : 'text-rose-900/60 dark:text-zinc-400 hover:bg-rose-50 dark:hover:bg-zinc-800 hover:text-rose-900 dark:hover:text-zinc-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'history' ? '' : 'opacity-40 group-hover:opacity-100' }}"><path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0Z"/><path d="M12 8v4l3 3"/></svg>
            Activity History
        </a>
        <a href="{{ route('dashboard.reports') }}" class="flex items-center gap-4 px-5 py-4 transition-all rounded-2xl font-bold group {{ $active === 'reports' ? 'bg-rose-500 text-white font-black shadow-lg shadow-rose-200 dark:shadow-none' : 'text-rose-900/60 dark:text-zinc-400 hover:bg-rose-50 dark:hover:bg-zinc-800 hover:text-rose-900 dark:hover:text-zinc-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'reports' ? '' : 'opacity-40 group-hover:opacity-100' }}"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>
            Reports
        </a>
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-5 py-4 transition-all rounded-2xl font-bold group {{ $active === 'settings' ? 'bg-rose-500 text-white font-black shadow-lg shadow-rose-200 dark:shadow-none' : 'text-rose-900/60 dark:text-zinc-400 hover:bg-rose-50 dark:hover:bg-zinc-800 hover:text-rose-900 dark:hover:text-zinc-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'settings' ? '' : 'opacity-40 group-hover:opacity-100' }}"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            Settings
        </a>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-8 bg-rose-50/50 dark:bg-zinc-800/40 border-t border-rose-100 dark:border-zinc-800 transition-colors duration-300">
        <!-- Dark Mode Toggle Button -->
        <div x-data="{ 
            darkMode: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
            toggle() {
                this.darkMode = !this.darkMode;
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                }
            }
        }" class="flex items-center justify-between mb-6 px-1">
            <span class="text-xs font-black uppercase tracking-widest text-rose-900/60 dark:text-zinc-400">Dark Mode</span>
            <button @click="toggle()" type="button" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2" :class="darkMode ? 'bg-rose-500' : 'bg-rose-200 dark:bg-zinc-700'">
                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="darkMode ? 'translate-x-5' : 'translate-x-0'"></span>
            </button>
        </div>

        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-white dark:bg-zinc-800 border border-rose-200 dark:border-zinc-700 flex items-center justify-center font-black text-rose-600 shadow-sm">AD</div>
            <div class="overflow-hidden">
                <span class="block font-black truncate text-sm text-rose-950 dark:text-zinc-50 uppercase tracking-tight">{{ Auth::user()->email }}</span>
                <span class="inline-block px-2 py-0.5 rounded-lg text-[10px] bg-rose-600 text-white font-black uppercase tracking-widest mt-1">Admin</span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-3 py-4 bg-white dark:bg-zinc-800 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-zinc-700 rounded-2xl font-black text-sm hover:bg-rose-500 dark:hover:bg-rose-500 hover:text-white dark:hover:text-white hover:border-rose-500 transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                Logout
            </button>
        </form>
    </div>
</aside>
