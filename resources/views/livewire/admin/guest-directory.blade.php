<div>
    {{-- Search --}}
    <div class="relative mb-6">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name, phone or email..." class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
    </div>

    {{-- Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Guest</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Stays</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Revenue</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Since</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($guests as $guest)
                <tr class="hover:bg-slate-50 transition group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($guest->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-slate-900">{{ $guest->name }}</div>
                                @if($guest->nationality)
                                <div class="text-xs text-slate-500">{{ $guest->nationality }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $guest->phone }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $guest->email ?: '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold">{{ $guest->bookings_count }}</span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-slate-900">₹{{ number_format($guest->total_revenue, 0) }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $guest->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <button wire:click="viewGuest({{ $guest->id }})" class="text-indigo-600 text-xs font-semibold hover:underline">View Profile</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-slate-400 text-sm">No guests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-100">{{ $guests->links() }}</div>
    </div>

    {{-- Guest Profile Modal --}}
    @if($showModal && $selectedGuest)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" wire:click.self="$set('showModal', false)">
        <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-lg shadow-xl">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">Guest Profile</h3>
                <button wire:click="$set('showModal', false)" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-700 font-black text-2xl">
                        {{ strtoupper(substr($selectedGuest->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-xl font-bold text-slate-900">{{ $selectedGuest->name }}</div>
                        <div class="text-sm text-slate-500">{{ $selectedGuest->phone }}</div>
                        <div class="text-sm text-slate-500">{{ $selectedGuest->email ?: 'No email' }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-blue-50 rounded-xl p-3 text-center">
                        <div class="text-2xl font-black text-blue-700">{{ $selectedGuest->bookings_count }}</div>
                        <div class="text-xs text-blue-500 font-semibold">Total Stays</div>
                    </div>
                    <div class="bg-emerald-50 rounded-xl p-3 text-center">
                        <div class="text-2xl font-black text-emerald-700">₹{{ number_format($selectedGuest->total_revenue, 0) }}</div>
                        <div class="text-xs text-emerald-500 font-semibold">Total Revenue</div>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-3 text-center">
                        <div class="text-2xl font-black text-purple-700">{{ $selectedGuest->created_at->diffInDays() }}d</div>
                        <div class="text-xs text-purple-500 font-semibold">Guest Since</div>
                    </div>
                </div>

                @if($selectedGuest->bookings->isNotEmpty())
                <div>
                    <h4 class="font-bold text-slate-700 mb-3">Recent Bookings</h4>
                    <div class="space-y-2">
                        @foreach($selectedGuest->bookings as $booking)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <div>
                                <div class="font-semibold text-slate-900 text-sm">{{ $booking->booking_number }}</div>
                                <div class="text-xs text-slate-500">{{ $booking->created_at->format('d M Y') }}</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold text-slate-900">₹{{ number_format($booking->total_amount, 0) }}</span>
                                <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $booking->status === 'checked_out' ? 'bg-slate-100 text-slate-600' : 'bg-blue-50 text-blue-700' }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
