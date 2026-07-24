<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xl">
    
    {{-- Toolbar --}}
    <div class="p-6 border-b border-slate-200 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Event Spaces</h2>
            <p class="text-sm text-slate-500 mt-1">Manage your banquet halls, lawns, and meeting rooms.</p>
        </div>
        
        <button wire:click="create" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-xl transition shadow-lg shadow-blue-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Space
        </button>
    </div>

    {{-- Spaces List --}}
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($spaces as $space)
                <div class="bg-slate-100/50 border border-slate-200/50 rounded-xl p-5 hover:border-slate-600 transition group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 group-hover:text-blue-400 transition">{{ $space->name }}</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-700 text-slate-600 mt-1 uppercase tracking-wider">
                                {{ str_replace('_', ' ', $space->type) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="edit({{ $space->id }})" class="text-slate-500 hover:text-blue-400 transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            <button wire:click="delete({{ $space->id }})" onclick="confirm('Are you sure you want to delete this space?') || event.stopImmediatePropagation()" class="text-slate-500 hover:text-red-400 transition" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm text-slate-500">
                        <div class="flex items-center justify-between">
                            <span>Capacity:</span>
                            <span class="text-slate-900 font-medium">{{ $space->capacity }} pax</span>
                        </div>
                        @if($space->price_per_hour)
                        <div class="flex items-center justify-between">
                            <span>Price / Hour:</span>
                            <span class="text-slate-900 font-medium">${{ number_format($space->price_per_hour, 2) }}</span>
                        </div>
                        @endif
                        @if($space->price_per_day)
                        <div class="flex items-center justify-between">
                            <span>Price / Day:</span>
                            <span class="text-slate-900 font-medium">${{ number_format($space->price_per_day, 2) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center border-2 border-dashed border-slate-200 rounded-xl">
                    <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <h3 class="text-lg font-medium text-slate-900">No Event Spaces</h3>
                    <p class="text-slate-500 mt-1">Get started by creating a banquet hall or lawn.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-50/80 backdrop-blur-sm p-4">
            <div class="relative w-full max-w-2xl bg-white border border-slate-200 rounded-2xl shadow-2xl">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">{{ $isEditing ? 'Edit Space' : 'New Event Space' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-500 hover:text-slate-900 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Space Name *</label>
                        <input wire:model="name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Type *</label>
                            <select wire:model="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="hall">Banquet Hall</option>
                                <option value="lawn">Lawn / Outdoor</option>
                                <option value="meeting_room">Meeting Room</option>
                            </select>
                            @error('type') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Max Capacity (Pax) *</label>
                            <input wire:model="capacity" type="number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('capacity') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Price per Hour</label>
                            <input wire:model="price_per_hour" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Price per Day</label>
                            <input wire:model="price_per_day" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Status *</label>
                        <select wire:model="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="maintenance">Under Maintenance</option>
                        </select>
                    </div>

                    <div class="pt-4 flex justify-end gap-3 border-t border-slate-200">
                        <button type="button" wire:click="$set('showModal', false)" class="px-5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-xl shadow-lg transition">Save Space</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
