<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white font-mono font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all duration-200']) }}>
    {{ $slot }}
</button>
