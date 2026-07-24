<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-wide">Platform Reports</h2>
            <p class="text-slate-500 mt-1">Comprehensive data grid and analytics for all active tenants.</p>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold shadow-sm hover:bg-slate-50 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </button>
        </div>
    </div>

    <!-- Aggregate KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 shadow-md text-white">
            <p class="text-blue-100 text-sm font-semibold uppercase tracking-wider mb-2">Total Platform Bookings</p>
            <p class="text-4xl font-bold">{{ number_format($platformTotalBookings) }}</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 shadow-md text-white">
            <p class="text-emerald-100 text-sm font-semibold uppercase tracking-wider mb-2">Total Platform Revenue</p>
            <p class="text-4xl font-bold">₹{{ number_format($platformTotalRevenue) }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-fuchsia-600 rounded-2xl p-6 shadow-md text-white">
            <p class="text-purple-100 text-sm font-semibold uppercase tracking-wider mb-2">Total Platform Users</p>
            <p class="text-4xl font-bold">{{ number_format($platformTotalUsers) }}</p>
        </div>
    </div>

    <!-- Data Grid -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-lg font-bold text-slate-900">Tenant Analytics Breakdown</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Tenant Name</th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Registered</th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Active Users</th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Total Bookings</th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px] text-right">Gross Revenue (GMV)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tenants as $tenant)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $tenant->name }}</div>
                            <div class="text-xs text-slate-500">{{ $tenant->subdomain }}.talleen.com</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">
                            {{ $tenant->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
                                {{ $tenant->users_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-700 font-bold">
                            {{ number_format($tenant->bookings_count) }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-600">
                            ₹{{ number_format($tenant->total_revenue ?? 0, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-medium">
                            No active tenants found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
