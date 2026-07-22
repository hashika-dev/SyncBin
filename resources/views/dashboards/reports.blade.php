<x-app-layout>
    @slot('hideNav')
        true
    @endslot

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="flex flex-col lg:flex-row min-h-screen w-full bg-gradient-to-br from-rose-50 via-white to-orange-50/50 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950 text-gray-900 dark:text-zinc-100 transition-colors duration-300" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        @include('layouts.sidebar', ['active' => 'reports'])

        <!-- Main Content -->
        <main class="flex-1 w-full min-w-0 ml-0 lg:ml-72 p-4 sm:p-6 lg:p-10 xl:p-12">
            <!-- Header -->
            <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 sm:mb-12">
                <div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-rose-950 dark:text-zinc-50 tracking-tighter">Reports & Analytics</h1>
                    <p class="text-rose-600 dark:text-rose-400 mt-2 font-bold text-sm sm:text-base lg:text-lg opacity-80">
                        System efficiency, recycling rate, and classification metrics
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <button onclick="seedDemoData(this)" class="w-full sm:w-auto flex items-center justify-center gap-3 px-6 py-3.5 bg-rose-500 hover:bg-rose-600 text-white rounded-2xl font-black shadow-lg shadow-rose-250 dark:shadow-none hover:-translate-y-0.5 transition-all active:scale-95 text-sm shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                        Seed Demo Data
                    </button>
                    <a href="{{ route('dashboard.export') }}" target="_blank" class="w-full sm:w-auto flex items-center justify-center gap-3 px-6 py-3.5 bg-emerald-500 text-white rounded-2xl font-black shadow-lg shadow-emerald-250 dark:shadow-none hover:bg-emerald-600 hover:-translate-y-0.5 transition-all active:scale-95 text-sm shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
                        Download PDF Report
                    </a>
                </div>
            </header>

            <!-- Key Performance Indicators (KPIs) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 mb-8 sm:mb-12">
                <!-- Total Items -->
                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl border border-white dark:border-zinc-800 rounded-3xl lg:rounded-[2.5rem] p-6 sm:p-8 shadow-xl dark:shadow-none hover:shadow-rose-200/20 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500">Total Items</span>
                        <div class="w-10 h-10 bg-rose-50 dark:bg-zinc-800 rounded-xl flex items-center justify-center text-rose-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 8h10"/><path d="M7 12h10"/><path d="M7 16h10"/></svg>
                        </div>
                    </div>
                    <span class="block text-3xl sm:text-4xl font-black text-rose-950 dark:text-zinc-50 tracking-tight">{{ $totalItemsCount }}</span>
                    <span class="block text-xs font-bold text-rose-500/70 dark:text-rose-400/70 mt-1.5">Classified waste items</span>
                </div>

                <!-- Recycling Rate -->
                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl border border-white dark:border-zinc-800 rounded-3xl lg:rounded-[2.5rem] p-6 sm:p-8 shadow-xl dark:shadow-none hover:shadow-rose-200/20 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500">Recycle Rate</span>
                        <div class="w-10 h-10 bg-emerald-50 dark:bg-zinc-800 rounded-xl flex items-center justify-center text-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-15 6.7L3 16"/><path d="M3 21v-5h5"/></svg>
                        </div>
                    </div>
                    <span class="block text-3xl sm:text-4xl font-black text-emerald-600 dark:text-emerald-400 tracking-tight">{{ $recyclingRate }}%</span>
                    <span class="block text-xs font-bold text-emerald-500/70 dark:text-emerald-400/60 mt-1.5">Eco-friendly ratio</span>
                </div>

                <!-- Total Weight -->
                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl border border-white dark:border-zinc-800 rounded-3xl lg:rounded-[2.5rem] p-6 sm:p-8 shadow-xl dark:shadow-none hover:shadow-rose-200/20 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500">Total Mass</span>
                        <div class="w-10 h-10 bg-sky-50 dark:bg-zinc-800 rounded-xl flex items-center justify-center text-sky-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="5" r="3"/><path d="M6.5 8a2 2 0 0 0-1.905 1.46L2.1 18.5A2 2 0 0 0 4 21h16a2 2 0 0 0 1.9-2.5l-2.495-9.04A2 2 0 0 0 17.5 8Z"/></svg>
                        </div>
                    </div>
                    <span class="block text-3xl sm:text-4xl font-black text-sky-600 dark:text-sky-400 tracking-tight">{{ $totalWeightKg }} kg</span>
                    <span class="block text-xs font-bold text-sky-500/70 dark:text-sky-400/60 mt-1.5">Combined waste weight</span>
                </div>

                <!-- Peak Bin -->
                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl border border-white dark:border-zinc-800 rounded-3xl lg:rounded-[2.5rem] p-6 sm:p-8 shadow-xl dark:shadow-none hover:shadow-rose-200/20 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500">Peak Bin</span>
                        <div class="w-10 h-10 bg-orange-50 dark:bg-zinc-800 rounded-xl flex items-center justify-center text-orange-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20"/><path d="m19 5-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <span class="block text-2xl sm:text-3xl font-black text-orange-500 dark:text-orange-400 tracking-tight truncate uppercase">{{ $mostActiveBin ? $mostActiveBin->name : 'None' }}</span>
                    <span class="block text-xs font-bold text-orange-500/70 dark:text-orange-400/60 mt-1.5">Most active classification</span>
                </div>
            </div>

            <!-- Activity Trends Line Chart Card -->
            <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl border border-white dark:border-zinc-800 rounded-3xl lg:rounded-[3rem] p-6 sm:p-10 lg:p-12 shadow-xl dark:shadow-none hover:shadow-rose-200/20 transition-all duration-300 mb-8 sm:mb-12">
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-xl sm:text-2xl font-black text-rose-950 dark:text-zinc-100 tracking-tight">Activity Trends</h3>
                    <p class="text-[9px] sm:text-[10px] font-black text-rose-400 dark:text-rose-400 uppercase tracking-[0.3em] mt-1">Waste items segregated over the last 7 days</p>
                </div>
                <div class="h-64 sm:h-80 w-full relative">
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>

            <!-- Detailed Visual Comparison Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-12 pb-16 sm:pb-24">
                <!-- Column 1 & 2: Volume Comparison Chart -->
                <div class="md:col-span-2 bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl border border-white dark:border-zinc-800 rounded-3xl lg:rounded-[3rem] p-6 sm:p-10 lg:p-12 shadow-xl dark:shadow-none hover:shadow-rose-200/20 transition-all duration-300 flex flex-col">
                    <div class="mb-6 sm:mb-10">
                        <h3 class="text-xl sm:text-2xl font-black text-rose-950 dark:text-zinc-100 tracking-tight">Classification Distribution</h3>
                        <p class="text-[9px] sm:text-[10px] font-black text-rose-400 dark:text-rose-400 uppercase tracking-[0.3em] mt-1">Volume and share percentage per category</p>
                    </div>

                    <div class="space-y-6 sm:space-y-8 flex-1 flex flex-col justify-center">
                        @foreach($bins as $bin)
                            @php
                                $percent = $totalItemsCount > 0 ? round(($bin->items->count() / $totalItemsCount) * 100) : 0;
                                $colorClasses = [
                                    'hazardous' => 'bg-red-400 text-red-600',
                                    'recyclable' => 'bg-sky-400 text-sky-600',
                                    'biodegradable' => 'bg-emerald-400 text-emerald-600',
                                    'non-bio' => 'bg-orange-400 text-orange-600'
                                ];
                                $color = $colorClasses[$bin->slug] ?? 'bg-rose-400 text-rose-600';
                            @endphp
                            <div class="space-y-2 sm:space-y-3">
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-base sm:text-lg">
                                            @if($bin->slug === 'hazardous') ☣️
                                            @elseif($bin->slug === 'recyclable') ♻️
                                            @elseif($bin->slug === 'biodegradable') 🥬
                                            @else 🗑️
                                            @endif
                                        </span>
                                        <span class="font-black text-rose-950 dark:text-zinc-100 uppercase tracking-widest text-[9px] sm:text-[10px]">{{ $bin->name }}</span>
                                    </div>
                                    <span class="font-black text-rose-950 dark:text-zinc-300 text-xs">{{ $bin->items->count() }} items ({{ $percent }}%)</span>
                                </div>
                                <div class="h-3 w-full bg-rose-50 dark:bg-zinc-800 rounded-full overflow-hidden border border-rose-100/30 dark:border-zinc-700">
                                    <div class="h-full rounded-full transition-all duration-500 {{ explode(' ', $color)[0] }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Column 3: Eco recycling target circular meter -->
                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl border border-white dark:border-zinc-800 rounded-3xl lg:rounded-[3rem] p-6 sm:p-10 lg:p-12 shadow-xl dark:shadow-none hover:shadow-rose-200/20 transition-all duration-300 flex flex-col items-center text-center justify-between">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-rose-950 dark:text-zinc-100 tracking-tight">Eco Target</h3>
                        <p class="text-[9px] sm:text-[10px] font-black text-rose-400 dark:text-rose-400 uppercase tracking-[0.3em] mt-1">Segregation Target Rate</p>
                    </div>

                    <!-- Circular Progress Meter -->
                    <div class="relative w-36 h-36 sm:w-44 sm:h-44 my-6 sm:my-8 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <!-- Background track -->
                            <circle class="text-rose-50 dark:text-zinc-800" stroke-width="10" stroke="currentColor" fill="transparent" r="40" cx="50" cy="50" />
                            <!-- Progress line -->
                            <circle class="text-emerald-500 transition-all duration-1000 ease-out" stroke-width="10" stroke-dasharray="251.2" stroke-dashoffset="{{ 251.2 - (251.2 * $recyclingRate) / 100 }}" stroke-linecap="round" stroke="currentColor" fill="transparent" r="40" cx="50" cy="50" />
                        </svg>
                        <div class="absolute flex flex-col items-center justify-center">
                            <span class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $recyclingRate }}%</span>
                            <span class="text-[8px] font-black uppercase tracking-widest text-emerald-500 mt-1">Achieved</span>
                        </div>
                    </div>

                    <div>
                        <span class="inline-block px-4 py-2 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-wider rounded-full border border-emerald-100 dark:border-emerald-900/30">
                            Target: 80% Recycle Rate
                        </span>
                        <p class="text-xs text-rose-500/70 dark:text-rose-400/60 mt-4 leading-relaxed max-w-[200px] mx-auto">
                            Total amount of green waste (biodegradable and recyclable) segregated properly.
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        async function seedDemoData(button) {
            if (!confirm('Are you sure you want to seed 30 days of mock history logs? This will reset current levels to showcase realistic demo statistics.')) return;
            
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg> Seeding...`;
            
            try {
                await axios.post('/api/system/seed-mock-data');
                window.location.reload();
            } catch (error) {
                console.error("Error seeding mock data:", error);
                button.disabled = false;
                button.innerHTML = originalText;
                alert('Failed to seed demo data. Please verify console errors.');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('trendsChart').getContext('2d');
            
            // Check if dark mode is active
            let isDark = document.documentElement.classList.contains('dark');
            let gridColor = isDark ? 'rgba(63, 63, 70, 0.3)' : 'rgba(244, 63, 94, 0.05)';
            let textColor = isDark ? '#a1a1aa' : '#9f1239';

            // Create gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(244, 63, 94, 0.3)');
            gradient.addColorStop(1, 'rgba(244, 63, 94, 0)');

            window.trendsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Segregated Items',
                        data: @json($chartData),
                        borderColor: '#f43f5e',
                        borderWidth: 4,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#f43f5e',
                        pointBorderColor: isDark ? '#18181b' : '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#27272a' : '#fff1f2',
                            titleColor: isDark ? '#f4f4f5' : '#9f1239',
                            bodyColor: isDark ? '#d4d4d8' : '#e11d48',
                            borderColor: isDark ? '#3f3f46' : '#ffe4e6',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' items segregated';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    family: 'Figtree, sans-serif',
                                    weight: 'bold'
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    family: 'Figtree, sans-serif',
                                    weight: 'bold'
                                },
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Listen for theme changes to update chart styling in real-time
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') {
                        const isDark = document.documentElement.classList.contains('dark');
                        const gridColor = isDark ? 'rgba(63, 63, 70, 0.3)' : 'rgba(244, 63, 94, 0.05)';
                        const textColor = isDark ? '#a1a1aa' : '#9f1239';
                        
                        window.trendsChart.options.scales.x.ticks.color = textColor;
                        window.trendsChart.options.scales.y.ticks.color = textColor;
                        window.trendsChart.options.scales.y.grid.color = gridColor;
                        window.trendsChart.options.plugins.tooltip.backgroundColor = isDark ? '#27272a' : '#fff1f2';
                        window.trendsChart.options.plugins.tooltip.titleColor = isDark ? '#f4f4f5' : '#9f1239';
                        window.trendsChart.options.plugins.tooltip.bodyColor = isDark ? '#d4d4d8' : '#e11d48';
                        window.trendsChart.options.plugins.tooltip.borderColor = isDark ? '#3f3f46' : '#ffe4e6';
                        window.trendsChart.update();
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });
        });
    </script>
</x-app-layout>
