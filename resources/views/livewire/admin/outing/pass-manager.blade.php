<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xl">
    
    {{-- Toolbar --}}
    <div class="p-6 border-b border-slate-200 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Day Passes</h2>
            <p class="text-sm text-slate-500 mt-1">Manage and validate guest day passes.</p>
        </div>
        
        <div class="relative w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name, email, phone..." 
                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50/50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 font-medium">Customer Details</th>
                    <th class="px-6 py-4 font-medium">Package</th>
                    <th class="px-6 py-4 font-medium">Visit Date</th>
                    <th class="px-6 py-4 font-medium">Pax</th>
                    <th class="px-6 py-4 font-medium">Amount</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/50">
                @forelse($passes as $pass)
                    <tr class="hover:bg-slate-100/30 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900">{{ $pass->customer_name }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $pass->customer_phone ?? $pass->customer_email }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $pass->package->name ?? 'Unknown Package' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="{{ $pass->visit_date->isToday() ? 'text-blue-400 font-medium' : 'text-slate-600' }}">
                                {{ $pass->visit_date->format('M d, Y') }}
                            </span>
                            @if($pass->visit_date->isToday())
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">Today</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $pass->pax }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">
                            ₹{{ number_format($pass->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    'confirmed' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                    'used' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                    'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                ];
                                $color = $statusColors[$pass->status] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $color }}">
                                {{ ucfirst($pass->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($pass->status === 'confirmed')
                                <button wire:click="updateStatus({{ $pass->id }}, 'used')" class="inline-flex items-center px-3 py-1.5 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 text-xs font-medium rounded-lg transition border border-emerald-500/20">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Mark Used
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            No day passes found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($passes->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $passes->links(data: ['scrollTo' => false]) }}
        </div>
    @endif
</div>
