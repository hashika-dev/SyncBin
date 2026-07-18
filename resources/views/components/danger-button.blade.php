<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3.5 bg-red-500 hover:bg-red-600 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:shadow-lg hover:shadow-red-100 dark:hover:shadow-none focus:outline-none focus:ring-4 focus:ring-red-100 dark:focus:ring-red-950/30 transition-all duration-300 active:scale-95']) }}>
    {{ $slot }}
</button>
