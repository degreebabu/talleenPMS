<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-wide">Tenant Manager</h2>
            <p class="text-slate-500 mt-1">Manage hotels, their active modules, and platform access.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl font-medium">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50/50 text-slate-500 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Tenant (Property)</th>
                    <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Plan / Access</th>
                    <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Status</th>
                    <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px] text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($companies as $company)
                    <tr class="bg-slate-100/50 border-y border-slate-200">
                        <td colspan="4" class="px-6 py-3">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold text-slate-700 text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    {{ $company->name }} <span class="text-xs text-slate-500 font-normal ml-2">({{ $company->hotels->count() }} Properties)</span>
                                </div>
                                <button wire:click="openPropertyModal({{ $company->id }})" class="text-xs font-bold text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 px-3 py-1 rounded-full transition-colors flex items-center gap-1 shadow-sm bg-white">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Add Property
                                </button>
                            </div>
                        </td>
                    </tr>
                    @foreach($company->hotels as $tenant)
                        @include('livewire.super-admin.partials.tenant-row', ['tenant' => $tenant, 'isChild' => true])
                    @endforeach
                @endforeach

                @if($tenants->count() > 0)
                    <tr class="bg-slate-100/50 border-y border-slate-200">
                        <td colspan="4" class="px-6 py-3">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold text-slate-700 text-sm">
                                    Independent Properties
                                </div>
                                <button wire:click="openPropertyModal()" class="text-xs font-bold text-slate-600 hover:text-white hover:bg-slate-600 border border-slate-600 px-3 py-1 rounded-full transition-colors flex items-center gap-1 shadow-sm bg-white">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Add Independent Property
                                </button>
                            </div>
                        </td>
                    </tr>
                    @foreach($tenants as $tenant)
                        @include('livewire.super-admin.partials.tenant-row', ['tenant' => $tenant, 'isChild' => false])
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Modules Modal -->
    @if($showModuleModal && $selectedTenant)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-4xl overflow-hidden shadow-2xl transform transition-all flex flex-col max-h-[90vh]">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50 flex-shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Manage Modules: {{ $selectedTenant->name }}</h3>
                    <p class="text-sm text-slate-500 mt-1">Configure which features and sub-features are active for this tenant.</p>
                </div>
                <button wire:click="$set('showModuleModal', false)" class="text-slate-400 hover:text-slate-600 transition bg-white rounded-full p-2 border border-slate-200 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto bg-slate-50/50 flex-1">
                @php
                    $tenantFeatures = is_array($selectedTenant->features) ? $selectedTenant->features : [];
                @endphp

                <h4 class="font-bold text-slate-800 text-lg mb-4">Core Platform Modules</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    @foreach($coreModules as $cm)
                    @php
                        $mainStatus = $tenantFeatures[$cm['slug']] ?? 'disabled';
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                            <div class="font-bold text-slate-900">{{ $cm['name'] }}</div>
                            <div class="flex bg-slate-100 p-1 rounded-lg">
                                <button wire:click="updateModuleStatus('{{ $cm['slug'] }}', 'active')" class="px-2 py-1 text-[10px] font-bold rounded-md transition uppercase tracking-wider {{ $mainStatus === 'active' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">ON</button>
                                <button wire:click="updateModuleStatus('{{ $cm['slug'] }}', 'paused')" class="px-2 py-1 text-[10px] font-bold rounded-md transition uppercase tracking-wider {{ $mainStatus === 'paused' ? 'bg-amber-500 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">PAUSE</button>
                                <button wire:click="updateModuleStatus('{{ $cm['slug'] }}', 'disabled')" class="px-2 py-1 text-[10px] font-bold rounded-md transition uppercase tracking-wider {{ $mainStatus === 'disabled' ? 'bg-slate-800 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">OFF</button>
                            </div>
                        </div>
                        @if(!empty($cm['features']))
                        <div class="bg-slate-50/50 p-3 space-y-2">
                            @foreach($cm['features'] as $fSlug => $fName)
                            @php
                                $fKey = $cm['slug'] . '_' . $fSlug;
                                $fStatus = $tenantFeatures[$fKey] ?? 'disabled';
                            @endphp
                            <div class="flex items-center justify-between pl-2">
                                <div class="text-xs font-semibold text-slate-600">{{ $fName }}</div>
                                <div class="flex bg-slate-200/50 p-1 rounded-md">
                                    <button wire:click="updateModuleStatus('{{ $cm['slug'] }}', 'active', '{{ $fSlug }}')" class="px-2 py-0.5 text-[9px] uppercase tracking-wider font-bold rounded transition {{ $fStatus === 'active' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-300' }}">ON</button>
                                    <button wire:click="updateModuleStatus('{{ $cm['slug'] }}', 'paused', '{{ $fSlug }}')" class="px-2 py-0.5 text-[9px] uppercase tracking-wider font-bold rounded transition {{ $fStatus === 'paused' ? 'bg-amber-500 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-300' }}">PAUSE</button>
                                    <button wire:click="updateModuleStatus('{{ $cm['slug'] }}', 'disabled', '{{ $fSlug }}')" class="px-2 py-0.5 text-[9px] uppercase tracking-wider font-bold rounded transition {{ $fStatus === 'disabled' ? 'bg-slate-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-300' }}">OFF</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                @if($dynamicModules->count() > 0)
                <h4 class="font-bold text-slate-800 text-lg mb-4 mt-6 border-t pt-6">Custom Dynamic Modules</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($dynamicModules as $dm)
                    @php
                        $dmStatus = $tenantFeatures[$dm->slug] ?? 'disabled';
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $dm->icon }}"/></svg>
                            </div>
                            <div class="font-bold text-slate-900">{{ $dm->name }}</div>
                        </div>
                        <div class="flex bg-slate-100 p-1 rounded-lg">
                            <button wire:click="updateModuleStatus('{{ $dm->slug }}', 'active')" class="px-2 py-1 text-[10px] font-bold rounded-md transition uppercase tracking-wider {{ $dmStatus === 'active' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">ON</button>
                            <button wire:click="updateModuleStatus('{{ $dm->slug }}', 'paused')" class="px-2 py-1 text-[10px] font-bold rounded-md transition uppercase tracking-wider {{ $dmStatus === 'paused' ? 'bg-amber-500 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">PAUSE</button>
                            <button wire:click="updateModuleStatus('{{ $dm->slug }}', 'disabled')" class="px-2 py-1 text-[10px] font-bold rounded-md transition uppercase tracking-wider {{ $dmStatus === 'disabled' ? 'bg-slate-800 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">OFF</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            
            <div class="px-6 py-4 bg-white border-t border-slate-100 flex justify-end flex-shrink-0">
                <button wire:click="$set('showModuleModal', false)" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl shadow-sm transition">Close Window</button>
            </div>
        </div>
    </div>
    @endif
    <!-- Create Property Modal -->
    @if($showPropertyModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl transform transition-all flex flex-col max-h-[90vh]">
            
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight">Add New Property</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ $propCompanyId ? 'Adding property to ' . ($companies->find($propCompanyId)?->name ?? 'Group') : 'Adding independent property' }}</p>
                </div>
                <button wire:click="$set('showPropertyModal', false)" class="text-slate-400 hover:text-slate-600 hover:bg-slate-50 p-2 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-8 overflow-y-auto flex-1">
                <form id="propertyForm" wire:submit.prevent="saveProperty" class="space-y-8">
                    
                    <div class="space-y-5">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Property Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Property Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="propName" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-900" required placeholder="e.g. Taj Lands End">
                                @error('propName') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Subdomain <span class="text-red-500">*</span></label>
                                <div class="flex">
                                    <input type="text" wire:model="propSubdomain" class="w-full rounded-l-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-900" required placeholder="tajlands">
                                    <span class="inline-flex items-center px-3 rounded-r-xl border border-l-0 border-slate-200 bg-slate-50 text-slate-500 text-sm">
                                        .talleen.com
                                    </span>
                                </div>
                                @error('propSubdomain') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Contact Email</label>
                                <input type="email" wire:model="propEmail" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-900" placeholder="hello@hotel.com">
                                @error('propEmail') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Contact Phone</label>
                                <input type="text" wire:model="propPhone" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-900" placeholder="+1 (555) 000-0000">
                                @error('propPhone') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 my-6"></div>
                    
                    <div class="space-y-5">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Compliance</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">GST Number</label>
                                <input type="text" wire:model="propGst" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-900" placeholder="e.g. 27AAAAA0000A1Z5">
                                @error('propGst') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Registration Number</label>
                                <input type="text" wire:model="propRegistration" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-900" placeholder="e.g. CIN/LLPIN">
                                @error('propRegistration') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex justify-end gap-4 rounded-b-2xl sticky bottom-0 z-10">
                <button type="button" wire:click="$set('showPropertyModal', false)" class="px-6 py-2.5 text-slate-700 font-semibold hover:bg-slate-200 bg-slate-100 rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit" form="propertyForm" class="px-6 py-2.5 bg-blue-600 text-white font-bold hover:bg-blue-700 rounded-xl transition-colors shadow-sm">
                    Create Property
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
