<x-app-layout>
    @slot('hideNav')
        true
    @endslot

    <div class="flex flex-col lg:flex-row min-h-screen w-full bg-gradient-to-br from-rose-50 via-white to-orange-50/50 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950 text-gray-900 dark:text-zinc-100 transition-colors duration-300" 
         x-data="{ 
             sidebarOpen: false, 
             activeTab: 'profile',
             init() {
                 if (window.location.hash) {
                     const hash = window.location.hash.replace('#', '');
                     if (['profile', 'security', 'sms', 'email', 'danger'].includes(hash)) {
                         this.activeTab = hash;
                     }
                 }
             },
             setTab(tab) {
                 this.activeTab = tab;
                 window.location.hash = tab;
             }
         }">
        <!-- Sidebar -->
        @include('layouts.sidebar', ['active' => 'settings'])

        <!-- Main Content -->
        <main class="flex-1 w-full min-w-0 ml-0 lg:ml-72 p-4 sm:p-6 lg:p-10 xl:p-12">
            <!-- Header -->
            <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
                <div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-rose-950 dark:text-zinc-50 tracking-tighter">Profile Settings</h1>
                    <p class="text-rose-600 dark:text-rose-400 mt-1 sm:mt-2 font-bold text-sm sm:text-base lg:text-lg opacity-80">
                        Manage your account information, password, and security settings
                    </p>
                </div>
            </header>

            <!-- Status Alert Message -->
            @if (session('status'))
                <div class="mb-8 p-5 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 rounded-2xl flex items-center gap-4 text-emerald-800 dark:text-emerald-400 animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    <span class="font-bold text-sm">{{ session('status') }}</span>
                </div>
            @endif

            <!-- Navigation Tabs -->
            <div class="max-w-4xl mb-8">
                <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto p-1.5 bg-white/60 dark:bg-zinc-900/60 backdrop-blur-xl border border-rose-100 dark:border-zinc-800 rounded-2xl shadow-sm scrollbar-none">
                    <!-- Profile Tab -->
                    <button type="button" @click="setTab('profile')"
                        :class="activeTab === 'profile' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/25 border-rose-500' : 'text-zinc-600 dark:text-zinc-400 hover:text-rose-950 dark:hover:text-zinc-100 hover:bg-rose-500/10 border-transparent'"
                        class="flex items-center gap-2.5 px-4 sm:px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition-all duration-200 whitespace-nowrap border shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>Profile & Password</span>
                    </button>

                    <!-- Security Tab -->
                    <button type="button" @click="setTab('security')"
                        :class="activeTab === 'security' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/25 border-rose-500' : 'text-zinc-600 dark:text-zinc-400 hover:text-rose-950 dark:hover:text-zinc-100 hover:bg-rose-500/10 border-transparent'"
                        class="flex items-center gap-2.5 px-4 sm:px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition-all duration-200 whitespace-nowrap border shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span>Security & 2FA</span>
                    </button>

                    <!-- SMS Alerts Tab -->
                    <button type="button" @click="setTab('sms')"
                        :class="activeTab === 'sms' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/25 border-rose-500' : 'text-zinc-600 dark:text-zinc-400 hover:text-rose-950 dark:hover:text-zinc-100 hover:bg-rose-500/10 border-transparent'"
                        class="flex items-center gap-2.5 px-4 sm:px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition-all duration-200 whitespace-nowrap border shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>SMS Alerts</span>
                    </button>

                    <!-- Email Reports Tab -->
                    <button type="button" @click="setTab('email')"
                        :class="activeTab === 'email' ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/25 border-sky-500' : 'text-zinc-600 dark:text-zinc-400 hover:text-sky-950 dark:hover:text-zinc-100 hover:bg-sky-500/10 border-transparent'"
                        class="flex items-center gap-2.5 px-4 sm:px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition-all duration-200 whitespace-nowrap border shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <span>Email Reports</span>
                    </button>

                    <!-- Danger Zone Tab -->
                    <button type="button" @click="setTab('danger')"
                        :class="activeTab === 'danger' ? 'bg-rose-700 text-white shadow-lg shadow-rose-700/25 border-rose-700' : 'text-zinc-600 dark:text-zinc-400 hover:text-rose-700 dark:hover:text-rose-400 hover:bg-rose-500/10 border-transparent'"
                        class="flex items-center gap-2.5 px-4 sm:px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition-all duration-200 whitespace-nowrap border shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                        <span>Danger Zone</span>
                    </button>
                </div>
            </div>

            <!-- Tab Content Sections -->
            <div class="max-w-4xl pb-16 sm:pb-24">
                <!-- TAB 1: Profile & Password -->
                <div x-show="activeTab === 'profile'"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-8 sm:space-y-12">
                    
                    <!-- Profile Information Card -->
                    <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-3xl lg:rounded-[2.5rem] shadow-xl dark:shadow-none border border-white dark:border-zinc-800 p-6 sm:p-10 lg:p-12 hover:shadow-rose-200/20 dark:hover:border-zinc-700 transition-all duration-500">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Update Password Card -->
                    <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-3xl lg:rounded-[2.5rem] shadow-xl dark:shadow-none border border-white dark:border-zinc-800 p-6 sm:p-10 lg:p-12 hover:shadow-rose-200/20 dark:hover:border-zinc-700 transition-all duration-500">
                        <div class="max-w-2xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <!-- TAB 2: Security & 2FA -->
                <div x-show="activeTab === 'security'"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak>
                    
                    <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-3xl lg:rounded-[2.5rem] shadow-xl dark:shadow-none border border-white dark:border-zinc-800 p-6 sm:p-10 lg:p-12 hover:shadow-rose-200/20 dark:hover:border-zinc-700 transition-all duration-500">
                        <div class="max-w-2xl">
                            @include('profile.partials.two-factor-authentication-form')
                        </div>
                    </div>
                </div>

                <!-- TAB 3: Emergency SMS Alerts -->
                <div x-show="activeTab === 'sms'"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak>
                    
                    <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-3xl lg:rounded-[2.5rem] shadow-xl dark:shadow-none border border-white dark:border-zinc-800 p-6 sm:p-10 lg:p-12 hover:shadow-rose-200/20 dark:hover:border-zinc-700 transition-all duration-500">
                        @include('profile.partials.emergency-sms-alerts-form')
                    </div>
                </div>

                <!-- TAB 4: Automated Email Reports -->
                <div x-show="activeTab === 'email'"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak>
                    
                    <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-3xl lg:rounded-[2.5rem] shadow-xl dark:shadow-none border border-white dark:border-zinc-800 p-6 sm:p-10 lg:p-12 hover:shadow-rose-200/20 dark:hover:border-zinc-700 transition-all duration-500">
                        @include('profile.partials.automated-email-reports-form')
                    </div>
                </div>

                <!-- TAB 5: Danger Zone -->
                <div x-show="activeTab === 'danger'"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak>
                    
                    <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-3xl lg:rounded-[2.5rem] shadow-xl dark:shadow-none border border-white dark:border-zinc-800 p-6 sm:p-10 lg:p-12 hover:shadow-rose-200/20 dark:hover:border-zinc-700 transition-all duration-500">
                        <div class="max-w-2xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
