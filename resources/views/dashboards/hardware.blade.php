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
                    <!-- Raspberry Pi 4 Node -->
                    <div class="bg-white/60 dark:bg-white/[0.04] rounded-2xl p-5 border border-white/70 dark:border-white/10 space-y-4 shadow-sm backdrop-blur-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Raspberry Pi 4B (Vision Engine)</h3>
                                <span class="text-xs text-slate-500 dark:text-slate-400">ARM Cortex-A72 &bull; 4GB LPDDR4</span>
                            </div>
                            <span class="px-2.5 py-0.5 bg-white/80 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-white/80 dark:border-emerald-400/30 rounded-xl text-[10px] font-mono font-bold uppercase shadow-sm">PRIMARY</span>
                        </div>

                        <div class="grid grid-cols-3 gap-3 text-center">
                            <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="block text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 font-semibold">CORE TEMP</span>
                                <span class="text-base font-mono font-extrabold text-emerald-600 dark:text-emerald-400 mt-1 block">42°C</span>
                            </div>
                            <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="block text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 font-semibold">CPU LOAD</span>
                                <span class="text-base font-mono font-extrabold text-emerald-600 dark:text-emerald-400 mt-1 block">35%</span>
                            </div>
                            <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="block text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 font-semibold">SUPPLY</span>
                                <span class="text-base font-mono font-extrabold text-emerald-600 dark:text-emerald-400 mt-1 block">5.11V</span>
                            </div>
                        </div>
                    </div>

                    <!-- ESP32 Node -->
                    <div class="bg-white/60 dark:bg-white/[0.04] rounded-2xl p-5 border border-white/70 dark:border-white/10 space-y-4 shadow-sm backdrop-blur-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">ESP32-WROOM-32 (Sensor Controller)</h3>
                                <span class="text-xs text-slate-500 dark:text-slate-400">Xtensa Dual-Core 240MHz &bull; UART Bus</span>
                            </div>
                            <span class="px-2.5 py-0.5 bg-white/80 dark:bg-sky-500/20 text-sky-800 dark:text-sky-300 border border-white/80 dark:border-sky-400/30 rounded-xl text-[10px] font-mono font-bold uppercase shadow-sm">NODE-01</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 block font-semibold">MAC ADDRESS</span>
                                <span class="text-slate-900 dark:text-slate-200 font-mono font-bold block mt-0.5">A4:CF:12:6E:3B:10</span>
                            </div>
                            <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 block font-semibold">FIRMWARE</span>
                                <span class="text-slate-900 dark:text-slate-200 font-mono font-bold block mt-0.5">EcoSync-v2.1.4</span>
                            </div>
                            <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 block font-semibold">PROTOCOL</span>
                                <span class="text-slate-900 dark:text-slate-200 font-mono font-bold block mt-0.5">UART / Serial (115200)</span>
                            </div>
                            <div class="p-3 bg-white/80 dark:bg-black/40 rounded-xl border border-white/80 dark:border-white/10 shadow-sm">
                                <span class="text-[9px] font-mono uppercase text-slate-500 dark:text-slate-400 block font-semibold">BUS IMPEDANCE</span>
                                <span class="text-slate-900 dark:text-slate-200 font-mono font-bold block mt-0.5">1k Nominal</span>
                            </div>
                        </div>
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
                    <!-- Peripheral 1: Vision Camera -->
                    <div class="p-4 bg-white/60 dark:bg-white/[0.04] rounded-2xl border border-white/70 dark:border-white/10 space-y-3 shadow-sm backdrop-blur-md">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900 dark:text-white">Optical Vision Cam</span>
                            <span class="px-2 py-0.5 bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-400/30 rounded-lg text-[9px] font-mono font-bold">LIVE</span>
                        </div>
                        <div class="aspect-video bg-white/80 dark:bg-black/50 rounded-xl border border-white/80 dark:border-white/10 flex flex-col items-center justify-center text-slate-500 dark:text-slate-400 shadow-sm backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m22 8-6 4 6 4V8Z"/><rect width="14" height="12" x="2" y="6" rx="2"/></svg>
                            <span class="text-[9px] font-mono mt-1 text-slate-500 dark:text-slate-400">720p @ 30 FPS</span>
                        </div>
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
