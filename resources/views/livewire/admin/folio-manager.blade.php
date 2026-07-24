<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm text-slate-500 hover:text-slate-700 flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Bookings
            </a>
            <h2 class="text-2xl font-bold text-slate-900">Guest Folio: {{ $booking->booking_number }}</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $booking->guest->name }} · Check-in {{ $booking->items->first()?->start_date?->format('d M Y') }} → Check-out {{ $booking->items->first()?->end_date?->format('d M Y') }}</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Folio Summary --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-900">Room & Base Charges</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($booking->items as $item)
                        @if($item->item)
                        <div class="flex justify-between items-center text-sm border-b border-slate-100 pb-4">
                            <div>
                                <div class="font-bold text-slate-900">Room {{ $item->item->room_number }} — {{ $item->item->category->name ?? '' }}</div>
                                <div class="text-slate-500 mt-1">{{ $item->start_date->format('d M Y') }} → {{ $item->end_date->format('d M Y') }}</div>
                            </div>
                            <div class="font-bold text-slate-900 text-base">₹{{ number_format($item->price, 0) }}</div>
                        </div>
                        @endif
                        @endforeach
                        
                        <div class="flex justify-between items-center text-sm pb-2">
                            <div class="font-semibold text-slate-600">Taxes (GST 12%)</div>
                            <div class="font-bold text-slate-600 text-base">₹{{ number_format($booking->tax_amount, 0) }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-900">Extra Charges</h3>
                    </div>
                    <div class="p-6">
                        @if($charges->isEmpty())
                            <div class="text-center text-slate-500 py-8 text-sm">No extra charges posted to this folio yet.</div>
                        @else
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="text-slate-500 border-b border-slate-200">
                                        <th class="pb-3 font-semibold">Date</th>
                                        <th class="pb-3 font-semibold">Description</th>
                                        <th class="pb-3 font-semibold">Type</th>
                                        <th class="pb-3 font-semibold text-right">Amount</th>
                                        <th class="pb-3 text-right"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($charges as $charge)
                                    <tr class="group hover:bg-slate-50 transition">
                                        <td class="py-3 text-slate-600">{{ $charge->created_at->format('d M, H:i') }}</td>
                                        <td class="py-3 font-medium text-slate-900">{{ $charge->description }}</td>
                                        <td class="py-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wide bg-slate-100 text-slate-600">
                                                {{ $charge->charge_type }}
                                            </span>
                                        </td>
                                        <td class="py-3 font-bold text-slate-900 text-right">₹{{ number_format($charge->amount, 0) }}</td>
                                        <td class="py-3 text-right">
                                            <button wire:click="deleteCharge({{ $charge->id }})" wire:confirm="Are you sure you want to delete this charge?" class="text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions sidebar --}}
            <div class="space-y-6">
                <div class="bg-slate-900 rounded-2xl p-6 shadow-xl text-white">
                    <div class="text-slate-400 text-sm font-semibold uppercase tracking-wider mb-2">Total Balance</div>
                    <div class="text-4xl font-black">₹{{ number_format($booking->total_amount, 0) }}</div>
                    
                    <a href="{{ route('admin.bookings.invoice', $booking->id) }}" target="_blank" class="mt-6 block w-full py-3 bg-white text-slate-900 font-bold text-center rounded-xl hover:bg-slate-100 transition">
                        Print Invoice
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Post a Charge</h3>
                    <form wire:submit.prevent="addCharge" class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Type</label>
                            <select wire:model="charge_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="food">Food & Beverage (POS)</option>
                                <option value="spa">Spa & Wellness</option>
                                <option value="laundry">Laundry</option>
                                <option value="other">Other / Misc</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                            <input wire:model="description" type="text" placeholder="e.g. Room Service order" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Amount (₹)</label>
                            <input wire:model="amount" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition shadow-md">
                            Add Charge
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
