<section x-data="{
    smsContacts: [
        { id: 1, name: 'Kurt Umali', phone: '+63 917 123 4567', role: 'Sanitation Lead', badgeClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' },
        { id: 2, name: 'Maria Santos', phone: '+63 918 987 6543', role: 'Maintenance Supervisor', badgeClass: 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800' }
    ],
    emailRecipients: [
        { id: 1, email: 'kurtumali06@gmail.com', primary: true },
        { id: 2, email: 'admin@ecosync.com', primary: false },
        { id: 3, email: 'superadmin@ecosync.com', primary: false }
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
            badgeClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800'
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
    <header class="flex items-center justify-between pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800 rounded text-[10px] font-mono font-bold uppercase tracking-wider">Alert System</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 font-mono">Emergency & Daily Telemetry Reports</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Notification & Alert Preferences</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
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
         class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl flex items-center gap-3 text-emerald-800 dark:text-emerald-300 font-semibold text-xs" x-cloak>
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
        <span x-text="toastMessage"></span>
    </div>

    <!-- Two-Column Grid (Recipients Section) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Left Column: SMS Contacts -->
        <div class="bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 flex flex-col justify-between space-y-6">
            <div class="space-y-6">
                <!-- Banner Notice -->
                <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl flex items-start gap-3.5 text-slate-700 dark:text-slate-300">
                    <div class="p-2 bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/><path d="M14.05 2a9 9 0 0 1 7.95 7.95"/><path d="M14.05 6a5 5 0 0 1 3.95 3.95"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-mono font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Emergency SMS Alerts</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                            SMS messages are automatically dispatched when any bin reaches <span class="font-bold text-slate-900 dark:text-white">85% capacity</span> threshold.
                        </p>
                    </div>
                </div>

                <!-- Contacts List -->
                <div>
                    <h4 class="text-xs font-mono font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-3">Configured Contacts (<span x-text="smsContacts.length"></span>)</h4>
                    <div class="space-y-2.5">
                        <template x-for="contact in smsContacts" :key="contact.id">
                            <div class="p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-between shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center font-bold text-xs font-mono border border-slate-200 dark:border-slate-700">
                                        <span x-text="contact.name.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-xs text-slate-900 dark:text-white" x-text="contact.name"></span>
                                            <span class="px-2 py-0.5 text-[9px] font-mono font-bold uppercase tracking-wider rounded border" :class="contact.badgeClass" x-text="contact.role"></span>
                                        </div>
                                        <span class="block text-[11px] font-mono text-slate-500 dark:text-slate-400 mt-0.5" x-text="contact.phone"></span>
                                    </div>
                                </div>
                                <button type="button" @click="deleteContact(contact.id)" title="Remove Contact" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Add New Contact Form -->
            <form @submit.prevent="addContact()" class="pt-5 border-t border-slate-200 dark:border-slate-800 space-y-4">
                <h4 class="text-xs font-mono font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Add New Contact</h4>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Full Name</label>
                        <input type="text" x-model="newContactName" placeholder="e.g. Juan Dela Cruz" required
                               class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 bg-white dark:bg-slate-900 text-xs font-medium text-slate-900 dark:text-slate-100">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Position / Role</label>
                            <select x-model="newContactRole" class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-white dark:bg-slate-900 text-xs font-medium text-slate-900 dark:text-slate-100">
                                <option value="Sanitation Lead">Sanitation Lead</option>
                                <option value="Maintenance Supervisor">Maintenance Supervisor</option>
                                <option value="Operations Staff">Operations Staff</option>
                                <option value="Safety Officer">Safety Officer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Phone Number</label>
                            <input type="tel" x-model="newContactPhone" placeholder="+63 917 000 0000" required
                                   class="w-full px-3.5 py-2.5 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 bg-white dark:bg-slate-900 text-xs font-mono font-medium text-slate-900 dark:text-slate-100">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-mono font-bold text-xs uppercase tracking-wider shadow-sm transition-all flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    Add SMS Contact
                </button>
            </form>
        </div>

        <!-- Right Column: Email Addresses -->
        <div class="bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 flex flex-col justify-between space-y-6">
            <div class="space-y-6">
                <!-- Banner Notice -->
                <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl flex items-start gap-3.5 text-slate-700 dark:text-slate-300">
                    <div class="p-2 bg-sky-50 dark:bg-sky-950 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800 rounded-lg shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-mono font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">Automated Email Reports</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                            Daily summaries and critical capacity alert PDF reports are dispatched to registered email addresses.
                        </p>
                    </div>
                </div>

                <!-- Emails List -->
                <div>
                    <h4 class="text-xs font-mono font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-3">Registered Recipients (<span x-text="emailRecipients.length"></span>)</h4>
                    <div class="space-y-2.5">
                        <template x-for="item in emailRecipients" :key="item.id">
                            <div class="p-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-between shadow-sm">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-sky-600 dark:text-sky-400 flex items-center justify-center font-mono font-bold text-xs shrink-0 border border-slate-200 dark:border-slate-700">
                                        @
                                    </div>
                                    <div class="overflow-hidden">
                                        <span class="block font-mono font-bold text-xs text-slate-900 dark:text-white truncate" x-text="item.email"></span>
                                        <span class="block text-[10px] text-slate-400 dark:text-slate-500 mt-0.5" x-text="item.primary ? 'Primary Administrator' : 'Secondary Alert Email'"></span>
                                    </div>
                                </div>
                                <button type="button" @click="deleteEmail(item.id)" title="Remove Email" class="p-2 text-slate-400 hover:text-red-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Inline Add Email Address Form -->
            <form @submit.prevent="addEmail()" class="pt-5 border-t border-slate-200 dark:border-slate-800 space-y-4">
                <h4 class="text-xs font-mono font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Add Email Address</h4>
                
                <div>
                    <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Email Address</label>
                    <div class="flex gap-2.5">
                        <input type="email" x-model="newEmail" placeholder="new.recipient@ecosync.com" required
                               class="flex-1 px-3.5 py-2.5 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 bg-white dark:bg-slate-900 text-xs font-medium text-slate-900 dark:text-slate-100">
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-mono font-bold text-xs uppercase tracking-wider shadow-sm transition-all shrink-0">
                            Add Email
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</section>
