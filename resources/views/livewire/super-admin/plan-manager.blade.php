<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Subscription Plans</h2>
            <p class="text-sm text-slate-500 mt-1 font-medium">Manage SaaS billing tiers and features for tenants.</p>
        </div>
        
        <button wire:click="openCreate" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Plan
        </button>
    </div>

    <div class="p-6">
        @if($plans->count())
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($plans as $plan)
            <div class="bg-slate-50 border {{ $plan->is_active ? 'border-slate-200' : 'border-dashed border-slate-300 opacity-70' }} rounded-2xl p-6 relative flex flex-col">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-slate-900">{{ $plan->name }}</h3>
                    <p class="text-xs font-medium text-slate-500 mt-1">{{ $plan->description ?: 'No description' }}</p>
                </div>
                
                <div class="flex items-end gap-1 mb-6">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">₹{{ number_format($plan->price_monthly, 0) }}</span>
                    <span class="text-sm font-medium text-slate-500 mb-1">/mo</span>
                </div>

                <div class="mb-6 flex-1">
                    <p class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Included Limits & Features</p>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-sm text-slate-600 font-medium">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $plan->max_rooms == 0 ? 'Unlimited' : $plan->max_rooms }} Rooms allowed
                        </li>
                        @if($plan->features)
                            @foreach($plan->features as $feature)
                            <li class="flex items-center gap-2 text-sm text-slate-600 font-medium">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <button wire:click="openEdit({{ $plan->id }})" class="w-full py-2 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold rounded-xl text-sm transition">
                    Edit Plan
                </button>
            </div>
            @endforeach
        </div>
        @else
        <div class="py-12 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
            <h3 class="text-slate-900 font-bold mb-1">No plans defined</h3>
            <p class="text-slate-500 text-sm mb-4">Create your first subscription tier to get started.</p>
        </div>
        @endif
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" wire:click.self="$set('showModal', false)">
        <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-xl shadow-xl flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">{{ $plan_id ? 'Edit Plan' : 'Create New Plan' }}</h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

            <div class="p-6 overflow-y-auto space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Plan Name *</label>
                    <input wire:model="name" type="text" placeholder="e.g. Premium" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
                    <textarea wire:model="description" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Monthly Price (₹) *</label>
                        <input wire:model="price_monthly" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Yearly Price (₹) *</label>
                        <input wire:model="price_yearly" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Max Rooms Allowed * (0 for unlimited)</label>
                    <input wire:model="max_rooms" type="number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Features list</label>
                    <div class="flex gap-2 mb-2">
                        <input wire:model="newFeature" wire:keydown.enter.prevent="addFeature" type="text" placeholder="e.g. Priority Support" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <button wire:click.prevent="addFeature" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">Add</button>
                    </div>
                    @if($features)
                    <div class="flex flex-wrap gap-2">
                        @foreach($features as $index => $feature)
                        <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 border border-slate-200 px-2 py-1 rounded-md text-xs font-medium">
                            {{ $feature }}
                            <button wire:click.prevent="removeFeature({{ $index }})" class="text-slate-400 hover:text-red-500 ml-1">&times;</button>
                        </span>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <input wire:model="is_active" type="checkbox" id="plan_active" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <label for="plan_active" class="text-sm font-semibold text-slate-700">Plan is Active</label>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 rounded-b-2xl">
                <button wire:click="$set('showModal', false)" class="px-4 py-2 text-slate-600 font-semibold hover:bg-slate-200 rounded-xl text-sm transition">Cancel</button>
                <button wire:click="save" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-sm transition">Save Plan</button>
            </div>
        </div>
    </div>
    @endif
</div>
