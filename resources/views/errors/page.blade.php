{{--
    Shared EcoSync error page. Each errors/{code}.blade.php includes this with:
    $code (int), $title (string), $message (string).
    Never print exception details here: production must not leak internals.
--}}
<x-guest-layout>
    <div class="text-center">
        <div class="mx-auto mb-5 w-14 h-14 rounded-2xl flex items-center justify-center border
                    {{ $code >= 500
                        ? 'bg-rose-50 dark:bg-rose-500/10 border-rose-200 dark:border-rose-400/30 text-rose-600 dark:text-rose-400'
                        : 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-400/30 text-emerald-600 dark:text-emerald-400' }}">
            @if ($code >= 500)
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            @elseif (in_array($code, [401, 403]))
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            @endif
        </div>

        <p class="text-xs font-mono font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
            Error {{ $code }}
        </p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
            {{ $title }}
        </h1>
        <p class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-300">
            {{ $message }}
        </p>

        <div class="mt-7 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center gap-2 py-2.5 px-5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                Go to EcoSync
            </a>
            <button type="button" onclick="history.length > 1 ? history.back() : (window.location.href = '{{ url('/') }}')"
                    class="inline-flex items-center justify-center gap-2 py-2.5 px-5 rounded-2xl bg-white/70 dark:bg-white/10 hover:bg-white/90 dark:hover:bg-white/20 border border-white/80 dark:border-white/15 text-slate-800 dark:text-slate-200 text-sm font-semibold backdrop-blur-md transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                Go back
            </button>
        </div>
    </div>
</x-guest-layout>
