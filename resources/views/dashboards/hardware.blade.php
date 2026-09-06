<x-app-layout>
    @slot('hideNav')
        true
    @endslot

    <div class="flex flex-col lg:flex-row min-h-screen w-full bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300" 
         x-data="{ 
            sidebarOpen: false,
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
        <!-- Sidebar -->
        @include('layouts.sidebar', ['active' => 'hardware'])

        <!-- Main Content Container -->
        <main class="flex-1 w-full min-w-0 ml-0 lg:ml-72 p-4 sm:p-6 lg:p-10 xl:p-12 bg-slate-100 dark:bg-slate-950 min-h-screen">
            <div class="max-w-7xl mx-auto space-y-8">
                
                <!-- 1. Page Header -->
                <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b border-slate-200 dark:border-slate-800">
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center flex-wrap gap-3">
                            <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Node Diagnostics</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Hardware Diagnostics</h1>
                            <span class="px-2.5 py-0.5 bg-cyan-100 dark:bg-cyan-950 text-cyan-700 dark:text-cyan-400 border border-cyan-300 dark:border-cyan-800 rounded text-[10px] font-mono font-bold uppercase tracking-wider">SUPER ADMIN</span>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm">
                            Live ESP32 & Raspberry Pi telemetry, sensor arrays, and network diagnostics
                        </p>
                    </div>

                    <div class="flex items-center gap-4 shrink-0">
                        <div class="text-right hidden sm:block">
                            <span class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 mb-0.5">Telemetry Refresh</span>
                            <span class="font-mono text-xs font-bold text-emerald-400" x-text="lastRefresh"></span>
                        </div>
                        <button type="button" @click="refreshData()" class="flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-xl font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" :class="isRefreshing ? 'animate-spin' : ''"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                            <span>Poll Nodes</span>
                        </button>
                    </div>
                </header>

                <!-- 2. Top Summary Row (4 Simple Telemetry Cards) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <!-- Card 1: 10 Devices Online -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-lg flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <span class="block text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 truncate mb-1">Fleet Status</span>
                            <span class="text-2xl font-mono font-extrabold text-slate-900 dark:text-white tracking-tight block truncate">10 Devices</span>
                            <span class="block text-[11px] text-slate-500 dark:text-slate-400 font-semibold truncate mt-0.5">Online & Responding</span>
                        </div>
                        <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-200 dark:border-emerald-800/60 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"/><path d="M2 12h20"/><circle cx="12" cy="12" r="9"/></svg>
                        </div>
                    </div>

                    <!-- Card 2: Host Processor -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-lg flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <span class="block text-[10px] font-mono font-bold uppercase tracking-widest text-sky-600 dark:text-sky-400 truncate mb-1">Host Processor</span>
                            <span class="text-2xl font-mono font-extrabold text-slate-900 dark:text-white tracking-tight block truncate">35% CPU</span>
                            <span class="block text-[11px] text-slate-500 dark:text-slate-400 font-semibold truncate mt-0.5">RPi 4B • 42°C Nominal</span>
                        </div>
                        <div class="w-10 h-10 bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 rounded-xl flex items-center justify-center border border-sky-200 dark:border-sky-800/60 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 9h6v6H9z"/><path d="M15 2v1"/><path d="M9 2v1"/></svg>
                        </div>
                    </div>

                    <!-- Card 3: Sensor Array -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-lg flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <span class="block text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 truncate mb-1">Sensor Array</span>
                            <span class="text-2xl font-mono font-extrabold text-emerald-600 dark:text-emerald-400 tracking-tight block truncate">4 / 4 Active</span>
                            <span class="block text-[11px] text-slate-500 dark:text-slate-400 font-semibold truncate mt-0.5">Calibrated & Nominal</span>
                        </div>
                        <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-200 dark:border-emerald-800/60 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 12 21 3"/><path d="m16 8 2 2"/></svg>
                        </div>
                    </div>

                    <!-- Card 4: Network Link -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-lg flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <span class="block text-[10px] font-mono font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 truncate mb-1">Wireless Mesh</span>
                            <span class="text-2xl font-mono font-extrabold text-slate-900 dark:text-white tracking-tight block truncate">-48 dBm</span>
                            <span class="block text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold truncate mt-0.5">100% Link • 8ms Ping</span>
                        </div>
                        <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center border border-indigo-200 dark:border-indigo-800/60 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" x2="12.01" y1="20" y2="20"/></svg>
                        </div>
                    </div>
                </div>

                <!-- 3. Navigation Filter Bar -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-2 rounded-2xl shadow-sm flex items-center gap-2 overflow-x-auto">
                    <button type="button" @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-emerald-600 text-white shadow font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shrink-0 font-mono">
                        All Nodes
                    </button>
                    <button type="button" @click="activeTab = 'processing'" :class="activeTab === 'processing' ? 'bg-emerald-600 text-white shadow font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shrink-0 font-mono">
                        Compute (RPi/ESP32)
                    </button>
                    <button type="button" @click="activeTab = 'sensors'" :class="activeTab === 'sensors' ? 'bg-emerald-600 text-white shadow font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shrink-0 font-mono">
                        Sensor Arrays
                    </button>
                    <button type="button" @click="activeTab = 'network'" :class="activeTab === 'network' ? 'bg-emerald-600 text-white shadow font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shrink-0 font-mono">
                        Network & Peripherals
                    </button>
                </div>

                <!-- SECTION C: Processing Units -->
                <div x-show="activeTab === 'all' || activeTab === 'processing'" class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm dark:shadow-xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                        <div>
                            <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Embedded Compute</span>
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">Host & Microcontroller Diagnostics</h2>
                        </div>
                        <span class="px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 rounded text-[10px] font-mono font-bold">ONLINE</span>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Raspberry Pi 4 Node -->
                        <div class="bg-slate-50 dark:bg-slate-950 rounded-xl p-5 border border-slate-200 dark:border-slate-800 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Raspberry Pi 4B (Vision Engine)</h3>
                                    <span class="text-xs font-mono text-slate-500 dark:text-slate-400">ARM Cortex-A72 • 4GB LPDDR4</span>
                                </div>
                                <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 rounded text-[10px] font-mono font-bold">PRIMARY</span>
                            </div>

                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                                    <span class="block text-[10px] font-mono uppercase text-slate-500">Core Temp</span>
                                    <span class="text-base font-mono font-extrabold text-emerald-600 dark:text-emerald-400">42°C</span>
                                </div>
                                <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                                    <span class="block text-[10px] font-mono uppercase text-slate-500">CPU Load</span>
                                    <span class="text-base font-mono font-extrabold text-slate-900 dark:text-white">35%</span>
                                </div>
                                <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                                    <span class="block text-[10px] font-mono uppercase text-slate-500">Supply</span>
                                    <span class="text-base font-mono font-extrabold text-amber-600 dark:text-amber-400">5.11V</span>
                                </div>
                            </div>
                        </div>

                        <!-- ESP32 Node -->
                        <div class="bg-slate-50 dark:bg-slate-950 rounded-xl p-5 border border-slate-200 dark:border-slate-800 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">ESP32-WROOM-32 (Sensor Controller)</h3>
                                    <span class="text-xs font-mono text-slate-500 dark:text-slate-400">Xtensa Dual-Core 240MHz • UART Bus</span>
                                </div>
                                <span class="px-2 py-0.5 bg-cyan-100 dark:bg-cyan-950 text-cyan-700 dark:text-cyan-400 border border-cyan-300 dark:border-cyan-800 rounded text-[10px] font-mono font-bold">NODE-01</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 font-mono text-xs">
                                <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                                    <span class="text-[10px] uppercase text-slate-500 block">MAC Address</span>
                                    <span class="text-slate-800 dark:text-slate-200 font-bold">A4:CF:12:6E:3B:10</span>
                                </div>
                                <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                                    <span class="text-[10px] uppercase text-slate-500 block">Firmware</span>
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">EcoSync-v2.1.4</span>
                                </div>
                                <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                                    <span class="text-[10px] uppercase text-slate-500 block">Protocol</span>
                                    <span class="text-slate-800 dark:text-slate-200 font-bold">UART / Serial (115200)</span>
                                </div>
                                <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                                    <span class="text-[10px] uppercase text-slate-500 block">Heartbeat</span>
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> 1s Nominal
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION D: Sensor Health Array -->
                <div x-show="activeTab === 'all' || activeTab === 'sensors'" class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm dark:shadow-xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                        <div>
                            <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Telemetry Array</span>
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">Sensor Telemetry & Diagnostic Matrix</h2>
                        </div>
                        <span class="px-3 py-1 bg-slate-100 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 rounded text-[10px] font-mono font-bold uppercase">4 / 4 Online</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Sensor 1: Ultrasonic -->
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-mono font-bold text-slate-900 dark:text-white">Ultrasonic (HC-SR04)</span>
                                <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 rounded text-[9px] font-mono font-bold">ONLINE</span>
                            </div>
                            <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 font-mono text-xs space-y-1">
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Chamber Ping</span>
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">18.2 cm</span>
                                </div>
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Signal Quality</span>
                                    <span class="text-slate-900 dark:text-white font-bold">99.4%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sensor 2: Proximity IR -->
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-mono font-bold text-slate-900 dark:text-white">Proximity IR (PIR)</span>
                                <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 rounded text-[9px] font-mono font-bold">ONLINE</span>
                            </div>
                            <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 font-mono text-xs space-y-1">
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Intake Beam</span>
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">Clear</span>
                                </div>
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Trigger Logic</span>
                                    <span class="text-slate-900 dark:text-white font-bold">Active LOW</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sensor 3: NIR Optical Moisture -->
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-mono font-bold text-slate-900 dark:text-white">NIR Moisture (AS7263)</span>
                                <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 rounded text-[9px] font-mono font-bold">ONLINE</span>
                            </div>
                            <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 font-mono text-xs space-y-1">
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Absorption</span>
                                    <span class="text-sky-600 dark:text-sky-400 font-bold">1450nm (15%)</span>
                                </div>
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>State</span>
                                    <span class="text-slate-900 dark:text-white font-bold">Dry Intake</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sensor 4: Inductive Metal -->
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-mono font-bold text-slate-900 dark:text-white">Metal Proximity (LJ12A3)</span>
                                <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 rounded text-[9px] font-mono font-bold">ONLINE</span>
                            </div>
                            <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 font-mono text-xs space-y-1">
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Field Detection</span>
                                    <span class="text-amber-600 dark:text-amber-400 font-bold">Standby</span>
                                </div>
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Target Met</span>
                                    <span class="text-slate-900 dark:text-white font-bold">0 Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION E: System Peripherals & Network -->
                <div x-show="activeTab === 'all' || activeTab === 'peripherals' || activeTab === 'network'" class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm dark:shadow-xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                        <div>
                            <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Connectivity & IO</span>
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">Peripheral Power & Wireless Telemetry</h2>
                        </div>
                        <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 rounded text-[10px] font-mono font-bold uppercase">Link 100%</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Peripheral 1: Vision Camera -->
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                            <span class="text-xs font-mono font-bold text-slate-900 dark:text-white block">Logitech C270 HD Cam</span>
                            <div class="aspect-video bg-slate-100 dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 flex items-center justify-center relative overflow-hidden">
                                <span class="absolute top-2 left-2 px-1.5 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 rounded text-[8px] font-mono font-bold">LIVE</span>
                                <span class="text-[10px] font-mono text-slate-400 dark:text-slate-500">720p @ 30 FPS</span>
                            </div>
                        </div>

                        <!-- Peripheral 2: Power Unit -->
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                            <span class="text-xs font-mono font-bold text-slate-900 dark:text-white block">Main 5V DC Supply</span>
                            <div class="grid grid-cols-2 gap-2 text-center font-mono text-xs">
                                <div class="p-2.5 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                                    <span class="text-[9px] uppercase text-slate-400 dark:text-slate-500 block">V-Bus</span>
                                    <span class="text-slate-900 dark:text-white font-bold">5.03V</span>
                                </div>
                                <div class="p-2.5 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                                    <span class="text-[9px] uppercase text-slate-400 dark:text-slate-500 block">Draw</span>
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">1.8A</span>
                                </div>
                            </div>
                        </div>

                        <!-- Peripheral 3: Network Node -->
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                            <span class="text-xs font-mono font-bold text-slate-900 dark:text-white block">Wireless Mesh</span>
                            <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 font-mono text-xs space-y-1">
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Signal (RSSI)</span>
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">-48 dBm</span>
                                </div>
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>IP Node</span>
                                    <span class="text-slate-800 dark:text-slate-200">192.168.1.104</span>
                                </div>
                            </div>
                        </div>

                        <!-- Peripheral 4: Gateway Ping -->
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                            <span class="text-xs font-mono font-bold text-slate-900 dark:text-white block">Cloud Gateway</span>
                            <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 font-mono text-xs space-y-1">
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Latency</span>
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">8 ms</span>
                                </div>
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Packet Loss</span>
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">0.0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</x-app-layout>
