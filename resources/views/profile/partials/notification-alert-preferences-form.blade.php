<section x-data="{
    smsContacts: [
        { id: 1, name: 'Kurt Umali', phone: '+63 917 123 4567', role: 'Sanitation Lead', badgeClass: 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-900/40' },
        { id: 2, name: 'Maria Santos', phone: '+63 918 987 6543', role: 'Maintenance Supervisor', badgeClass: 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-900/40' }
    ],
    emailRecipients: [
        { id: 1, email: 'kurtumali06@gmail.com', primary: true },
        { id: 2, email: 'admin@wastesync.com', primary: false },
        { id: 3, email: 'superadmin@wastesync.com', primary: false }
    ],
    newContactName: '',
    newContactRole: 'Sanitation Lead',
    newContactPhone: '',
    newEmail: '',
    toastMessage: '',
    showToast: false,

    triggerToast(msg) {
        this.toastMessage = msg;
        this.showToast = true;
        setTimeout(() => { this.showToast = false; }, 3000);
    },

    addContact() {
        if (!this.newContactName || !this.newContactPhone) return;
        this.smsContacts.push({
            id: Date.now(),
            name: this.newContactName,
            phone: this.newContactPhone,
            role: this.newContactRole,
            badgeClass: 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-900/40'
        });
        this.triggerToast('New SMS alert contact added successfully!');
        this.newContactName = '';
        this.newContactPhone = '';
    },

    deleteContact(id) {
        this.smsContacts = this.smsContacts.filter(c => c.id !== id);
        this.triggerToast('SMS contact removed.');
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
}" class="space-y-8">

    <!-- Header Section -->
    <header class="flex items-center justify-between pb-6 border-b border-rose-100 dark:border-zinc-800">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 bg-rose-500 text-white rounded-lg text-[10px] font-black uppercase tracking-widest">Alert System</span>
                <span class="text-xs font-bold text-rose-600 dark:text-rose-400">Emergency & Daily Reports</span>
            </div>
            <h2 class="text-2xl font-black text-rose-950 dark:text-zinc-50 tracking-tight">Notification & Alert Preferences</h2>
            <p class="text-sm font-bold text-rose-900/60 dark:text-zinc-400 mt-1">
                Configure real-time SMS emergency dispatch contacts and automated email report recipients.
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

    <!-- Two-Column Grid (Recipients Section) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Left Column: SMS Contacts -->
        <div class="bg-gray-50/50 dark:bg-zinc-800/40 border border-rose-100 dark:border-zinc-800 rounded-3xl p-8 flex flex-col justify-between space-y-6">
            <div class="space-y-6">
                <!-- Banner Notice -->
                <div class="p-5 bg-rose-500/10 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/30 rounded-2xl flex items-start gap-4 text-rose-900 dark:text-rose-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 text-rose-500 mt-0.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/><path d="M14.05 2a9 9 0 0 1 7.95 7.95"/><path d="M14.05 6a5 5 0 0 1 3.95 3.95"/></svg>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-rose-600 dark:text-rose-400">Emergency SMS Alerts</h4>
                        <p class="text-xs font-bold opacity-80 mt-1 leading-relaxed">
                            SMS messages are automatically dispatched to these contacts when any bin reaches <span class="font-black text-rose-600 dark:text-rose-400">85% capacity</span> threshold.
                        </p>
                    </div>
                </div>

                <!-- Contacts List -->
                <div>
                    <h4 class="text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500 mb-4">Configured Contacts (<span x-text="smsContacts.length"></span>)</h4>
                    <div class="space-y-3">
                        <template x-for="contact in smsContacts" :key="contact.id">
                            <div class="p-4 bg-white dark:bg-zinc-900 border border-rose-100 dark:border-zinc-700/60 rounded-2xl flex items-center justify-between shadow-sm hover:border-rose-200 dark:hover:border-zinc-600 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-zinc-800 text-rose-600 dark:text-rose-400 flex items-center justify-center font-black text-sm">
                                        <span x-text="contact.name.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-black text-sm text-rose-950 dark:text-zinc-100" x-text="contact.name"></span>
                                            <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-lg border" :class="contact.badgeClass" x-text="contact.role"></span>
                                        </div>
                                        <span class="block text-xs font-mono font-semibold text-rose-900/60 dark:text-zinc-400 mt-0.5" x-text="contact.phone"></span>
                                    </div>
                                </div>
                                <button type="button" @click="deleteContact(contact.id)" title="Remove Contact" class="p-2.5 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-zinc-800 rounded-xl transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Add New Contact Form -->
            <form @submit.prevent="addContact()" class="pt-6 border-t border-rose-100 dark:border-zinc-800 space-y-4">
                <h4 class="text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500">Add New Contact</h4>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-rose-900/60 dark:text-zinc-400 mb-1 ml-1">Full Name</label>
                        <input type="text" x-model="newContactName" placeholder="e.g. Juan Dela Cruz" required
                               class="w-full px-4 py-3 border border-rose-100 dark:border-zinc-700 rounded-xl focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all placeholder:text-gray-400 dark:placeholder:text-zinc-600 bg-white dark:bg-zinc-900 text-sm font-bold text-rose-950 dark:text-zinc-100">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-rose-900/60 dark:text-zinc-400 mb-1 ml-1">Position / Role</label>
                            <select x-model="newContactRole" class="w-full px-4 py-3 border border-rose-100 dark:border-zinc-700 rounded-xl focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all bg-white dark:bg-zinc-900 text-sm font-bold text-rose-950 dark:text-zinc-100">
                                <option value="Sanitation Lead">Sanitation Lead</option>
                                <option value="Maintenance Supervisor">Maintenance Supervisor</option>
                                <option value="Operations Staff">Operations Staff</option>
                                <option value="Safety Officer">Safety Officer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-rose-900/60 dark:text-zinc-400 mb-1 ml-1">Phone Number</label>
                            <input type="tel" x-model="newContactPhone" placeholder="+63 917 000 0000" required
                                   class="w-full px-4 py-3 border border-rose-100 dark:border-zinc-700 rounded-xl focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all placeholder:text-gray-400 dark:placeholder:text-zinc-600 bg-white dark:bg-zinc-900 text-sm font-bold text-rose-950 dark:text-zinc-100">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-black text-xs uppercase tracking-wider shadow-lg shadow-rose-200 dark:shadow-none hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    Add SMS Contact
                </button>
            </form>
        </div>

        <!-- Right Column: Email Addresses -->
        <div class="bg-gray-50/50 dark:bg-zinc-800/40 border border-rose-100 dark:border-zinc-800 rounded-3xl p-8 flex flex-col justify-between space-y-6">
            <div class="space-y-6">
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
            </div>

            <!-- Inline Add Email Address Form -->
            <form @submit.prevent="addEmail()" class="pt-6 border-t border-rose-100 dark:border-zinc-800 space-y-4">
                <h4 class="text-xs font-black uppercase tracking-widest text-rose-400 dark:text-zinc-500">Add Email Address</h4>
                
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-rose-900/60 dark:text-zinc-400 mb-1 ml-1">Email Address</label>
                    <div class="flex gap-3">
                        <input type="email" x-model="newEmail" placeholder="new.recipient@wastesync.com" required
                               class="flex-1 px-4 py-3 border border-rose-100 dark:border-zinc-700 rounded-xl focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all placeholder:text-gray-400 dark:placeholder:text-zinc-600 bg-white dark:bg-zinc-900 text-sm font-bold text-rose-950 dark:text-zinc-100">
                        <button type="submit" class="px-6 py-3 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-black text-xs uppercase tracking-wider shadow-lg shadow-rose-200 dark:shadow-none hover:-translate-y-0.5 active:translate-y-0 transition-all shrink-0">
                            Add Email
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</section>
