<x-app-layout>
    @slot('hideNav')
        true
    @endslot

    <div class="flex flex-col lg:flex-row min-h-screen w-full bg-slate-50 dark:bg-zinc-950 text-gray-900 dark:text-zinc-100 transition-colors duration-300" 
         x-data="{ 
            sidebarOpen: false,
            activeTab: 'all',
            lastRefresh: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
            isRefreshing: false,
            testMessage: 'System Ready — Click any control below to test physical hardware',
            testStatus: 'idle',
            servos: { bio: 0 },
            runTest(component, action) {
                this.testStatus = 'running';
                this.testMessage = 'Executing command: Sending signal to ' + component + ' ➔ ' + action + '...';
                setTimeout(() => {
                    this.testStatus = 'success';
                    this.testMessage = '✓ Verified! ' + component + ' responded to ' + action + ' command.';
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
        <main class="flex-1 w-full min-w-0 ml-0 lg:ml-72 p-4 sm:p-6 lg:p-12 bg-slate-50 dark:bg-zinc-950 min-h-screen">
            <div class="max-w-7xl mx-auto space-y-10">
                
                <!-- 1. Page Header -->
                <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 mb-8 border-b border-rose-200/60 dark:border-zinc-800">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center flex-wrap gap-3">
                            <h1 class="text-3xl lg:text-4xl font-black text-rose-950 dark:text-zinc-100 tracking-tight leading-tight">Hardware Monitor</h1>
                            <span class="px-4 py-1 bg-rose-950 dark:bg-rose-900 text-white rounded-full text-xs font-black uppercase tracking-widest shadow shrink-0">SUPER ADMIN</span>
                        </div>
                        <p class="text-rose-600 dark:text-rose-400 font-bold text-sm lg:text-base opacity-90">
                            Live diagnostics, remote test bench, and component health tracker
                        </p>
                    </div>

                    <div class="flex items-center gap-6 shrink-0">
                        <div class="text-right hidden sm:block">
                            <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-0.5">Last manual refresh</span>
                            <span class="font-mono text-sm font-black text-rose-950 dark:text-zinc-100" x-text="lastRefresh"></span>
                        </div>
                        <button type="button" @click="refreshData()" class="flex items-center gap-2.5 px-6 py-3 bg-rose-950 hover:bg-rose-900 dark:bg-rose-900 dark:hover:bg-rose-800 text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-md hover:-translate-y-0.5 active:translate-y-0 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" :class="isRefreshing ? 'animate-spin' : ''"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                            Refresh
                        </button>
                    </div>
                </header>

                <!-- 2. Top Summary Row (4 Separate Floating Cards) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    <!-- Card 1: 10 Devices Online -->
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-rose-100 dark:border-zinc-800 shadow-sm flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <span class="block text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 truncate mb-1">Status Overview</span>
                            <span class="text-2xl font-black text-rose-950 dark:text-zinc-100 tracking-tight block truncate">10 Devices</span>
                            <span class="block text-xs font-bold text-gray-500 dark:text-zinc-400 truncate mt-0.5">Online & Active</span>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-500/20 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M2 12h20"/><circle cx="12" cy="12" r="9"/></svg>
                        </div>
                    </div>

                    <!-- Card 2: 1 Parts Alert -->
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-rose-100 dark:border-zinc-800 shadow-sm flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <span class="block text-[10px] font-black uppercase tracking-widest text-amber-600 dark:text-amber-400 truncate mb-1">Parts Lifespan</span>
                            <span class="text-2xl font-black text-amber-500 tracking-tight block truncate">1 Warning</span>
                            <span class="block text-xs font-bold text-gray-500 dark:text-zinc-400 truncate mt-0.5">Servo #3 High Wear</span>
                        </div>
                        <div class="w-12 h-12 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center border border-amber-500/20 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                        </div>
                    </div>

                    <!-- Card 3: Remote Test Bench -->
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-rose-100 dark:border-zinc-800 shadow-sm flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <span class="block text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 truncate mb-1">Remote Controls</span>
                            <span class="text-2xl font-black text-rose-950 dark:text-zinc-100 tracking-tight block truncate">Test Bench</span>
                            <span class="block text-xs font-bold text-emerald-500 truncate mt-0.5">Ready to Trigger</span>
                        </div>
                        <div class="w-12 h-12 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center border border-indigo-500/20 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                        </div>
                    </div>

                    <!-- Card 4: 35% CPU Load -->
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-rose-100 dark:border-zinc-800 shadow-sm flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-zinc-500 truncate mb-1">Processing Load</span>
                            <span class="text-2xl font-black text-rose-950 dark:text-zinc-100 tracking-tight block truncate">35% CPU</span>
                            <span class="block text-xs font-bold text-gray-500 dark:text-zinc-400 truncate mt-0.5">Quad-Core Nominal</span>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300 rounded-xl flex items-center justify-center border border-gray-200 dark:border-zinc-700 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 9h6v6H9z"/><path d="M15 2v1"/><path d="M9 2v1"/></svg>
                        </div>
                    </div>
                </div>

                <!-- 3. Navigation Filter Bar -->
                <div class="my-10 bg-white dark:bg-zinc-900 border border-rose-100 dark:border-zinc-800 p-3 rounded-2xl shadow-sm flex items-center gap-3 overflow-x-auto">
                    <button type="button" @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-rose-950 dark:bg-rose-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-rose-950 dark:hover:text-white font-semibold'" class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all shrink-0">
                        All Telemetry
                    </button>
                    <button type="button" @click="activeTab = 'testbench'" :class="activeTab === 'testbench' ? 'bg-rose-950 dark:bg-rose-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-rose-950 dark:hover:text-white font-semibold'" class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all shrink-0 flex items-center gap-2">
                        <span>🎮</span> Remote Test Bench
                    </button>

                    <button type="button" @click="activeTab = 'health'" :class="activeTab === 'health' ? 'bg-rose-950 dark:bg-rose-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-rose-950 dark:hover:text-white font-semibold'" class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all shrink-0 flex items-center gap-2">
                        <span>🩺</span> Parts Health & Lifespan
                    </button>
                    <button type="button" @click="activeTab = 'processing'" :class="activeTab === 'processing' ? 'bg-rose-950 dark:bg-rose-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-rose-950 dark:hover:text-white font-semibold'" class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all shrink-0">
                        Processing Units
                    </button>
                    <button type="button" @click="activeTab = 'sensors'" :class="activeTab === 'sensors' ? 'bg-rose-950 dark:bg-rose-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-rose-950 dark:hover:text-white font-semibold'" class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all shrink-0">
                        Sensor Health
                    </button>
                    <button type="button" @click="activeTab = 'actuators'" :class="activeTab === 'actuators' ? 'bg-rose-950 dark:bg-rose-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-rose-950 dark:hover:text-white font-semibold'" class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all shrink-0">
                        Actuator States
                    </button>
                    <button type="button" @click="activeTab = 'peripherals'" :class="activeTab === 'peripherals' ? 'bg-rose-950 dark:bg-rose-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-rose-950 dark:hover:text-white font-semibold'" class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all shrink-0">
                        Peripherals
                    </button>
                    <button type="button" @click="activeTab = 'network'" :class="activeTab === 'network' ? 'bg-rose-950 dark:bg-rose-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-zinc-400 hover:text-rose-950 dark:hover:text-white font-semibold'" class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all shrink-0">
                        Network
                    </button>
                </div>

                <!-- NEW SECTION A: Interactive Hardware Remote Test Bench -->
                <div x-show="activeTab === 'all' || activeTab === 'testbench'" class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-rose-100 dark:border-zinc-800 p-8 lg:p-10 mb-10 space-y-8">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b border-rose-100/60 dark:border-zinc-800">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black border border-indigo-200 dark:border-indigo-900/40 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-2xl lg:text-3xl font-black text-rose-950 dark:text-zinc-100 tracking-tight truncate">Interactive Remote Test Bench</h2>
                                <p class="text-sm font-bold text-rose-600/80 dark:text-zinc-400 mt-1 truncate">One-click live manual overrides for physical servos, motors, and relays</p>
                            </div>
                        </div>
                        <span class="px-4 py-1.5 bg-indigo-500/15 text-indigo-700 dark:text-indigo-300 border border-indigo-500/30 rounded-full text-xs font-black uppercase tracking-widest shrink-0">SuperAdmin Test Mode</span>
                    </div>

                    <!-- Live Command Banner -->
                    <div class="p-5 rounded-2xl border transition-all flex items-center gap-4 shadow-sm"
                         :class="{
                            'bg-gray-50 dark:bg-zinc-950 border-rose-100 dark:border-zinc-800 text-gray-600 dark:text-zinc-400': testStatus === 'idle',
                            'bg-amber-500/10 border-amber-500/30 text-amber-700 dark:text-amber-300 animate-pulse': testStatus === 'running',
                            'bg-emerald-500/10 border-emerald-500/30 text-emerald-700 dark:text-emerald-300': testStatus === 'success'
                         }">
                        <div class="w-3 h-3 rounded-full shrink-0" 
                             :class="{
                                'bg-gray-400': testStatus === 'idle',
                                'bg-amber-500 animate-ping': testStatus === 'running',
                                'bg-emerald-500': testStatus === 'success'
                             }"></div>
                        <span class="font-mono text-xs font-black leading-relaxed" x-text="testMessage"></span>
                    </div>

                    <!-- Test Controls Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <!-- Flap Test Card (Servos) -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-rose-500 dark:text-rose-400 mb-6 truncate">Servo Bin Lid Controls (4 Bins)</h3>
                                
                                <div class="space-y-4">
                                    <!-- Servo Bio Lid -->
                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 space-y-3">
                                        <div class="flex justify-between items-center text-xs font-bold">
                                            <span class="text-rose-950 dark:text-zinc-100 flex items-center gap-2">
                                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Servo #1 (Biodegradable Lid Motor)
                                            </span>
                                            <span class="font-mono text-rose-600 dark:text-rose-400 font-black" x-text="servos.bio + '°'"></span>
                                        </div>
                                        <div class="grid grid-cols-3 gap-2 pt-1">
                                            <button type="button" @click="runTest('Bio Lid Servo', '0°')" class="py-2 bg-gray-100 dark:bg-zinc-800 hover:bg-rose-950 hover:text-white dark:hover:bg-rose-600 text-gray-700 dark:text-zinc-300 rounded-lg text-[10px] font-black uppercase transition-all">0° (Closed)</button>
                                            <button type="button" @click="runTest('Bio Lid Servo', '45°')" class="py-2 bg-gray-100 dark:bg-zinc-800 hover:bg-rose-950 hover:text-white dark:hover:bg-rose-600 text-gray-700 dark:text-zinc-300 rounded-lg text-[10px] font-black uppercase transition-all">45° (Partial)</button>
                                            <button type="button" @click="runTest('Bio Lid Servo', '90°')" class="py-2 bg-gray-100 dark:bg-zinc-800 hover:bg-rose-950 hover:text-white dark:hover:bg-rose-600 text-gray-700 dark:text-zinc-300 rounded-lg text-[10px] font-black uppercase transition-all">90° (Fully Open)</button>
                                        </div>
                                    </div>

                                    <!-- Compartment Design Note -->
                                    <div class="p-3.5 bg-emerald-500/10 dark:bg-emerald-950/20 border border-emerald-500/30 rounded-xl text-xs font-bold text-emerald-800 dark:text-emerald-300">
                                        💡 <strong>Hardware Architecture Note:</strong> An automated servo lid is equipped on the <strong>Biodegradable Bin</strong> for odor sealing. Recyclable, Hazardous, and Non-Bio compartments utilize open-top chutes for instant disposal.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Motor & Vibration Tests -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-rose-500 dark:text-rose-400 mb-6 truncate">Motor & Conveyor Overrides</h3>
                                
                                <div class="space-y-4">
                                    <!-- Conveyor DC -->
                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 space-y-3">
                                        <span class="block text-xs font-black text-rose-950 dark:text-zinc-100">DC Conveyor Motor (L298N)</span>
                                        <div class="grid grid-cols-2 gap-2">
                                            <button type="button" @click="runTest('DC Conveyor', 'Forward 5s')" class="py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-[10px] font-black uppercase shadow-sm">▶ Forward 5s</button>
                                            <button type="button" @click="runTest('DC Conveyor', 'Reverse 3s')" class="py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-[10px] font-black uppercase shadow-sm">◀ Reverse 3s</button>
                                        </div>
                                    </div>

                                    <!-- Vibration Motor -->
                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 space-y-3">
                                        <span class="block text-xs font-black text-rose-950 dark:text-zinc-100">Vibration Agitation Motor</span>
                                        <button type="button" @click="runTest('Vibration Motor', 'Pulse Agitation 3s')" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-black uppercase shadow-sm flex items-center justify-center gap-2">
                                            <span>⚡</span> Trigger Unjam Vibration (3s)
                                        </button>
                                    </div>

                                    <!-- Organic Grinder Motor -->
                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 space-y-3">
                                        <span class="block text-xs font-black text-rose-950 dark:text-zinc-100">Organic Waste Grinder Motor (Bio Chute)</span>
                                        <button type="button" @click="runTest('Grinder Motor', 'Pulse Shredder 4s')" class="w-full py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-black uppercase shadow-sm flex items-center justify-center gap-2">
                                            <span>⚙️</span> Run Bio Shredder & Grinder (4s)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Relays & Peripherals -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-rose-950 dark:text-zinc-100 mb-6 truncate">Relay & Lighting Overrides</h3>
                                
                                <div class="space-y-4">
                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center">
                                        <span class="text-xs font-black text-rose-950 dark:text-zinc-100">Relay #1 (Motor Power)</span>
                                        <button type="button" @click="runTest('Relay #1', 'Toggle Switch')" class="px-4 py-2 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-lg text-[10px] font-black uppercase hover:bg-emerald-500 hover:text-white transition-all">Toggle ON/OFF</button>
                                    </div>

                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center">
                                        <span class="text-xs font-black text-rose-950 dark:text-zinc-100">Relay #2 (LED Array)</span>
                                        <button type="button" @click="runTest('Relay #2', 'Toggle Light')" class="px-4 py-2 bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30 rounded-lg text-[10px] font-black uppercase hover:bg-amber-500 hover:text-white transition-all">Toggle ON/OFF</button>
                                    </div>

                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center">
                                        <span class="text-xs font-black text-rose-950 dark:text-zinc-100">C270 Camera Sensor</span>
                                        <button type="button" @click="runTest('C270 Camera', 'Snapshot Test')" class="px-4 py-2 bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-500/30 rounded-lg text-[10px] font-black uppercase hover:bg-sky-500 hover:text-white transition-all">Test Frame</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>



                <!-- NEW SECTION B: Component Lifespan & Parts Health Tracker -->
                <div x-show="activeTab === 'all' || activeTab === 'health'" class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-rose-100 dark:border-zinc-800 p-8 lg:p-10 mb-10 space-y-8">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b border-rose-100/60 dark:border-zinc-800">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-black border border-amber-200 dark:border-amber-900/40 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-2xl lg:text-3xl font-black text-rose-950 dark:text-zinc-100 tracking-tight truncate">Parts Health & Lifespan Tracker</h2>
                                <p class="text-sm font-bold text-rose-600/80 dark:text-zinc-400 mt-1 truncate">Automatic cycle counter & oil-change style replacement warnings</p>
                            </div>
                        </div>
                        <span class="px-4 py-1.5 bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30 rounded-full text-xs font-black uppercase tracking-widest shrink-0">1 Warning Active</span>
                    </div>

                    <!-- Lifespan Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- Part 1: Servo #1 Bio Lid -->
                        <div class="p-6 bg-rose-50/50 dark:bg-zinc-950 rounded-2xl border border-rose-100/80 dark:border-zinc-800 space-y-4">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <span class="font-black text-sm text-rose-950 dark:text-zinc-100 block">Servo #1 (Bio Lid Seal)</span>
                                    <span class="text-xs text-emerald-500 font-bold">Biodegradable Bin Lid Motor</span>
                                </div>
                                <span class="px-2.5 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 rounded-full text-[10px] font-black uppercase">92% Health</span>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-bold">
                                    <span class="text-gray-500 dark:text-zinc-400">Actuations</span>
                                    <span class="font-mono text-rose-950 dark:text-zinc-100 font-black">4,120 / 50,000</span>
                                </div>
                                <div class="h-2.5 w-full bg-gray-200 dark:bg-zinc-800 rounded-full overflow-hidden p-0.5">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 92%"></div>
                                </div>
                            </div>
                            <span class="block text-[10px] font-bold text-emerald-600 dark:text-emerald-400">✓ Operating in optimal condition</span>
                        </div>

                        <!-- Part 2: Gantry Linear Rail Drive -->
                        <div class="p-6 bg-rose-50/50 dark:bg-zinc-950 rounded-2xl border border-rose-100/80 dark:border-zinc-800 space-y-4">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <span class="font-black text-sm text-rose-950 dark:text-zinc-100 block">GT2 Gantry Linear Rail Drive</span>
                                    <span class="text-xs text-indigo-500 font-bold">Left-to-Right Carriage Motor</span>
                                </div>
                                <span class="px-2.5 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 rounded-full text-[10px] font-black uppercase">96% Health</span>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-bold">
                                    <span class="text-gray-500 dark:text-zinc-400">Travel Cycles</span>
                                    <span class="font-mono text-rose-950 dark:text-zinc-100 font-black">12,450 / 200,000</span>
                                </div>
                                <div class="h-2.5 w-full bg-gray-200 dark:bg-zinc-800 rounded-full overflow-hidden p-0.5">
                                    <div class="h-full bg-indigo-500 rounded-full" style="width: 96%"></div>
                                </div>
                            </div>
                            <span class="block text-[10px] font-bold text-emerald-600 dark:text-emerald-400">✓ Belt tension nominal</span>
                        </div>

                        <!-- Part 3: Robotic Arm Claw Servo -->
                        <div class="p-6 bg-rose-50/50 dark:bg-zinc-950 rounded-2xl border border-rose-100/80 dark:border-zinc-800 space-y-4">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <span class="font-black text-sm text-rose-950 dark:text-zinc-100 block">Robotic Arm Claw Servo</span>
                                    <span class="text-xs text-amber-500 font-bold">Gripper Mechanism</span>
                                </div>
                                <span class="px-2.5 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 rounded-full text-[10px] font-black uppercase">88% Health</span>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-bold">
                                    <span class="text-gray-500 dark:text-zinc-400">Grip Cycles</span>
                                    <span class="font-mono text-rose-950 dark:text-zinc-100 font-black">6,120 / 50,000</span>
                                </div>
                                <div class="h-2.5 w-full bg-gray-200 dark:bg-zinc-800 rounded-full overflow-hidden p-0.5">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 88%"></div>
                                </div>
                            </div>
                            <span class="block text-[10px] font-bold text-emerald-600 dark:text-emerald-400">✓ Operating in optimal condition</span>
                        </div>

                        <!-- Part 5: DC Motor -->
                        <div class="p-6 bg-rose-50/50 dark:bg-zinc-950 rounded-2xl border border-rose-100/80 dark:border-zinc-800 space-y-4">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <span class="font-black text-sm text-rose-950 dark:text-zinc-100 block">DC Conveyor Motor</span>
                                    <span class="text-xs text-gray-400 font-bold">L298N Drive</span>
                                </div>
                                <span class="px-2.5 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 rounded-full text-[10px] font-black uppercase">93% Health</span>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-bold">
                                    <span class="text-gray-500 dark:text-zinc-400">Run Time</span>
                                    <span class="font-mono text-rose-950 dark:text-zinc-100 font-black">142 / 2,000 hrs</span>
                                </div>
                                <div class="h-2.5 w-full bg-gray-200 dark:bg-zinc-800 rounded-full overflow-hidden p-0.5">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 93%"></div>
                                </div>
                            </div>
                            <span class="block text-[10px] font-bold text-emerald-600 dark:text-emerald-400">✓ Brush wear normal</span>
                        </div>

                        <!-- Part 6: Relay Module -->
                        <div class="p-6 bg-rose-50/50 dark:bg-zinc-950 rounded-2xl border border-rose-100/80 dark:border-zinc-800 space-y-4">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <span class="font-black text-sm text-rose-950 dark:text-zinc-100 block">3-Channel Relay</span>
                                    <span class="text-xs text-gray-400 font-bold">Optocoupled 5V</span>
                                </div>
                                <span class="px-2.5 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 rounded-full text-[10px] font-black uppercase">87% Health</span>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-bold">
                                    <span class="text-gray-500 dark:text-zinc-400">Switches</span>
                                    <span class="font-mono text-rose-950 dark:text-zinc-100 font-black">12,800 / 100,000</span>
                                </div>
                                <div class="h-2.5 w-full bg-gray-200 dark:bg-zinc-800 rounded-full overflow-hidden p-0.5">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 87%"></div>
                                </div>
                            </div>
                            <span class="block text-[10px] font-bold text-emerald-600 dark:text-emerald-400">✓ Contacts clean</span>
                        </div>

                        <!-- Part 7: HC-SR04 Array -->
                        <div class="p-6 bg-rose-50/50 dark:bg-zinc-950 rounded-2xl border border-rose-100/80 dark:border-zinc-800 space-y-4">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <span class="font-black text-sm text-rose-950 dark:text-zinc-100 block">HC-SR04 Sensors</span>
                                    <span class="text-xs text-gray-400 font-bold">Ultrasonic x 4</span>
                                </div>
                                <span class="px-2.5 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 rounded-full text-[10px] font-black uppercase">98% Health</span>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs font-bold">
                                    <span class="text-gray-500 dark:text-zinc-400">Active Duty</span>
                                    <span class="font-mono text-rose-950 dark:text-zinc-100 font-black">14 Days Continuous</span>
                                </div>
                                <div class="h-2.5 w-full bg-gray-200 dark:bg-zinc-800 rounded-full overflow-hidden p-0.5">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 98%"></div>
                                </div>
                            </div>
                            <span class="block text-[10px] font-bold text-emerald-600 dark:text-emerald-400">✓ Echo response calibrated</span>
                        </div>

                    </div>
                </div>

                <!-- 4. Section: Processing Units -->
                <div x-show="activeTab === 'all' || activeTab === 'processing'" class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-rose-100 dark:border-zinc-800 p-8 lg:p-10 mb-10 space-y-8">
                    <!-- Section Header -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b border-rose-100/60 dark:border-zinc-800">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center font-black border border-rose-200 dark:border-rose-900/40 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 9h6v6H9z"/><path d="M15 2v1"/><path d="M9 2v1"/></svg>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-2xl lg:text-3xl font-black text-rose-950 dark:text-zinc-100 tracking-tight truncate">Processing Units</h2>
                                <p class="text-sm font-bold text-rose-600/80 dark:text-zinc-400 mt-1 truncate">Raspberry Pi 4 and ESP32 microcontroller status</p>
                            </div>
                        </div>
                        <span class="px-4 py-1.5 bg-emerald-500 text-white rounded-full text-xs font-black uppercase tracking-widest flex items-center gap-2 shadow shrink-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-white animate-ping"></span>
                            Online
                        </span>
                    </div>

                    <!-- 2 Equal Cards Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        <!-- Raspberry Pi 4 Node -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 lg:p-8 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div class="flex items-center justify-between gap-4 mb-2">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-12 h-12 bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-2xl flex items-center justify-center font-black shrink-0 border border-rose-200 dark:border-rose-900/40">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/><rect width="18" height="18" x="3" y="3" rx="2"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-xl font-black text-rose-950 dark:text-zinc-100 tracking-tight truncate">Raspberry Pi 4</h3>
                                        <span class="text-xs font-bold text-gray-500 dark:text-zinc-400 block truncate mt-0.5">Model B - 4 GB RAM - 64 GB SD</span>
                                    </div>
                                </div>
                                <span class="px-3.5 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-black uppercase shrink-0">Online</span>
                            </div>

                            <!-- 3 Circular Telemetry Gauges -->
                            <div class="grid grid-cols-3 gap-4 text-center py-6 px-4 bg-white dark:bg-zinc-900 rounded-2xl border border-rose-100/60 dark:border-zinc-800 my-2">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="relative w-20 h-20 flex items-center justify-center mb-2">
                                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                            <path class="text-gray-200 dark:text-zinc-800" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                            <path class="text-amber-500 stroke-current" stroke-width="3" stroke-dasharray="90, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        </svg>
                                        <span class="absolute text-xs font-black font-mono text-rose-950 dark:text-zinc-100">5.11V</span>
                                    </div>
                                    <span class="text-xs font-black uppercase text-rose-950 dark:text-zinc-100">Power</span>
                                    <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 mt-0.5">Stable</span>
                                </div>

                                <div class="flex flex-col items-center justify-center">
                                    <div class="relative w-20 h-20 flex items-center justify-center mb-2">
                                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                            <path class="text-gray-200 dark:text-zinc-800" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                            <path class="text-emerald-500 stroke-current" stroke-width="3" stroke-dasharray="50, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        </svg>
                                        <span class="absolute text-xs font-black font-mono text-rose-950 dark:text-zinc-100">42°C</span>
                                    </div>
                                    <span class="text-xs font-black uppercase text-rose-950 dark:text-zinc-100">Temp</span>
                                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">Normal</span>
                                </div>

                                <div class="flex flex-col items-center justify-center">
                                    <div class="relative w-20 h-20 flex items-center justify-center mb-2">
                                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                            <path class="text-gray-200 dark:text-zinc-800" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                            <path class="text-emerald-500 stroke-current" stroke-width="3" stroke-dasharray="35, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        </svg>
                                        <span class="absolute text-xs font-black font-mono text-rose-950 dark:text-zinc-100">35%</span>
                                    </div>
                                    <span class="text-xs font-black uppercase text-rose-950 dark:text-zinc-100">CPU</span>
                                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">Low Load</span>
                                </div>
                            </div>

                            <!-- Data Rows -->
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div class="p-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-1">Uptime</span>
                                    <span class="block text-xs font-black text-rose-950 dark:text-zinc-100 font-mono truncate">14d 6h 22m</span>
                                </div>
                                <div class="p-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-1">RAM Used</span>
                                    <span class="block text-xs font-black text-rose-950 dark:text-zinc-100 font-mono truncate">1.8 / 4 GB</span>
                                </div>
                                <div class="p-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-1">Storage</span>
                                    <span class="block text-xs font-black text-rose-950 dark:text-zinc-100 font-mono truncate">12.4 / 64 GB</span>
                                </div>
                            </div>
                        </div>

                        <!-- ESP32 Node -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 lg:p-8 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div class="flex items-center justify-between gap-4 mb-2">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-12 h-12 bg-sky-500/10 text-sky-600 dark:text-sky-400 rounded-2xl flex items-center justify-center font-black shrink-0 border border-sky-200 dark:border-sky-900/40">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h.01"/><path d="M2 8.82a15 15 0 0 1 20 0"/><path d="M5 12.85a10 10 0 0 1 14 0"/><path d="M8.5 16.88a5 5 0 0 1 7 0"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-xl font-black text-rose-950 dark:text-zinc-100 tracking-tight truncate">ESP32 Node</h3>
                                        <span class="text-xs font-bold text-gray-500 dark:text-zinc-400 block truncate mt-0.5">WROOM-32 - BLE + WIFI</span>
                                    </div>
                                </div>
                                <span class="px-3.5 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-black uppercase shrink-0">Online</span>
                            </div>

                            <div class="flex items-center justify-center gap-4 py-5 px-6 bg-white dark:bg-zinc-900 rounded-2xl border border-rose-100/60 dark:border-zinc-800 my-2">
                                <div class="w-10 h-10 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-base font-black text-emerald-600 dark:text-emerald-400 block truncate">Link Connected</span>
                                    <span class="block text-xs font-bold text-gray-400 truncate">Serial Bus & Sensor Array Active</span>
                                </div>
                            </div>

                            <!-- Data Rows -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-0.5">MAC Address</span>
                                    <span class="font-mono text-xs font-black text-rose-950 dark:text-zinc-100 block truncate">A4:CF:12:6E:3B:10</span>
                                </div>
                                <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-0.5">Heartbeat</span>
                                    <span class="text-xs font-black text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 truncate">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span> 1s ago
                                    </span>
                                </div>
                                <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-0.5">Protocol</span>
                                    <span class="text-xs font-black text-rose-950 dark:text-zinc-100 block truncate">UART / Serial</span>
                                </div>
                                <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-zinc-500 mb-0.5">Firmware</span>
                                    <span class="font-mono text-xs font-black text-rose-950 dark:text-zinc-100 block truncate">v2.1.4</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 5. Section: Sensor Health Array -->
                <div x-show="activeTab === 'all' || activeTab === 'sensors'" class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-rose-100 dark:border-zinc-800 p-8 lg:p-10 mb-10 space-y-8">
                    <!-- Section Header -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b border-rose-100/60 dark:border-zinc-800">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center font-black border border-rose-200 dark:border-rose-900/40 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M2 12h20"/></svg>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-2xl lg:text-3xl font-black text-rose-950 dark:text-zinc-100 tracking-tight truncate">Sensor Health Array</h2>
                                <p class="text-sm font-bold text-rose-600/80 dark:text-zinc-400 mt-1 truncate">Ultrasonic, proximity, and moisture sensors</p>
                            </div>
                        </div>
                        <span class="px-4 py-1.5 bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 rounded-full text-xs font-black uppercase tracking-widest shrink-0">4 / 5 nominal</span>
                    </div>

                    <!-- 3-Column Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        
                        <!-- Primary Card 1: Ultrasonic HC-SR04 x 4 -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 lg:p-8 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-rose-500 dark:text-rose-400 mb-6 truncate">Ultrasonic Distance Sensors (HC-SR04 x 4 Bins)</h3>

                                <div class="space-y-4">
                                    <!-- Hazardous -->
                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl space-y-2 border border-rose-100/60 dark:border-zinc-800">
                                        <div class="flex items-center justify-between gap-3 mb-1">
                                            <span class="font-black text-xs text-rose-950 dark:text-zinc-100 truncate">Ultrasonic #1 <span class="text-[10px] font-bold text-red-500">(Hazardous)</span></span>
                                            <span class="px-2.5 py-0.5 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-full text-[10px] font-black uppercase shrink-0">Online</span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs font-bold my-1">
                                            <span class="text-gray-500 dark:text-zinc-400 font-mono">18.2 cm</span>
                                            <span class="text-emerald-500 font-mono font-black">45% Fill</span>
                                        </div>
                                        <div class="h-2.5 w-full bg-gray-200 dark:bg-zinc-800 rounded-full overflow-hidden p-0.5">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width: 45%"></div>
                                        </div>
                                    </div>

                                    <!-- Rec -->
                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl space-y-2 border border-rose-100/60 dark:border-zinc-800">
                                        <div class="flex items-center justify-between gap-3 mb-1">
                                            <span class="font-black text-xs text-rose-950 dark:text-zinc-100 truncate">Ultrasonic #2 <span class="text-[10px] font-bold text-sky-500">(Recyclable)</span></span>
                                            <span class="px-2.5 py-0.5 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-full text-[10px] font-black uppercase shrink-0">Online</span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs font-bold my-1">
                                            <span class="text-gray-500 dark:text-zinc-400 font-mono">28.7 cm</span>
                                            <span class="text-emerald-500 font-mono font-black">28% Fill</span>
                                        </div>
                                        <div class="h-2.5 w-full bg-gray-200 dark:bg-zinc-800 rounded-full overflow-hidden p-0.5">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width: 28%"></div>
                                        </div>
                                    </div>

                                    <!-- Non-Bio -->
                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl space-y-2 border border-rose-100/60 dark:border-zinc-800">
                                        <div class="flex items-center justify-between gap-3 mb-1">
                                            <span class="font-black text-xs text-rose-950 dark:text-zinc-100 truncate">Ultrasonic #3 <span class="text-[10px] font-bold text-orange-400">(Non-Bio)</span></span>
                                            <span class="px-2.5 py-0.5 bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30 rounded-full text-[10px] font-black uppercase shrink-0">Warning</span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs font-bold my-1">
                                            <span class="text-gray-500 dark:text-zinc-400 font-mono">35.1 cm</span>
                                            <span class="text-rose-500 font-mono font-black">12% Fill</span>
                                        </div>
                                        <div class="h-2.5 w-full bg-gray-200 dark:bg-zinc-800 rounded-full overflow-hidden p-0.5">
                                            <div class="h-full bg-rose-500 rounded-full" style="width: 12%"></div>
                                        </div>
                                    </div>

                                    <!-- Bio -->
                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl space-y-2 border border-rose-100/60 dark:border-zinc-800">
                                        <div class="flex items-center justify-between gap-3 mb-1">
                                            <span class="font-black text-xs text-rose-950 dark:text-zinc-100 truncate">Ultrasonic #4 <span class="text-[10px] font-bold text-emerald-500">(Bio)</span></span>
                                            <span class="px-2.5 py-0.5 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-full text-[10px] font-black uppercase shrink-0">Online</span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs font-bold my-1">
                                            <span class="text-gray-500 dark:text-zinc-400 font-mono">12.4 cm</span>
                                            <span class="text-amber-500 font-mono font-black">69% Fill</span>
                                        </div>
                                        <div class="h-2.5 w-full bg-gray-200 dark:bg-zinc-800 rounded-full overflow-hidden p-0.5">
                                            <div class="h-full bg-amber-400 rounded-full" style="width: 69%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-amber-500/10 rounded-xl text-amber-800 dark:text-amber-300 text-xs font-bold leading-relaxed mt-4 border border-amber-500/20">
                                ⚠ Reading drift detected — check sensor alignment
                            </div>
                        </div>

                        <!-- Primary Card 2: Proximity IR -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 lg:p-8 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div>
                                <div class="flex items-center justify-between gap-4 mb-6">
                                    <h3 class="text-xs font-black uppercase tracking-widest text-rose-500 dark:text-rose-400 truncate">Proximity IR (HC-SR501)</h3>
                                    <span class="px-3 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-full text-[10px] font-black uppercase shrink-0">Online</span>
                                </div>

                                <div class="py-6 px-6 my-4 bg-white dark:bg-zinc-900 rounded-2xl border border-rose-100/60 dark:border-zinc-800 flex flex-col items-center justify-center text-center space-y-2">
                                    <div class="w-16 h-16 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-1 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    </div>
                                    <span class="text-xl font-black text-emerald-600 dark:text-emerald-400">Clear</span>
                                    <span class="text-xs font-bold text-gray-400">No Obstruction Detected</span>
                                </div>

                                <!-- Data Rows -->
                                <div class="space-y-3 pt-4">
                                    <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="text-gray-500 dark:text-zinc-400">Distance</span>
                                        <span class="font-mono text-rose-950 dark:text-zinc-100 font-black">20.5 cm</span>
                                    </div>
                                    <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="text-gray-500 dark:text-zinc-400">Trigger</span>
                                        <span class="text-emerald-600 dark:text-emerald-400 font-mono font-black">LOW</span>
                                    </div>
                                    <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="text-gray-500 dark:text-zinc-400">Voltage</span>
                                        <span class="font-mono text-rose-950 dark:text-zinc-100 font-black">3.3 V</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Primary Card 3: NIR Optical Moisture Sensor -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 lg:p-8 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div>
                                <div class="flex items-center justify-between gap-4 mb-6">
                                    <h3 class="text-xs font-black uppercase tracking-widest text-rose-500 dark:text-rose-400 truncate">NIR Optical Moisture (AS7263)</h3>
                                    <span class="px-3 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-full text-[10px] font-black uppercase shrink-0">Online</span>
                                </div>

                                <div class="py-6 px-6 my-4 bg-white dark:bg-zinc-900 rounded-2xl border border-rose-100/60 dark:border-zinc-800 flex flex-col items-center justify-center text-center space-y-2">
                                    <div class="relative w-20 h-20 flex items-center justify-center mb-1">
                                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                            <path class="text-gray-200 dark:text-zinc-800" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                            <path class="text-sky-500 stroke-current" stroke-width="3" stroke-dasharray="15, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        </svg>
                                        <span class="absolute text-sm font-black text-rose-950 dark:text-zinc-100 font-mono">15%</span>
                                    </div>
                                    <span class="text-xl font-black text-sky-600 dark:text-sky-400">Dry Item</span>
                                    <span class="text-xs font-bold text-gray-400">1450nm NIR Light Absorption</span>
                                </div>

                                <!-- Data Rows -->
                                <div class="space-y-3 pt-4">
                                    <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="text-gray-500 dark:text-zinc-400">Sensor Method</span>
                                        <span class="text-emerald-600 font-mono font-black">Non-Contact Optical</span>
                                    </div>
                                    <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="text-gray-500 dark:text-zinc-400">Mount Location</span>
                                        <span class="font-mono text-rose-950 dark:text-zinc-100 font-black">Overhead Camera Module</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Primary Card 4: Inductive Metal Sensor -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 lg:p-8 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div>
                                <div class="flex items-center justify-between gap-4 mb-6">
                                    <h3 class="text-xs font-black uppercase tracking-widest text-amber-500 dark:text-amber-400 truncate">Inductive Metal Proximity (LJ12A3)</h3>
                                    <span class="px-3 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-full text-[10px] font-black uppercase shrink-0">Online</span>
                                </div>

                                <div class="py-6 px-6 my-4 bg-white dark:bg-zinc-900 rounded-2xl border border-rose-100/60 dark:border-zinc-800 flex flex-col items-center justify-center text-center space-y-2">
                                    <div class="w-16 h-16 rounded-full bg-amber-500/15 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-1 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                    </div>
                                    <span class="text-xl font-black text-amber-600 dark:text-amber-400">Standby (No Metal)</span>
                                    <span class="text-xs font-bold text-gray-400">Electromagnetic Field Ready</span>
                                </div>

                                <!-- Data Rows -->
                                <div class="space-y-3 pt-4">
                                    <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="text-gray-500 dark:text-zinc-400">Target Material</span>
                                        <span class="font-mono text-rose-950 dark:text-zinc-100 font-black">Aluminum Cans & Tins</span>
                                    </div>
                                    <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="text-gray-500 dark:text-zinc-400">Sensor Type</span>
                                        <span class="text-amber-600 font-mono font-black">NPN Inductive (4mm)</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 6. Section: Actuator States -->
                <div x-show="activeTab === 'all' || activeTab === 'actuators'" class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-rose-100 dark:border-zinc-800 p-8 lg:p-10 mb-10 space-y-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b border-rose-100/60 dark:border-zinc-800">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center font-black border border-rose-200 dark:border-rose-900/40 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-2xl lg:text-3xl font-black text-rose-950 dark:text-zinc-100 tracking-tight truncate">Actuator States</h2>
                                <p class="text-sm font-bold text-rose-600/80 dark:text-zinc-400 mt-1 truncate">Servos, DC motors, and vibration motor operational health</p>
                            </div>
                        </div>
                    </div>

                    <!-- 3-Column Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        
                        <!-- Servo Motors SG90 x 4 -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 lg:p-8 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-rose-500 dark:text-rose-400 mb-6 truncate">Servo Motors (SG90 x 4 Lids)</h3>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl text-center border border-rose-100/60 dark:border-zinc-800">
                                        <span class="block font-black text-xs text-rose-950 dark:text-zinc-100 truncate">Servo #1</span>
                                        <span class="block text-[10px] font-bold text-red-500 truncate mb-1">Hazardous Lid</span>
                                        <span class="text-base font-black font-mono text-red-500" x-text="servos.s1 + '°'"></span>
                                    </div>
                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl text-center border border-rose-100/60 dark:border-zinc-800">
                                        <span class="block font-black text-xs text-rose-950 dark:text-zinc-100 truncate">Servo #2</span>
                                        <span class="block text-[10px] font-bold text-emerald-500 truncate mb-1">Bio Lid</span>
                                        <span class="text-base font-black font-mono text-emerald-500" x-text="servos.s2 + '°'"></span>
                                    </div>
                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl text-center border border-rose-100/60 dark:border-zinc-800">
                                        <span class="block font-black text-xs text-rose-950 dark:text-zinc-100 truncate">Servo #3</span>
                                        <span class="block text-[10px] font-bold text-blue-500 truncate mb-1">Recyclable Lid</span>
                                        <span class="text-base font-black font-mono text-blue-500" x-text="servos.s3 + '°'"></span>
                                    </div>
                                    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl text-center border border-rose-100/60 dark:border-zinc-800">
                                        <span class="block font-black text-xs text-rose-950 dark:text-zinc-100 truncate">Servo #4</span>
                                        <span class="block text-[10px] font-bold text-zinc-400 truncate mb-1">Non-Bio Lid</span>
                                        <span class="text-base font-black font-mono text-zinc-400" x-text="servos.s4 + '°'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DC Motor -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 lg:p-8 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div>
                                <div class="flex items-center justify-between gap-4 mb-6">
                                    <h3 class="text-xs font-black uppercase tracking-widest text-rose-500 dark:text-rose-400 truncate">DC Motor (L298N)</h3>
                                    <span class="px-3 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-full text-[10px] font-black uppercase shrink-0">Online</span>
                                </div>

                                <div class="py-5 px-6 my-4 bg-white dark:bg-zinc-900 rounded-2xl border border-rose-100/60 dark:border-zinc-800 flex flex-col items-center justify-center text-center space-y-2">
                                    <div class="relative w-20 h-20 flex items-center justify-center mb-1">
                                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                            <path class="text-gray-200 dark:text-zinc-800" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                            <path class="text-rose-500 stroke-current" stroke-width="3" stroke-dasharray="47, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        </svg>
                                        <span class="absolute text-sm font-black font-mono text-rose-950 dark:text-zinc-100">113 RPM</span>
                                    </div>
                                    <span class="text-sm font-black text-rose-950 dark:text-zinc-100">Active Conveyor</span>
                                </div>

                                <div class="space-y-3 pt-4">
                                    <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="text-gray-500 dark:text-zinc-400">Direction</span>
                                        <span class="text-emerald-600 dark:text-emerald-400 font-mono font-black">Forward</span>
                                    </div>
                                    <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="text-gray-500 dark:text-zinc-400">Duty Cycle</span>
                                        <span class="font-mono text-rose-600 dark:text-rose-400 font-black">47%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vibration Motor -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 lg:p-8 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800" x-data="{ motorActive: false }">
                            <div>
                                <div class="flex items-center justify-between gap-4 mb-6">
                                    <h3 class="text-xs font-black uppercase tracking-widest text-rose-500 dark:text-rose-400 truncate">Vibration Motor</h3>
                                    <span class="px-3 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-full text-[10px] font-black uppercase shrink-0">Online</span>
                                </div>

                                <div class="py-5 px-6 my-4 bg-white dark:bg-zinc-900 rounded-2xl border border-rose-100/60 dark:border-zinc-800 flex flex-col items-center justify-center text-center space-y-2">
                                    <div class="w-14 h-14 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-1 shrink-0" :class="motorActive ? 'animate-bounce' : ''">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M4.93 4.93l14.14 14.14"/><path d="M19.07 4.93L4.93 19.07"/></svg>
                                    </div>
                                    <span class="text-base font-black" :class="motorActive ? 'text-rose-600' : 'text-emerald-600'" x-text="motorActive ? 'Vibrating...' : 'Idle'"></span>
                                </div>

                                <div class="space-y-3 pt-4">
                                    <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="text-gray-500 dark:text-zinc-400">Frequency</span>
                                        <span class="font-mono text-rose-950 dark:text-zinc-100 font-black" x-text="motorActive ? '180 Hz' : '0 Hz'"></span>
                                    </div>
                                    <div class="px-5 py-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="text-gray-500 dark:text-zinc-400">GPIO Pin</span>
                                        <span class="font-mono text-rose-950 dark:text-zinc-100 font-black">GPIO 18</span>
                                    </div>
                                </div>
                            </div>

                            <button type="button" @click="runTest('Vibration Motor', 'Pulse Agitation 3s'); motorActive = true; setTimeout(() => motorActive = false, 3000)" class="w-full py-3.5 bg-white dark:bg-zinc-900 border border-rose-200 dark:border-zinc-700 text-rose-600 dark:text-rose-400 rounded-xl font-black text-xs uppercase hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                <span>Pulse Agitation (3s)</span>
                            </button>
                        </div>

                    </div>
                </div>

                <!-- 7. Section: System Peripherals -->
                <div x-show="activeTab === 'all' || activeTab === 'peripherals'" class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-rose-100 dark:border-zinc-800 p-8 lg:p-10 mb-10 space-y-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b border-rose-100/60 dark:border-zinc-800">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center font-black border border-rose-200 dark:border-rose-900/40 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/></svg>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-2xl lg:text-3xl font-black text-rose-950 dark:text-zinc-100 tracking-tight truncate">System Peripherals</h2>
                                <p class="text-sm font-bold text-rose-600/80 dark:text-zinc-400 mt-1 truncate">Webcam, LEDs, power supply, and relay modules</p>
                            </div>
                        </div>
                    </div>

                    <!-- 4 Equal Cards Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        
                        <!-- Webcam -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-4">
                                    <h3 class="text-xs font-black uppercase tracking-widest text-rose-950 dark:text-zinc-100 truncate">Webcam (C270)</h3>
                                    <span class="px-3 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-full text-[10px] font-black uppercase shrink-0">Online</span>
                                </div>
                                <div class="relative w-full aspect-video bg-zinc-950 rounded-xl flex items-center justify-center my-3 overflow-hidden shadow-inner border border-zinc-800">
                                    <span class="absolute top-2 left-2 px-2 py-0.5 bg-red-600 text-white rounded text-[8px] font-black uppercase tracking-wider">LIVE</span>
                                    <span class="text-xs font-bold text-zinc-500 uppercase">Vision AI Feed</span>
                                </div>
                                <div class="space-y-3 pt-2">
                                    <div class="px-4 py-3 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="text-gray-500 dark:text-zinc-400">Res</span>
                                        <span class="font-mono text-rose-950 dark:text-zinc-100">1280x720</span>
                                    </div>
                                    <div class="px-4 py-3 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="text-gray-500 dark:text-zinc-400">FPS</span>
                                        <span class="text-emerald-600 dark:text-emerald-400 font-mono font-black">30 FPS</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- LED Arrays -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-rose-950 dark:text-zinc-100 mb-4 truncate">LED Arrays (PWM)</h3>
                                <div class="space-y-3">
                                    <div class="p-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="truncate">Stage #1</span>
                                        <span class="text-amber-500 font-mono font-black shrink-0">ON (85%)</span>
                                    </div>
                                    <div class="p-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="truncate">Bin Bay #2</span>
                                        <span class="text-amber-500 font-mono font-black shrink-0">ON (70%)</span>
                                    </div>
                                    <div class="p-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold opacity-60">
                                        <span class="truncate">Cam Zone #3</span>
                                        <span class="text-gray-400 font-mono shrink-0">OFF (0%)</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Power Supply -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-4">
                                    <h3 class="text-xs font-black uppercase tracking-widest text-rose-950 dark:text-zinc-100 truncate">Power (5V/3A)</h3>
                                    <span class="px-3 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-full text-[10px] font-black uppercase shrink-0">Online</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-center text-xs font-bold">
                                    <div class="p-3 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800">
                                        <span class="block text-[8px] text-gray-400 uppercase font-black mb-1">Voltage</span>
                                        <span class="font-mono text-rose-950 dark:text-zinc-100 text-xs font-black">5.03 V</span>
                                    </div>
                                    <div class="p-3 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800">
                                        <span class="block text-[8px] text-gray-400 uppercase font-black mb-1">Current</span>
                                        <span class="font-mono text-rose-600 dark:text-rose-400 text-xs font-black">1.8 A</span>
                                    </div>
                                    <div class="p-3 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800">
                                        <span class="block text-[8px] text-gray-400 uppercase font-black mb-1">Power</span>
                                        <span class="font-mono text-emerald-600 dark:text-emerald-400 text-xs font-black">9.1 W</span>
                                    </div>
                                    <div class="p-3 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800">
                                        <span class="block text-[8px] text-gray-400 uppercase font-black mb-1">Efficiency</span>
                                        <span class="font-mono text-sky-600 dark:text-sky-400 text-xs font-black">92%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Relays -->
                        <div class="bg-rose-50/50 dark:bg-zinc-950 rounded-2xl p-6 flex flex-col justify-between space-y-6 border border-rose-100/80 dark:border-zinc-800">
                            <div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-rose-950 dark:text-zinc-100 mb-4 truncate">Relays (3-Ch)</h3>
                                <div class="space-y-3">
                                    <div class="p-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="truncate">#1 Motor Power</span>
                                        <span class="text-emerald-600 dark:text-emerald-400 text-[10px] font-mono font-black shrink-0">CLOSED (ACTIVE)</span>
                                    </div>
                                    <div class="p-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="truncate">#2 LED Array</span>
                                        <span class="text-emerald-600 dark:text-emerald-400 text-[10px] font-mono font-black shrink-0">CLOSED (ACTIVE)</span>
                                    </div>
                                    <div class="p-3.5 bg-white dark:bg-zinc-900 rounded-xl border border-rose-100/60 dark:border-zinc-800 flex justify-between items-center text-xs font-bold">
                                        <span class="truncate">#3 Bio Grinder</span>
                                        <span class="text-amber-500 text-[10px] font-mono font-black shrink-0">OPEN (STANDBY)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center text-xs font-black text-gray-500 dark:text-zinc-400">3 / 3 Channels Configured</div>
                        </div>

                    </div>
                </div>

                <!-- 8. Section: Network & Connectivity -->
                <div x-show="activeTab === 'all' || activeTab === 'network'" class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-rose-100 dark:border-zinc-800 p-8 lg:p-10 mb-10 space-y-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b border-rose-100/60 dark:border-zinc-800">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center font-black border border-rose-200 dark:border-rose-900/40 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h.01"/><path d="M2 8.82a15 15 0 0 1 20 0"/><path d="M5 12.85a10 10 0 0 1 14 0"/><path d="M8.5 16.88a5 5 0 0 1 7 0"/></svg>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-2xl lg:text-3xl font-black text-rose-950 dark:text-zinc-100 tracking-tight truncate">Network & Connectivity</h2>
                                <p class="text-sm font-bold text-rose-600/80 dark:text-zinc-400 mt-1 truncate">WiFi signal strength and connection diagnostics</p>
                            </div>
                        </div>
                        <span class="px-3.5 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-black uppercase shrink-0">Online</span>
                    </div>

                    <!-- Spacious 2-Column Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <!-- Signal Quality Card -->
                        <div class="bg-gradient-to-b from-emerald-500/10 to-transparent dark:from-emerald-950/30 dark:to-zinc-950 border border-emerald-500/30 rounded-2xl p-8 flex flex-col items-center justify-center text-center space-y-4">
                            <div class="w-16 h-16 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-500/30 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h.01"/><path d="M2 8.82a15 15 0 0 1 20 0"/><path d="M5 12.85a10 10 0 0 1 14 0"/><path d="M8.5 16.88a5 5 0 0 1 7 0"/></svg>
                            </div>
                            <div>
                                <span class="text-4xl font-black text-rose-950 dark:text-zinc-100 font-mono">100%</span>
                                <span class="block text-xs font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mt-1">Signal Quality</span>
                            </div>
                            <span class="text-base font-black text-emerald-600 dark:text-emerald-400">Excellent Link</span>
                        </div>

                        <!-- Data Grid & Live RSSI -->
                        <div class="lg:col-span-2 space-y-6 flex flex-col justify-between">
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                <div class="p-4 bg-rose-50/50 dark:bg-zinc-950 border border-rose-100/60 dark:border-zinc-800 rounded-xl space-y-1">
                                    <span class="block text-[10px] font-black uppercase text-gray-400 dark:text-zinc-500 tracking-wider">SSID</span>
                                    <span class="block font-black text-xs text-rose-950 dark:text-zinc-100 truncate">KKK-Network</span>
                                </div>
                                <div class="p-4 bg-rose-50/50 dark:bg-zinc-950 border border-rose-100/60 dark:border-zinc-800 rounded-xl space-y-1">
                                    <span class="block text-[10px] font-black uppercase text-gray-400 dark:text-zinc-500 tracking-wider">IP Address</span>
                                    <span class="block font-black text-xs font-mono text-rose-950 dark:text-zinc-100 truncate">192.168.1.104</span>
                                </div>
                                <div class="p-4 bg-rose-50/50 dark:bg-zinc-950 border border-rose-100/60 dark:border-zinc-800 rounded-xl space-y-1">
                                    <span class="block text-[10px] font-black uppercase text-gray-400 dark:text-zinc-500 tracking-wider">RSSI</span>
                                    <span class="block font-black text-xs font-mono text-emerald-600 dark:text-emerald-400 truncate">-48 dBm</span>
                                </div>
                                <div class="p-4 bg-rose-50/50 dark:bg-zinc-950 border border-rose-100/60 dark:border-zinc-800 rounded-xl space-y-1">
                                    <span class="block text-[10px] font-black uppercase text-gray-400 dark:text-zinc-500 tracking-wider">Gateway</span>
                                    <span class="block font-black text-xs font-mono text-rose-950 dark:text-zinc-100 truncate">192.168.1.1</span>
                                </div>
                                <div class="p-4 bg-rose-50/50 dark:bg-zinc-955 border border-rose-100/60 dark:border-zinc-800 rounded-xl space-y-1">
                                    <span class="block text-[10px] font-black uppercase text-gray-400 dark:text-zinc-500 tracking-wider">DNS</span>
                                    <span class="block font-black text-xs font-mono text-rose-950 dark:text-zinc-100 truncate">8.8.8.8</span>
                                </div>
                                <div class="p-4 bg-rose-50/50 dark:bg-zinc-955 border border-rose-100/60 dark:border-zinc-800 rounded-xl space-y-1">
                                    <span class="block text-[10px] font-black uppercase text-gray-400 dark:text-zinc-500 tracking-wider">Ping</span>
                                    <span class="block font-black text-xs font-mono text-emerald-600 dark:text-emerald-400 truncate">8 ms</span>
                                </div>
                            </div>

                            <div class="p-5 bg-rose-50/50 dark:bg-zinc-955 border border-rose-100/60 dark:border-zinc-800 rounded-xl space-y-2">
                                <div class="flex justify-between items-center text-xs font-bold">
                                    <span class="text-rose-950 dark:text-zinc-100">Live RSSI Progress</span>
                                    <span class="text-emerald-600 dark:text-emerald-400 font-mono font-black">-48 dBm</span>
                                </div>
                                <div class="h-2.5 w-full bg-gray-200 dark:bg-zinc-800 rounded-full overflow-hidden p-0.5">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 88%"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </main>
    </div>
</x-app-layout>
