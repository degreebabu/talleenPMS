<div class="bg-white border border-slate-200 rounded-2xl flex flex-col flex-grow overflow-hidden h-full shadow-2xl">
    
    {{-- Calendar Header / Toolbar --}}
    <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-white/50 backdrop-blur-md z-20">
        <div class="flex items-center gap-4">
            <button wire:click="previousPeriod" class="p-2 bg-slate-100 text-slate-500 hover:text-slate-900 rounded-lg transition hover:bg-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div class="text-slate-900 font-medium">
                {{ $start->format('M d, Y') }} - {{ $end->format('M d, Y') }}
            </div>
            <button wire:click="nextPeriod" class="p-2 bg-slate-100 text-slate-500 hover:text-slate-900 rounded-lg transition hover:bg-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 text-sm">
                <div class="w-3 h-3 rounded-full bg-blue-500"></div><span class="text-slate-500">Confirmed</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <div class="w-3 h-3 rounded-full bg-emerald-500"></div><span class="text-slate-500">Checked In</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <div class="w-3 h-3 rounded-full bg-slate-500"></div><span class="text-slate-500">Checked Out</span>
            </div>
        </div>
    </div>

    {{-- The Grid Wrapper --}}
    <div class="overflow-auto relative flex-grow bg-slate-50">
        <div class="inline-block min-w-full">
            
            {{-- Dates Header Row --}}
            <div class="flex border-b border-slate-200 sticky top-0 bg-white z-10">
                {{-- Room Name Column Header (sticky left) --}}
                <div class="w-48 shrink-0 border-r border-slate-200 p-3 sticky left-0 bg-white z-20 flex items-end">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Room</span>
                </div>
                
                {{-- Dates --}}
                @foreach($dates as $date)
                    <div class="w-16 shrink-0 border-r border-slate-200/50 p-2 flex flex-col items-center justify-center {{ $date['isWeekend'] ? 'bg-slate-100/20' : '' }} {{ $date['isToday'] ? 'bg-blue-900/20' : '' }}">
                        <span class="text-[10px] uppercase font-bold {{ $date['isToday'] ? 'text-blue-400' : 'text-slate-500' }}">{{ $date['dayName'] }}</span>
                        <span class="text-lg font-medium {{ $date['isToday'] ? 'text-blue-400' : 'text-slate-600' }}">{{ $date['day'] }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Room Rows --}}
            <div class="relative">
                @foreach($categories as $category)
                    {{-- Category Header Row --}}
                    <div class="flex bg-slate-100/50 border-b border-slate-200 sticky left-0 w-max z-10 min-w-full">
                        <div class="w-48 shrink-0 border-r border-slate-200 p-2 sticky left-0 bg-slate-100/80 backdrop-blur-sm">
                            <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">{{ $category->name }}</span>
                        </div>
                        {{-- Empty cells for the rest of the row to maintain grid lines --}}
                        <div class="flex flex-grow">
                            @foreach($dates as $date)
                                <div class="w-16 shrink-0 border-r border-slate-200/20"></div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Rooms in Category --}}
                    @foreach($category->rooms as $room)
                        <div class="flex border-b border-slate-200 group hover:bg-slate-100/30 transition min-w-full relative">
                            {{-- Room Info (sticky left) --}}
                            <div class="w-48 shrink-0 border-r border-slate-200 p-3 sticky left-0 bg-white group-hover:bg-slate-100/90 transition flex items-center justify-between z-10">
                                <span class="font-medium text-slate-200">Room {{ $room->room_number }}</span>
                                @if($room->status === 'maintenance')
                                    <span class="w-2 h-2 rounded-full bg-red-500" title="Maintenance"></span>
                                @elseif($room->status === 'dirty')
                                    <span class="w-2 h-2 rounded-full bg-amber-500" title="Dirty"></span>
                                @endif
                            </div>

                            {{-- Days Cells & Bookings --}}
                            <div class="flex relative">
                                {{-- Grid background cells --}}
                                @foreach($dates as $date)
                                    <div class="w-16 shrink-0 border-r border-slate-200/50 {{ $date['isWeekend'] ? 'bg-slate-100/20' : '' }} {{ $date['isToday'] ? 'bg-blue-900/10' : '' }} h-12"></div>
                                @endforeach

                                {{-- Render Booking Blocks --}}
                                @if(isset($bookingsByRoom[$room->id]))
                                    @foreach($bookingsByRoom[$room->id] as $item)
                                        @php
                                            $itemStart = \Carbon\Carbon::parse($item->start_date)->startOfDay();
                                            $itemEnd = \Carbon\Carbon::parse($item->end_date)->startOfDay();
                                            
                                            // Calculate offset and width
                                            $startOffsetDays = max(0, $start->diffInDays($itemStart, false));
                                            
                                            // If booking starts before our view, it starts at offset 0
                                            if ($itemStart->lt($start)) {
                                                $startOffsetDays = 0;
                                            }
                                            
                                            // Calculate total days this booking spans within our view window
                                            $effectiveStart = $itemStart->max($start);
                                            $effectiveEnd = $itemEnd->min($end->copy()->addDay()); // +1 because checkout is the morning of that day
                                            
                                            $durationDays = $effectiveStart->diffInDays($effectiveEnd);
                                            if ($durationDays <= 0) continue; // Should not happen based on query
                                            
                                            $leftPx = $startOffsetDays * 64; // 64px = w-16
                                            $widthPx = $durationDays * 64;

                                            // Determine color based on booking status
                                            $status = $item->booking->status ?? 'pending';
                                            $colorClass = 'bg-slate-600 border-slate-500'; // Default/pending
                                            if ($status === 'confirmed') $colorClass = 'bg-blue-500/80 border-blue-400 hover:bg-blue-500';
                                            if ($status === 'checked_in') $colorClass = 'bg-emerald-500/80 border-emerald-400 hover:bg-emerald-500';
                                            if ($status === 'checked_out') $colorClass = 'bg-slate-600/80 border-slate-500 hover:bg-slate-600';
                                        @endphp
                                        
                                        <div class="absolute top-1.5 h-9 rounded-md border text-xs text-slate-900 p-1.5 overflow-hidden shadow-lg backdrop-blur-sm cursor-pointer transition-all {{ $colorClass }}"
                                             style="left: {{ $leftPx }}px; width: {{ $widthPx }}px; z-index: 5;"
                                             title="Booking #{{ $item->booking_id }} - {{ $item->booking->guest->name ?? 'Guest' }}">
                                            <div class="font-semibold whitespace-nowrap truncate">{{ $item->booking->guest->name ?? 'Guest' }}</div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>
