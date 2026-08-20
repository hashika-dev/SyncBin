<div class="relative w-full p-5 sm:p-7 rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 shadow-xl dark:shadow-2xl overflow-hidden text-slate-700 dark:text-slate-200">
    <style>
        @keyframes trash-to-hole {
            0% { transform: translate(-40px, -40px) scale(0.5); opacity: 0; }
            10% { transform: translate(0, 0) scale(1); opacity: 1; }
            25% { transform: translate(0, 0) scale(1); opacity: 1; }
            35% { transform: translate(0, 5px) scale(0); opacity: 0; }
            100% { transform: translate(0, 5px) scale(0); opacity: 0; }
        }

        @keyframes item-appear {
            0%, 40% { transform: translateY(-20px); opacity: 0; }
            50% { transform: translateY(0); opacity: 1; }
            90% { transform: translateY(0); opacity: 1; }
            100% { transform: translateY(10px); opacity: 0; }
        }

        @keyframes led-red {
            0%, 25% { background-color: #ef4444; box-shadow: 0 0 10px rgba(239,68,68,0.8); }
            26%, 100% { background-color: #450a0a; box-shadow: none; }
        }

        @keyframes led-yellow {
            0%, 25% { background-color: #422006; box-shadow: none; }
            26%, 50% { background-color: #eab308; box-shadow: 0 0 10px rgba(234,179,8,0.8); }
            51%, 100% { background-color: #422006; box-shadow: none; }
        }

        @keyframes led-green {
            0%, 50% { background-color: #064e3b; box-shadow: none; }
            51%, 75% { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.8); }
            76%, 100% { background-color: #064e3b; box-shadow: none; }
        }

        .animate-trash-1 { animation: trash-to-hole 8s infinite; }
        .animate-trash-2 { animation: trash-to-hole 8s infinite 2s; }
        .animate-trash-3 { animation: trash-to-hole 8s infinite 4s; }
        .animate-trash-4 { animation: trash-to-hole 8s infinite 6s; }

        .appear-1 { animation: item-appear 8s infinite; }
        .appear-2 { animation: item-appear 8s infinite 2s; }
        .appear-3 { animation: item-appear 8s infinite 4s; }
        .appear-4 { animation: item-appear 8s infinite 6s; }

        .led-r { animation: led-red 8s infinite; }
        .led-y { animation: led-yellow 8s infinite; }
        .led-g { animation: led-green 8s infinite; }
    </style>

    <!-- Header / System Status Banner -->
    <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-2">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-[11px] font-bold tracking-wider text-slate-700 dark:text-slate-300 uppercase">Automated Telemetry Chamber</span>
        </div>
        <span class="px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60 text-[10px] font-mono font-bold tracking-wider">
            OPTICAL AI ACTIVE
        </span>
    </div>

    <!-- Main Chamber Enclosure -->
    <div class="relative">
        <div class="bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col h-64 w-full shadow-inner">
            
            <!-- Intake & Sensor Array Bar -->
            <div class="h-14 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-5 shrink-0 bg-white dark:bg-slate-900/90">
                <!-- Intake Hatch with Animated Deposit Items -->
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="w-16 h-7 bg-slate-100 dark:bg-slate-950 border border-slate-300 dark:border-slate-700/80 rounded-lg shadow-inner flex items-center justify-center overflow-visible">
                            <!-- Animated Trash Item 1: Hazardous -->
                            <div class="absolute -top-10 -left-10 animate-trash-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-skull"><path d="M9 10h.01"/><path d="M15 10h.01"/><path d="M12 2a8 8 0 0 0-8 8v1a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4v-1a8 8 0 0 0-8-8z"/><path d="M9 14v3a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-3"/></svg>
                            </div>
                            <!-- Animated Trash Item 2: Recyclable -->
                            <div class="absolute -top-10 -left-10 animate-trash-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                            </div>
                            <!-- Animated Trash Item 3: Biodegradable -->
                            <div class="absolute -top-10 -left-10 animate-trash-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/></svg>
                            </div>
                            <!-- Animated Trash Item 4: Non-Biodegradable -->
                            <div class="absolute -top-10 -left-10 animate-trash-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            </div>
                        </div>
                        <span class="absolute -bottom-4 left-0 text-[7px] font-mono font-bold text-slate-400 dark:text-slate-500 uppercase">Intake</span>
                    </div>

                    <!-- Sensor Array Status LEDs -->
                    <div class="flex gap-2 ml-1">
                        <div class="flex flex-col items-center gap-0.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-950 led-r transition-all"></div>
                            <span class="text-[6px] font-mono font-bold text-slate-400 dark:text-slate-500 uppercase">SCAN</span>
                        </div>
                        <div class="flex flex-col items-center gap-0.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-yellow-950 led-y transition-all"></div>
                            <span class="text-[6px] font-mono font-bold text-slate-400 dark:text-slate-500 uppercase">SORT</span>
                        </div>
                        <div class="flex flex-col items-center gap-0.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-green-950 led-g transition-all"></div>
                            <span class="text-[6px] font-mono font-bold text-slate-400 dark:text-slate-500 uppercase">PASS</span>
                        </div>
                    </div>
                </div>

                <!-- Servo Controller Pill -->
                <div class="hidden sm:flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 dark:bg-slate-950 rounded border border-slate-200 dark:border-slate-800">
                    <span class="font-mono text-[9px] text-cyan-600 dark:text-cyan-400 font-bold">SERVO-01: READY</span>
                </div>
            </div>

            <!-- The 4 Segregation Chutes -->
            <div class="grid grid-cols-4 flex-grow divide-x divide-slate-200 dark:divide-slate-800/80 bg-slate-50/60 dark:bg-slate-950/40">
                <!-- Column 1: Hazardous -->
                <div class="flex flex-col justify-between p-3 relative bg-red-50/40 dark:bg-red-950/10 hover:bg-red-100/40 dark:hover:bg-red-950/20 transition-colors">
                    <div class="flex-grow flex flex-col items-center justify-center">
                        <div class="appear-1 opacity-0">
                            <div class="p-2 bg-white dark:bg-slate-900 rounded-lg border border-red-200 dark:border-red-900/60 shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" class="lucide lucide-skull"><path d="M9 10h.01"/><path d="M15 10h.01"/><path d="M12 2a8 8 0 0 0-8 8v1a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4v-1a8 8 0 0 0-8-8z"/><path d="M9 14v3a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-3"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="text-center font-bold text-red-600 dark:text-red-400 text-[8px] sm:text-[9px] uppercase tracking-wider">
                        Hazardous
                    </div>
                </div>

                <!-- Column 2: Recyclable -->
                <div class="flex flex-col justify-between p-3 relative bg-sky-50/40 dark:bg-sky-950/10 hover:bg-sky-100/40 dark:hover:bg-sky-950/20 transition-colors">
                    <div class="flex-grow flex flex-col items-center justify-center">
                        <div class="appear-2 opacity-0">
                            <div class="p-2 bg-white dark:bg-slate-900 rounded-lg border border-sky-200 dark:border-sky-900/60 shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2"><path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="text-center font-bold text-sky-600 dark:text-sky-400 text-[8px] sm:text-[9px] uppercase tracking-wider">
                        Recyclable
                    </div>
                </div>

                <!-- Column 3: Biodegradable -->
                <div class="flex flex-col justify-between p-3 relative bg-emerald-50/40 dark:bg-emerald-950/10 hover:bg-emerald-100/40 dark:hover:bg-emerald-950/20 transition-colors">
                    <div class="flex-grow flex flex-col items-center justify-center">
                        <div class="appear-3 opacity-0">
                            <div class="p-2 bg-white dark:bg-slate-900 rounded-lg border border-emerald-200 dark:border-emerald-900/60 shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="text-center font-bold text-emerald-600 dark:text-emerald-400 text-[8px] sm:text-[9px] uppercase tracking-wider">
                        Bio-Waste
                    </div>
                </div>

                <!-- Column 4: Non-Biodegradable -->
                <div class="flex flex-col justify-between p-3 relative bg-amber-50/40 dark:bg-amber-950/10 hover:bg-amber-100/40 dark:hover:bg-amber-950/20 transition-colors">
                    <div class="flex-grow flex flex-col items-center justify-center">
                        <div class="appear-4 opacity-0">
                            <div class="p-2 bg-white dark:bg-slate-900 rounded-lg border border-amber-200 dark:border-amber-900/60 shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="text-center font-bold text-amber-600 dark:text-amber-400 text-[8px] sm:text-[9px] uppercase tracking-wider">
                        General
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
