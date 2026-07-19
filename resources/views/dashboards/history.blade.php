<x-app-layout>
    @slot('hideNav')
        true
    @endslot

    <div class="flex min-h-screen bg-gradient-to-br from-rose-50 via-white to-orange-50/50 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950 text-gray-900 dark:text-zinc-100 transition-colors duration-300" x-data="{ showClearConfirm: false, setQuickRange(val) { document.getElementById('quick_range_input').value = val; document.getElementById('from_date').value = ''; document.getElementById('to_date').value = ''; document.getElementById('historyFilterForm').submit(); } }">
        <!-- Sidebar -->
        @include('layouts.sidebar', ['active' => 'history'])

        <!-- Main Content -->
        <main class="flex-1 ml-72 p-16">
            <!-- Header -->
            <header class="flex items-center justify-between mb-16">
                <div>
                    <h1 class="text-5xl font-black text-rose-950 dark:text-zinc-50 tracking-tighter">Activity History</h1>
                    <p class="text-rose-600 dark:text-rose-400 mt-3 font-bold text-xl opacity-80">
                        Audit trail and logs of segregated waste items
                    </p>
                </div>
                
                @if($logs->isNotEmpty())
                    <button type="button" @click="showClearConfirm = true" class="flex items-center gap-3 px-8 py-4 bg-rose-500 text-white rounded-2xl font-black shadow-xl shadow-rose-200 hover:bg-rose-600 hover:-translate-y-1 transition-all active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        Clear All Logs
                    </button>
                @endif
            </header>

            <!-- Filters Banner -->
            <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-[2.5rem] shadow-2xl dark:shadow-none border border-white dark:border-zinc-800 p-8 mb-12 transition-colors duration-300">
                <form action="{{ route('dashboard.history') }}" method="GET" id="historyFilterForm" class="space-y-6">
                    <!-- Quick Select Preset Badges -->
                    <div class="flex flex-wrap items-center gap-3 pb-2 border-b border-rose-50 dark:border-zinc-800">
                        <span class="text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500 mr-2">Quick Select:</span>
                        <input type="hidden" name="quick_range" id="quick_range_input" value="{{ request('quick_range') }}">
                        
                        <button type="button" @click="setQuickRange('')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all {{ !request('quick_range') ? 'bg-rose-500 text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 hover:bg-rose-100 dark:hover:bg-zinc-700' }}">
                            All Time
                        </button>
                        <button type="button" @click="setQuickRange('today')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all {{ request('quick_range') == 'today' ? 'bg-rose-500 text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 hover:bg-rose-100 dark:hover:bg-zinc-700' }}">
                            Today
                        </button>
                        <button type="button" @click="setQuickRange('yesterday')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all {{ request('quick_range') == 'yesterday' ? 'bg-rose-500 text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 hover:bg-rose-100 dark:hover:bg-zinc-700' }}">
                            Yesterday
                        </button>
                        <button type="button" @click="setQuickRange('7days')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all {{ request('quick_range') == '7days' ? 'bg-rose-500 text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 hover:bg-rose-100 dark:hover:bg-zinc-700' }}">
                            Last 7 Days
                        </button>
                        <button type="button" @click="setQuickRange('30days')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all {{ request('quick_range') == '30days' ? 'bg-rose-500 text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 hover:bg-rose-100 dark:hover:bg-zinc-700' }}">
                            Last 30 Days
                        </button>
                        <button type="button" @click="setQuickRange('this_month')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all {{ request('quick_range') == 'this_month' ? 'bg-rose-500 text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 hover:bg-rose-100 dark:hover:bg-zinc-700' }}">
                            This Month
                        </button>
                    </div>

                    <!-- Input Controls Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                        <!-- Search Field -->
                        <div>
                            <label for="search" class="block text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500 mb-3">Search Item</label>
                            <input type="text" name="search" id="search" placeholder="Item name (e.g. Battery)..." value="{{ request('search') }}"
                                   class="w-full px-5 py-4 border border-rose-100 dark:border-zinc-800 rounded-2xl focus:ring-4 focus:ring-rose-100 focus:border-rose-300 outline-none transition-all placeholder:text-gray-400 dark:placeholder:text-zinc-600 bg-gray-50/50 dark:bg-zinc-800/50 hover:bg-white dark:hover:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-800 text-sm font-bold text-rose-950 dark:text-zinc-100">
                        </div>

                        <!-- Classification / Event Type Filter -->
                        <div>
                            <label for="bin" class="block text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500 mb-3">Event Classification</label>
                            <select name="bin" id="bin" class="w-full px-5 py-4 border border-rose-100 dark:border-zinc-800 rounded-2xl focus:ring-4 focus:ring-rose-100 focus:border-rose-300 outline-none transition-all bg-gray-50/50 dark:bg-zinc-800/50 hover:bg-white dark:hover:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-800 text-sm font-bold text-rose-950 dark:text-zinc-100">
                                <option value="">All Classifications</option>
                                @foreach($bins as $b)
                                    <option value="{{ $b->slug }}" {{ request('bin') == $b->slug ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- From Date -->
                        <div>
                            <label for="from_date" class="block text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500 mb-3">From Date</label>
                            <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}"
                                   class="w-full px-5 py-4 border border-rose-100 dark:border-zinc-800 rounded-2xl focus:ring-4 focus:ring-rose-100 focus:border-rose-300 outline-none transition-all bg-gray-50/50 dark:bg-zinc-800/50 hover:bg-white dark:hover:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-800 text-sm font-bold text-rose-950 dark:text-zinc-100">
                        </div>

                        <!-- To Date -->
                        <div>
                            <label for="to_date" class="block text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500 mb-3">To Date</label>
                            <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}"
                                   class="w-full px-5 py-4 border border-rose-100 dark:border-zinc-800 rounded-2xl focus:ring-4 focus:ring-rose-100 focus:border-rose-300 outline-none transition-all bg-gray-50/50 dark:bg-zinc-800/50 hover:bg-white dark:hover:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-800 text-sm font-bold text-rose-950 dark:text-zinc-100">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 items-center justify-end pt-2">
                        <button type="submit" class="px-8 py-4 bg-rose-500 hover:bg-rose-600 text-white rounded-2xl font-black shadow-lg shadow-rose-200 transition-all text-sm uppercase tracking-wider">
                            Apply Filters
                        </button>
                        @if(request()->anyFilled(['search', 'bin', 'from_date', 'to_date', 'quick_range']))
                            <a href="{{ route('dashboard.history') }}" class="px-8 py-4 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 rounded-2xl font-black transition-all text-sm text-center uppercase tracking-wider">
                                Reset Filters
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Status Alert Message -->
            @if (session('status'))
                <div class="mb-10 p-6 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 rounded-3xl flex items-center gap-4 text-emerald-800 dark:text-emerald-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    <span class="font-bold text-sm">{{ session('status') }}</span>
                </div>
            @endif

            <!-- History Logs Table -->
            <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-[3rem] shadow-2xl dark:shadow-none border border-white dark:border-zinc-800 overflow-hidden p-8 transition-colors duration-300">
                @if($logs->isEmpty())
                    <div class="text-center py-20">
                        <div class="w-20 h-20 bg-rose-50 dark:bg-zinc-800 rounded-[2rem] flex items-center justify-center text-rose-500 mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                        </div>
                        <h4 class="text-xl font-black text-rose-950 dark:text-zinc-100">No logs found</h4>
                        <p class="text-rose-500/70 dark:text-rose-400/70 font-bold mt-2">Try adjusting your filters or simulate some scans from the dashboard.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-rose-50 dark:border-zinc-800">
                                    <th class="pb-6 font-black text-xs uppercase tracking-widest text-rose-400 dark:text-zinc-500 pl-4">Timestamp</th>
                                    <th class="pb-6 font-black text-xs uppercase tracking-widest text-rose-400 dark:text-zinc-500">Classification</th>
                                    <th class="pb-6 font-black text-xs uppercase tracking-widest text-rose-400 dark:text-zinc-500">Icon</th>
                                    <th class="pb-6 font-black text-xs uppercase tracking-widest text-rose-400 dark:text-zinc-500">Item Name</th>
                                    <th class="pb-6 font-black text-xs uppercase tracking-widest text-rose-400 dark:text-zinc-500 text-right pr-4">Weight</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-rose-50/50 dark:divide-zinc-800/50">
                                @foreach($logs as $log)
                                    <tr class="hover:bg-rose-50/30 dark:hover:bg-zinc-800/30 transition-all duration-300">
                                        <td class="py-6 text-sm font-bold text-rose-900/60 dark:text-zinc-400 pl-4">
                                            {{ $log->created_at->format('M d, Y') }}
                                            <span class="block text-xs font-medium text-rose-400 dark:text-rose-500 mt-1">{{ $log->created_at->format('h:i:s A') }}</span>
                                        </td>
                                        <td class="py-6">
                                            @php
                                                $badgeColors = [
                                                    'hazardous' => 'bg-red-100 dark:bg-red-950/40 text-red-800 dark:text-red-300 border-red-200 dark:border-red-900/40',
                                                    'recyclable' => 'bg-sky-100 dark:bg-sky-950/40 text-sky-800 dark:text-sky-300 border-sky-200 dark:border-sky-900/40',
                                                    'biodegradable' => 'bg-emerald-100 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900/40',
                                                    'non-bio' => 'bg-orange-100 dark:bg-orange-950/40 text-orange-800 dark:text-orange-300 border-orange-200 dark:border-orange-900/40'
                                                ];
                                                $colorClass = $badgeColors[$log->bin->slug] ?? 'bg-gray-100 dark:bg-zinc-800 text-gray-800 dark:text-zinc-300 border-gray-200 dark:border-zinc-700';
                                            @endphp
                                            <span class="inline-block px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border {{ $colorClass }}">
                                                {{ $log->bin->name }}
                                            </span>
                                        </td>
                                        <td class="py-6 text-2xl">{{ $log->icon }}</td>
                                        <td class="py-6 text-sm font-black text-rose-950 dark:text-zinc-100">{{ $log->name }}</td>
                                        <td class="py-6 text-sm font-bold text-rose-900/60 dark:text-zinc-400 text-right pr-4">{{ $log->weight }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Container -->
                    <div class="mt-8 pt-8 border-t border-rose-50 dark:border-zinc-800">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </main>

        <!-- Custom Confirmation Modal Overlay (Moved inside Alpine boundary) -->
        <div x-show="showClearConfirm" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            
            <!-- Modal Container -->
            <div @click.away="showClearConfirm = false" 
                 class="bg-white dark:bg-zinc-900 rounded-[2.5rem] shadow-2xl dark:shadow-none max-w-md w-full p-10 relative overflow-hidden border border-rose-100 dark:border-zinc-800 transition-colors duration-300"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <!-- Warning Icon -->
                <div class="w-16 h-16 bg-red-50 dark:bg-red-950/20 text-red-500 rounded-[2rem] flex items-center justify-center mb-6 border border-red-100 dark:border-red-900/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                </div>

                <!-- Content -->
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-rose-950 dark:text-zinc-100 tracking-tight">Clear History Logs?</h3>
                    <p class="text-sm font-bold text-rose-500/70 dark:text-rose-450/70 mt-3 leading-relaxed">
                        Are you sure you want to clear all history logs? This action is permanent and will reset all bin fill levels to 0% as well.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <button @click="showClearConfirm = false" 
                            class="py-4 border-2 border-rose-100 dark:border-zinc-800 text-rose-350 dark:text-zinc-500 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all active:scale-95">
                        Cancel
                    </button>
                    
                    <form action="{{ route('dashboard.history.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full py-4 bg-red-500 hover:bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-red-100 dark:shadow-none transition-all active:scale-95">
                            Clear Logs
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
