<x-app-layout>
    @slot('hideNav')
        true
    @endslot

    <div class="flex min-h-screen bg-gradient-to-br from-rose-50 via-white to-orange-50/50 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950 text-gray-900 dark:text-zinc-100 transition-colors duration-300">
        <!-- Sidebar -->
        @include('layouts.sidebar', ['active' => 'settings'])

        <!-- Main Content -->
        <main class="flex-1 ml-72 p-16">
            <!-- Header -->
            <header class="flex items-center justify-between mb-16">
                <div>
                    <h1 class="text-5xl font-black text-rose-950 dark:text-zinc-50 tracking-tighter">Profile Settings</h1>
                    <p class="text-rose-600 dark:text-rose-400 mt-3 font-bold text-xl opacity-80">
                        Manage your account information, password, and security settings
                    </p>
                </div>
            </header>

            <!-- Status Alert Message -->
            @if (session('status'))
                <div class="mb-10 p-6 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 rounded-3xl flex items-center gap-4 text-emerald-800 dark:text-emerald-400 animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    <span class="font-bold text-sm">{{ session('status') }}</span>
                </div>
            @endif

            <div class="space-y-12 pb-24 max-w-4xl">
                <!-- Profile Information Card -->
                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-[2.5rem] shadow-2xl dark:shadow-none border border-white dark:border-zinc-800 p-12 hover:shadow-rose-200/20 dark:hover:border-zinc-700 transition-all duration-500">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password Card -->
                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-[2.5rem] shadow-2xl dark:shadow-none border border-white dark:border-zinc-800 p-12 hover:shadow-rose-200/20 dark:hover:border-zinc-700 transition-all duration-500">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Two Factor Authentication Card -->
                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-[2.5rem] shadow-2xl dark:shadow-none border border-white dark:border-zinc-800 p-12 hover:shadow-rose-200/20 dark:hover:border-zinc-700 transition-all duration-500">
                    <div class="max-w-2xl">
                        @include('profile.partials.two-factor-authentication-form')
                    </div>
                </div>

                <!-- Delete User Card -->
                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl rounded-[2.5rem] shadow-2xl dark:shadow-none border border-white dark:border-zinc-800 p-12 hover:shadow-rose-200/20 dark:hover:border-zinc-700 transition-all duration-500">
                    <div class="max-w-2xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
