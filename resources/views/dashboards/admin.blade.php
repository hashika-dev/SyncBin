<div x-data="binModal()" class="w-full bg-[#0B0F17] text-slate-100 min-h-[calc(100vh-3.5rem)] py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-[1720px] mx-auto space-y-6">

        <!-- 1. Top Metrics Summary Row (4 Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <!-- Metric 1: Items Today -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                <span class="text-xs text-slate-400 font-medium block mb-2">Items Today</span>
                <span class="text-3xl font-extrabold text-white tracking-tight" x-text="totalProcessed">0</span>
            </div>

            <!-- Metric 2: Bins Active -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                <span class="text-xs text-slate-400 font-medium block mb-2">Bins Active</span>
                <span class="text-3xl font-extrabold text-white tracking-tight">4 / 4</span>
            </div>

            <!-- Metric 3: Critical Alerts -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                <span class="text-xs text-slate-400 font-medium block mb-2">Critical Alerts</span>
                <span class="text-3xl font-extrabold text-red-500 tracking-tight" x-text="criticalAlertsCount">0</span>
            </div>

            <!-- Metric 4: Avg Fill Level -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                <span class="text-xs text-slate-400 font-medium block mb-2">Avg Fill Level</span>
                <span class="text-3xl font-extrabold text-white tracking-tight" x-text="avgFillLevel + '%'">0%</span>
            </div>
        </div>

        <!-- 2. Main Live Monitoring Content Grid (Bins 2x2 on Left, Activity on Right) -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
            
            <!-- Left Area: 2x2 Bin Cards (Col-span 8 or 9) -->
            <div class="xl:col-span-8 2xl:col-span-9 grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- CARD 1: Hazardous (NODE 01) -->
                <div class="bg-[#111622] border border-[#1E2638] border-t-2 border-t-red-500 rounded-2xl p-5 sm:p-6 flex flex-col justify-between shadow-sm transition-all hover:border-[#2C374E]">
                    <div>
                        <!-- Header: Node & Badge -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-mono uppercase tracking-widest text-slate-400 font-semibold">NODE 01</span>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-semibold tracking-wider"
                                  :class="bins.hazardous.level >= 80 ? 'bg-red-950/70 text-red-400 border border-red-800/60' : (bins.hazardous.level >= 50 ? 'bg-amber-950/70 text-amber-400 border border-amber-800/60' : 'bg-emerald-950/70 text-emerald-400 border border-emerald-800/60')"
                                  x-text="bins.hazardous.level >= 80 ? 'Critical' : (bins.hazardous.level >= 50 ? 'Near Capacity' : 'Nominal')">
                            </span>
                        </div>

                        <!-- Title & Description -->
                        <h3 class="text-lg font-bold text-white tracking-tight">Hazardous</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Chemical · Toxic · Batteries</p>

                        <!-- Fill Level Gauge -->
                        <div class="mt-5 mb-4">
                            <div class="flex items-baseline justify-between mb-1.5 text-xs">
                                <span class="text-slate-400 font-medium">Fill level</span>
                                <span class="font-mono font-bold text-red-400" x-text="bins.hazardous.level + '%'"></span>
                            </div>
                            <div class="h-1.5 w-full bg-[#182133] rounded-full overflow-hidden">
                                <div class="h-full bg-red-500 rounded-full transition-all duration-500" :style="'width: ' + bins.hazardous.level + '%'"></div>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-400 font-mono pt-2">
                            <span>Emptied: </span>
                            <span class="text-slate-300" x-text="bins.hazardous.lastEmptied"></span>
                        </div>
                    </div>

                    <!-- Actions Row -->
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-4 border-t border-[#1C2538]">
                        <button @click="openModal('hazardous')" class="py-2 bg-[#161D2B] hover:bg-[#1E273A] border border-[#243046] rounded-xl text-xs font-semibold text-slate-200 transition-colors shadow-sm">
                            Details
                        </button>
                        <button @click="emptyBinDirect('hazardous')" class="py-2 bg-[#161D2B] hover:bg-[#1E273A] border border-[#243046] rounded-xl text-xs font-semibold text-slate-200 transition-colors shadow-sm">
                            Empty
                        </button>
                    </div>
                </div>

                <!-- CARD 2: Recyclable (NODE 02) -->
                <div class="bg-[#111622] border border-[#1E2638] border-t-2 border-t-blue-500 rounded-2xl p-5 sm:p-6 flex flex-col justify-between shadow-sm transition-all hover:border-[#2C374E]">
                    <div>
                        <!-- Header: Node & Badge -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-mono uppercase tracking-widest text-slate-400 font-semibold">NODE 02</span>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-semibold tracking-wider"
                                  :class="bins.recyclable.level >= 80 ? 'bg-red-950/70 text-red-400 border border-red-800/60' : (bins.recyclable.level >= 50 ? 'bg-amber-950/70 text-amber-400 border border-amber-800/60' : 'bg-emerald-950/70 text-emerald-400 border border-emerald-800/60')"
                                  x-text="bins.recyclable.level >= 80 ? 'Critical' : (bins.recyclable.level >= 50 ? 'Near Capacity' : 'Nominal')">
                            </span>
                        </div>

                        <!-- Title & Description -->
                        <h3 class="text-lg font-bold text-white tracking-tight">Recyclable</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Plastics · Paper · Cans · Glass</p>

                        <!-- Fill Level Gauge -->
                        <div class="mt-5 mb-4">
                            <div class="flex items-baseline justify-between mb-1.5 text-xs">
                                <span class="text-slate-400 font-medium">Fill level</span>
                                <span class="font-mono font-bold text-blue-400" x-text="bins.recyclable.level + '%'"></span>
                            </div>
                            <div class="h-1.5 w-full bg-[#182133] rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full transition-all duration-500" :style="'width: ' + bins.recyclable.level + '%'"></div>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-400 font-mono pt-2">
                            <span>Emptied: </span>
                            <span class="text-slate-300" x-text="bins.recyclable.lastEmptied"></span>
                        </div>
                    </div>

                    <!-- Actions Row -->
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-4 border-t border-[#1C2538]">
                        <button @click="openModal('recyclable')" class="py-2 bg-[#161D2B] hover:bg-[#1E273A] border border-[#243046] rounded-xl text-xs font-semibold text-slate-200 transition-colors shadow-sm">
                            Details
                        </button>
                        <button @click="emptyBinDirect('recyclable')" class="py-2 bg-[#161D2B] hover:bg-[#1E273A] border border-[#243046] rounded-xl text-xs font-semibold text-slate-200 transition-colors shadow-sm">
                            Empty
                        </button>
                    </div>
                </div>

                <!-- CARD 3: Biodegradable (NODE 03) -->
                <div class="bg-[#111622] border border-[#1E2638] border-t-2 border-t-emerald-500 rounded-2xl p-5 sm:p-6 flex flex-col justify-between shadow-sm transition-all hover:border-[#2C374E]">
                    <div>
                        <!-- Header: Node & Badge -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-mono uppercase tracking-widest text-slate-400 font-semibold">NODE 03</span>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-semibold tracking-wider"
                                  :class="bins.biodegradable.level >= 80 ? 'bg-red-950/70 text-red-400 border border-red-800/60' : (bins.biodegradable.level >= 50 ? 'bg-amber-950/70 text-amber-400 border border-amber-800/60' : 'bg-emerald-950/70 text-emerald-400 border border-emerald-800/60')"
                                  x-text="bins.biodegradable.level >= 80 ? 'Critical — Overfill Alert' : (bins.biodegradable.level >= 50 ? 'Near Capacity' : 'Nominal')">
                            </span>
                        </div>

                        <!-- Title & Description -->
                        <h3 class="text-lg font-bold text-white tracking-tight">Biodegradable</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Organic & Food Waste</p>

                        <!-- Fill Level Gauge -->
                        <div class="mt-5 mb-4">
                            <div class="flex items-baseline justify-between mb-1.5 text-xs">
                                <span class="text-slate-400 font-medium">Fill level</span>
                                <span class="font-mono font-bold text-emerald-400" x-text="bins.biodegradable.level + '%'"></span>
                            </div>
                            <div class="h-1.5 w-full bg-[#182133] rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" :style="'width: ' + bins.biodegradable.level + '%'"></div>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-400 font-mono pt-2">
                            <span>Emptied: </span>
                            <span class="text-slate-300" x-text="bins.biodegradable.lastEmptied"></span>
                        </div>
                    </div>

                    <!-- Actions Row -->
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-4 border-t border-[#1C2538]">
                        <button @click="openModal('biodegradable')" class="py-2 bg-[#161D2B] hover:bg-[#1E273A] border border-[#243046] rounded-xl text-xs font-semibold text-slate-200 transition-colors shadow-sm">
                            Details
                        </button>
                        <button @click="emptyBinDirect('biodegradable')" class="py-2 bg-[#161D2B] hover:bg-[#1E273A] border border-[#243046] rounded-xl text-xs font-semibold text-slate-200 transition-colors shadow-sm">
                            Empty
                        </button>
                    </div>
                </div>

                <!-- CARD 4: Non-Biodegradable (NODE 04) -->
                <div class="bg-[#111622] border border-[#1E2638] border-t-2 border-t-amber-500 rounded-2xl p-5 sm:p-6 flex flex-col justify-between shadow-sm transition-all hover:border-[#2C374E]">
                    <div>
                        <!-- Header: Node & Badge -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-mono uppercase tracking-widest text-slate-400 font-semibold">NODE 04</span>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-semibold tracking-wider"
                                  :class="bins['non-bio'].level >= 80 ? 'bg-red-950/70 text-red-400 border border-red-800/60' : (bins['non-bio'].level >= 50 ? 'bg-amber-950/70 text-amber-400 border border-amber-800/60' : 'bg-emerald-950/70 text-emerald-400 border border-emerald-800/60')"
                                  x-text="bins['non-bio'].level >= 80 ? 'Critical' : (bins['non-bio'].level >= 50 ? 'Near Capacity' : 'Nominal')">
                            </span>
                        </div>

                        <!-- Title & Description -->
                        <h3 class="text-lg font-bold text-white tracking-tight">Non-Biodegradable</h3>
                        <p class="text-xs text-slate-400 mt-0.5">General Residual Waste</p>

                        <!-- Fill Level Gauge -->
                        <div class="mt-5 mb-4">
                            <div class="flex items-baseline justify-between mb-1.5 text-xs">
                                <span class="text-slate-400 font-medium">Fill level</span>
                                <span class="font-mono font-bold text-amber-400" x-text="bins['non-bio'].level + '%'"></span>
                            </div>
                            <div class="h-1.5 w-full bg-[#182133] rounded-full overflow-hidden">
                                <div class="h-full bg-amber-500 rounded-full transition-all duration-500" :style="'width: ' + bins['non-bio'].level + '%'"></div>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-400 font-mono pt-2">
                            <span>Emptied: </span>
                            <span class="text-slate-300" x-text="bins['non-bio'].lastEmptied"></span>
                        </div>
                    </div>

                    <!-- Actions Row -->
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-4 border-t border-[#1C2538]">
                        <button @click="openModal('non-bio')" class="py-2 bg-[#161D2B] hover:bg-[#1E273A] border border-[#243046] rounded-xl text-xs font-semibold text-slate-200 transition-colors shadow-sm">
                            Details
                        </button>
                        <button @click="emptyBinDirect('non-bio')" class="py-2 bg-[#161D2B] hover:bg-[#1E273A] border border-[#243046] rounded-xl text-xs font-semibold text-slate-200 transition-colors shadow-sm">
                            Empty
                        </button>
                    </div>
                </div>

            </div>

            <!-- Right Area: Live Activity Feed Stream (Col-span 4 or 3) -->
            <div class="xl:col-span-4 2xl:col-span-3 bg-[#111622] border border-[#1E2638] rounded-2xl p-5 shadow-sm flex flex-col justify-between min-h-[540px]">
                <div>
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#1C2538]">
                        <h3 class="text-sm font-bold text-white tracking-tight">Activity</h3>
                        <span class="flex items-center gap-1.5 text-[10px] font-mono font-bold text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> LIVE
                        </span>
                    </div>

                    <!-- Items Stream -->
                    <div class="space-y-3.5">
                        <template x-for="item in recentActivity" :key="item.id">
                            <div class="flex items-center justify-between text-xs py-1">
                                <div class="flex items-start gap-2.5 min-w-0 flex-1 pr-2">
                                    <span class="w-2 h-2 rounded-full mt-1.5 shrink-0"
                                          :class="{
                                              'bg-emerald-500': item.binName === 'Biodegradable',
                                              'bg-blue-500': item.binName === 'Recyclable',
                                              'bg-red-500': item.binName === 'Hazardous',
                                              'bg-amber-500': item.binName === 'Non-Biodegradable'
                                          }"></span>
                                    <div class="min-w-0">
                                        <span class="font-semibold text-slate-200 block truncate" x-text="item.name"></span>
                                        <span class="text-[11px] text-slate-400 truncate block">
                                            <span x-text="item.binName"></span> · <span x-text="item.weight"></span>
                                        </span>
                                    </div>
                                </div>
                                <span class="text-[10px] font-mono text-slate-500 shrink-0" x-text="formatTimeAgo(item.created_at)"></span>
                            </div>
                        </template>

                        <!-- Fallback empty state -->
                        <div x-show="recentActivity.length === 0" class="py-12 text-center text-xs text-slate-500">
                            No recent activity detected.
                        </div>
                    </div>
                </div>

                <!-- Footer Link -->
                <div class="pt-4 mt-6 border-t border-[#1C2538]">
                    <a href="{{ route('dashboard.history') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition-colors flex items-center justify-between">
                        <span>Full audit trail</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- Bin Details & Scanner Modal -->
    <div x-show="isOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        <div class="bg-[#111622] border border-[#1E2638] rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-5"
             @click.away="isOpen = false">
            
            <div class="flex items-center justify-between pb-3 border-b border-[#1E2638]">
                <div>
                    <h3 class="text-lg font-bold text-white" x-text="activeBin.name + ' Bin'"></h3>
                    <p class="text-xs text-slate-400" x-text="activeBin.subtitle"></p>
                </div>
                <button @click="isOpen = false" class="text-slate-400 hover:text-white text-lg font-bold p-1">&times;</button>
            </div>

            <!-- Modal Metrics -->
            <div class="grid grid-cols-2 gap-3 text-center">
                <div class="bg-[#161D2B] p-3 rounded-xl border border-[#243046]">
                    <span class="text-[10px] uppercase text-slate-400 block font-mono">Fill Level</span>
                    <span class="text-2xl font-bold font-mono text-white" x-text="activeBin.level + '%'"></span>
                </div>
                <div class="bg-[#161D2B] p-3 rounded-xl border border-[#243046]">
                    <span class="text-[10px] uppercase text-slate-400 block font-mono">Items In Bin</span>
                    <span class="text-2xl font-bold font-mono text-white" x-text="activeBin.items.length"></span>
                </div>
            </div>

            <!-- Recent Items in Bin -->
            <div>
                <span class="text-xs font-semibold text-slate-300 block mb-2">Recent Items:</span>
                <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1">
                    <template x-for="item in activeBin.items" :key="item.id">
                        <div class="p-2.5 rounded-lg bg-[#161D2B] border border-[#243046] flex items-center justify-between text-xs">
                            <span class="font-medium text-slate-200" x-text="item.name"></span>
                            <span class="text-[11px] font-mono text-slate-400" x-text="item.weight"></span>
                        </div>
                    </template>
                    <div x-show="activeBin.items.length === 0" class="text-xs text-slate-500 py-3 text-center">
                        Bin is empty.
                    </div>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-[#1E2638]">
                <button type="button" @click="simulateScan()" class="py-2.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-semibold shadow-sm transition-colors">
                    Simulate Scan
                </button>
                <button type="button" @click="emptyBin()" class="py-2.5 px-4 bg-[#161D2B] hover:bg-[#1E273A] border border-[#243046] text-slate-200 rounded-xl text-xs font-semibold transition-colors">
                    Empty Bin
                </button>
            </div>
        </div>
    </div>

    <script>
        function binModal() {
            return {
                isOpen: false,
                activeKey: '',
                activeBin: {
                    name: '',
                    subtitle: '',
                    color: 'emerald',
                    level: 0,
                    status: '',
                    lastEmptied: '',
                    items: []
                },
                bins: {
                    hazardous: {
                        name: 'Hazardous',
                        subtitle: 'Chemical · Toxic · Batteries',
                        color: 'red',
                        level: 0,
                        status: 'Empty',
                        lastEmptied: 'Never',
                        items: []
                    },
                    recyclable: {
                        name: 'Recyclable',
                        subtitle: 'Plastics · Paper · Cans · Glass',
                        color: 'blue',
                        level: 0,
                        status: 'Empty',
                        lastEmptied: 'Never',
                        items: []
                    },
                    biodegradable: {
                        name: 'Biodegradable',
                        subtitle: 'Organic & Food Waste',
                        color: 'emerald',
                        level: 0,
                        status: 'Empty',
                        lastEmptied: 'Never',
                        items: []
                    },
                    'non-bio': {
                        name: 'Non-Biodegradable',
                        subtitle: 'General Residual Waste',
                        color: 'amber',
                        level: 0,
                        status: 'Empty',
                        lastEmptied: 'Never',
                        items: []
                    }
                },
                init() {
                    this.fetchBins();
                    setInterval(() => {
                        this.fetchBins();
                    }, 5000);
                },
                async fetchBins() {
                    try {
                        const response = await axios.get('/api/bins');
                        const data = response.data;
                        Object.keys(this.bins).forEach(key => {
                            if (data[key]) {
                                this.bins[key].level = data[key].level;
                                this.bins[key].status = data[key].status;
                                this.bins[key].items = data[key].items || [];
                                this.bins[key].lastEmptied = this.formatLastEmptied(data[key].last_emptied_at);
                            }
                        });
                    } catch (error) {
                        console.error("Error fetching bin details:", error);
                    }
                },
                formatLastEmptied(timestamp) {
                    if (!timestamp) return 'Never';
                    const date = new Date(timestamp);
                    const now = new Date();
                    const diffMs = now - date;
                    const diffMins = Math.floor(diffMs / 60000);
                    const diffHours = Math.floor(diffMs / 3600000);
                    
                    if (diffMins < 1) return 'Just now';
                    if (diffMins < 60) return `${diffMins}m ago`;
                    if (diffHours < 24) return `${diffHours}h ago`;
                    return date.toLocaleDateString();
                },
                formatTimeAgo(timestamp) {
                    if (!timestamp) return '1m ago';
                    const date = new Date(timestamp);
                    const now = new Date();
                    const diffMins = Math.floor((now - date) / 60000);
                    if (diffMins < 1) return 'Just now';
                    if (diffMins < 60) return `${diffMins}m ago`;
                    const diffHours = Math.floor(diffMins / 60);
                    if (diffHours < 24) return `${diffHours}h ago`;
                    return `${Math.floor(diffHours / 24)}d ago`;
                },
                get totalProcessed() {
                    return Object.values(this.bins).reduce((acc, bin) => acc + bin.items.length, 0);
                },
                get criticalAlertsCount() {
                    return Object.values(this.bins).filter(bin => bin.level >= 80 || bin.status === 'Critical').length;
                },
                get avgFillLevel() {
                    const levels = Object.values(this.bins).map(b => b.level || 0);
                    if (levels.length === 0) return 0;
                    return Math.round(levels.reduce((a, b) => a + b, 0) / levels.length);
                },
                get recentActivity() {
                    let allItems = [];
                    Object.keys(this.bins).forEach(key => {
                        const bin = this.bins[key];
                        if (bin.items) {
                            bin.items.forEach(item => {
                                allItems.push({
                                    id: item.id,
                                    name: item.name,
                                    weight: item.weight || '~45g',
                                    created_at: item.created_at,
                                    binName: bin.name
                                });
                            });
                        }
                    });
                    return allItems.sort((a, b) => new Date(b.created_at || b.id) - new Date(a.created_at || a.id)).slice(0, 8);
                },
                openModal(binKey) {
                    this.activeKey = binKey;
                    this.activeBin = this.bins[binKey];
                    this.isOpen = true;
                },
                async emptyBin() {
                    try {
                        const response = await axios.post(`/api/bins/${this.activeKey}/empty`);
                        const updatedBin = response.data;
                        this.bins[this.activeKey].level = updatedBin.level;
                        this.bins[this.activeKey].items = updatedBin.items || [];
                        this.bins[this.activeKey].status = updatedBin.status;
                        this.bins[this.activeKey].lastEmptied = 'Just now';
                        this.activeBin = this.bins[this.activeKey];
                    } catch (error) {
                        console.error("Error emptying bin:", error);
                    }
                },
                async emptyBinDirect(binKey) {
                    try {
                        const response = await axios.post(`/api/bins/${binKey}/empty`);
                        const updatedBin = response.data;
                        this.bins[binKey].level = updatedBin.level;
                        this.bins[binKey].items = updatedBin.items || [];
                        this.bins[binKey].status = updatedBin.status;
                        this.bins[binKey].lastEmptied = 'Just now';
                    } catch (error) {
                        console.error("Error emptying bin:", error);
                    }
                },
                async simulateScan() {
                    try {
                        const response = await axios.post(`/api/bins/${this.activeKey}/scan`);
                        const updatedBin = response.data;
                        this.bins[this.activeKey].level = updatedBin.level;
                        this.bins[this.activeKey].items = updatedBin.items || [];
                        this.bins[this.activeKey].status = updatedBin.status;
                        this.activeBin = this.bins[this.activeKey];
                    } catch (error) {
                        console.error("Error simulating scan:", error);
                    }
                }
            }
        }
    </script>
</div>
