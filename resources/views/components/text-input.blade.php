@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-4 focus:ring-rose-100 dark:focus:ring-rose-950/30 focus:border-rose-400 dark:focus:border-rose-500 outline-none transition-all placeholder:text-gray-300 dark:placeholder:text-zinc-650 bg-gray-50/50 dark:bg-zinc-800/50 hover:bg-white dark:hover:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-800 text-gray-900 dark:text-zinc-100 shadow-sm']) }}>
