<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3.5 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl font-bold text-xs text-gray-700 dark:text-zinc-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-4 focus:ring-rose-50 dark:focus:ring-rose-950/20 disabled:opacity-25 transition-all duration-300 active:scale-95']) }}>
    {{ $slot }}
</button>
