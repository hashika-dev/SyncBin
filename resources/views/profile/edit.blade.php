<x-app-layout>
    @slot('hideNav')
        true
    @endslot

    <div class="flex flex-col lg:flex-row min-h-screen w-full bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        @include('layouts.sidebar', ['active' => 'settings'])

        <!-- Main Content -->
        <main class="flex-1 w-full min-w-0 ml-0 lg:ml-72 p-4 sm:p-6 lg:p-10 xl:p-12 bg-slate-100 dark:bg-slate-950 min-h-screen">
            <!-- Header -->
            <header class="flex items-center justify-between pb-6 mb-8 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Account Management</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Security & Profile Settings</h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1 text-xs sm:text-sm">
                        Manage your operator profile, MFA credentials, emergency dispatch contacts, and security policies
                    </p>
                </div>
            </header>

            <!-- Status Alert Message -->
            @if (session('status'))
                <div class="mb-8 p-4 bg-emerald-950/40 border border-emerald-800 rounded-xl flex items-center gap-3 text-emerald-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    <span class="font-bold text-xs">{{ session('status') }}</span>
                </div>
            @endif

            <div class="space-y-8 max-w-4xl">
                <!-- Profile Information Card -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm dark:shadow-xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password Card -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm dark:shadow-xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Two Factor Authentication Card -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm dark:shadow-xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.two-factor-authentication-form')
                    </div>
                </div>

                <!-- Notification & Alert Preferences Card -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm dark:shadow-xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8">
                    @include('profile.partials.notification-alert-preferences-form')
                </div>

                <!-- Delete User Card -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm dark:shadow-xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>

