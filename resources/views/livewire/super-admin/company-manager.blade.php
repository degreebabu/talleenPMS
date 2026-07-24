<div class="p-6 lg:p-10 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Hospitality Groups</h1>
            <p class="text-slate-500 mt-1">Manage parent companies and multi-property groups.</p>
        </div>
        <button wire:click="openModal" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Group
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Companies Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($companies as $company)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition duration-200 flex flex-col h-full">
                <div class="p-6 flex-grow">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-xl shadow-sm border border-blue-100">
                            {{ substr($company->name, 0, 1) }}
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="toggleActive({{ $company->id }})" class="p-2 text-slate-400 hover:text-{{ $company->is_active ? 'amber' : 'green' }}-600 bg-slate-50 hover:bg-slate-100 rounded-lg transition" title="Toggle Status">
                                @if($company->is_active)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </button>
                            <button wire:click="editCompany({{ $company->id }})" class="p-2 text-slate-400 hover:text-blue-600 bg-slate-50 hover:bg-slate-100 rounded-lg transition" title="Edit Group">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-slate-900 mb-1 flex items-center gap-2">
                        {{ $company->name }}
                        @if(!$company->is_active)
                            <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-medium border border-slate-200">Inactive</span>
                        @endif
                    </h3>
                    
                    <div class="space-y-2 mt-4 text-sm text-slate-600">
                        @if($company->contact_email)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $company->contact_email }}
                        </div>
                        @endif
                        @if($company->contact_phone)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $company->contact_phone }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="bg-slate-50 p-4 border-t border-slate-100 rounded-b-2xl flex items-center justify-between">
                    <div class="text-sm font-medium text-slate-600">Properties</div>
                    <div class="bg-white px-3 py-1 rounded-full text-sm font-semibold text-blue-600 border border-slate-200 shadow-sm">
                        {{ $company->hotels_count }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white border border-slate-200 rounded-2xl border-dashed">
                <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-lg font-medium text-slate-900 mb-1">No Hospitality Groups</h3>
                <p class="text-slate-500 mb-4">You haven't created any parent companies yet.</p>
                <button wire:click="openModal" class="text-blue-600 font-medium hover:text-blue-700">Create the first group</button>
            </div>
        @endforelse
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl transform transition-all flex flex-col max-h-[90vh]">
            
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight">{{ $companyId ? 'Edit Hospitality Group' : 'Create Hospitality Group' }}</h3>
                    <p class="text-sm text-slate-500 mt-1">Fill in the corporate entity details below.</p>
                </div>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 hover:bg-slate-50 p-2 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <!-- Modal Body (Scrollable) -->
            <div class="p-8 overflow-y-auto flex-1">
                <form id="companyForm" wire:submit.prevent="saveCompany" class="space-y-8">
                    
                    <!-- Basic Info Section -->
                    <div class="space-y-5">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Corporate Information</h4>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Company Name <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <input type="text" wire:model="name" class="pl-10 w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-900" required placeholder="e.g. Taj Group">
                            </div>
                            @error('name') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">GST Number</label>
                                <input type="text" wire:model="gst_number" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-900" placeholder="e.g. 27AAAAA0000A1Z5">
                                @error('gst_number') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Registration Number</label>
                                <input type="text" wire:model="registration_number" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-900" placeholder="e.g. CIN/LLPIN">
                                @error('registration_number') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 my-6"></div>
                    
                    <!-- Contact Section -->
                    <div class="space-y-5">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Contact & Location</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Corporate Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <input type="email" wire:model="contact_email" class="pl-10 w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-900" placeholder="hello@group.com">
                                </div>
                                @error('contact_email') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Corporate Phone</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <input type="text" wire:model="contact_phone" class="pl-10 w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-900" placeholder="+1 (555) 000-0000">
                                </div>
                                @error('contact_phone') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Headquarters Address</label>
                            <textarea wire:model="address" rows="3" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-900" placeholder="Enter full address..."></textarea>
                            @error('address') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-slate-50 p-5 rounded-xl border border-slate-100 flex items-center justify-between">
                        <div>
                            <label for="isActive" class="text-sm font-bold text-slate-900 block cursor-pointer">Group Status</label>
                            <p class="text-xs text-slate-500 mt-0.5">Toggle to instantly suspend or activate all properties under this group.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" id="isActive" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex justify-end gap-4 rounded-b-2xl sticky bottom-0 z-10">
                <button type="button" wire:click="$set('showModal', false)" class="px-6 py-2.5 text-slate-700 font-semibold hover:bg-slate-200 bg-slate-100 rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit" form="companyForm" class="px-6 py-2.5 bg-blue-600 text-white font-bold hover:bg-blue-700 rounded-xl transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $companyId ? 'Save Changes' : 'Create Group' }}
                </button>
            </div>
            
        </div>
    </div>
    @endif
</div>
