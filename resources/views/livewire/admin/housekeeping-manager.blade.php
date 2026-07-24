<div class="space-y-6">
    {{-- Header & Stats --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <h2 class="text-xl font-bold text-slate-900 mb-6 border-b border-slate-100 pb-4">Housekeeping Dashboard</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex flex-col justify-center cursor-pointer hover:border-emerald-300 hover:bg-emerald-50 transition" wire:click="$set('statusFilter', 'clean')">
                <div class="text-2xl font-black text-slate-900">{{ $stats['clean'] }}</div>
                <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mt-1">Clean</div>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex flex-col justify-center cursor-pointer hover:border-red-300 hover:bg-red-50 transition" wire:click="$set('statusFilter', 'dirty')">
                <div class="text-2xl font-black text-slate-900">{{ $stats['dirty'] }}</div>
                <div class="text-xs font-semibold text-red-600 uppercase tracking-wider mt-1">Dirty</div>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex flex-col justify-center cursor-pointer hover:border-amber-300 hover:bg-amber-50 transition" wire:click="$set('statusFilter', 'inspect')">
                <div class="text-2xl font-black text-slate-900">{{ $stats['inspect'] }}</div>
                <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider mt-1">Inspect</div>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex flex-col justify-center cursor-pointer hover:border-slate-300 hover:bg-slate-100 transition" wire:click="$set('statusFilter', 'out_of_order')">
                <div class="text-2xl font-black text-slate-900">{{ $stats['out_of_order'] }}</div>
                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wider mt-1">Out of Order</div>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <button wire:click="$set('statusFilter', '')" class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $statusFilter === '' ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">All</button>
            <button wire:click="$set('statusFilter', 'clean')" class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $statusFilter === 'clean' ? 'bg-emerald-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50' }}">Clean</button>
            <button wire:click="$set('statusFilter', 'dirty')" class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $statusFilter === 'dirty' ? 'bg-red-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-red-50' }}">Dirty</button>
            <button wire:click="$set('statusFilter', 'inspect')" class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $statusFilter === 'inspect' ? 'bg-amber-500 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-amber-50' }}">Inspect</button>
        </div>
        
        <div class="relative w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search Room..." class="w-full bg-white border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Room Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($rooms as $room)
            @php
                $statusColors = [
                    'clean' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'dirty' => 'bg-red-100 text-red-700 border-red-200',
                    'inspect' => 'bg-amber-100 text-amber-700 border-amber-200',
                    'out_of_order' => 'bg-slate-100 text-slate-700 border-slate-200',
                ];
                $colorClass = $statusColors[$room->housekeeping_status] ?? $statusColors['clean'];
            @endphp
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-2xl font-black text-slate-900">{{ $room->room_number }}</div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $room->category->name ?? 'Standard' }}</div>
                    </div>
                    <div class="px-2.5 py-1 rounded-md border text-[10px] font-bold uppercase tracking-wider {{ $colorClass }}">
                        {{ str_replace('_', ' ', $room->housekeeping_status) }}
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-2 mt-auto pt-4 border-t border-slate-100">
                    @if($room->housekeeping_status !== 'clean')
                        <button wire:click="updateStatus({{ $room->id }}, 'clean')" class="flex-1 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold transition">Mark Clean</button>
                    @endif
                    @if($room->housekeeping_status !== 'dirty')
                        <button wire:click="updateStatus({{ $room->id }}, 'dirty')" class="flex-1 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded-lg text-xs font-bold transition">Mark Dirty</button>
                    @endif
                    @if($room->housekeeping_status !== 'inspect')
                        <button wire:click="updateStatus({{ $room->id }}, 'inspect')" class="flex-1 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-lg text-xs font-bold transition">Inspect</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                No rooms found matching your criteria.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $rooms->links() }}
    </div>
</div>
