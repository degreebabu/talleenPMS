<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xl">
    
    {{-- Toolbar --}}
    <div class="p-6 border-b border-slate-200 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Day Packages</h2>
            <p class="text-sm text-slate-500 mt-1">Manage day outing packages and pricing.</p>
        </div>
        
        <button wire:click="create" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-xl transition shadow-lg shadow-blue-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Package
        </button>
    </div>

    {{-- List --}}
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($packages as $package)
                <div class="bg-slate-100/50 border border-slate-200/50 rounded-xl p-5 hover:border-slate-600 transition group flex flex-col h-full relative overflow-hidden">
                    @if(!$package->is_active)
                        <div class="absolute inset-0 bg-white/60 backdrop-blur-[1px] z-10 flex items-center justify-center">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 text-sm font-medium rounded-full border border-slate-200">Inactive</span>
                        </div>
                    @endif
                    
                    <div class="flex justify-between items-start mb-4 relative z-20">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 group-hover:text-blue-400 transition">{{ $package->name }}</h3>
                            <div class="text-2xl font-bold text-slate-900 mt-1">₹{{ number_format($package->price, 2) }} <span class="text-sm font-normal text-slate-500">/ pax</span></div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="edit({{ $package->id }})" class="text-slate-500 hover:text-blue-400 transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            <button wire:click="delete({{ $package->id }})" onclick="confirm('Are you sure you want to delete this package?') || event.stopImmediatePropagation()" class="text-slate-500 hover:text-red-400 transition" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    
                    <p class="text-sm text-slate-500 mb-4 flex-grow">{{ $package->description }}</p>

                    <div class="space-y-2 text-sm relative z-20">
                        @if($package->start_time || $package->end_time)
                            <div class="flex items-center gap-2 text-slate-600">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $package->start_time ?? 'Anytime' }} - {{ $package->end_time ?? 'Closing' }}
                            </div>
                        @endif
                        
                        @if(!empty($package->inclusions))
                            <div class="mt-4 border-t border-slate-200/50 pt-3">
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">Inclusions</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($package->inclusions as $inc)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                            {{ $inc }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center border-2 border-dashed border-slate-200 rounded-xl">
                    <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    <h3 class="text-lg font-medium text-slate-900">No Day Packages</h3>
                    <p class="text-slate-500 mt-1">Create your first day outing package to get started.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-50/80 backdrop-blur-sm p-4">
            <div class="relative w-full max-w-2xl bg-white border border-slate-200 rounded-2xl shadow-2xl">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">{{ $isEditing ? 'Edit Package' : 'New Day Package' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-500 hover:text-slate-900 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Package Name *</label>
                        <input wire:model="name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Description</label>
                        <textarea wire:model="description" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Price per Pax *</label>
                            <input wire:model="price" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('price') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Start Time</label>
                            <input wire:model="start_time" type="time" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">End Time</label>
                            <input wire:model="end_time" type="time" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Inclusions (Comma separated)</label>
                        <input wire:model="inclusions" type="text" placeholder="e.g. Welcome Drink, Lunch, Pool Access" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="flex items-center mt-4">
                        <input wire:model="is_active" type="checkbox" id="is_active" class="w-4 h-4 text-blue-600 bg-slate-50 border-slate-200 rounded focus:ring-blue-500">
                        <label for="is_active" class="ml-2 text-sm font-medium text-slate-600">Package is Active</label>
                    </div>

                    <div class="pt-4 flex justify-end gap-3 border-t border-slate-200">
                        <button type="button" wire:click="$set('showModal', false)" class="px-5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-xl shadow-lg transition">Save Package</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
