<!-- Mobile Top Header Bar (Visible on screens < lg) -->
<div class="lg:hidden sticky top-0 z-40 w-full shrink-0 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-md border-b border-rose-100 dark:border-zinc-800 px-4 py-3 flex items-center justify-between shadow-sm">
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen" type="button" class="p-2 rounded-xl text-rose-950 dark:text-zinc-100 hover:bg-rose-50 dark:hover:bg-zinc-800 focus:outline-none transition-colors" aria-label="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" x2="21" y1="6" y2="6"/>
                <line x1="3" x2="21" y1="12" y2="12"/>
                <line x1="3" x2="21" y1="18" y2="18"/>
            </svg>
        </button>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <div class="text-rose-500 animate-float">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 8v12c0 1-1 2-2 2H7c-1 0-2-1-2-2V8"/>
                    <path d="M3 8h18"/>
                    <path d="M9 8V5c0-1 1-2 2-2h2c1 0 2 1 2 2v3"/>
                </svg>
            </div>
            <span class="text-xl font-black tracking-tight text-rose-950 dark:text-zinc-50">SyncBin</span>
        </a>
    </div>
    <div class="flex items-center gap-2">
        <span class="px-2.5 py-1 rounded-lg text-[10px] {{ Auth::user()->isSuperAdmin() ? 'bg-indigo-600' : 'bg-rose-600' }} text-white font-black uppercase tracking-widest">
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
     class="fixed inset-0 bg-zinc-950/60 backdrop-blur-sm z-40 lg:hidden"
     x-cloak></div>

<!-- Sidebar Drawer -->
<aside class="fixed inset-y-0 left-0 w-72 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-xl border-r border-rose-100 dark:border-zinc-800 flex flex-col z-50 transition-transform duration-300 ease-in-out lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'">
    <!-- Brand / Navigation Logo -->
    <div class="px-8 pt-8 pb-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="text-rose-500 animate-float">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 8v12c0 1-1 2-2 2H7c-1 0-2-1-2-2V8"/>
                    <path d="M3 8h18"/>
                    <path d="M9 8V5c0-1 1-2 2-2h2c1 0 2 1 2 2v3"/>
                </svg>
            </div>
            <span class="text-2xl font-black tracking-tight text-rose-950 dark:text-zinc-50">SyncBin</span>
        </div>
        <!-- Close button on mobile -->
        <button @click="sidebarOpen = false" class="lg:hidden p-2 text-rose-900/60 dark:text-zinc-400 hover:text-rose-950 dark:hover:text-white rounded-xl hover:bg-rose-50 dark:hover:bg-zinc-800 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-6 py-6 space-y-2 overflow-y-auto">
        <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="flex items-center gap-4 px-5 py-3.5 transition-all rounded-2xl font-bold group {{ $active === 'dashboard' ? 'bg-rose-500 text-white font-black shadow-lg shadow-rose-200 dark:shadow-none' : 'text-rose-900/60 dark:text-zinc-400 hover:bg-rose-50 dark:hover:bg-zinc-800 hover:text-rose-900 dark:hover:text-zinc-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'dashboard' ? '' : 'opacity-40 group-hover:opacity-100' }}"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            Dashboard
        </a>
        <a href="{{ route('dashboard.history') }}" @click="sidebarOpen = false" class="flex items-center gap-4 px-5 py-3.5 transition-all rounded-2xl font-bold group {{ $active === 'history' ? 'bg-rose-500 text-white font-black shadow-lg shadow-rose-200 dark:shadow-none' : 'text-rose-900/60 dark:text-zinc-400 hover:bg-rose-50 dark:hover:bg-zinc-800 hover:text-rose-900 dark:hover:text-zinc-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'history' ? '' : 'opacity-40 group-hover:opacity-100' }}"><path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0Z"/><path d="M12 8v4l3 3"/></svg>
            Activity History
        </a>
        <a href="{{ route('dashboard.reports') }}" @click="sidebarOpen = false" class="flex items-center gap-4 px-5 py-3.5 transition-all rounded-2xl font-bold group {{ $active === 'reports' ? 'bg-rose-500 text-white font-black shadow-lg shadow-rose-200 dark:shadow-none' : 'text-rose-900/60 dark:text-zinc-400 hover:bg-rose-50 dark:hover:bg-zinc-800 hover:text-rose-900 dark:hover:text-zinc-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'reports' ? '' : 'opacity-40 group-hover:opacity-100' }}"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>
            Reports
        </a>
        @if(auth()->user()->isSuperAdmin())
        <a href="{{ route('dashboard.hardware') }}" @click="sidebarOpen = false" class="flex items-center gap-4 px-5 py-3.5 transition-all rounded-2xl font-bold group {{ $active === 'hardware' ? 'bg-indigo-600 text-white font-black shadow-lg shadow-indigo-200 dark:shadow-none' : 'text-indigo-900/70 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-zinc-800 hover:text-indigo-950 dark:hover:text-zinc-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'hardware' ? '' : 'opacity-60 group-hover:opacity-100' }}"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/><rect width="18" height="18" x="3" y="3" rx="2"/></svg>
            Hardware Monitor
        </a>
        @endif
        <a href="{{ route('profile.edit') }}" @click="sidebarOpen = false" class="flex items-center gap-4 px-5 py-3.5 transition-all rounded-2xl font-bold group {{ $active === 'settings' ? 'bg-rose-500 text-white font-black shadow-lg shadow-rose-200 dark:shadow-none' : 'text-rose-900/60 dark:text-zinc-400 hover:bg-rose-50 dark:hover:bg-zinc-800 hover:text-rose-900 dark:hover:text-zinc-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="{{ $active === 'settings' ? '' : 'opacity-40 group-hover:opacity-100' }}"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            Settings
        </a>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-6 bg-rose-50/50 dark:bg-zinc-800/40 border-t border-rose-100 dark:border-zinc-800 transition-colors duration-300">
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
        }" class="flex items-center justify-between mb-5 px-1">
            <span class="text-xs font-black uppercase tracking-widest text-rose-900/60 dark:text-zinc-400">Dark Mode</span>
            <button @click="toggle()" type="button" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2" :class="darkMode ? 'bg-rose-500' : 'bg-rose-200 dark:bg-zinc-700'">
                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="darkMode ? 'translate-x-5' : 'translate-x-0'"></span>
            </button>
        </div>

        <div class="flex items-center gap-3.5 mb-5">
            <div class="w-11 h-11 rounded-2xl bg-white dark:bg-zinc-800 border border-rose-200 dark:border-zinc-700 flex items-center justify-center font-black text-rose-600 shadow-sm shrink-0">
                {{ Auth::user()->isSuperAdmin() ? 'SA' : 'AD' }}
            </div>
            <div class="overflow-hidden">
                <span class="block font-black truncate text-xs text-rose-950 dark:text-zinc-50 uppercase tracking-tight">{{ Auth::user()->email }}</span>
                <span class="inline-block px-2 py-0.5 rounded-lg text-[9px] {{ Auth::user()->isSuperAdmin() ? 'bg-indigo-600' : 'bg-rose-600' }} text-white font-black uppercase tracking-widest mt-0.5">
                    {{ Auth::user()->isSuperAdmin() ? 'SuperAdmin' : 'Admin' }}
                </span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2.5 py-3 bg-white dark:bg-zinc-800 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-zinc-700 rounded-2xl font-black text-xs hover:bg-rose-500 dark:hover:bg-rose-500 hover:text-white dark:hover:text-white hover:border-rose-500 transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                Logout
            </button>
        </form>
    </div>
</aside>
