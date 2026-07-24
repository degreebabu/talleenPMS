<div class="space-y-6">
    {{-- Toolbar --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Tape Chart / Calendar</h2>
            <p class="text-sm text-slate-500">Visual overview of room occupancy</p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="previousPeriod" class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-slate-600 transition" title="Previous 7 Days">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button wire:click="today" class="px-4 py-2 font-semibold bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-slate-700 text-sm transition">
                Today
            </button>
            <input type="date" wire:model.live="startDate" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-700 focus:ring-blue-500">
            <button wire:click="nextPeriod" class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-slate-600 transition" title="Next 7 Days">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    {{-- Legend --}}
    <div class="flex items-center gap-6 px-2 text-xs font-semibold text-slate-600 uppercase tracking-wider">
        <div class="flex items-center gap-2"><div class="w-3 h-3 rounded bg-blue-500"></div> Confirmed</div>
        <div class="flex items-center gap-2"><div class="w-3 h-3 rounded bg-emerald-500"></div> Checked In</div>
        <div class="flex items-center gap-2"><div class="w-3 h-3 rounded bg-slate-400"></div> Checked Out</div>
    </div>

    {{-- The Tape Chart --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
        <div class="flex overflow-x-auto">
            
            {{-- Y-Axis: Rooms Column --}}
            <div class="w-48 flex-shrink-0 border-r border-slate-200 bg-slate-50 z-10 sticky left-0">
                <div class="h-14 border-b border-slate-200 flex items-end pb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    Room
                </div>
                
                @foreach($rooms as $room)
                <div class="h-16 border-b border-slate-100 px-4 flex flex-col justify-center bg-white group hover:bg-slate-50 transition">
                    <div class="font-bold text-slate-900 flex items-center justify-between">
                        {{ $room->room_number }}
                        <span class="w-2 h-2 rounded-full {{ $room->status === 'available' ? 'bg-emerald-500' : ($room->status === 'maintenance' ? 'bg-red-500' : 'bg-slate-300') }}" title="{{ $room->status }}"></span>
                    </div>
                    <div class="text-[10px] text-slate-500 uppercase font-semibold truncate">{{ $room->category->name ?? '' }}</div>
                </div>
                @endforeach
            </div>

            {{-- X-Axis & Grid --}}
            <div class="flex-grow min-w-[800px]">
                {{-- Date Headers --}}
                <div class="grid grid-cols-[repeat(14,minmax(80px,1fr))] border-b border-slate-200 bg-slate-50 h-14">
                    @foreach($dates as $date)
                    <div class="border-r border-slate-200 flex flex-col items-center justify-center text-center {{ $date->isToday() ? 'bg-blue-50 text-blue-700' : 'text-slate-600' }}">
                        <div class="text-[10px] font-bold uppercase tracking-wider">{{ $date->format('D') }}</div>
                        <div class="text-sm font-black">{{ $date->format('d M') }}</div>
                    </div>
                    @endforeach
                </div>

                {{-- Grid Rows --}}
                @foreach($rooms as $room)
                <div class="relative h-16 border-b border-slate-100 group hover:bg-slate-50/50 transition">
                    {{-- The background grid columns --}}
                    <div class="absolute inset-0 grid grid-cols-[repeat(14,minmax(80px,1fr))] pointer-events-none">
                        @for($i=0; $i<14; $i++)
                        <div class="border-r border-slate-100/50"></div>
                        @endfor
                    </div>

                    {{-- The Booking Blocks --}}
                    <div class="absolute inset-y-0 left-0 right-0 grid grid-cols-[repeat(14,minmax(80px,1fr))] gap-x-1 py-1 px-0.5">
                        @foreach($gridData[$room->id] as $booking)
                        <div class="relative rounded-lg shadow-sm {{ $booking['color'] }} text-white border p-1.5 flex flex-col justify-center overflow-hidden hover:opacity-90 cursor-pointer transition z-10"
                             style="grid-column: {{ $booking['colStart'] }} / span {{ $booking['colSpan'] }};"
                             title="Ref: {{ $booking['ref'] }}&#10;Guest: {{ $booking['guest'] }}&#10;In: {{ $booking['fullStartDate'] }}&#10;Out: {{ $booking['fullEndDate'] }}"
                             onclick="window.location.href='{{ route('admin.bookings.index') }}'">
                             
                             <div class="text-xs font-bold truncate leading-tight">{{ $booking['guest'] }}</div>
                             <div class="text-[10px] opacity-90 truncate leading-tight">{{ $booking['fullStartDate'] }} → {{ $booking['fullEndDate'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
