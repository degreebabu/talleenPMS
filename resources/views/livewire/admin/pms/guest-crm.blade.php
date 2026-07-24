<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    
    {{-- Toolbar --}}
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Guest CRM</h2>
            <p class="text-sm text-slate-500 mt-1 font-medium">Manage guest profiles, history, and total spending.</p>
        </div>
        
        <div class="relative w-full sm:w-80">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search guests..." 
                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm">
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold tracking-wider border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Guest Name</th>
                    <th class="px-6 py-4">Contact</th>
                    <th class="px-6 py-4">Document ID</th>
                    <th class="px-6 py-4 text-center">Total Stays</th>
                    <th class="px-6 py-4 text-right">Total Spend</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($guests as $guest)
                    <tr class="hover:bg-slate-50/80 transition duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm border border-blue-100 shadow-sm">
                                    {{ substr($guest->first_name, 0, 1) }}{{ substr($guest->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900">{{ $guest->first_name }} {{ $guest->last_name }}</div>
                                    <div class="text-[11px] text-slate-500 uppercase tracking-wider mt-0.5 font-medium">Guest Since {{ $guest->created_at->format('M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-700 font-medium">{{ $guest->email ?? 'N/A' }}</div>
                            <div class="text-slate-500 text-xs mt-0.5">{{ $guest->phone ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-700">
                            <span class="font-medium">{{ $guest->document_type ?? 'None' }}</span>
                            @if($guest->document_number)
                                <div class="text-xs text-slate-500 mt-0.5">{{ $guest->document_number }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $guest->bookings_count }} Bookings
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                                ₹{{ number_format($guest->bookings_sum_total_amount ?? 0, 0) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-500">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <p class="font-medium text-slate-600">No guests found matching your criteria.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($guests->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $guests->links(data: ['scrollTo' => false]) }}
        </div>
    @endif
</div>
