<x-app-layout>
    @slot('hideNav')
        true
    @endslot

    <div class="flex flex-col lg:flex-row min-h-screen w-full bg-gradient-to-br from-rose-50 via-white to-orange-50/50 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950 text-gray-900 dark:text-zinc-100 transition-colors duration-300" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        @include('layouts.sidebar', ['active' => 'settings'])

        <!-- Main Content -->
        <main class="flex-1 w-full min-w-0 ml-0 lg:ml-72 p-4 sm:p-6 lg:p-10 xl:p-12">
            <!-- Header -->
            <header class="flex items-center justify-between mb-8 sm:mb-12">
                <div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-rose-950 dark:text-zinc-50 tracking-tighter">Profile Settings</h1>
                    <p class="text-rose-600 dark:text-rose-400 mt-2 font-bold text-sm sm:text-base lg:text-lg opacity-80">
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

            <div class="space-y-8 sm:space-y-12 pb-16 sm:pb-24 max-w-4xl">
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

                <!-- Two Factor Authentication Card -->
                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-3xl lg:rounded-[2.5rem] shadow-xl dark:shadow-none border border-white dark:border-zinc-800 p-6 sm:p-10 lg:p-12 hover:shadow-rose-200/20 dark:hover:border-zinc-700 transition-all duration-500">
                    <div class="max-w-2xl">
                        @include('profile.partials.two-factor-authentication-form')
                    </div>
                </div>

                <!-- Notification & Alert Preferences Card -->
                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-3xl lg:rounded-[2.5rem] shadow-xl dark:shadow-none border border-white dark:border-zinc-800 p-6 sm:p-10 lg:p-12 hover:shadow-rose-200/20 dark:hover:border-zinc-700 transition-all duration-500">
                    @include('profile.partials.notification-alert-preferences-form')
                </div>

                <!-- Delete User Card -->
                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-3xl lg:rounded-[2.5rem] shadow-xl dark:shadow-none border border-white dark:border-zinc-800 p-6 sm:p-10 lg:p-12 hover:shadow-rose-200/20 dark:hover:border-zinc-700 transition-all duration-500">
                    <div class="max-w-2xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
