<x-app-layout>
    @slot('hideNav')
        true
    @endslot

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="flex flex-col lg:flex-row min-h-screen w-full bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        @include('layouts.sidebar', ['active' => 'reports'])

        <!-- Main Content -->
        <main class="flex-1 w-full min-w-0 ml-0 lg:ml-72 p-4 sm:p-6 lg:p-10 xl:p-12">
            <!-- Header -->
            <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Telemetry Intelligence</span>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-0.5">Reports & Analytics</h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1 text-xs sm:text-sm">
                        IoT fleet efficiency, waste diversion index, and response time metrics
                    </p>
                </div>
                
                <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
                    <button onclick="seedDemoData(this)" class="px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-xl font-semibold text-xs transition-colors flex items-center gap-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                        <span>Seed Demo Logs</span>
                    </button>
                    <a href="{{ route('dashboard.export.csv') }}" class="px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-xl font-semibold text-xs transition-colors flex items-center gap-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
                        <span>Export CSV</span>
                    </a>
                    <a href="{{ route('dashboard.export') }}" target="_blank" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs transition-colors flex items-center gap-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <span>Download PDF</span>
                    </a>
                </div>
            </header>

            <!-- Key Performance Indicators (KPIs) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                <!-- Total Items -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm dark:shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Total Items</span>
                        <div class="w-8 h-8 bg-slate-100 dark:bg-slate-950 rounded-lg flex items-center justify-center text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 8h10"/><path d="M7 12h10"/><path d="M7 16h10"/></svg>
                        </div>
                    </div>
                    <span class="block text-3xl font-mono font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $totalItemsCount }}</span>
                    <span class="block text-[11px] text-slate-500 dark:text-slate-400 font-semibold mt-1">Classified items</span>
                </div>

                <!-- Recycling Rate -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm dark:shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Diversion Rate</span>
                        <div class="w-8 h-8 bg-emerald-50 dark:bg-emerald-950/60 rounded-lg flex items-center justify-center text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-15 6.7L3 16"/><path d="M3 21v-5h5"/></svg>
                        </div>
                    </div>
                    <span class="block text-3xl font-mono font-extrabold text-emerald-600 dark:text-emerald-400 tracking-tight">{{ $recyclingRate }}%</span>
                    <span class="block text-[11px] text-emerald-600/80 dark:text-emerald-500/80 font-semibold mt-1">Eco diversion ratio</span>
                </div>

                <!-- Total Weight -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm dark:shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-sky-600 dark:text-sky-400">Total Mass</span>
                        <div class="w-8 h-8 bg-sky-50 dark:bg-sky-950/60 rounded-lg flex items-center justify-center text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800/60">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="3"/><path d="M6.5 8a2 2 0 0 0-1.905 1.46L2.1 18.5A2 2 0 0 0 4 21h16a2 2 0 0 0 1.9-2.5l-2.495-9.04A2 2 0 0 0 17.5 8Z"/></svg>
                        </div>
                    </div>
                    <span class="block text-3xl font-mono font-extrabold text-sky-600 dark:text-sky-400 tracking-tight">{{ $totalWeightKg }} kg</span>
                    <span class="block text-[11px] text-sky-600/80 dark:text-sky-400/80 font-semibold mt-1">Combined payload</span>
                </div>

                <!-- Peak Storage -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm dark:shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400">Peak Storage</span>
                        <div class="w-8 h-8 bg-amber-50 dark:bg-amber-950/60 rounded-lg flex items-center justify-center text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800/60">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"/><path d="m19 5-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <span class="block text-2xl font-bold text-slate-900 dark:text-white tracking-tight truncate uppercase">{{ $mostActiveBin ? $mostActiveBin->name : 'None' }}</span>
                    <span class="block text-[11px] text-slate-500 dark:text-slate-400 font-semibold mt-1">Highest item volume</span>
                </div>
            </div>

            <!-- Activity Trends Line Chart Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 mb-8 shadow-sm dark:shadow-xl">
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-200 dark:border-slate-800">
                    <div>
                        <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">7-Day Telemetry Trend</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">Segregated Waste Volume</h3>
                    </div>
                    <span class="font-mono text-xs text-slate-500 dark:text-slate-400">Items / Day</span>
                </div>
                <div class="h-64 sm:h-80 w-full relative">
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>

            <!-- Evacuation & Maintenance Performance Section -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 mb-8 shadow-sm dark:shadow-xl">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
                    <div>
                        <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Response Analytics</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">Evacuation Audit Trail</h3>
                    </div>
                    <div class="flex items-center gap-2 px-3.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">Avg Fleet Response:</span>
                        <span class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400">{{ $avgResponseTimeMinutes > 0 ? $avgResponseTimeMinutes . ' min' . ($avgResponseTimeMinutes > 1 ? 's' : '') : 'N/A' }}</span>
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
                                    <th class="py-3 px-3 text-right">Timestamp</th>
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
                @endif
            </div>

            <!-- Detailed Visual Comparison Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-12">
                <!-- Column 1 & 2: Volume Comparison Chart -->
                <div class="md:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm dark:shadow-xl flex flex-col justify-between">
                    <div class="mb-6 pb-3 border-b border-slate-200 dark:border-slate-800">
                        <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Distribution Analysis</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">Classification Volume Share</h3>
                    </div>

                    <div class="space-y-5 flex-1 flex flex-col justify-center">
                        @foreach($bins as $bin)
                            @php
                                $percent = $totalItemsCount > 0 ? round(($bin->items->count() / $totalItemsCount) * 100) : 0;
                                $barColor = match($bin->slug) {
                                    'hazardous' => 'bg-red-500',
                                    'recyclable' => 'bg-sky-500',
                                    'biodegradable' => 'bg-emerald-500',
                                    default => 'bg-amber-500'
                                };
                            @endphp
                            <div>
                                <div class="flex justify-between items-center text-xs font-semibold mb-1.5">
                                    <span class="text-slate-700 dark:text-slate-300 font-bold uppercase tracking-wider text-[11px]">{{ $bin->name }}</span>
                                    <span class="font-mono text-slate-500 dark:text-slate-400">{{ $bin->items->count() }} items ({{ $percent }}%)</span>
                                </div>
                                <div class="h-2 w-full bg-slate-100 dark:bg-slate-950 rounded-full overflow-hidden border border-slate-200 dark:border-slate-800">
                                    <div class="h-full rounded-full transition-all duration-700 {{ $barColor }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Column 3: Eco Target Circular Gauge -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm dark:shadow-xl flex flex-col items-center text-center justify-between">
                    <div class="pb-3 border-b border-slate-200 dark:border-slate-800 w-full text-left">
                        <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Target Benchmark</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">Recycling Index</h3>
                    </div>

                    <!-- Circular Gauge -->
                    <div class="relative w-36 h-36 my-6 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <circle class="text-slate-100 dark:text-slate-950" stroke-width="10" stroke="currentColor" fill="transparent" r="40" cx="50" cy="50" />
                            <circle class="text-emerald-500 transition-all duration-1000 ease-out" stroke-width="10" stroke-dasharray="251.2" stroke-dashoffset="{{ 251.2 - (251.2 * $recyclingRate) / 100 }}" stroke-linecap="round" stroke="currentColor" fill="transparent" r="40" cx="50" cy="50" />
                        </svg>
                        <div class="absolute flex flex-col items-center justify-center">
                            <span class="text-3xl font-mono font-extrabold text-emerald-600 dark:text-emerald-400">{{ $recyclingRate }}%</span>
                            <span class="text-[9px] font-mono uppercase tracking-widest text-slate-400 mt-0.5">Achieved</span>
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 rounded-lg text-xs font-mono font-semibold border border-emerald-200 dark:border-emerald-800/60 mb-2">
                            Fleet Target: 80% Diversion
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Proportion of recyclable and organic waste successfully segregated.
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        async function seedDemoData(button) {
            if (!confirm('Are you sure you want to seed 30 days of mock telemetry logs?')) return;
            
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = `<span>Seeding...</span>`;
            
            try {
                await axios.post('/api/system/seed-mock-data');
                window.location.reload();
            } catch (error) {
                console.error("Error seeding mock data:", error);
                button.disabled = false;
                button.innerHTML = originalText;
                alert('Failed to seed demo data.');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('trendsChart').getContext('2d');
            
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

            window.trendsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Segregated Items',
                        data: @json($chartData),
                        borderColor: '#10b981',
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#020617',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#f8fafc',
                            bodyColor: '#10b981',
                            borderColor: '#334155',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(51, 65, 85, 0.3)' },
                            ticks: { color: '#94a3b8', font: { family: 'ui-monospace, monospace' } }
                        },
                        y: {
                            grid: { color: 'rgba(51, 65, 85, 0.3)' },
                            ticks: { color: '#94a3b8', font: { family: 'ui-monospace, monospace' }, precision: 0 }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
