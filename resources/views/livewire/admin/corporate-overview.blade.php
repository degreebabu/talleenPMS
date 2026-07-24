<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">{{ $company->name }} Overview</h2>
            <p class="text-sm text-slate-500 mt-1">Corporate Dashboard for Group Managers</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="text-sm font-medium text-slate-500 mb-1">Total Properties</div>
            <div class="text-3xl font-bold text-slate-900">{{ $totalHotels }}</div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="text-sm font-medium text-slate-500 mb-1">Total Rooms</div>
            <div class="text-3xl font-bold text-slate-900">{{ $totalRooms }}</div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="text-sm font-medium text-slate-500 mb-1">Avg Occupancy</div>
            <div class="text-3xl font-bold text-blue-600">{{ $totalOccupancy }}%</div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="text-sm font-medium text-slate-500 mb-1">Total Revenue</div>
            <div class="text-3xl font-bold text-emerald-600">${{ number_format($totalRevenue, 2) }}</div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-800 text-lg">Group Properties</h3>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($hotels as $hotel)
                <div class="p-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                    <div>
                        <div class="font-semibold text-slate-900">{{ $hotel->name }}</div>
                        <div class="text-sm text-slate-500 mt-0.5">{{ $hotel->subdomain }}.talleen.com</div>
                    </div>
                    <div>
                        <a href="{{ route('admin.switch-property', $hotel->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 px-4 py-2 rounded-xl transition-colors">
                            Switch to Dashboard &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500">
                    No properties added to this group yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
