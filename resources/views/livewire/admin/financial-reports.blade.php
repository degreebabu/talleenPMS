<div class="space-y-6">

    {{-- Toolbar --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Financial Reports</h2>
            <p class="text-sm text-slate-500 mt-1">Analyze revenue, bookings, and occupancy over time.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <select wire:model.live="dateRange" class="bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-2 text-sm text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                <option value="today">Today</option>
                <option value="this_week">This Week</option>
                <option value="this_month">This Month</option>
                <option value="last_month">Last Month</option>
                <option value="this_year">This Year</option>
            </select>
            <button onclick="window.print()" class="p-2.5 bg-slate-50 border border-slate-200 hover:bg-slate-100 text-slate-600 rounded-xl transition" title="Print Report">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            </button>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Total Revenue</div>
            <div class="text-3xl font-black text-slate-900 tracking-tight">₹{{ number_format($totalRevenue, 0) }}</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Total Bookings</div>
            <div class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($totalBookings) }}</div>
        </div>
        <div class="bg-white border border-emerald-100 rounded-2xl p-6 shadow-sm">
            <div class="text-emerald-600 text-xs font-bold uppercase tracking-wider mb-2">Confirmed Bookings</div>
            <div class="text-3xl font-black text-emerald-700 tracking-tight">{{ number_format($confirmedBookings) }}</div>
        </div>
        <div class="bg-white border border-red-100 rounded-2xl p-6 shadow-sm">
            <div class="text-red-500 text-xs font-bold uppercase tracking-wider mb-2">Cancelled Bookings</div>
            <div class="text-3xl font-black text-red-600 tracking-tight">{{ number_format($cancelledBookings) }}</div>
        </div>
    </div>

    {{-- Detailed breakdowns --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Revenue by Type --}}
        <div class="md:col-span-1 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-900">Revenue by Source</h3>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-center">
                @if(empty($revenueByType))
                    <div class="text-center text-slate-400 py-8">
                        No revenue data for this period.
                    </div>
                @else
                    <ul class="space-y-4">
                        @foreach($revenueByType as $type => $amount)
                        <li>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-semibold text-slate-700 capitalize">{{ $type }}</span>
                                <span class="text-sm font-bold text-slate-900">₹{{ number_format($amount, 0) }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $totalRevenue > 0 ? ($amount / $totalRevenue) * 100 : 0 }}%"></div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="md:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-900">Recent Transactions</h3>
            </div>
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-white text-slate-400 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Ref</th>
                            <th class="px-6 py-3">Guest</th>
                            <th class="px-6 py-3 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentTransactions as $tx)
                        <tr>
                            <td class="px-6 py-4 text-slate-500">{{ $tx->created_at->format('M d, H:i') }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-900">{{ $tx->booking_number }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $tx->guest->name }}</td>
                            <td class="px-6 py-4 text-right font-bold {{ $tx->status === 'cancelled' ? 'text-red-500 line-through' : 'text-emerald-600' }}">
                                ₹{{ number_format($tx->total_amount, 0) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-400">No transactions in this period.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
