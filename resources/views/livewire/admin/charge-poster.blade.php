<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Post Charge to Folio</h2>
            <p class="text-sm text-slate-500 mt-1">Easily bill guests for Spa, Gift Shop, or Room Service purchases.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl font-medium">{{ session('success') }}</div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm max-w-2xl">
        <div class="space-y-6">
            {{-- Guest Search --}}
            <div class="relative">
                <label class="block text-sm font-bold text-slate-700 mb-1">Search Checked-In Guest <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <input wire:model.live.debounce.300ms="searchQuery" type="text" placeholder="Type name or booking #..." 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500"
                        {{ $selectedBookingId ? 'disabled' : '' }}>
                    
                    @if($selectedBookingId)
                    <button wire:click="clearSelection" class="px-3 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl transition">×</button>
                    @endif
                </div>

                @if(!empty($searchResults) && !$selectedBookingId)
                <div class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden">
                    @foreach($searchResults as $booking)
                    <button wire:click="selectBooking({{ $booking->id }})" class="w-full text-left px-4 py-3 hover:bg-slate-50 border-b border-slate-100 last:border-0 transition">
                        <div class="font-bold text-slate-900">{{ $booking->guest->name }}</div>
                        <div class="text-xs text-slate-500">Room {{ $booking->room->room_number ?? 'TBD' }} · {{ $booking->booking_number }}</div>
                    </button>
                    @endforeach
                </div>
                @endif
                @error('selectedBookingId') <span class="text-red-500 text-xs">Please select a guest.</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Department <span class="text-red-500">*</span></label>
                    <select wire:model="department" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="spa">Spa & Wellness</option>
                        <option value="gift_shop">Gift Shop</option>
                        <option value="room_service">Room Service</option>
                        <option value="laundry">Laundry</option>
                        <option value="other">Other / Misc</option>
                    </select>
                    @error('department') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Amount (₹) <span class="text-red-500">*</span></label>
                    <input wire:model="amount" type="number" step="0.01" placeholder="0.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Description <span class="text-red-500">*</span></label>
                    <input wire:model="description" type="text" placeholder="e.g. 60-min Deep Tissue Massage" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 text-right">
                <button wire:click="postCharge" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-sm transition shadow-sm border border-blue-700">
                    Post to Room Folio
                </button>
            </div>
        </div>
    </div>
</div>
