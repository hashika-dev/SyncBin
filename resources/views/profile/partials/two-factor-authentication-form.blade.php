<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-zinc-100">
            {{ __('Two-Factor Authentication (MFA)') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-zinc-400">
            {{ __('Secure your account by adding an extra layer of defense. When enabled, you will be prompted for a secure 6-digit verification code from your authenticator app during login.') }}
        </p>
    </header>

    <div class="p-6 bg-gray-50 dark:bg-zinc-800/50 rounded-2xl border border-gray-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm border {{ auth()->user()->twoFactorAuth()->exists() && auth()->user()->twoFactorAuth->first()->enabled_at ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/30' : 'bg-rose-50 dark:bg-rose-950/20 text-rose-500 dark:text-rose-400 border-rose-100 dark:border-rose-900/30' }}">
                @if(auth()->user()->twoFactorAuth()->exists() && auth()->user()->twoFactorAuth->first()->enabled_at)
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 11 2 2 4-4"/></svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                @endif
            </div>
            <div>
                <span class="block text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-zinc-500">Current Status</span>
                @if(auth()->user()->twoFactorAuth()->exists() && auth()->user()->twoFactorAuth->first()->enabled_at)
                    <span class="inline-flex items-center gap-1.5 text-sm font-black text-emerald-600 dark:text-emerald-400 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Active & Protected
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 text-sm font-black text-rose-500 dark:text-rose-400 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                        Inactive / Unsecured
                    </span>
                @endif
            </div>
        </div>

        <div>
            @if(auth()->user()->twoFactorAuth()->exists() && auth()->user()->twoFactorAuth->first()->enabled_at)
                <form method="POST" action="{{ route('2fa.disable') }}" onsubmit="return confirm('Are you sure you want to disable Multi-Factor Authentication? Your account will be less secure.');">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all shadow-md shadow-rose-100 hover:shadow-rose-200 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
                        Disable MFA
                    </button>
                </form>
            @else
                <a href="{{ route('2fa.setup') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all shadow-md shadow-rose-100 hover:shadow-rose-200 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Enable MFA
                </a>
            @endif
        </div>
    </div>
</section>
