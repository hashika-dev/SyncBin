<div class="relative w-full p-4 sm:p-8 rounded-[2rem] bg-gradient-to-br from-pink-50 via-rose-50/50 to-orange-50 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950 shadow-xl border border-white/60 dark:border-zinc-800/80 overflow-hidden transition-colors duration-300">
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
            0%, 25% { background-color: #ef4444; box-shadow: 0 0 8px #ef4444; }
            26%, 100% { background-color: #450a0a; box-shadow: none; }
        }

        @keyframes led-yellow {
            0%, 25% { background-color: #422006; box-shadow: none; }
            26%, 50% { background-color: #eab308; box-shadow: 0 0 8px #eab308; }
            51%, 100% { background-color: #422006; box-shadow: none; }
        }

        @keyframes led-green {
            0%, 50% { background-color: #064e3b; box-shadow: none; }
            51%, 75% { background-color: #22c55e; box-shadow: 0 0 8px #22c55e; }
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

    <!-- Title -->
    <div class="flex justify-center mb-6">
        <h3 class="text-[10px] font-bold tracking-[0.2em] text-rose-400 dark:text-rose-400/90 uppercase">
            SyncBin in Action
        </h3>
    </div>

    <!-- Main Window Container -->
    <div class="relative px-2">
        
        <!-- Main Mock Window -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-2xl dark:shadow-none border border-gray-100 dark:border-zinc-800 overflow-hidden flex flex-col h-64 w-full transition-colors duration-300">
            
            <!-- Header Bar with Hole and LEDs -->
            <div class="h-14 border-b border-gray-100 dark:border-zinc-800 flex items-center justify-between px-6 shrink-0 bg-gray-50/50 dark:bg-zinc-950/60 backdrop-blur-sm">
                <!-- Left: The Entry Hole -->
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="w-16 h-6 bg-slate-900 dark:bg-zinc-950 border border-slate-700/50 dark:border-zinc-800 rounded-full shadow-inner flex items-center justify-center overflow-visible">
                            <!-- Animated Trash Item 1: Hazardous -->
                            <div class="absolute -top-10 -left-10 animate-trash-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-skull"><path d="M9 10h.01"/><path d="M15 10h.01"/><path d="M12 2a8 8 0 0 0-8 8v1a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4v-1a8 8 0 0 0-8-8z"/><path d="M9 14v3a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-3"/></svg>
                            </div>
                            <!-- Animated Trash Item 2: Recyclable -->
                            <div class="absolute -top-10 -left-10 animate-trash-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                            </div>
                            <!-- Animated Trash Item 3: Biodegradable -->
                            <div class="absolute -top-10 -left-10 animate-trash-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/></svg>
                            </div>
                            <!-- Animated Trash Item 4: Non-Biodegradable -->
                            <div class="absolute -top-10 -left-10 animate-trash-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            </div>
                        </div>
                        <span class="absolute -bottom-5 left-0 text-[7px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-tighter">Entry Hole</span>
                    </div>

                    <!-- The 3 LEDs -->
                    <div class="flex gap-1.5 ml-2">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-950 led-r transition-all"></div>
                            <span class="text-[5px] font-bold text-slate-400 dark:text-zinc-500 uppercase">Scan</span>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-2.5 h-2.5 rounded-full bg-yellow-950 led-y transition-all"></div>
                            <span class="text-[5px] font-bold text-slate-400 dark:text-zinc-500 uppercase">Ready</span>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-2.5 h-2.5 rounded-full bg-green-950 led-g transition-all"></div>
                            <span class="text-[5px] font-bold text-slate-400 dark:text-zinc-500 uppercase">In</span>
                        </div>
                    </div>
                </div>

                <!-- Status Pill -->
                <div class="hidden sm:block px-3 py-1 bg-rose-50 dark:bg-rose-950/40 rounded-full border border-rose-100 dark:border-rose-900/50">
                    <span class="text-[8px] font-bold text-rose-500 dark:text-rose-400 uppercase tracking-widest">System Active</span>
                </div>
            </div>

            <!-- The 4 Segregation Columns -->
            <div class="grid grid-cols-4 flex-grow divide-x divide-gray-100 dark:divide-zinc-800">
                <!-- Column 1: Hazardous -->
                <div class="bg-red-50/60 dark:bg-red-950/20 flex flex-col justify-between p-4 relative group">
                    <div class="flex-grow flex flex-col items-center justify-center gap-2">
                        <!-- Appearing Item -->
                        <div class="appear-1 opacity-0">
                            <div class="p-2 bg-white dark:bg-zinc-900 rounded-lg shadow-sm border border-red-100 dark:border-red-900/50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" class="lucide lucide-skull"><path d="M9 10h.01"/><path d="M15 10h.01"/><path d="M12 2a8 8 0 0 0-8 8v1a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4v-1a8 8 0 0 0-8-8z"/><path d="M9 14v3a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-3"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="text-center font-bold text-red-600 dark:text-red-400 text-[8px] sm:text-[9px] uppercase tracking-wide whitespace-nowrap z-10">
                        Hazardous
                    </div>
                </div>

                <!-- Column 2: Recyclable -->
                <div class="bg-blue-50/60 dark:bg-blue-950/20 flex flex-col justify-between p-4 relative group">
                    <div class="flex-grow flex flex-col items-center justify-center gap-2">
                        <!-- Appearing Item -->
                        <div class="appear-2 opacity-0">
                            <div class="p-2 bg-white dark:bg-zinc-900 rounded-lg shadow-sm border border-blue-100 dark:border-blue-900/50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="text-center font-bold text-blue-500 dark:text-blue-400 text-[8px] sm:text-[9px] uppercase tracking-wide whitespace-nowrap z-10">
                        Recyclable
                    </div>
                </div>

                <!-- Column 3: Bio Degradable -->
                <div class="bg-green-50/60 dark:bg-emerald-950/20 flex flex-col justify-between p-4 relative group">
                    <div class="flex-grow flex flex-col items-center justify-center gap-2">
                        <!-- Appearing Item -->
                        <div class="appear-3 opacity-0">
                            <div class="p-2 bg-white dark:bg-zinc-900 rounded-lg shadow-sm border border-green-100 dark:border-emerald-900/50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="text-center font-bold text-green-600 dark:text-emerald-400 text-[8px] sm:text-[9px] uppercase tracking-wide whitespace-nowrap z-10">
                        Biodegradable
                    </div>
                </div>

                <!-- Column 4: Non-Bio Degradable -->
                <div class="bg-orange-50/60 dark:bg-orange-950/20 flex flex-col justify-between p-4 relative group">
                    <div class="flex-grow flex flex-col items-center justify-center gap-2">
                        <!-- Appearing Item -->
                        <div class="appear-4 opacity-0">
                            <div class="p-2 bg-white dark:bg-zinc-900 rounded-lg shadow-sm border border-orange-100 dark:border-orange-900/50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="text-center font-bold text-orange-500 dark:text-orange-400 text-[8px] sm:text-[9px] uppercase tracking-wide whitespace-nowrap z-10">
                        Non-Bio
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
