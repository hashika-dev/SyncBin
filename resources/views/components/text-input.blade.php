@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border border-white/80 dark:border-white/10 rounded-xl focus:ring-4 focus:ring-emerald-500/15 focus:border-emerald-500 dark:focus:border-emerald-400 outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 bg-white/80 dark:bg-white/[0.04] text-slate-900 dark:text-slate-100 shadow-sm text-sm backdrop-blur-md']) }}>
