<nav x-data="{
    open: false,
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
}" @click.outside="open = false" class="sticky top-0 z-50 w-full bg-[#0E131F]/90 backdrop-blur-md border-b border-[#1A2234] transition-colors duration-300">
    <div class="max-w-[1720px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14">
            
            <!-- Left Side: Logo & Main Navigation Links -->
            <div class="flex items-center gap-1 sm:gap-2 lg:gap-6">
                <!-- System Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 shrink-0 group py-1">
                    <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-6 h-6 object-contain group-hover:scale-105 transition-transform duration-200">
                    <span class="text-base font-bold tracking-tight text-white">EcoSync</span>
                </a>

                <!-- Desktop Navigation Pills -->
                <div class="hidden lg:flex items-center gap-1.5 ml-2">
                    <a href="{{ route('dashboard') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#1C2638] text-white border border-[#2B3A52] shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-[#151D2C]' }}">
                        Live Monitor
                    </a>

                    <a href="{{ route('dashboard.reports') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('dashboard.reports') ? 'bg-[#1C2638] text-white border border-[#2B3A52] shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-[#151D2C]' }}">
                        Analytics
                    </a>

                    <a href="{{ route('dashboard.history') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('dashboard.history') ? 'bg-[#1C2638] text-white border border-[#2B3A52] shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-[#151D2C]' }}">
                        Audit Trail
                    </a>

                    @if(Auth::user()->isSuperAdmin())
                    <a href="{{ route('dashboard.hardware') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('dashboard.hardware') ? 'bg-[#1C2638] text-white border border-[#2B3A52] shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-[#151D2C]' }}">
                        Hardware Monitor
                    </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('profile.edit') ? 'bg-[#1C2638] text-white border border-[#2B3A52] shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-[#151D2C]' }}">
                        Settings
                    </a>
                </div>
            </div>

            <!-- Right Side: User Details & Controls (Desktop & Tablet) -->
            <div class="hidden sm:flex items-center gap-3.5">
                <!-- Role Badge -->
                <span class="px-2.5 py-0.5 rounded text-[10px] font-mono font-bold uppercase tracking-wider {{ Auth::user()->isSuperAdmin() ? 'bg-cyan-950/80 text-cyan-400 border border-cyan-800/80' : 'bg-emerald-950/80 text-emerald-400 border border-emerald-800/80' }}">
                    {{ Auth::user()->isSuperAdmin() ? 'SuperAdmin' : 'Admin' }}
                </span>

                <!-- User Email -->
                <span class="text-xs font-mono text-slate-300 truncate max-w-[180px] lg:max-w-[240px]">{{ Auth::user()->email }}</span>

                <!-- Theme Toggle Button -->
                <button @click="toggleTheme()" type="button" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-200 hover:bg-[#151D2C] transition-colors" title="Toggle Theme" aria-label="Toggle Color Theme">
                    <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-400"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                    <svg x-show="isDark" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-300"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                </button>

                <!-- Divider -->
                <span class="h-4 w-px bg-slate-800"></span>

                <!-- Sign Out -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-slate-400 hover:text-rose-400 font-medium transition-colors">
                        Sign out
                    </button>
                </form>
            </div>

            <!-- Mobile Menu Toggle Button (Visible < lg) -->
            <div class="flex items-center gap-2 lg:hidden">
                <button @click="open = !open" 
                        class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-[#151D2C] focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition-colors" 
                        aria-label="Toggle navigation menu"
                        :aria-expanded="open">
                    <svg class="h-5 w-5 transition-transform duration-200" :class="open ? 'rotate-90' : ''" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Menu (< lg) -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-3"
         class="lg:hidden border-t border-[#1A2234] bg-[#0E131F]/95 backdrop-blur-lg px-4 pt-3 pb-5 space-y-2 shadow-2xl"
         x-cloak>
        
        <!-- Mobile User Info & Role Banner -->
        <div class="flex items-center justify-between pb-3 mb-2 border-b border-[#1C2638]">
            <div class="min-w-0 flex-1 pr-2">
                <span class="text-xs font-mono text-slate-300 block truncate">{{ Auth::user()->email }}</span>
                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[9px] font-mono font-bold uppercase tracking-wider {{ Auth::user()->isSuperAdmin() ? 'bg-cyan-950 text-cyan-400 border border-cyan-800/80' : 'bg-emerald-950 text-emerald-400 border border-emerald-800/80' }}">
                    {{ Auth::user()->isSuperAdmin() ? 'SuperAdmin' : 'Admin' }}
                </span>
            </div>
            <!-- Mobile Theme Toggle -->
            <button @click="toggleTheme()" type="button" class="p-2 rounded-lg bg-[#161D2B] border border-[#243046] text-slate-300 hover:text-white text-xs flex items-center gap-1.5 shrink-0" title="Toggle Theme">
                <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-400"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                <svg x-show="isDark" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-300"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                <span class="text-[11px] font-medium" x-text="isDark ? 'Light' : 'Dark'"></span>
            </button>
        </div>

        <!-- Navigation Links -->
        <a href="{{ route('dashboard') }}" @click="open = false" 
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('dashboard') ? 'bg-[#1C2638] text-white border border-[#2B3A52]' : 'text-slate-400 hover:text-white hover:bg-[#151D2C]' }}">
            <span>Live Monitor</span>
            @if(request()->routeIs('dashboard'))
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            @endif
        </a>

        <a href="{{ route('dashboard.reports') }}" @click="open = false" 
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('dashboard.reports') ? 'bg-[#1C2638] text-white border border-[#2B3A52]' : 'text-slate-400 hover:text-white hover:bg-[#151D2C]' }}">
            <span>Analytics</span>
            @if(request()->routeIs('dashboard.reports'))
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            @endif
        </a>

        <a href="{{ route('dashboard.history') }}" @click="open = false" 
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('dashboard.history') ? 'bg-[#1C2638] text-white border border-[#2B3A52]' : 'text-slate-400 hover:text-white hover:bg-[#151D2C]' }}">
            <span>Audit Trail</span>
            @if(request()->routeIs('dashboard.history'))
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            @endif
        </a>

        @if(Auth::user()->isSuperAdmin())
        <a href="{{ route('dashboard.hardware') }}" @click="open = false" 
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('dashboard.hardware') ? 'bg-[#1C2638] text-white border border-[#2B3A52]' : 'text-slate-400 hover:text-white hover:bg-[#151D2C]' }}">
            <span>Hardware Monitor</span>
            @if(request()->routeIs('dashboard.hardware'))
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
            @endif
        </a>
        @endif

        <a href="{{ route('profile.edit') }}" @click="open = false" 
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('profile.edit') ? 'bg-[#1C2638] text-white border border-[#2B3A52]' : 'text-slate-400 hover:text-white hover:bg-[#151D2C]' }}">
            <span>Settings</span>
            @if(request()->routeIs('profile.edit'))
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            @endif
        </a>

        <div class="pt-3 border-t border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center py-2 text-xs text-rose-400 hover:text-rose-300 font-semibold bg-rose-950/30 border border-rose-900/40 rounded-xl transition-colors">
                    Sign out
                </button>
            </form>
        </div>
    </div>
</nav>
