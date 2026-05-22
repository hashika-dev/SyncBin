<div class="relative w-full p-6 sm:p-8 rounded-[2rem] bg-gradient-to-br from-pink-50 to-orange-50 shadow-xl border border-white/60 overflow-hidden">
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
            0%, 50% { background-color: #422006; box-shadow: none; }
            51%, 75% { background-color: #eab308; box-shadow: 0 0 8px #eab308; }
            76%, 100% { background-color: #422006; box-shadow: none; }
        }

        @keyframes led-green {
            0%, 75% { background-color: #064e3b; box-shadow: none; }
            76%, 95% { background-color: #22c55e; box-shadow: 0 0 8px #22c55e; }
            96%, 100% { background-color: #064e3b; box-shadow: none; }
        }

        .animate-trash-1 { animation: trash-to-hole 6s infinite; }
        .animate-trash-2 { animation: trash-to-hole 6s infinite 2s; }
        .animate-trash-3 { animation: trash-to-hole 6s infinite 4s; }

        .appear-1 { animation: item-appear 6s infinite; }
        .appear-2 { animation: item-appear 6s infinite 2s; }
        .appear-3 { animation: item-appear 6s infinite 4s; }

        .led-r { animation: led-red 6s infinite; }
        .led-y { animation: led-yellow 6s infinite; }
        .led-g { animation: led-green 6s infinite; }
    </style>

    <!-- Title -->
    <div class="flex justify-center mb-6">
        <h3 class="text-[10px] font-bold tracking-[0.2em] text-rose-400 uppercase">
            SyncBin in Action
        </h3>
    </div>

    <!-- Main Window Container -->
    <div class="relative px-2">
        
        <!-- Main Mock Window -->
        <div class="bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col h-64 w-full">
            
            <!-- Header Bar with Hole and LEDs -->
            <div class="h-14 border-b border-gray-100 flex items-center justify-between px-6 shrink-0 bg-gray-50/50 backdrop-blur-sm">
                <!-- Left: The Entry Hole -->
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="w-16 h-6 bg-slate-900 rounded-full shadow-inner flex items-center justify-center overflow-visible">
                            <!-- Animated Trash Item Entering -->
                            <div class="absolute -top-10 -left-10 animate-trash-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/></svg>
                            </div>
                        </div>
                        <span class="absolute -bottom-5 left-0 text-[7px] font-bold text-slate-400 uppercase tracking-tighter">Entry Hole</span>
                    </div>

                    <!-- The 3 LEDs -->
                    <div class="flex gap-1.5 ml-2">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-950 led-r transition-all"></div>
                            <span class="text-[5px] font-bold text-slate-400 uppercase">Scan</span>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-2.5 h-2.5 rounded-full bg-yellow-950 led-y transition-all"></div>
                            <span class="text-[5px] font-bold text-slate-400 uppercase">Ready</span>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-2.5 h-2.5 rounded-full bg-green-950 led-g transition-all"></div>
                            <span class="text-[5px] font-bold text-slate-400 uppercase">In</span>
                        </div>
                    </div>
                </div>

                <!-- Status Pill -->
                <div class="hidden sm:block px-3 py-1 bg-rose-50 rounded-full border border-rose-100">
                    <span class="text-[8px] font-bold text-rose-400 uppercase tracking-widest">System Active</span>
                </div>
            </div>

            <!-- The 3 Segregation Columns -->
            <div class="grid grid-cols-3 flex-grow divide-x divide-white/50">
                <!-- Column 1: Bio Degradable -->
                <div class="bg-green-50/60 flex flex-col justify-between p-4 relative group">
                    <div class="flex-grow flex flex-col items-center justify-center gap-2">
                        <!-- Appearing Item -->
                        <div class="appear-1 opacity-0">
                            <div class="p-2 bg-white rounded-lg shadow-sm border border-green-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="text-center font-bold text-green-600 text-[8px] sm:text-[9px] uppercase tracking-wide whitespace-nowrap z-10">
                        Bio Degradable
                    </div>
                </div>

                <!-- Column 2: Non-Bio Degradable -->
                <div class="bg-orange-50/60 flex flex-col justify-between p-4 relative group">
                    <div class="flex-grow flex flex-col items-center justify-center gap-2">
                        <!-- Appearing Item -->
                        <div class="appear-2 opacity-0">
                            <div class="p-2 bg-white rounded-lg shadow-sm border border-orange-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="text-center font-bold text-orange-500 text-[8px] sm:text-[9px] uppercase tracking-wide whitespace-nowrap z-10">
                        Non-Bio Degradable
                    </div>
                </div>

                <!-- Column 3: Recyclable -->
                <div class="bg-blue-50/60 flex flex-col justify-between p-4 relative group">
                    <div class="flex-grow flex flex-col items-center justify-center gap-2">
                        <!-- Appearing Item -->
                        <div class="appear-3 opacity-0">
                            <div class="p-2 bg-white rounded-lg shadow-sm border border-blue-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="text-center font-bold text-blue-500 text-[8px] sm:text-[9px] uppercase tracking-wide whitespace-nowrap z-10">
                        Recyclable
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
