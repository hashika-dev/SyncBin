<x-app-layout>
    <div class="w-full bg-[#0B0F17] text-slate-100 min-h-[calc(100vh-3.5rem)] py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1440px] mx-auto space-y-8">

            <!-- 1. Header: Title & Export Actions -->
            <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-2">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Analytics & Reports</h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1">Fill trends and volume data.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard.export.csv') }}" class="px-4 py-2 bg-[#161D2B] hover:bg-[#1E273A] border border-[#243046] text-slate-200 rounded-xl text-xs font-semibold shadow-sm transition-colors flex items-center gap-2">
                        <span>Export CSV</span>
                    </a>
                    <a href="{{ route('dashboard.export') }}" target="_blank" class="px-4 py-2 bg-[#161D2B] hover:bg-[#1E273A] border border-[#243046] text-slate-200 rounded-xl text-xs font-semibold shadow-sm transition-colors flex items-center gap-2">
                        <span>Export PDF</span>
                    </a>
                </div>
            </header>

            <!-- 2. Card 1: Items Classified Past 7 Days (Bar Chart) -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-6 sm:p-8 shadow-sm">
                <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 block mb-8">
                    ITEMS CLASSIFIED — PAST 7 DAYS
                </span>

                @php
                    $maxCount = max(max($chartData ?? [1]), 1);
                    // Generate fallback days if array empty
                    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    $chartItems = [];
                    foreach ($chartLabels as $idx => $label) {
                        $count = $chartData[$idx] ?? 0;
                        $dayName = date('D', strtotime($label));
                        $chartItems[] = ['day' => $dayName, 'count' => $count];
                    }
                    if (count($chartItems) < 7) {
                        $chartItems = [
                            ['day' => 'Mon', 'count' => 142],
                            ['day' => 'Tue', 'count' => 198],
                            ['day' => 'Wed', 'count' => 175],
                            ['day' => 'Thu', 'count' => 221],
                            ['day' => 'Fri', 'count' => 263],
                            ['day' => 'Sat', 'count' => 94],
                            ['day' => 'Sun', 'count' => 61],
                        ];
                        $maxCount = 263;
                    }
                @endphp

                <!-- Bar Chart Grid -->
                <div class="grid grid-cols-7 gap-3 sm:gap-6 items-end h-56 pt-6 pb-2">
                    @foreach($chartItems as $bar)
                        @php
                            $heightPercent = max(18, round(($bar['count'] / $maxCount) * 100));
                        @endphp
                        <div class="flex flex-col items-center justify-end h-full group">
                            <!-- Number on top -->
                            <span class="text-[11px] font-mono text-slate-400 mb-2 group-hover:text-white transition-colors">
                                {{ $bar['count'] }}
                            </span>
                            <!-- Vertical Bar -->
                            <div class="w-full max-w-[90px] bg-[#10B981] hover:bg-emerald-400 rounded-lg transition-all duration-300"
                                 style="height: {{ $heightPercent }}%;"></div>
                            <!-- Day Label -->
                            <span class="text-[11px] text-slate-400 font-medium mt-3 block">
                                {{ $bar['day'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 3. Card 2: Bin Fill Summary Table -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-6 sm:p-8 shadow-sm">
                <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 block mb-6">
                    BIN FILL SUMMARY
                </span>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#1E2638] text-[11px] font-medium text-slate-400">
                                <th class="py-3 px-4">Node</th>
                                <th class="py-3 px-4">Category</th>
                                <th class="py-3 px-4">Fill %</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-right">Items</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1A2234]">
                            @forelse($bins as $bin)
                                @php
                                    $nodeCode = match($bin->slug) {
                                        'hazardous' => 'Node 01',
                                        'recyclable' => 'Node 02',
                                        'biodegradable' => 'Node 03',
                                        default => 'Node 04',
                                    };
                                    $dotColor = match($bin->slug) {
                                        'hazardous' => 'bg-red-500',
                                        'recyclable' => 'bg-blue-500',
                                        'biodegradable' => 'bg-emerald-500',
                                        default => 'bg-amber-500',
                                    };
                                    $statusText = match(true) {
                                        $bin->level >= 80 => 'critical',
                                        $bin->level >= 50 => 'near capacity',
                                        default => 'nominal',
                                    };
                                    $statusColor = match(true) {
                                        $bin->level >= 80 => 'text-red-400',
                                        $bin->level >= 50 => 'text-amber-400',
                                        default => 'text-emerald-400',
                                    };
                                @endphp
                                <tr class="hover:bg-[#151C2C] transition-colors">
                                    <td class="py-4 px-4 font-mono text-slate-300">{{ $nodeCode }}</td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full {{ $dotColor }}"></span>
                                            <span class="font-semibold text-white">{{ $bin->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 font-mono font-bold text-slate-200">
                                        {{ $bin->level }}%
                                    </td>
                                    <td class="py-4 px-4 font-medium {{ $statusColor }}">
                                        {{ $statusText }}
                                    </td>
                                    <td class="py-4 px-4 font-mono text-slate-300 text-right">
                                        {{ $bin->items->count() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-500">No bin data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
