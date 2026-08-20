<x-app-layout>
    @slot('hideNav')
        true
    @endslot

    <div class="flex flex-col lg:flex-row min-h-screen w-full bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300" x-data="{ sidebarOpen: false, showClearConfirm: false, setQuickRange(val) { document.getElementById('quick_range_input').value = val; document.getElementById('from_date').value = ''; document.getElementById('to_date').value = ''; document.getElementById('historyFilterForm').submit(); } }">
        <!-- Sidebar -->
        @include('layouts.sidebar', ['active' => 'history'])

        <!-- Main Content -->
        <main class="flex-1 w-full min-w-0 ml-0 lg:ml-72 p-4 sm:p-6 lg:p-10 xl:p-12">
            <!-- Header -->
            <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Audit Trail Console</span>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-0.5">Activity History</h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1 text-xs sm:text-sm">
                        Verified telemetry records and segregation audit history
                    </p>
                </div>
                
                @if($logs->isNotEmpty())
                    <button type="button" @click="showClearConfirm = true" class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2.5 bg-red-950/60 hover:bg-red-900/80 text-red-300 border border-red-800/80 rounded-xl font-semibold transition-colors text-xs shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        <span>Purge History Logs</span>
                    </button>
                @endif
            </header>

            <!-- Filters Banner -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 sm:p-6 mb-8 shadow-sm dark:shadow-xl">
                <form action="{{ route('dashboard.history') }}" method="GET" id="historyFilterForm" class="space-y-4">
                    <!-- Input Controls Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        <!-- Search Field -->
                        <div>
                            <label for="search" class="block text-[10px] font-mono font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1.5">Search Item</label>
                            <input type="text" name="search" id="search" placeholder="Item name..." value="{{ request('search') }}"
                                   @keydown.enter="$el.form.submit()"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none text-xs text-slate-800 dark:text-slate-200 placeholder:text-slate-400">
                        </div>

                        <!-- Classification / Event Type Filter -->
                        <div>
                            <label for="bin" class="block text-[10px] font-mono font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1.5">Bin Category</label>
                            <select name="bin" id="bin" @change="$el.form.submit()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none text-xs text-slate-800 dark:text-slate-200">
                                <option value="">All Categories</option>
                                @foreach($bins as $b)
                                    <option value="{{ $b->slug }}" {{ request('bin') == $b->slug ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- From Date -->
                        <div>
                            <label for="from_date" class="block text-[10px] font-mono font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1.5">From Date</label>
                            <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}"
                                   @change="$el.form.submit()"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none text-xs font-mono text-slate-800 dark:text-slate-200">
                        </div>

                        <!-- To Date -->
                        <div>
                            <label for="to_date" class="block text-[10px] font-mono font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1.5">To Date</label>
                            <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}"
                                   @change="$el.form.submit()"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none text-xs font-mono text-slate-800 dark:text-slate-200">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row flex-wrap gap-2.5 items-stretch sm:items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800/80">
                        <div>
                            @if(request()->anyFilled(['search', 'bin', 'from_date', 'to_date']))
                                <a href="{{ route('dashboard.history') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 font-semibold">
                                    <span>✕ Reset Filters</span>
                                </a>
                            @endif
                        </div>

                        <div class="flex items-center gap-2.5">
                            <a href="{{ route('dashboard.export.csv', request()->query()) }}" class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-xl font-semibold text-xs transition-colors flex items-center justify-center gap-2 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
                                <span>Export CSV</span>
                            </a>

                            <a href="{{ route('dashboard.export', request()->query()) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs transition-colors flex items-center justify-center gap-2 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <span>Export PDF</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Status Alert Message -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/80 rounded-xl flex items-center gap-3 text-emerald-700 dark:text-emerald-400 text-xs font-mono shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- History Logs Table -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden p-5 sm:p-6 shadow-sm dark:shadow-xl mb-8">
                <div class="mb-4 pb-3 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Classified Item Stream</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">Telemetry Ingestion Logs</h3>
                    </div>
                </div>

                @if($logs->isEmpty())
                    <div class="text-center py-12 text-slate-400 dark:text-slate-500 text-xs font-mono">
                        No telemetry logs match your active filter criteria.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[600px] text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-800 font-mono text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    <th class="pb-3 pl-3 min-w-[140px]">Timestamp</th>
                                    <th class="pb-3 min-w-[140px]">Classification</th>
                                    <th class="pb-3 min-w-[50px]">Icon</th>
                                    <th class="pb-3 min-w-[160px]">Item Description</th>
                                    <th class="pb-3 text-right pr-3 min-w-[90px]">Weight</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-700 dark:text-slate-200">
                                @foreach($logs as $log)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                        <td class="py-3 pl-3 font-mono text-slate-500 dark:text-slate-400">
                                            {{ $log->created_at->format('M d, Y') }}
                                            <span class="text-slate-400 dark:text-slate-500 block text-[11px]">{{ $log->created_at->format('H:i:s') }}</span>
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $badgeColors = [
                                                    'hazardous' => 'bg-red-50 dark:bg-red-950 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800',
                                                    'recyclable' => 'bg-sky-50 dark:bg-sky-950 text-sky-700 dark:text-sky-400 border-sky-200 dark:border-sky-800',
                                                    'biodegradable' => 'bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                                    'non-bio' => 'bg-amber-50 dark:bg-amber-950 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800'
                                                ];
                                                $colorClass = $badgeColors[$log->bin->slug] ?? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700';
                                            @endphp
                                            <span class="inline-block px-2 py-0.5 text-[10px] font-mono font-bold uppercase tracking-wider rounded border {{ $colorClass }}">
                                                {{ $log->bin->name }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-xl">{{ $log->icon }}</td>
                                        <td class="py-3 text-slate-900 dark:text-white font-bold">{{ $log->name }}</td>
                                        <td class="py-3 font-mono text-slate-500 dark:text-slate-400 text-right pr-3">{{ $log->weight }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Container -->
                    <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

            <!-- Evacuation Audit History Table -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden p-5 sm:p-6 shadow-sm dark:shadow-xl">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4 pb-3 border-b border-slate-200 dark:border-slate-800">
                    <div>
                        <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Maintenance Dispatch</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">Evacuation Audit Trail</h3>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs">
                        <span class="text-slate-500 dark:text-slate-400 font-mono">Avg Fleet Response:</span>
                        <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400">{{ $avgResponseTimeMinutes > 0 ? $avgResponseTimeMinutes . 'm' : 'N/A' }}</span>
                    </div>
                </div>

                @if($clearanceLogs->isEmpty())
                    <div class="text-center py-8 text-slate-400 dark:text-slate-500 text-xs font-mono">
                        No evacuation records recorded yet.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-800 font-mono text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    <th class="py-3 px-3">Bin Node</th>
                                    <th class="py-3 px-3">Operator</th>
                                    <th class="py-3 px-3 text-center">Pre-Clear Fill</th>
                                    <th class="py-3 px-3 text-center">Response Speed</th>
                                    <th class="py-3 px-3 text-right">Cleared At</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-700 dark:text-slate-200">
                                @foreach($clearanceLogs as $log)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                        <td class="py-3 px-3 font-bold text-slate-900 dark:text-white">
                                            {{ $log->bin ? $log->bin->name : 'Unknown Node' }}
                                        </td>
                                        <td class="py-3 px-3 text-slate-500 dark:text-slate-400 font-mono">
                                            {{ $log->cleared_by_email }}
                                        </td>
                                        <td class="py-3 px-3 text-center font-mono">
                                            <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-bold {{ $log->level_before_clearance >= 85 ? 'bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-400 border border-red-300 dark:border-red-800' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">
                                                {{ $log->level_before_clearance }}%
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 text-center font-mono">
                                            @if($log->response_time_minutes !== null)
                                                <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 text-xs">
                                                    ⚡ {{ $log->response_time_minutes }}m
                                                </span>
                                            @else
                                                <span class="text-slate-400 dark:text-slate-500">Immediate</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-right text-slate-500 dark:text-slate-400 font-mono">
                                            {{ $log->cleared_at ? $log->cleared_at->format('M d, Y • H:i') : 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($clearanceLogs->hasPages())
                        <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800">
                            {{ $clearanceLogs->appends(request()->query())->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </main>

        <!-- Custom Confirmation Modal Overlay -->
        <div x-show="showClearConfirm" 
             style="display: none;"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            
            <div @click.away="showClearConfirm = false" 
                 class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full p-6 relative overflow-hidden border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <div class="w-12 h-12 bg-red-100 dark:bg-red-950/40 text-red-600 dark:text-red-400 rounded-xl flex items-center justify-center mb-4 border border-red-300 dark:border-red-800/60">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                </div>

                <div class="mb-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Purge Telemetry Logs?</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                        This action will permanently delete historical telemetry data and reset all current bin storage levels to 0%.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button @click="showClearConfirm = false" 
                            class="py-2.5 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-transparent hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">
                        Cancel
                    </button>
                    
                    <form action="{{ route('dashboard.history.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full py-2.5 bg-red-600 hover:bg-red-500 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">
                            Confirm Purge
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
