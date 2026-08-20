<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">
            {{ __('Two-Factor Authentication (MFA)') }}
        </h2>

        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            {{ __('Secure your account by adding an extra layer of defense. When enabled, you will be prompted for a secure 6-digit verification code from your authenticator app during login.') }}
        </p>
    </header>

    <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-sm border {{ auth()->user()->twoFactorAuth()->exists() && auth()->user()->twoFactorAuth->first()->enabled_at ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : 'bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-800' }}">
                @if(auth()->user()->twoFactorAuth()->exists() && auth()->user()->twoFactorAuth->first()->enabled_at)
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 11 2 2 4-4"/></svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                @endif
            </div>
            <div>
                <span class="block text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Current Status</span>
                @if(auth()->user()->twoFactorAuth()->exists() && auth()->user()->twoFactorAuth->first()->enabled_at)
                    <span class="inline-flex items-center gap-1.5 text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Active & Protected
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 text-xs font-mono font-bold text-amber-600 dark:text-amber-400 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Inactive / Unsecured
                    </span>
                @endif
            </div>
        </div>

        <div>
            @if(auth()->user()->twoFactorAuth()->exists() && auth()->user()->twoFactorAuth->first()->enabled_at)
                <form method="POST" action="{{ route('2fa.disable') }}" onsubmit="return confirm('Are you sure you want to disable Multi-Factor Authentication? Your account will be less secure.');">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-500 text-white text-xs font-mono font-bold uppercase tracking-wider rounded-xl transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
                        Disable MFA
                    </button>
                </form>
            @else
                <a href="{{ route('2fa.setup') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-mono font-bold uppercase tracking-wider rounded-xl transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Enable MFA
                </a>
            @endif
        </div>
    </div>
</section>
