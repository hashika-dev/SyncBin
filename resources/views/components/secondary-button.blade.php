<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3 bg-white/70 dark:bg-white/[0.05] border border-slate-200/80 dark:border-white/10 rounded-xl font-mono font-bold text-xs text-slate-700 dark:text-slate-200 uppercase tracking-wider backdrop-blur-md shadow-sm hover:bg-white dark:hover:bg-white/10 hover:border-slate-300 dark:hover:border-white/20 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 disabled:opacity-25 transition-all duration-200']) }}>
    {{ $slot }}
</button>
