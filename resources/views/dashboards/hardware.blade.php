<x-app-layout>
    <div class="w-full bg-[#0B0F17] text-slate-100 min-h-[calc(100vh-3.5rem)] py-8 px-4 sm:px-6 lg:px-8"
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
        <div class="max-w-[1440px] mx-auto space-y-7">

            <!-- 1. Page Header -->
            <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-2">
                <div>
                    <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 block mb-1">NODE DIAGNOSTICS</span>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Hardware Diagnostics</h1>
                        <span class="px-2.5 py-0.5 bg-emerald-950/80 text-emerald-400 border border-emerald-800/80 rounded text-[10px] font-mono font-bold uppercase tracking-wider">SUPER ADMIN</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Live ESP32 & Raspberry Pi telemetry, sensor arrays, and network diagnostics</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <span class="block text-[9px] font-mono uppercase tracking-widest text-slate-400 mb-0.5">TELEMETRY REFRESH</span>
                        <span class="font-mono text-xs font-bold text-slate-200" x-text="lastRefresh"></span>
                    </div>
                    <button type="button" @click="refreshData()" class="flex items-center gap-2 px-4 py-2 bg-[#161D2B] hover:bg-[#1E273A] text-slate-200 border border-[#243046] rounded-xl font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" :class="isRefreshing ? 'animate-spin' : ''"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                        <span>Poll Nodes</span>
                    </button>
                </div>
            </header>

            <!-- 2. Top Summary Cards (4 Columns) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <!-- Card 1: Fleet Status -->
                <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 block mb-1">FLEET STATUS</span>
                        <span class="text-2xl font-mono font-extrabold text-white tracking-tight block">10 Devices</span>
                        <span class="text-[11px] text-slate-400 font-medium block mt-0.5">Online & Responding</span>
                    </div>
                    <div class="w-10 h-10 bg-[#161D2B] text-emerald-400 rounded-xl flex items-center justify-center border border-[#243046]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h10"/><path d="M7 12h10"/><path d="M7 17h10"/></svg>
                    </div>
                </div>

                <!-- Card 2: Host Processor -->
                <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 block mb-1">HOST PROCESSOR</span>
                        <span class="text-2xl font-mono font-extrabold text-white tracking-tight block">35% CPU</span>
                        <span class="text-[11px] text-slate-400 font-medium block mt-0.5">RPi 4B · 42°C Nominal</span>
                    </div>
                    <div class="w-10 h-10 bg-[#161D2B] text-slate-300 rounded-xl flex items-center justify-center border border-[#243046]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 9h6v6H9z"/><path d="M15 2v1"/><path d="M9 2v1"/><path d="M15 21v1"/><path d="M9 21v1"/></svg>
                    </div>
                </div>

                <!-- Card 3: Sensor Array -->
                <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 block mb-1">SENSOR ARRAY</span>
                        <span class="text-2xl font-mono font-extrabold text-white tracking-tight block">4 / 4 Active</span>
                        <span class="text-[11px] text-slate-400 font-medium block mt-0.5">Calibrated & Nominal</span>
                    </div>
                    <div class="w-10 h-10 bg-[#161D2B] text-emerald-400 rounded-xl flex items-center justify-center border border-[#243046]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12h5"/><path d="M17 12h5"/><path d="M12 2v5"/><path d="M12 17v5"/><circle cx="12" cy="12" r="4"/></svg>
                    </div>
                </div>

                <!-- Card 4: Wireless Mesh -->
                <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 block mb-1">WIRELESS MESH</span>
                        <span class="text-2xl font-mono font-extrabold text-white tracking-tight block">-48 dBm</span>
                        <span class="text-[11px] text-slate-400 font-medium block mt-0.5">100% Link · 8ms Ping</span>
                    </div>
                    <div class="w-10 h-10 bg-[#161D2B] text-emerald-400 rounded-xl flex items-center justify-center border border-[#243046]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" x2="12.01" y1="20" y2="20"/></svg>
                    </div>
                </div>
            </div>

            <!-- 3. Navigation Filter Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                <button type="button" @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-[#1C2638] text-white border border-[#2B3A52]' : 'text-slate-400 hover:text-white bg-transparent'" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors shrink-0">
                    All Nodes
                </button>
                <button type="button" @click="activeTab = 'processing'" :class="activeTab === 'processing' ? 'bg-[#1C2638] text-white border border-[#2B3A52]' : 'text-slate-400 hover:text-white bg-transparent'" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors shrink-0">
                    Compute (RPI/ESP32)
                </button>
                <button type="button" @click="activeTab = 'sensors'" :class="activeTab === 'sensors' ? 'bg-[#1C2638] text-white border border-[#2B3A52]' : 'text-slate-400 hover:text-white bg-transparent'" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors shrink-0">
                    Sensor Arrays
                </button>
                <button type="button" @click="activeTab = 'network'" :class="activeTab === 'network' ? 'bg-[#1C2638] text-white border border-[#2B3A52]' : 'text-slate-400 hover:text-white bg-transparent'" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors shrink-0">
                    Network & Peripherals
                </button>
            </div>

            <!-- SECTION 1: Embedded Compute (RPi & ESP32) -->
            <div x-show="activeTab === 'all' || activeTab === 'processing'" class="bg-[#111622] border border-[#1E2638] rounded-2xl p-6 sm:p-8 shadow-sm space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-[#1E2638]">
                    <div>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 block mb-0.5">EMBEDDED COMPUTE</span>
                        <h2 class="text-base font-bold text-white tracking-tight">Host & Microcontroller Diagnostics</h2>
                    </div>
                    <span class="px-2.5 py-0.5 bg-emerald-950/80 text-emerald-400 border border-emerald-800/80 rounded text-[10px] font-mono font-bold uppercase">Online</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Raspberry Pi 4 Node -->
                    <div class="bg-[#0E131F] rounded-xl p-5 border border-[#1E2638] space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-white">Raspberry Pi 4B (Vision Engine)</h3>
                                <span class="text-xs text-slate-400">ARM Cortex-A72 · 4GB LPDDR4</span>
                            </div>
                            <span class="px-2 py-0.5 bg-[#161D2B] text-emerald-400 border border-[#243046] rounded text-[10px] font-mono font-bold uppercase">PRIMARY</span>
                        </div>

                        <div class="grid grid-cols-3 gap-3 text-center">
                            <div class="p-3 bg-[#111622] rounded-lg border border-[#1E2638]">
                                <span class="block text-[9px] font-mono uppercase text-slate-400">CORE TEMP</span>
                                <span class="text-base font-mono font-bold text-emerald-400 mt-1 block">42°C</span>
                            </div>
                            <div class="p-3 bg-[#111622] rounded-lg border border-[#1E2638]">
                                <span class="block text-[9px] font-mono uppercase text-slate-400">CPU LOAD</span>
                                <span class="text-base font-mono font-bold text-emerald-400 mt-1 block">35%</span>
                            </div>
                            <div class="p-3 bg-[#111622] rounded-lg border border-[#1E2638]">
                                <span class="block text-[9px] font-mono uppercase text-slate-400">SUPPLY</span>
                                <span class="text-base font-mono font-bold text-emerald-400 mt-1 block">5.11V</span>
                            </div>
                        </div>
                    </div>

                    <!-- ESP32 Node -->
                    <div class="bg-[#0E131F] rounded-xl p-5 border border-[#1E2638] space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-white">ESP32-WROOM-32 (Sensor Controller)</h3>
                                <span class="text-xs text-slate-400">Xtensa Dual-Core 240MHz · UART Bus</span>
                            </div>
                            <span class="px-2 py-0.5 bg-[#161D2B] text-slate-300 border border-[#243046] rounded text-[10px] font-mono font-bold uppercase">NODE-01</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="p-3 bg-[#111622] rounded-lg border border-[#1E2638]">
                                <span class="text-[9px] font-mono uppercase text-slate-400 block">MAC ADDRESS</span>
                                <span class="text-slate-200 font-mono font-semibold block mt-0.5">A4:CF:12:6E:3B:10</span>
                            </div>
                            <div class="p-3 bg-[#111622] rounded-lg border border-[#1E2638]">
                                <span class="text-[9px] font-mono uppercase text-slate-400 block">FIRMWARE</span>
                                <span class="text-slate-200 font-mono font-semibold block mt-0.5">EcoSync-v2.1.4</span>
                            </div>
                            <div class="p-3 bg-[#111622] rounded-lg border border-[#1E2638]">
                                <span class="text-[9px] font-mono uppercase text-slate-400 block">PROTOCOL</span>
                                <span class="text-slate-200 font-mono font-semibold block mt-0.5">UART / Serial (115200)</span>
                            </div>
                            <div class="p-3 bg-[#111622] rounded-lg border border-[#1E2638]">
                                <span class="text-[9px] font-mono uppercase text-slate-400 block">RESISTANCE</span>
                                <span class="text-slate-200 font-mono font-semibold block mt-0.5">1k Nominal</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Telemetry Array (Sensors) -->
            <div x-show="activeTab === 'all' || activeTab === 'sensors'" class="bg-[#111622] border border-[#1E2638] rounded-2xl p-6 sm:p-8 shadow-sm space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-[#1E2638]">
                    <div>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 block mb-0.5">TELEMETRY ARRAY</span>
                        <h2 class="text-base font-bold text-white tracking-tight">Sensor Telemetry & Diagnostic Matrix</h2>
                    </div>
                    <span class="px-2.5 py-0.5 bg-emerald-950/80 text-emerald-400 border border-emerald-800/80 rounded text-[10px] font-mono font-bold uppercase">4 / 4 Online</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Sensor 1 -->
                    <div class="p-4 bg-[#0E131F] rounded-xl border border-[#1E2638] space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-white">Ultrasonic (HC-SR04)</span>
                            <span class="px-2 py-0.5 bg-emerald-950/80 text-emerald-400 border border-emerald-800/80 rounded text-[9px] font-mono font-bold">ONLINE</span>
                        </div>
                        <div class="space-y-1 font-mono text-xs pt-1">
                            <div class="flex justify-between text-slate-400">
                                <span>Chamber Ping</span>
                                <span class="text-emerald-400 font-bold">18.3 cm</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Signal Quality</span>
                                <span class="text-emerald-400 font-bold">98.4%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sensor 2 -->
                    <div class="p-4 bg-[#0E131F] rounded-xl border border-[#1E2638] space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-white">Proximity IR (PIR)</span>
                            <span class="px-2 py-0.5 bg-emerald-950/80 text-emerald-400 border border-emerald-800/80 rounded text-[9px] font-mono font-bold">ONLINE</span>
                        </div>
                        <div class="space-y-1 font-mono text-xs pt-1">
                            <div class="flex justify-between text-slate-400">
                                <span>Intake Beam</span>
                                <span class="text-emerald-400 font-bold">Clear</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Trigger Logic</span>
                                <span class="text-slate-300 font-bold">Active LOW</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sensor 3 -->
                    <div class="p-4 bg-[#0E131F] rounded-xl border border-[#1E2638] space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-white">NIR Moisture (AS7263)</span>
                            <span class="px-2 py-0.5 bg-emerald-950/80 text-emerald-400 border border-emerald-800/80 rounded text-[9px] font-mono font-bold">ONLINE</span>
                        </div>
                        <div class="space-y-1 font-mono text-xs pt-1">
                            <div class="flex justify-between text-slate-400">
                                <span>Absorption</span>
                                <span class="text-emerald-400 font-bold">1460nm (35%)</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>State</span>
                                <span class="text-slate-300 font-bold">Dry Intake</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sensor 4 -->
                    <div class="p-4 bg-[#0E131F] rounded-xl border border-[#1E2638] space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-white">Metal Proximity (LJ12A3)</span>
                            <span class="px-2 py-0.5 bg-emerald-950/80 text-emerald-400 border border-emerald-800/80 rounded text-[9px] font-mono font-bold">ONLINE</span>
                        </div>
                        <div class="space-y-1 font-mono text-xs pt-1">
                            <div class="flex justify-between text-slate-400">
                                <span>Field Detection</span>
                                <span class="text-slate-300 font-bold">Standby</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Target Met</span>
                                <span class="text-slate-300 font-bold">0 Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Connectivity & IO (Peripherals & Network) -->
            <div x-show="activeTab === 'all' || activeTab === 'network'" class="bg-[#111622] border border-[#1E2638] rounded-2xl p-6 sm:p-8 shadow-sm space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-[#1E2638]">
                    <div>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 block mb-0.5">CONNECTIVITY & IO</span>
                        <h2 class="text-base font-bold text-white tracking-tight">Peripheral Power & Wireless Telemetry</h2>
                    </div>
                    <span class="px-2.5 py-0.5 bg-emerald-950/80 text-emerald-400 border border-emerald-800/80 rounded text-[10px] font-mono font-bold uppercase">Link 100%</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Peripheral 1: Vision Camera -->
                    <div class="p-4 bg-[#0E131F] rounded-xl border border-[#1E2638] space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-white">Logitech C270 HD Cam</span>
                            <span class="px-2 py-0.5 bg-rose-950/80 text-rose-400 border border-rose-800/80 rounded text-[9px] font-mono font-bold">LIVE</span>
                        </div>
                        <div class="aspect-video bg-[#111622] rounded-lg border border-[#1E2638] flex flex-col items-center justify-center text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m22 8-6 4 6 4V8Z"/><rect width="14" height="12" x="2" y="6" rx="2"/></svg>
                            <span class="text-[9px] font-mono mt-1 text-slate-500">720p @ 30 FPS</span>
                        </div>
                    </div>

                    <!-- Peripheral 2: Main 5V DC Supply -->
                    <div class="p-4 bg-[#0E131F] rounded-xl border border-[#1E2638] space-y-3">
                        <span class="text-xs font-semibold text-white block">Main 5V DC Supply</span>
                        <div class="grid grid-cols-2 gap-2 text-center font-mono text-xs pt-2">
                            <div class="p-2.5 bg-[#111622] rounded-lg border border-[#1E2638]">
                                <span class="text-[9px] uppercase text-slate-400 block">VOLTAGE</span>
                                <span class="text-emerald-400 font-bold block mt-0.5">5.03V</span>
                            </div>
                            <div class="p-2.5 bg-[#111622] rounded-lg border border-[#1E2638]">
                                <span class="text-[9px] uppercase text-slate-400 block">CURRENT</span>
                                <span class="text-emerald-400 font-bold block mt-0.5">1.8A</span>
                            </div>
                        </div>
                    </div>

                    <!-- Peripheral 3: Wireless Mesh -->
                    <div class="p-4 bg-[#0E131F] rounded-xl border border-[#1E2638] space-y-3">
                        <span class="text-xs font-semibold text-white block">Wireless Mesh</span>
                        <div class="space-y-1 font-mono text-xs pt-2">
                            <div class="flex justify-between text-slate-400">
                                <span>Signal (RSSI)</span>
                                <span class="text-emerald-400 font-bold">-48 dBm</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>IP Node</span>
                                <span class="text-slate-300">192.168.1.104</span>
                            </div>
                        </div>
                    </div>

                    <!-- Peripheral 4: Cloud Gateway -->
                    <div class="p-4 bg-[#0E131F] rounded-xl border border-[#1E2638] space-y-3">
                        <span class="text-xs font-semibold text-white block">Cloud Gateway</span>
                        <div class="space-y-1 font-mono text-xs pt-2">
                            <div class="flex justify-between text-slate-400">
                                <span>Latency</span>
                                <span class="text-emerald-400 font-bold">8 ms</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Packet Loss</span>
                                <span class="text-slate-300">0.0%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
