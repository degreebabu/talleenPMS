@php
$statusColors = [
    'pending'     => 'bg-amber-50 text-amber-700 border-amber-200',
    'confirmed'   => 'bg-blue-50 text-blue-700 border-blue-200',
    'checked_in'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    'checked_out' => 'bg-slate-100 text-slate-600 border-slate-200',
    'cancelled'   => 'bg-red-50 text-red-600 border-red-200',
];
$statusLabels = [
    'pending'     => 'Pending',
    'confirmed'   => 'Confirmed',
    'checked_in'  => 'Checked In',
    'checked_out' => 'Checked Out',
    'cancelled'   => 'Cancelled',
];
@endphp

<div class="space-y-5">

    {{-- Flash message --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">All Bookings</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage reservations, check-ins and check-outs.</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Status Filter --}}
                <select wire:model.live="statusFilter" class="bg-slate-50 border border-slate-200 rounded-xl pl-3 pr-10 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="checked_in">Checked In</option>
                    <option value="checked_out">Checked Out</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                {{-- Search --}}
                <div class="relative w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by guest, ref..."
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                </div>

                <button wire:click="openCreate" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Booking
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Booking Ref</th>
                        <th class="px-6 py-4">Guest</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Booked On</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-slate-50/60 transition duration-150">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-slate-900 text-sm">{{ $booking->booking_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-900">{{ $booking->guest->name }}</div>
                            <div class="text-xs text-slate-400">{{ $booking->guest->phone }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="capitalize text-slate-600 font-medium">{{ str_replace('_', ' ', $booking->booking_type) }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">₹{{ number_format($booking->total_amount, 0) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border {{ $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $statusLabels[$booking->status] ?? $booking->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs">{{ $booking->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button wire:click="viewBooking({{ $booking->id }})"
                                        class="p-1.5 text-slate-400 hover:text-fuchsia-600 hover:bg-fuchsia-50 border border-transparent hover:border-fuchsia-100 rounded-lg transition" title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>

                                @if($booking->status === 'confirmed')
                                <button wire:click="checkIn({{ $booking->id }})"
                                        class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 border border-transparent hover:border-emerald-100 rounded-lg transition" title="Check In">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                </button>
                                @endif

                                @if($booking->status === 'checked_in')
                                <button wire:click="checkOut({{ $booking->id }})"
                                        class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-100 rounded-lg transition" title="Check Out">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                </button>
                                @endif

                                @if(in_array($booking->status, ['pending','confirmed']))
                                <button wire:click="cancelBooking({{ $booking->id }})" wire:confirm="Cancel this booking?"
                                        class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-lg transition" title="Cancel">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                @endif

                                <a href="{{ route('admin.bookings.invoice', $booking->id) }}" target="_blank"
                                   class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 border border-transparent hover:border-slate-200 rounded-lg transition" title="Print Invoice">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <p class="font-semibold text-slate-600">No bookings found</p>
                            <p class="text-sm text-slate-400 mt-1">Create your first booking using the button above.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $bookings->links(data: ['scrollTo' => false]) }}
        </div>
        @endif
    </div>

    {{-- Create Booking Modal --}}
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" wire:click.self="$set('showCreateModal', false)">
        <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-xl shadow-xl" x-data x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">New Walk-in Booking</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Create a manual booking for a walk-in or phone reservation.</p>
                </div>
                <button wire:click="$set('showCreateModal', false)" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-4 overflow-y-auto max-h-[70vh]">
                {{-- Guest Info --}}
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Guest Information</p>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Full Name *</label>
                            <input wire:model="guest_name" type="text" placeholder="John Doe" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            @error('guest_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Phone *</label>
                                <input wire:model="guest_phone" type="text" placeholder="+91 XXXXX XXXXX" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                @error('guest_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Email</label>
                                <input wire:model="guest_email" type="email" placeholder="optional" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Room Selection --}}
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Room & Dates</p>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Select Room *</label>
                            <select wire:model="room_id" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                <option value="">-- Choose a room --</option>
                                @foreach($rooms as $room)
                                <option value="{{ $room->id }}">
                                    Room {{ $room->room_number }} — {{ $room->category->name }} (₹{{ number_format($room->category->base_price, 0) }}/night)
                                </option>
                                @endforeach
                            </select>
                            @error('room_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Check-In *</label>
                                <input wire:model="check_in" type="date" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                @error('check_in') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Check-Out *</label>
                                <input wire:model="check_out" type="date" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                @error('check_out') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Adults *</label>
                                <input wire:model="adults" type="number" min="1" max="10" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Children</label>
                                <input wire:model="children" type="number" min="0" max="10" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Special Notes / Requests</label>
                    <textarea wire:model="notes" rows="2" placeholder="Any special requests or notes..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 resize-none"></textarea>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50 rounded-b-2xl flex justify-end gap-3">
                <button wire:click="$set('showCreateModal', false)" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 rounded-xl transition">Cancel</button>
                <button wire:click="createBooking" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition">
                    Confirm Booking
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Detail Modal --}}
    @if($showDetailModal && $selectedBooking)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" wire:click.self="$set('showDetailModal', false)">
        <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-lg shadow-xl">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">{{ $selectedBooking->booking_number }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Created {{ $selectedBooking->created_at->format('d M Y, H:i') }}</p>
                </div>
                <button wire:click="$set('showDetailModal', false)" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-5">
                {{-- Guest --}}
                <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr($selectedBooking->guest->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-slate-900">{{ $selectedBooking->guest->name }}</div>
                        <div class="text-xs text-slate-500">{{ $selectedBooking->guest->phone }} · {{ $selectedBooking->guest->email ?: 'No email' }}</div>
                    </div>
                    <div class="ml-auto">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border {{ $statusColors[$selectedBooking->status] ?? '' }}">
                            {{ $statusLabels[$selectedBooking->status] ?? $selectedBooking->status }}
                        </span>
                    </div>
                </div>

                {{-- Items --}}
                @foreach($selectedBooking->items as $item)
                @if($item->item)
                <div class="flex justify-between items-center text-sm">
                    <div>
                        <div class="font-semibold text-slate-900">Room {{ $item->item->room_number }} — {{ $item->item->category->name ?? '' }}</div>
                        <div class="text-xs text-slate-500">{{ $item->start_date->format('d M') }} → {{ $item->end_date->format('d M Y') }} · {{ $item->start_date->diffInDays($item->end_date) }} night(s)</div>
                    </div>
                    <div class="font-bold text-slate-900">₹{{ number_format($item->price, 0) }}</div>
                </div>
                @endif
                @endforeach

                <div class="border-t border-slate-100 pt-3 space-y-1">
                    <div class="flex justify-between text-sm text-slate-600">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($selectedBooking->total_amount - $selectedBooking->tax_amount, 0) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-600">
                        <span>GST (12%)</span>
                        <span>₹{{ number_format($selectedBooking->tax_amount, 0) }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-slate-900 text-base pt-1 border-t border-slate-200">
                        <span>Total</span>
                        <span>₹{{ number_format($selectedBooking->total_amount, 0) }}</span>
                    </div>
                </div>

                {{-- Pax & notes --}}
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="text-xs text-slate-500 font-medium">Guests</div>
                        <div class="font-bold text-slate-900 mt-0.5">{{ $selectedBooking->adults }} Adults, {{ $selectedBooking->children }} Children</div>
                    </div>
                    @if($selectedBooking->notes)
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-100">
                        <div class="text-xs text-amber-600 font-medium">Notes</div>
                        <div class="text-slate-700 text-xs mt-0.5">{{ $selectedBooking->notes }}</div>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 pt-2">
                    @if($selectedBooking->status === 'confirmed')
                    <button wire:click="checkIn({{ $selectedBooking->id }})" class="flex-1 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 font-semibold text-sm rounded-xl transition">✓ Check In</button>
                    @endif
                    @if($selectedBooking->status === 'checked_in')
                    <button wire:click="checkOut({{ $selectedBooking->id }})" class="flex-1 py-2 bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 font-semibold text-sm rounded-xl transition">→ Check Out</button>
                    @endif
                    <a href="{{ route('admin.bookings.folio', $selectedBooking->id) }}"
                       class="flex-1 py-2 text-center bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 font-semibold text-sm rounded-xl transition">🧾 Folio</a>
                    <a href="{{ route('admin.bookings.invoice', $selectedBooking->id) }}" target="_blank"
                       class="flex-1 py-2 text-center bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100 font-semibold text-sm rounded-xl transition">🖨 Invoice</a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
