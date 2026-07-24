<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    
    {{-- Header & Stats --}}
    <div class="p-6 border-b border-slate-100">
        <div class="flex flex-col md:flex-row justify-between md:items-end gap-6 mb-8">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Housekeeping</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">Manage real-time room statuses and cleaning tasks.</p>
            </div>
            
            {{-- Filters --}}
            <div class="flex bg-slate-50 p-1.5 rounded-xl border border-slate-200 shadow-inner">
                <button wire:click="$set('filter', 'all')" class="px-4 py-2 text-sm font-semibold rounded-lg transition {{ $filter === 'all' ? 'bg-white text-slate-900 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-700' }}">
                    All
                </button>
                <button wire:click="$set('filter', 'clean')" class="px-4 py-2 text-sm font-semibold rounded-lg transition {{ $filter === 'clean' ? 'bg-emerald-50 text-emerald-700 shadow-sm border border-emerald-200' : 'text-slate-500 hover:text-emerald-600' }}">
                    Clean
                </button>
                <button wire:click="$set('filter', 'dirty')" class="px-4 py-2 text-sm font-semibold rounded-lg transition {{ $filter === 'dirty' ? 'bg-amber-50 text-amber-700 shadow-sm border border-amber-200' : 'text-slate-500 hover:text-amber-600' }}">
                    Dirty
                </button>
                <button wire:click="$set('filter', 'maintenance')" class="px-4 py-2 text-sm font-semibold rounded-lg transition {{ $filter === 'maintenance' ? 'bg-red-50 text-red-700 shadow-sm border border-red-200' : 'text-slate-500 hover:text-red-600' }}">
                    Maintenance
                </button>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 shadow-sm">
                <div class="text-sm font-semibold text-slate-500 mb-1">Total Rooms</div>
                <div class="text-3xl font-black text-slate-900">{{ $stats['total'] }}</div>
            </div>
            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-5 shadow-sm">
                <div class="text-sm font-semibold text-emerald-600 mb-1">Clean</div>
                <div class="text-3xl font-black text-emerald-700">{{ $stats['clean'] }}</div>
            </div>
            <div class="bg-amber-50 border border-amber-100 rounded-xl p-5 shadow-sm">
                <div class="text-sm font-semibold text-amber-600 mb-1">Dirty / Needs Cleaning</div>
                <div class="text-3xl font-black text-amber-700">{{ $stats['dirty'] }}</div>
            </div>
            <div class="bg-red-50 border border-red-100 rounded-xl p-5 shadow-sm">
                <div class="text-sm font-semibold text-red-600 mb-1">Maintenance</div>
                <div class="text-3xl font-black text-red-700">{{ $stats['maintenance'] }}</div>
            </div>
        </div>
    </div>

    {{-- Room Grid --}}
    <div class="p-6 bg-slate-50">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @forelse($rooms as $room)
                @php
                    $colors = [
                        'clean' => 'border-emerald-200 bg-emerald-50 hover:border-emerald-300 shadow-sm',
                        'dirty' => 'border-amber-200 bg-amber-50 hover:border-amber-300 shadow-sm',
                        'maintenance' => 'border-red-200 bg-red-50 hover:border-red-300 shadow-sm',
                    ];
                    $bg = $colors[$room->housekeeping_status] ?? 'border-slate-200 bg-white shadow-sm';

                    $textColors = [
                        'clean' => 'text-emerald-700',
                        'dirty' => 'text-amber-700',
                        'maintenance' => 'text-red-700',
                    ];
                    $text = $textColors[$room->housekeeping_status] ?? 'text-slate-600';
                @endphp
                <div class="border rounded-2xl p-5 flex flex-col items-center justify-center text-center transition-all duration-200 {{ $bg }}">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ $room->category->name }}</span>
                    <span class="text-3xl font-black text-slate-900 mb-2">{{ $room->number }}</span>
                    
                    <span class="text-xs font-bold uppercase tracking-wider mb-5 {{ $text }}">
                        {{ $room->housekeeping_status }}
                    </span>
                    
                    {{-- Quick Action Dropdown (simulated with Alpine) --}}
                    <div x-data="{ open: false }" class="relative w-full">
                        <button @click="open = !open" @click.away="open = false" class="w-full py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl transition duration-150 border border-slate-200 shadow-sm flex items-center justify-center gap-1">
                            Update
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        
                        <div x-show="open" style="display: none;" class="absolute bottom-full left-0 w-full mb-2 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden z-10 py-1">
                            @if($room->housekeeping_status !== 'clean')
                                <button wire:click="updateRoomStatus({{ $room->id }}, 'clean')" @click="open = false" class="w-full text-left px-4 py-2.5 text-xs font-semibold text-emerald-600 hover:bg-emerald-50 transition">Mark Clean</button>
                            @endif
                            @if($room->housekeeping_status !== 'dirty')
                                <button wire:click="updateRoomStatus({{ $room->id }}, 'dirty')" @click="open = false" class="w-full text-left px-4 py-2.5 text-xs font-semibold text-amber-600 hover:bg-amber-50 transition">Mark Dirty</button>
                            @endif
                            @if($room->housekeeping_status !== 'maintenance')
                                <button wire:click="updateRoomStatus({{ $room->id }}, 'maintenance')" @click="open = false" class="w-full text-left px-4 py-2.5 text-xs font-semibold text-red-600 hover:bg-red-50 transition">Maintenance</button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-500">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200 shadow-sm">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <p class="font-medium text-slate-600">No rooms found matching this status.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
