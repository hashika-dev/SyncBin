<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-rose-50">
                <div class="p-8 text-gray-900 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Hello, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-500 mt-1">Welcome back to your SyncBin minimalist workspace.</p>
                    </div>
                    <div class="hidden sm:block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard opacity-20"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                    </div>
                </div>
            </div>

            <!-- Security Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-rose-50">
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-rose-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">Account Security</h3>
                    </div>

                    <div class="flex flex-col md:flex-row items-center justify-between gap-6 p-6 rounded-2xl bg-gray-50/50 border border-gray-100">
                        <div class="flex items-center gap-4">
                            @if(Auth::user()->twoFactorAuth()->exists() && Auth::user()->twoFactorAuth->enabled_at)
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 shadow-sm border-2 border-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-keyhole"><circle cx="12" cy="16" r="1"/><rect width="18" height="12" x="3" y="10" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Two-Factor Authentication is ON</h4>
                                    <p class="text-sm text-gray-500">Your account is protected by an extra layer of security.</p>
                                </div>
                            @else
                                <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center text-rose-600 shadow-sm border-2 border-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-keyhole-open"><circle cx="12" cy="16" r="1"/><rect width="18" height="12" x="3" y="10" rx="2"/><path d="M7 10V7a5 5 0 0 1 9.33-2.5"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Two-Factor Authentication is OFF</h4>
                                    <p class="text-sm text-gray-500">Enable MFA to better protect your SyncBin account.</p>
                                </div>
                            @endif
                        </div>

                        <div>
                            @if(Auth::user()->twoFactorAuth()->exists() && Auth::user()->twoFactorAuth->enabled_at)
                                <form method="POST" action="{{ route('2fa.disable') }}">
                                    @csrf
                                    <button type="submit" class="px-6 py-2.5 bg-white border border-rose-200 text-rose-600 font-bold rounded-xl hover:bg-rose-50 transition-colors shadow-sm active:scale-[0.98]">
                                        Disable MFA
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('2fa.setup') }}" class="inline-block px-6 py-2.5 bg-rose-500 text-white font-bold rounded-xl hover:bg-rose-600 transition-all shadow-lg shadow-rose-200 active:scale-[0.98]">
                                    Setup MFA
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
