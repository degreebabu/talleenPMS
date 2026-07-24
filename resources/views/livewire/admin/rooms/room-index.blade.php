<x-slot name="header">All Rooms</x-slot>

<div>
{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <p class="text-slate-500 text-sm">Manage individual room inventory and statuses</p>
    <button wire:click="openCreate" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Room
    </button>
</div>

{{-- Rooms Table --}}
@if($rooms->count())
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm mb-6">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50">
                <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Room #</th>
                <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Floor</th>
                <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="text-right px-5 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @foreach($rooms as $room)
            @php
            $statusColors = [
                'available'   => 'text-emerald-400 bg-emerald-400/10',
                'occupied'    => 'text-blue-400 bg-blue-400/10',
                'dirty'       => 'text-amber-400 bg-amber-400/10',
                'maintenance' => 'text-red-400 bg-red-400/10',
            ];
            @endphp
            <tr class="hover:bg-slate-100/50 transition">
                <td class="px-5 py-3.5 font-semibold text-slate-900">{{ $room->room_number }}</td>
                <td class="px-5 py-3.5 text-slate-600">{{ $room->floor ?? '—' }}</td>
                <td class="px-5 py-3.5 text-slate-600">{{ $room->category?->name ?? '—' }}</td>
                <td class="px-5 py-3.5">
                    <select wire:change="updateStatus({{ $room->id }}, $event.target.value)"
                            class="text-xs font-medium px-2 py-1 rounded-lg border-0 bg-transparent {{ $statusColors[$room->status] ?? '' }} cursor-pointer focus:outline-none">
                        <option value="available"   @selected($room->status === 'available')>Available</option>
                        <option value="occupied"    @selected($room->status === 'occupied')>Occupied</option>
                        <option value="dirty"       @selected($room->status === 'dirty')>Dirty</option>
                        <option value="maintenance" @selected($room->status === 'maintenance')>Maintenance</option>
                    </select>
                </td>
                <td class="px-5 py-3.5 text-right">
                    <button wire:click="openEdit({{ $room->id }})" class="text-xs text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition mr-1">Edit</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $rooms->links() }}
@else
<div class="bg-white border border-dashed border-slate-200 rounded-2xl p-16 text-center">
    <svg class="w-12 h-12 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
    <h3 class="text-slate-900 font-semibold mb-2">No rooms yet</h3>
    <p class="text-slate-500 text-sm mb-4">Add your first room to get started</p>
    <button wire:click="openCreate" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">+ Add First Room</button>
</div>
@endif

{{-- Modal --}}
@if($showModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" wire:click.self="$set('showModal', false)">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-md shadow-2xl">
        <div class="flex items-center justify-between p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">{{ $editingId ? 'Edit' : 'New' }} Room</h3>
            <button wire:click="$set('showModal', false)" class="text-slate-500 hover:text-slate-900"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Room Number *</label>
                    <input wire:model="room_number" type="text" placeholder="e.g. 101" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('room_number') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1.5">Floor</label>
                    <input wire:model="floor" type="text" placeholder="e.g. 1st" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1.5">Category *</label>
                <select wire:model="room_category_id" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select category...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('room_category_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1.5">Status</label>
                <select wire:model="status" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="dirty">Dirty</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
        </div>

        <div class="flex gap-3 p-6 pt-0">
            <button wire:click="$set('showModal', false)" class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition">Cancel</button>
            <button wire:click="save" wire:loading.attr="disabled" class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition disabled:opacity-50">
                <span wire:loading.remove>Save Room</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </div>
</div>
@endif
</div>
