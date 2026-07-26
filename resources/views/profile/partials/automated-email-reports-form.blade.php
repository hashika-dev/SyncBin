<section x-data="{
    emailRecipients: [
        { id: 1, email: 'kurtumali06@gmail.com', primary: true },
        { id: 2, email: 'admin@wastesync.com', primary: false },
        { id: 3, email: 'superadmin@wastesync.com', primary: false }
    ],
    newEmail: '',
    toastMessage: '',
    showToast: false,

    triggerToast(msg) {
        this.toastMessage = msg;
        this.showToast = true;
        setTimeout(() => { this.showToast = false; }, 3000);
    },

    addEmail() {
        if (!this.newEmail) return;
        this.emailRecipients.push({
            id: Date.now(),
            email: this.newEmail,
            primary: false
        });
        this.triggerToast('Email alert recipient added successfully!');
        this.newEmail = '';
    },

    deleteEmail(id) {
        this.emailRecipients = this.emailRecipients.filter(e => e.id !== id);
        this.triggerToast('Email recipient removed.');
    }
}" class="space-y-6">

    <!-- Header Section -->
    <header class="flex items-center justify-between pb-6 border-b border-rose-100 dark:border-zinc-800">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 bg-sky-500 text-white rounded-lg text-[10px] font-black uppercase tracking-widest">Report System</span>
                <span class="text-xs font-bold text-sky-600 dark:text-sky-400">Daily Summaries</span>
            </div>
            <h2 class="text-2xl font-black text-rose-950 dark:text-zinc-50 tracking-tight">Automated Email Reports</h2>
            <p class="text-sm font-bold text-rose-900/60 dark:text-zinc-400 mt-1">
                Configure registered email addresses to receive daily summaries and critical capacity alert reports.
            </p>
        </div>
    </header>

    <!-- Success Toast Notification -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/40 rounded-2xl flex items-center gap-3 text-emerald-800 dark:text-emerald-300 font-bold text-sm" x-cloak>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
        <span x-text="toastMessage"></span>
    </div>

    <!-- Banner Notice -->
    <div class="p-5 bg-sky-500/10 dark:bg-sky-950/20 border border-sky-200 dark:border-sky-900/30 rounded-2xl flex items-start gap-4 text-sky-900 dark:text-sky-300">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 text-sky-500 mt-0.5"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
        <div>
            <h4 class="text-xs font-black uppercase tracking-wider text-sky-600 dark:text-sky-400">Automated Email Reports</h4>
            <p class="text-xs font-bold opacity-80 mt-1 leading-relaxed">
                Daily summaries and critical capacity alert PDF reports are dispatched to these registered email addresses.
            </p>
        </div>
    </div>

    <!-- Emails List -->
    <div>
        <h4 class="text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500 mb-4">Registered Recipients (<span x-text="emailRecipients.length"></span>)</h4>
        <div class="space-y-3">
            <template x-for="item in emailRecipients" :key="item.id">
                <div class="p-4 bg-white dark:bg-zinc-900 border border-rose-100 dark:border-zinc-700/60 rounded-2xl flex items-center justify-between shadow-sm hover:border-rose-200 dark:hover:border-zinc-600 transition-all">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-xl bg-sky-50 dark:bg-zinc-800 text-sky-600 dark:text-sky-400 flex items-center justify-center font-black text-xs shrink-0">
                            @
                        </div>
                        <div class="overflow-hidden">
                            <span class="block font-black text-sm text-rose-950 dark:text-zinc-100 truncate" x-text="item.email"></span>
                            <span class="block text-[10px] font-bold text-gray-400 dark:text-zinc-500 mt-0.5" x-text="item.primary ? 'Primary Administrator' : 'Secondary Alert Email'"></span>
                        </div>
                    </div>
                    <button type="button" @click="deleteEmail(item.id)" title="Remove Email" class="p-2.5 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-zinc-800 rounded-xl transition-all shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    </button>
                </div>
            </template>
        </div>
    </div>

    <!-- Inline Add Email Address Form -->
    <form @submit.prevent="addEmail()" class="pt-6 border-t border-rose-100 dark:border-zinc-800 space-y-4">
        <h4 class="text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500">Add Email Address</h4>
        
        <div>
            <label class="block text-[10px] font-black uppercase tracking-wider text-rose-900/60 dark:text-zinc-400 mb-1 ml-1">Email Address</label>
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="email" x-model="newEmail" placeholder="new.recipient@wastesync.com" required
                       class="flex-1 px-4 py-3 border border-rose-100 dark:border-zinc-700 rounded-xl focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all placeholder:text-gray-400 dark:placeholder:text-zinc-600 bg-white dark:bg-zinc-900 text-sm font-bold text-rose-950 dark:text-zinc-100">
                <button type="submit" class="px-6 py-3 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-black text-xs uppercase tracking-wider shadow-lg shadow-rose-200 dark:shadow-none hover:-translate-y-0.5 active:translate-y-0 transition-all shrink-0">
                    Add Email
                </button>
            </div>
        </div>
    </form>
</section>
