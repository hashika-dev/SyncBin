<x-app-layout>
    <div class="relative isolate w-full min-h-[calc(100vh-3.5rem)] bg-gradient-to-br from-emerald-100/60 via-slate-100 to-teal-100/40 dark:from-[#030906] dark:via-[#06120D] dark:to-[#040C09] text-slate-800 dark:text-slate-100 py-6 sm:py-8 px-4 sm:px-6 lg:px-8 selection:bg-emerald-500 selection:text-white transition-colors duration-300 overflow-hidden"
         x-data="{ 
            activeTab: 'all',
            lastRefresh: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
            isRefreshing: false,
            refreshData() {
                this.isRefreshing = true;
                setTimeout(() => {
                    this.lastRefresh = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    this.isRefreshing = false;
                }, 400);
            },
            init() {
                setInterval(() => {
                    this.lastRefresh = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                }, 2000);
            }
         }">
        
        <!-- VIBRANT BACKLIGHT AURORA FOR REAL GLASS REFRACTION -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden select-none z-0">
            <!-- Center Emerald Glow -->
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[850px] h-[550px] bg-emerald-400/35 dark:bg-emerald-500/20 blur-[130px] rounded-full"></div>
            <!-- Left Cyan/Teal Orb -->
            <div class="absolute top-1/3 -left-32 w-[650px] h-[650px] bg-teal-400/30 dark:bg-teal-600/15 blur-[140px] rounded-full"></div>
            <!-- Right Emerald/Sage Orb -->
            <div class="absolute -bottom-24 right-10 w-[700px] h-[550px] bg-emerald-500/30 dark:bg-emerald-600/15 blur-[140px] rounded-full"></div>
            <!-- Fine Dot Grid Matrix -->
            <div class="absolute inset-0 bg-[radial-gradient(#059669_1.2px,transparent_1.2px)] dark:bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:26px_26px] opacity-25 dark:opacity-[0.08]"></div>
        </div>

        <!-- MAIN HARDWARE DIAGNOSTICS CONTENT -->
        <div class="max-w-[1720px] mx-auto space-y-6 sm:space-y-7 relative z-10">

            <!-- 1. Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-white/50 dark:border-white/10">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight drop-shadow-sm">
                            Hardware Diagnostics
                        </h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/70 dark:bg-cyan-500/15 border border-white/80 dark:border-cyan-400/30 text-cyan-800 dark:text-cyan-300 shadow-[0_4px_16px_rgba(6,182,212,0.15)] backdrop-blur-xl font-mono uppercase">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-ping"></span>
                            SuperAdmin Engineering
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Live ESP32 & Raspberry Pi telemetry, multi-sensor calibration array, and cryptographic bus diagnostics
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <span class="block text-[10px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-0.5">TELEMETRY POLL</span>
                        <span class="font-mono text-xs font-bold text-slate-800 dark:text-slate-200" x-text="lastRefresh"></span>
                    </div>
                    <button type="button" @click="refreshData()" 
                            class="flex items-center gap-2 px-4 py-2 bg-white/70 dark:bg-white/10 hover:bg-white/90 dark:hover:bg-white/20 text-slate-800 dark:text-slate-200 border border-white/80 dark:border-white/15 rounded-2xl font-semibold text-xs backdrop-blur-md transition-all shadow-sm active:scale-95 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" :class="isRefreshing ? 'animate-spin' : ''"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                        <span>Poll Nodes</span>
                    </button>
                </div>
            </div>

            <!-- 2. Top Summary Cards (True Frosted Glass) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                
                <!-- Card 1: Fleet Status -->
                <div class="relative rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] hover:border-emerald-400/50 transition-all flex items-center justify-between overflow-hidden group">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                    <div>
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold block">Fleet Status</span>
                        <span class="text-2xl sm:text-3xl font-mono font-extrabold text-slate-900 dark:text-white tracking-tight mt-1.5 block">10 Devices</span>
                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold block mt-1">Online & Responding</span>
                    </div>
                    <div class="w-10 h-10 bg-emerald-500/15 border border-emerald-400/40 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center shadow-[0_0_12px_rgba(16,185,129,0.2)]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h10"/><path d="M7 12h10"/><path d="M7 17h10"/></svg>
                    </div>
                </div>

                <!-- Card 2: Host Processor -->
                <div class="relative rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] hover:border-sky-400/50 transition-all flex items-center justify-between overflow-hidden group">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-sky-400/40 to-transparent pointer-events-none"></div>

                    <div>
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold block">Host Processor</span>
                        <span class="text-2xl sm:text-3xl font-mono font-extrabold text-slate-900 dark:text-white tracking-tight mt-1.5 block">35% CPU</span>
                        <span class="text-xs text-sky-600 dark:text-sky-400 font-semibold block mt-1">RPi 4B &bull; 42°C Nominal</span>
                    </div>
                    <div class="w-10 h-10 bg-sky-500/15 border border-sky-400/40 text-sky-600 dark:text-sky-400 rounded-2xl flex items-center justify-center shadow-[0_0_12px_rgba(56,189,248,0.2)]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 9h6v6H9z"/><path d="M15 2v1"/><path d="M9 2v1"/><path d="M15 21v1"/><path d="M9 21v1"/></svg>
                    </div>
                </div>

                <!-- Card 3: Sensor Array -->
                <div class="relative rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] hover:border-emerald-400/50 transition-all flex items-center justify-between overflow-hidden group">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                    <div>
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold block">Sensor Array</span>
                        <span class="text-2xl sm:text-3xl font-mono font-extrabold text-slate-900 dark:text-white tracking-tight mt-1.5 block">4 / 4 Active</span>
                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold block mt-1">Calibrated & Nominal</span>
                    </div>
                    <div class="w-10 h-10 bg-emerald-500/15 border border-emerald-400/40 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center shadow-[0_0_12px_rgba(16,185,129,0.2)]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12h5"/><path d="M17 12h5"/><path d="M12 2v5"/><path d="M12 17v5"/><circle cx="12" cy="12" r="4"/></svg>
                    </div>
                </div>

                <!-- Card 4: Wireless Mesh -->
                <div class="relative rounded-3xl p-5 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] hover:border-cyan-400/50 transition-all flex items-center justify-between overflow-hidden group">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-cyan-400/40 to-transparent pointer-events-none"></div>

                    <div>
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold block">Wireless Mesh</span>
                        <span class="text-2xl sm:text-3xl font-mono font-extrabold text-slate-900 dark:text-white tracking-tight mt-1.5 block">-48 dBm</span>
                        <span class="text-xs text-cyan-600 dark:text-cyan-400 font-semibold block mt-1">100% Link &bull; 8ms Ping</span>
                    </div>
                    <div class="w-10 h-10 bg-cyan-500/15 border border-cyan-400/40 text-cyan-600 dark:text-cyan-400 rounded-2xl flex items-center justify-center shadow-[0_0_12px_rgba(6,182,212,0.2)]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" x2="12.01" y1="20" y2="20"/></svg>
                    </div>
                </div>
            </div>

            <!-- 3. Navigation Filter Pills (Frosted Glass) -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                <button type="button" @click="activeTab = 'all'" 
                        :class="activeTab === 'all' ? 'bg-white text-emerald-800 border border-emerald-400/40 shadow-sm dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5'" 
                        class="px-4 py-2 rounded-2xl text-xs font-semibold transition-all shrink-0 cursor-pointer backdrop-blur-md">
                    All Nodes
                </button>
                <button type="button" @click="activeTab = 'processing'" 
                        :class="activeTab === 'processing' ? 'bg-white text-emerald-800 border border-emerald-400/40 shadow-sm dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5'" 
                        class="px-4 py-2 rounded-2xl text-xs font-semibold transition-all shrink-0 cursor-pointer backdrop-blur-md">
                    Compute (RPI/ESP32)
                </button>
                <button type="button" @click="activeTab = 'sensors'" 
                        :class="activeTab === 'sensors' ? 'bg-white text-emerald-800 border border-emerald-400/40 shadow-sm dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5'" 
                        class="px-4 py-2 rounded-2xl text-xs font-semibold transition-all shrink-0 cursor-pointer backdrop-blur-md">
                    Sensor Arrays
                </button>
                <button type="button" @click="activeTab = 'network'" 
                        :class="activeTab === 'network' ? 'bg-white text-emerald-800 border border-emerald-400/40 shadow-sm dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5'" 
                        class="px-4 py-2 rounded-2xl text-xs font-semibold transition-all shrink-0 cursor-pointer backdrop-blur-md">
                    Network & Peripherals
                </button>
            </div>

            <!-- SECTION 1: Embedded Compute (Frosted Glass Container) -->
            <div x-show="activeTab === 'all' || activeTab === 'processing'" 
                 class="relative rounded-3xl p-6 sm:p-7 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] space-y-5 transition-all overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                <div class="flex items-center justify-between pb-4 border-b border-white/50 dark:border-white/10">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Host & Microcontroller Diagnostics</h2>
                    </div>
                    <span class="px-3 py-1 bg-white/70 dark:bg-emerald-500/15 text-emerald-800 dark:text-emerald-300 border border-white/80 dark:border-emerald-400/30 rounded-full text-[10px] font-mono font-bold uppercase backdrop-blur-md shadow-sm">Online</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Raspberry Pi 4 Node — Live Telemetry (auto-polls every 5s) -->
                    <div class="bg-white/60 dark:bg-white/[0.04] rounded-2xl p-5 border border-white/70 dark:border-white/10 space-y-4 shadow-sm backdrop-blur-md"
                         x-data="{
                            rpi: null,
                            rpiStatus: 'loading',
                            rpiLastSeen: '—',
                            async fetchRpiTelemetry() {
                                try {
                                    const res  = await fetch('{{ route('rpi.telemetry.get', [], false) }}', {
                                        credentials: 'same-origin',
                                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                                    });
                                    const json = await res.json();
                                    this.rpiStatus  = json.status;
                                    this.rpi        = json.data ?? null;
                                    this.rpiLastSeen = json.data?.last_seen ?? '—';
                                } catch (e) {
                                    this.rpiStatus = 'offline';
                                }
                            },
                            init() {
                                this.fetchRpiTelemetry();
                                setInterval(() => this.fetchRpiTelemetry(), 5000);
                            }
                         }">

                        <!-- Header -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Raspberry Pi 4B (Vision Engine)</h3>
                                <span class="text-xs text-slate-500 dark:text-slate-400">Cortex-A72 &times; 4 @ 1.8GHz &bull; 8GB LPDDR4</span>
                            </div>
                            <!-- Status badge -->
                            <span x-show="rpiStatus === 'live'"
                                  class="px-2.5 py-0.5 bg-white/80 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-white/80 dark:border-emerald-400/30 rounded-xl text-[10px] font-mono font-bold uppercase shadow-sm">
                                LIVE
                            </span>
                            <span x-show="rpiStatus === 'stale'"
                                  class="px-2.5 py-0.5 bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-400/30 rounded-xl text-[10px] font-mono font-bold uppercase shadow-sm">
                                STALE
                            </span>
                            <span x-show="rpiStatus === 'offline' || rpiStatus === 'loading'"
                                  class="px-2.5 py-0.5 bg-rose-100 dark:bg-rose-500/20 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-400/30 rounded-xl text-[10px] font-mono font-bold uppercase shadow-sm"
                                  x-text="rpiStatus === 'loading' ? 'POLLING…' : 'OFFLINE'">
                            </span>
                        </div>

                        <!-- Primary stats row: CORE TEMP / CPU LOAD / SUPPLY -->
                        <div class="grid grid-cols-3 gap-3 text-center">
                            <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="block text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 font-semibold">CORE TEMP</span>
                                <span class="text-base font-mono font-extrabold mt-1 block"
                                      :class="rpi && rpi.cpu_temp >= 80 ? 'text-rose-600 dark:text-rose-400' : (rpi && rpi.cpu_temp >= 65 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400')"
                                      x-text="rpi ? rpi.cpu_temp + '°C' : '—'">
                                    —
                                </span>
                            </div>
                            <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="block text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 font-semibold">CPU LOAD</span>
                                <span class="text-base font-mono font-extrabold mt-1 block"
                                      :class="rpi && rpi.cpu_load >= 90 ? 'text-rose-600 dark:text-rose-400' : (rpi && rpi.cpu_load >= 70 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400')"
                                      x-text="rpi ? rpi.cpu_load + '%' : '—'">
                                    —
                                </span>
                            </div>
                            <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="block text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 font-semibold">SUPPLY</span>
                                <span class="text-base font-mono font-extrabold mt-1 block"
                                      :class="rpi && rpi.supply_voltage < 4.8 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400'"
                                      x-text="rpi ? rpi.supply_voltage + 'V' : '—'">
                                    —
                                </span>
                            </div>
                        </div>

                        <!-- Secondary stats: RAM / Disk / IP / Uptime -->
                        <div class="grid grid-cols-2 gap-2.5 font-mono text-xs" x-show="rpi">
                            <div class="p-2.5 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="text-[9px] uppercase text-slate-500 dark:text-slate-400 font-semibold block">RAM</span>
                                <span class="text-slate-900 dark:text-slate-200 font-bold block mt-0.5"
                                      x-text="rpi ? rpi.memory_percent + '% (' + rpi.memory_used_mb + ' / ' + rpi.memory_total_mb + ' MB)' : '—'"></span>
                            </div>
                            <div class="p-2.5 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="text-[9px] uppercase text-slate-500 dark:text-slate-400 font-semibold block">DISK</span>
                                <span class="text-slate-900 dark:text-slate-200 font-bold block mt-0.5"
                                      x-text="rpi ? rpi.disk_percent + '% (' + rpi.disk_used_gb + ' / ' + rpi.disk_total_gb + ' GB)' : '—'"></span>
                            </div>
                            <div class="p-2.5 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="text-[9px] uppercase text-slate-500 dark:text-slate-400 font-semibold block">IP ADDRESS</span>
                                <span class="text-slate-900 dark:text-slate-200 font-bold block mt-0.5"
                                      x-text="rpi?.ip_address ?? '—'"></span>
                            </div>
                            <div class="p-2.5 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="text-[9px] uppercase text-slate-500 dark:text-slate-400 font-semibold block">UPTIME</span>
                                <span class="text-slate-900 dark:text-slate-200 font-bold block mt-0.5"
                                      x-text="rpi?.uptime ?? '—'"></span>
                            </div>
                        </div>

                        <!-- Last seen footer -->
                        <p class="text-[10px] font-mono text-slate-400 dark:text-slate-500 text-right -mt-1">
                            Last update: <span x-text="rpiLastSeen">—</span>
                        </p>
                    </div>
                    <!-- ESP32 Node — Live Telemetry (auto-polls every 5s) -->
                    <div class="bg-white/60 dark:bg-white/[0.04] rounded-2xl p-5 border border-white/70 dark:border-white/10 space-y-4 shadow-sm backdrop-blur-md flex flex-col justify-between"
                         x-data="{
                            esp32: null,
                            esp32Status: 'loading',
                            esp32LastSeen: '—',
                            async fetchEsp32Telemetry() {
                                try {
                                    const res  = await fetch('{{ route('esp32.telemetry.get', [], false) }}', {
                                        credentials: 'same-origin',
                                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                                    });
                                    const json = await res.json();
                                    this.esp32Status   = json.status;
                                    this.esp32         = json.data ?? null;
                                    this.esp32LastSeen = json.data?.last_seen ?? '—';
                                } catch (e) {
                                    this.esp32Status = 'offline';
                                }
                            },
                            init() {
                                this.fetchEsp32Telemetry();
                                setInterval(() => this.fetchEsp32Telemetry(), 5000);
                            }
                         }">

                        <div class="space-y-4">
                            <!-- Header -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">ESP32 DevKitC (Sensor Controller)</h3>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">Xtensa Dual-Core 240MHz &bull; UART Bus</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 bg-white/80 dark:bg-sky-500/20 text-sky-800 dark:text-sky-300 border border-white/80 dark:border-sky-400/30 rounded-xl text-[10px] font-mono font-bold uppercase shadow-sm">NODE-01</span>
                                    <!-- Status badge -->
                                    <span x-show="esp32Status === 'live'"
                                          class="px-2.5 py-0.5 bg-white/80 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-white/80 dark:border-emerald-400/30 rounded-xl text-[10px] font-mono font-bold uppercase shadow-sm">
                                        LIVE
                                    </span>
                                    <span x-show="esp32Status === 'stale'"
                                          class="px-2.5 py-0.5 bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-400/30 rounded-xl text-[10px] font-mono font-bold uppercase shadow-sm">
                                        STALE
                                    </span>
                                    <span x-show="esp32Status === 'offline' || esp32Status === 'loading'"
                                          class="px-2.5 py-0.5 bg-rose-100 dark:bg-rose-500/20 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-400/30 rounded-xl text-[10px] font-mono font-bold uppercase shadow-sm"
                                          x-text="esp32Status === 'loading' ? 'POLLING…' : 'OFFLINE'">
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                    <span class="text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 block font-semibold">MAC ADDRESS</span>
                                    <span class="text-slate-900 dark:text-slate-200 font-mono font-bold block mt-0.5" x-text="esp32?.mac_address ?? 'A4:CF:12:6E:3B:10'">A4:CF:12:6E:3B:10</span>
                                </div>
                                <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                    <span class="text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 block font-semibold">FIRMWARE</span>
                                    <span class="text-slate-900 dark:text-slate-200 font-mono font-bold block mt-0.5" x-text="esp32?.firmware ?? 'EcoSync-v2.1.4'">EcoSync-v2.1.4</span>
                                </div>
                                <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                    <span class="text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 block font-semibold">PROTOCOL</span>
                                    <span class="text-slate-900 dark:text-slate-200 font-mono font-bold block mt-0.5" x-text="esp32?.protocol ?? 'UART / Serial (115200)'">UART / Serial (115200)</span>
                                </div>
                                <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                    <span class="text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 block font-semibold">BUS IMPEDANCE</span>
                                    <span class="text-slate-900 dark:text-slate-200 font-mono font-bold block mt-0.5" x-text="esp32?.bus_status ?? '1k Nominal'">1k Nominal</span>
                                </div>
                            </div>
                        </div>

                        <!-- Last seen footer -->
                        <p class="text-[10px] font-mono text-slate-400 dark:text-slate-500 text-right -mt-1">
                            Last update: <span x-text="esp32LastSeen">—</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Telemetry Array (Frosted Glass Container) -->
            <div x-show="activeTab === 'all' || activeTab === 'sensors'" 
                 class="relative rounded-3xl p-6 sm:p-7 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] space-y-5 transition-all overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                <div class="flex items-center justify-between pb-4 border-b border-white/50 dark:border-white/10">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Sensor Telemetry & Diagnostic Matrix</h2>
                    </div>
                    <span class="px-3 py-1 bg-white/70 dark:bg-emerald-500/15 text-emerald-800 dark:text-emerald-300 border border-white/80 dark:border-emerald-400/30 rounded-full text-[10px] font-mono font-bold uppercase backdrop-blur-md shadow-sm">4 / 4 Online</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Sensor 1: Ultrasonic -->
                    <div class="p-4 bg-white/60 dark:bg-white/[0.04] rounded-2xl border border-white/70 dark:border-white/10 space-y-3 shadow-sm backdrop-blur-md">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900 dark:text-white">Ultrasonic (HC-SR04)</span>
                            <span class="px-2 py-0.5 bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-400/30 rounded-lg text-[9px] font-mono font-bold">ONLINE</span>
                        </div>
                        <div class="space-y-1.5 font-mono text-xs pt-1">
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Chamber Ping</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">18.3 cm</span>
                            </div>
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Signal Quality</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">98.4%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sensor 2: Proximity IR -->
                    <div class="p-4 bg-white/60 dark:bg-white/[0.04] rounded-2xl border border-white/70 dark:border-white/10 space-y-3 shadow-sm backdrop-blur-md">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900 dark:text-white">Proximity IR (PIR)</span>
                            <span class="px-2 py-0.5 bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-400/30 rounded-lg text-[9px] font-mono font-bold">ONLINE</span>
                        </div>
                        <div class="space-y-1.5 font-mono text-xs pt-1">
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Intake Beam</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">Clear</span>
                            </div>
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Trigger Logic</span>
                                <span class="text-slate-800 dark:text-slate-200 font-bold">Active LOW</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sensor 3: NIR Moisture -->
                    <div class="p-4 bg-white/60 dark:bg-white/[0.04] rounded-2xl border border-white/70 dark:border-white/10 space-y-3 shadow-sm backdrop-blur-md">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900 dark:text-white">NIR Moisture (AS7263)</span>
                            <span class="px-2 py-0.5 bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-400/30 rounded-lg text-[9px] font-mono font-bold">ONLINE</span>
                        </div>
                        <div class="space-y-1.5 font-mono text-xs pt-1">
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Absorption</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">1460nm (35%)</span>
                            </div>
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Classification</span>
                                <span class="text-slate-800 dark:text-slate-200 font-bold">Dry Intake</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sensor 4: Metal Proximity -->
                    <div class="p-4 bg-white/60 dark:bg-white/[0.04] rounded-2xl border border-white/70 dark:border-white/10 space-y-3 shadow-sm backdrop-blur-md">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900 dark:text-white">Inductive (LJ12A3)</span>
                            <span class="px-2 py-0.5 bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-400/30 rounded-lg text-[9px] font-mono font-bold">ONLINE</span>
                        </div>
                        <div class="space-y-1.5 font-mono text-xs pt-1">
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Eddy Current</span>
                                <span class="text-slate-800 dark:text-slate-200 font-bold">Standby</span>
                            </div>
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Ferrous Detect</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">0 Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Connectivity & IO (Frosted Glass Container) -->
            <div x-show="activeTab === 'all' || activeTab === 'network'" 
                 class="relative rounded-3xl p-6 sm:p-7 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] space-y-5 transition-all overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                <div class="flex items-center justify-between pb-4 border-b border-white/50 dark:border-white/10">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Peripheral Power & Wireless Telemetry</h2>
                    </div>
                    <span class="px-3 py-1 bg-white/70 dark:bg-emerald-500/15 text-emerald-800 dark:text-emerald-300 border border-white/80 dark:border-emerald-400/30 rounded-full text-[10px] font-mono font-bold uppercase backdrop-blur-md shadow-sm">Link 100%</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Peripheral 1: Vision Camera — Snapshot on Demand -->
                    <div class="p-4 bg-white/60 dark:bg-white/[0.04] rounded-2xl border border-white/70 dark:border-white/10 space-y-3 shadow-sm backdrop-blur-md"
                         x-data="{
                            snapState: 'idle',
                            snapImage: null,
                            snapError: null,
                            snapCapturedAt: null,
                            snapWidth: null,
                            snapHeight: null,
                            lightboxOpen: false,
                            openLightbox() { this.lightboxOpen = true; document.body.style.overflow = 'hidden'; },
                            closeLightbox() { this.lightboxOpen = false; document.body.style.overflow = ''; },
                            downloadSnap() {
                                if (!this.snapImage) return;
                                const a = document.createElement('a');
                                a.href = this.snapImage;
                                a.download = 'rpi-snapshot-' + Date.now() + '.jpg';
                                a.click();
                            },
                            async takeSnapshot() {
                                this.snapState = 'loading';
                                this.snapError = null;
                                this.lightboxOpen = false;
                                try {
                                    const res = await fetch('{{ route('rpi.snapshot', [], false) }}', {
                                        method: 'POST',
                                        credentials: 'same-origin',
                                        headers: {
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    });

                                    if (res.status === 401) {
                                        this.snapError = 'Your session expired. Please refresh the page and sign in again.';
                                        this.snapState = 'error';
                                        return;
                                    }

                                    const json = await res.json();
                                    if (json.status === 'ok' && json.image) {
                                        this.snapImage      = json.image;
                                        this.snapCapturedAt = json.captured ? new Date(json.captured).toLocaleTimeString() : '—';
                                        this.snapWidth      = json.width;
                                        this.snapHeight     = json.height;
                                        this.snapState      = 'done';
                                    } else {
                                        this.snapError = json.message ?? 'Unknown error from snapshot server.';
                                        this.snapState = 'error';
                                    }
                                } catch (e) {
                                    this.snapError = 'Network error — could not reach server.';
                                    this.snapState = 'error';
                                }
                            }
                         }"
                         @keydown.escape.window="closeLightbox()">

                        <!-- Header row -->
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900 dark:text-white">Optical Vision Cam</span>
                            <span class="px-2 py-0.5 bg-sky-500/15 text-sky-700 dark:text-sky-400 border border-sky-400/30 rounded-lg text-[9px] font-mono font-bold">RPi CAM</span>
                        </div>

                        <!-- Viewport: idle placeholder / loading / snapshot image / error -->
                        <div class="relative aspect-video bg-white/80 dark:bg-black/50 rounded-xl border border-white/80 dark:border-white/10 overflow-hidden shadow-sm group">

                            <!-- IDLE state -->
                            <div x-show="snapState === 'idle'" class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-slate-400 dark:text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m22 8-6 4 6 4V8Z"/><rect width="14" height="12" x="2" y="6" rx="2"/></svg>
                                <span class="text-[9px] font-mono">Press Snap Test to capture</span>
                            </div>

                            <!-- LOADING state -->
                            <div x-show="snapState === 'loading'" class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-emerald-500 dark:text-emerald-400">
                                <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                                <span class="text-[9px] font-mono text-slate-400">Triggering rpicam-still…</span>
                            </div>

                            <!-- SNAPSHOT IMAGE -->
                            <img x-show="snapState === 'done' && snapImage"
                                 :src="snapImage"
                                 alt="RPi Snapshot"
                                 class="absolute inset-0 w-full h-full object-cover rounded-xl" />

                            <!-- EXPAND button — hover overlay on image -->
                            <div x-show="snapState === 'done' && snapImage"
                                 class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-black/30 rounded-xl">
                                <button type="button"
                                        @click="openLightbox()"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/90 dark:bg-black/70 text-slate-800 dark:text-white text-[10px] font-bold font-mono uppercase tracking-wide shadow-lg hover:bg-white dark:hover:bg-black/90 transition-colors border border-white/60 dark:border-white/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
                                    Expand
                                </button>
                            </div>

                            <!-- ERROR state -->
                            <div x-show="snapState === 'error'" class="absolute inset-0 flex flex-col items-center justify-center gap-1.5 px-3 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-rose-500"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span class="text-[9px] font-mono text-rose-500 dark:text-rose-400 leading-tight" x-text="snapError"></span>
                            </div>
                        </div>

                        <!-- Metadata row (only when image is shown) -->
                        <div x-show="snapState === 'done'" class="flex items-center justify-between font-mono text-[9px] text-slate-500 dark:text-slate-400 px-0.5">
                            <span x-text="(snapWidth && snapHeight) ? snapWidth + '×' + snapHeight + ' px' : '720p'"></span>
                            <span>Captured <span class="text-slate-700 dark:text-slate-300 font-semibold" x-text="snapCapturedAt"></span></span>
                        </div>

                        <!-- Snap button -->
                        <button type="button"
                                @click="takeSnapshot()"
                                :disabled="snapState === 'loading'"
                                class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-[10px] font-bold font-mono uppercase tracking-wider transition-all cursor-pointer active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed bg-sky-500/15 hover:bg-sky-500/25 text-sky-700 dark:text-sky-300 border border-sky-400/30 hover:border-sky-400/50 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3z"/><circle cx="12" cy="13" r="3"/></svg>
                            <span x-text="snapState === 'loading' ? 'Capturing…' : (snapState === 'done' ? 'Snap Again' : 'Snap Test')"></span>
                        </button>

                        <!-- ── Lightbox Modal ── -->
                        <template x-teleport="body">
                            <div x-show="lightboxOpen"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
                                 @click.self="closeLightbox()">

                                <div x-show="lightboxOpen"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="relative max-w-5xl w-full rounded-2xl overflow-hidden shadow-2xl border border-white/10">

                                    <!-- Image -->
                                    <img :src="snapImage" alt="RPi Snapshot" class="w-full h-auto block max-h-[85vh] object-contain bg-black" />

                                    <!-- Top-right controls -->
                                    <div class="absolute top-3 right-3 flex items-center gap-2">
                                        <!-- Download -->
                                        <button type="button"
                                                @click="downloadSnap()"
                                                title="Download image"
                                                class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-[10px] font-mono font-bold uppercase tracking-wide backdrop-blur-sm border border-white/20 transition-colors shadow">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                            Save
                                        </button>
                                        <!-- Close -->
                                        <button type="button"
                                                @click="closeLightbox()"
                                                title="Close (Esc)"
                                                class="flex items-center justify-center w-7 h-7 rounded-lg bg-white/10 hover:bg-rose-500/70 text-white backdrop-blur-sm border border-white/20 transition-colors shadow">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </div>

                                    <!-- Bottom metadata bar -->
                                    <div class="absolute bottom-0 inset-x-0 px-4 py-2 bg-black/60 backdrop-blur-sm flex items-center justify-between font-mono text-[10px] text-white/70">
                                        <span x-text="(snapWidth && snapHeight) ? snapWidth + '×' + snapHeight + ' px' : 'RPi Snapshot'"></span>
                                        <span>Captured <span class="text-white font-semibold" x-text="snapCapturedAt"></span></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Peripheral 2: Main 5V DC Supply -->
                    <div class="p-4 bg-white/60 dark:bg-white/[0.04] rounded-2xl border border-white/70 dark:border-white/10 space-y-3 shadow-sm backdrop-blur-md">
                        <span class="text-xs font-bold text-slate-900 dark:text-white block">Main 5V DC Supply</span>
                        <div class="grid grid-cols-2 gap-2 text-center font-mono text-xs pt-2">
                            <div class="p-2.5 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="text-[9px] uppercase text-slate-500 dark:text-slate-400 font-semibold block">VOLTAGE</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold block mt-0.5">5.03V</span>
                            </div>
                            <div class="p-2.5 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="text-[9px] uppercase text-slate-500 dark:text-slate-400 font-semibold block">CURRENT</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold block mt-0.5">1.8A</span>
                            </div>
                        </div>
                    </div>

                    <!-- Peripheral 3: Wireless Mesh -->
                    <div class="p-4 bg-white/60 dark:bg-white/[0.04] rounded-2xl border border-white/70 dark:border-white/10 space-y-3 shadow-sm backdrop-blur-md">
                        <span class="text-xs font-bold text-slate-900 dark:text-white block">Wireless Mesh</span>
                        <div class="space-y-1.5 font-mono text-xs pt-2">
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Signal (RSSI)</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">-48 dBm</span>
                            </div>
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>IP Node</span>
                                <span class="text-slate-800 dark:text-slate-200 font-bold">192.168.1.104</span>
                            </div>
                        </div>
                    </div>

                    <!-- Peripheral 4: Cloud Gateway -->
                    <div class="p-4 bg-white/60 dark:bg-white/[0.04] rounded-2xl border border-white/70 dark:border-white/10 space-y-3 shadow-sm backdrop-blur-md">
                        <span class="text-xs font-bold text-slate-900 dark:text-white block">Cloud Gateway</span>
                        <div class="space-y-1.5 font-mono text-xs pt-2">
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Latency</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">8 ms</span>
                            </div>
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Packet Loss</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">0.0%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
