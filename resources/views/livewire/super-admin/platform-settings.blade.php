<div class="space-y-6 max-w-4xl">
    
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Platform Settings</h2>
                <p class="text-sm text-slate-500 mt-1">Manage global configuration, branding, and SaaS payment integrations.</p>
            </div>
            <button wire:click="save" class="px-5 py-2.5 bg-fuchsia-600 hover:bg-fuchsia-500 text-white font-semibold rounded-xl text-sm transition">
                Save Changes
            </button>
        </div>

        <div class="p-8 space-y-8">
            {{-- General --}}
            <section>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">General Info</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Platform Name *</label>
                        <input wire:model="platform_name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-fuchsia-500/20 focus:border-fuchsia-500">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Support Email *</label>
                            <input wire:model="support_email" type="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-fuchsia-500/20 focus:border-fuchsia-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Support Phone</label>
                            <input wire:model="support_phone" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-fuchsia-500/20 focus:border-fuchsia-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Default Currency</label>
                        <select wire:model="currency" class="w-full md:w-1/2 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-fuchsia-500/20 focus:border-fuchsia-500">
                            <option value="INR">INR (₹)</option>
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (€)</option>
                            <option value="GBP">GBP (£)</option>
                        </select>
                    </div>
                </div>
            </section>

            {{-- Payment Gateways --}}
            <section>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">SaaS Payment Gateway (Stripe)</h3>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-4">
                    <p class="text-xs text-slate-500 font-medium">Used to collect subscription payments from your tenants (Hotels).</p>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Stripe Public Key</label>
                        <input wire:model="stripe_public_key" type="text" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-slate-900 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500/20 focus:border-fuchsia-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Stripe Secret Key</label>
                        <input wire:model="stripe_secret_key" type="password" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-slate-900 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500/20 focus:border-fuchsia-500">
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
