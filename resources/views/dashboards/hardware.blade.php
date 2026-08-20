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
            testMessage: 'Telemetry Ready — Click any diagnostic control below to test physical actuators',
            testStatus: 'idle',
            servos: { bio: 0 },
            runTest(component, action) {
                this.testStatus = 'running';
                this.testMessage = 'Transmitting Command: Signal sent to ' + component + ' ➔ ' + action + '...';
                setTimeout(() => {
                    this.testStatus = 'success';
                    this.testMessage = '✓ Acknowledged: ' + component + ' executed ' + action + ' successfully.';
                    if(component.includes('Bio')) this.servos.bio = parseInt(action);
                    setTimeout(() => { this.testStatus = 'idle'; }, 4000);
                }, 500);
            },
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
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Hardware Monitor & Test Bench</h1>
                            <span class="px-2.5 py-0.5 bg-cyan-100 dark:bg-cyan-950 text-cyan-700 dark:text-cyan-400 border border-cyan-300 dark:border-cyan-800 rounded text-[10px] font-mono font-bold uppercase tracking-wider">SUPER ADMIN</span>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm">
                            Live ESP32 telemetry, actuator control relays, and component health tracker
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

                <!-- 2. Top Summary Row (4 Separate Cards) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <!-- Card 1: 10 Devices Online -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-lg flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <span class="block text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 truncate mb-1">Fleet Health</span>
                            <span class="text-2xl font-mono font-extrabold text-slate-900 dark:text-white tracking-tight block truncate">10 Devices</span>
                            <span class="block text-[11px] text-slate-500 dark:text-slate-400 font-semibold truncate mt-0.5">Online & Responding</span>
                        </div>
                        <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-200 dark:border-emerald-800/60 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"/><path d="M2 12h20"/><circle cx="12" cy="12" r="9"/></svg>
                        </div>
                    </div>

                    <!-- Card 2: 1 Parts Alert -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-lg flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <span class="block text-[10px] font-mono font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400 truncate mb-1">Wear Lifecycle</span>
                            <span class="text-2xl font-mono font-extrabold text-amber-600 dark:text-amber-400 tracking-tight block truncate">1 Notice</span>
                            <span class="block text-[11px] text-slate-500 dark:text-slate-400 font-semibold truncate mt-0.5">Bio Lid Servo Cycle</span>
                        </div>
                        <div class="w-10 h-10 bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center border border-amber-200 dark:border-amber-800/60 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                        </div>
                    </div>

                    <!-- Card 3: Remote Test Bench -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-lg flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <span class="block text-[10px] font-mono font-bold uppercase tracking-widest text-cyan-600 dark:text-cyan-400 truncate mb-1">Manual Overrides</span>
                            <span class="text-2xl font-mono font-extrabold text-slate-900 dark:text-white tracking-tight block truncate">Test Bench</span>
                            <span class="block text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold truncate mt-0.5">Telemetry Ready</span>
                        </div>
                        <div class="w-10 h-10 bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 rounded-xl flex items-center justify-center border border-cyan-200 dark:border-cyan-800/60 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                        </div>
                    </div>

                    <!-- Card 4: 35% CPU Load -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-lg flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <span class="block text-[10px] font-mono font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 truncate mb-1">Host Processor</span>
                            <span class="text-2xl font-mono font-extrabold text-slate-900 dark:text-white tracking-tight block truncate">35% CPU</span>
                            <span class="block text-[11px] text-slate-500 dark:text-slate-400 font-semibold truncate mt-0.5">RPi 4B • 42°C Nominal</span>
                        </div>
                        <div class="w-10 h-10 bg-slate-100 dark:bg-slate-950 rounded-lg flex items-center justify-center text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 9h6v6H9z"/><path d="M15 2v1"/><path d="M9 2v1"/></svg>
                        </div>
                    </div>
                </div>

                <!-- 3. Navigation Filter Bar -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-2 rounded-2xl shadow-sm flex items-center gap-2 overflow-x-auto">
                    <button type="button" @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-emerald-600 text-white shadow font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shrink-0 font-mono">
                        All Nodes
                    </button>
                    <button type="button" @click="activeTab = 'testbench'" :class="activeTab === 'testbench' ? 'bg-emerald-600 text-white shadow font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shrink-0 font-mono">
                        Remote Bench
                    </button>
                    <button type="button" @click="activeTab = 'health'" :class="activeTab === 'health' ? 'bg-emerald-600 text-white shadow font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shrink-0 font-mono">
                        Parts Health
                    </button>
                    <button type="button" @click="activeTab = 'processing'" :class="activeTab === 'processing' ? 'bg-emerald-600 text-white shadow font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shrink-0 font-mono">
                        Compute (RPi/ESP32)
                    </button>
                    <button type="button" @click="activeTab = 'sensors'" :class="activeTab === 'sensors' ? 'bg-emerald-600 text-white shadow font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shrink-0 font-mono">
                        Sensor Arrays
                    </button>
                    <button type="button" @click="activeTab = 'actuators'" :class="activeTab === 'actuators' ? 'bg-emerald-600 text-white shadow font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shrink-0 font-mono">
                        Actuators
                    </button>
                    <button type="button" @click="activeTab = 'network'" :class="activeTab === 'network' ? 'bg-emerald-600 text-white shadow font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold'" class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-colors shrink-0 font-mono">
                        Network
                    </button>
                </div>

                <!-- SECTION A: Interactive Hardware Remote Test Bench -->
                <div x-show="activeTab === 'all' || activeTab === 'testbench'" class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm dark:shadow-xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                        <div>
                            <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Actuator Bench</span>
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">Remote Diagnostic Test Controls</h2>
                        </div>
                        <span class="px-3 py-1 bg-cyan-100 dark:bg-cyan-950 text-cyan-700 dark:text-cyan-400 border border-cyan-300 dark:border-cyan-800 rounded text-[10px] font-mono font-bold uppercase tracking-wider">SuperAdmin Live Mode</span>
                    </div>

                    <!-- Live Command Banner -->
                    <div class="p-4 rounded-xl border transition-all flex items-center gap-3 font-mono text-xs"
                         :class="{
                            'bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300': testStatus === 'idle',
                            'bg-amber-50 dark:bg-amber-950/40 border-amber-300 dark:border-amber-800 text-amber-800 dark:text-amber-300 animate-pulse': testStatus === 'running',
                            'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300': testStatus === 'success'
                         }">
                        <div class="w-2.5 h-2.5 rounded-full shrink-0" 
                             :class="{
                                'bg-slate-400 dark:bg-slate-500': testStatus === 'idle',
                                'bg-amber-500 animate-ping': testStatus === 'running',
                                'bg-emerald-500': testStatus === 'success'
                             }"></div>
                        <span class="font-semibold" x-text="testMessage"></span>
                    </div>

                    <!-- Test Controls Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Servos -->
                        <div class="bg-slate-50 dark:bg-slate-950 rounded-xl p-5 border border-slate-200 dark:border-slate-800 space-y-4">
                            <h3 class="text-xs font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Bio Lid Motor Control</h3>
                            <div class="p-4 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 space-y-3">
                                <div class="flex justify-between items-center text-xs font-semibold">
                                    <span class="text-slate-900 dark:text-white flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Servo #1 (Odor Seal Lid)
                                    </span>
                                    <span class="font-mono text-emerald-600 dark:text-emerald-400 font-bold" x-text="servos.bio + '°'"></span>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <button type="button" @click="runTest('Bio Lid Servo', '0°')" class="py-2 bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-[10px] font-mono font-bold uppercase transition-colors shadow-sm">0° (Closed)</button>
                                    <button type="button" @click="runTest('Bio Lid Servo', '45°')" class="py-2 bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-[10px] font-mono font-bold uppercase transition-colors shadow-sm">45° (Mid)</button>
                                    <button type="button" @click="runTest('Bio Lid Servo', '90°')" class="py-2 bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-[10px] font-mono font-bold uppercase transition-colors shadow-sm">90° (Open)</button>
                                </div>
                            </div>
                        </div>

                        <!-- Agitation Motor -->
                        <div class="bg-slate-50 dark:bg-slate-950 rounded-xl p-5 border border-slate-200 dark:border-slate-800 space-y-4">
                            <h3 class="text-xs font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Agitation & Grinder Motors</h3>
                            <div class="space-y-3">
                                <div class="p-3.5 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                                    <span class="block text-xs font-bold text-slate-900 dark:text-white mb-2">Vibration Agitation Motor</span>
                                    <button type="button" @click="runTest('Vibration Motor', 'Pulse Agitation 3s')" class="w-full py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold uppercase font-mono transition-colors shadow-sm">
                                        Trigger Agitation (3s)
                                    </button>
                                </div>
                                <div class="p-3.5 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800">
                                    <span class="block text-xs font-bold text-slate-900 dark:text-white mb-2">Organic Grinder Motor</span>
                                    <button type="button" @click="runTest('Grinder Motor', 'Pulse Shredder 4s')" class="w-full py-2 bg-amber-100 dark:bg-amber-950/60 hover:bg-amber-200 dark:hover:bg-amber-900/80 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800/80 rounded-lg text-xs font-semibold uppercase font-mono transition-colors shadow-sm">
                                        Run Bio Shredder (4s)
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Relays -->
                        <div class="bg-slate-50 dark:bg-slate-950 rounded-xl p-5 border border-slate-200 dark:border-slate-800 space-y-4">
                            <h3 class="text-xs font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Relay & Peripheral Overrides</h3>
                            <div class="space-y-2.5">
                                <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 flex justify-between items-center text-xs">
                                    <span class="font-bold text-slate-900 dark:text-white">Relay #1 (Motor VCC)</span>
                                    <button type="button" @click="runTest('Relay #1', 'Toggle Switch')" class="px-3 py-1.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 rounded text-[10px] font-mono font-bold uppercase hover:bg-emerald-200 dark:hover:bg-emerald-900 transition-colors">Toggle</button>
                                </div>
                                <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 flex justify-between items-center text-xs">
                                    <span class="font-bold text-slate-900 dark:text-white">Relay #2 (LED Illuminator)</span>
                                    <button type="button" @click="runTest('Relay #2', 'Toggle Light')" class="px-3 py-1.5 bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-400 border border-amber-300 dark:border-amber-800 rounded text-[10px] font-mono font-bold uppercase hover:bg-amber-200 dark:hover:bg-amber-900 transition-colors">Toggle</button>
                                </div>
                                <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 flex justify-between items-center text-xs">
                                    <span class="font-bold text-slate-900 dark:text-white">C270 USB Camera</span>
                                    <button type="button" @click="runTest('C270 Camera', 'Snapshot Test')" class="px-3 py-1.5 bg-cyan-100 dark:bg-cyan-950 text-cyan-700 dark:text-cyan-400 border border-cyan-300 dark:border-cyan-800 rounded text-[10px] font-mono font-bold uppercase hover:bg-cyan-200 dark:hover:bg-cyan-900 transition-colors">Capture</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION B: Component Lifespan & Parts Health Tracker -->
                <div x-show="activeTab === 'all' || activeTab === 'health'" class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm dark:shadow-xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                        <div>
                            <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Predictive Maintenance</span>
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">Component Lifecycle & Degradation Index</h2>
                        </div>
                        <span class="px-3 py-1 bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-400 border border-amber-300 dark:border-amber-800 rounded text-[10px] font-mono font-bold">1 Attention Notice</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <!-- Part 1 -->
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-bold text-sm text-slate-900 dark:text-white block">Servo #1 (Bio Lid)</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">SG90 9g Metal Gear</span>
                                </div>
                                <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 rounded text-[10px] font-mono font-bold">92%</span>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-mono text-slate-500 dark:text-slate-400">
                                    <span>Duty Count</span>
                                    <span>4,120 / 50,000</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-200 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-300 dark:border-slate-800">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 92%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Part 2 -->
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-bold text-sm text-slate-900 dark:text-white block">Static Inspection Platform</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">Intake Chamber Base</span>
                                </div>
                                <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 rounded text-[10px] font-mono font-bold">100%</span>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-mono text-slate-500 dark:text-slate-400">
                                    <span>Solid State</span>
                                    <span>Zero Moving Parts</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-200 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-300 dark:border-slate-800">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Part 3 -->
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-bold text-sm text-slate-900 dark:text-white block">HC-SR04 Ultrasonic Array</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">4x Transceiver Nodes</span>
                                </div>
                                <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 rounded text-[10px] font-mono font-bold">98%</span>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-mono text-slate-500 dark:text-slate-400">
                                    <span>Continuous Ping</span>
                                    <span>Nominal Waveform</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-200 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-300 dark:border-slate-800">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 98%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight mt-0.5">Sensor Health & Diagnostic Matrix</h2>
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
