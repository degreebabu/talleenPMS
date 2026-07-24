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
                <div class="w-3 h-3 rounded-full bg-emerald-500"></div><span class="text-slate-500">Completed</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <div class="w-3 h-3 rounded-full bg-slate-500"></div><span class="text-slate-500">Inquiry</span>
            </div>
        </div>
    </div>

    {{-- The Grid Wrapper --}}
    <div class="overflow-auto relative flex-grow bg-slate-50">
        <div class="inline-block min-w-full">
            
            {{-- Dates Header Row --}}
            <div class="flex border-b border-slate-200 sticky top-0 bg-white z-10">
                {{-- Space Name Column Header (sticky left) --}}
                <div class="w-48 shrink-0 border-r border-slate-200 p-3 sticky left-0 bg-white z-20 flex items-end">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Event Space</span>
                </div>
                
                {{-- Dates --}}
                @foreach($dates as $date)
                    <div class="w-16 shrink-0 border-r border-slate-200/50 p-2 flex flex-col items-center justify-center {{ $date['isWeekend'] ? 'bg-slate-100/20' : '' }} {{ $date['isToday'] ? 'bg-blue-900/20' : '' }}">
                        <span class="text-[10px] uppercase font-bold {{ $date['isToday'] ? 'text-blue-400' : 'text-slate-500' }}">{{ $date['dayName'] }}</span>
                        <span class="text-lg font-medium {{ $date['isToday'] ? 'text-blue-400' : 'text-slate-600' }}">{{ $date['day'] }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Spaces Rows --}}
            <div class="relative">
                @foreach($spaces as $space)
                    <div class="flex border-b border-slate-200 group hover:bg-slate-100/30 transition min-w-full relative">
                        {{-- Space Info (sticky left) --}}
                        <div class="w-48 shrink-0 border-r border-slate-200 p-3 sticky left-0 bg-white group-hover:bg-slate-100/90 transition flex flex-col justify-center z-10">
                            <span class="font-medium text-slate-200 truncate" title="{{ $space->name }}">{{ $space->name }}</span>
                            <span class="text-xs text-slate-500 uppercase">{{ str_replace('_', ' ', $space->type) }}</span>
                        </div>

                        {{-- Days Cells & Bookings --}}
                        <div class="flex relative">
                            {{-- Grid background cells --}}
                            @foreach($dates as $date)
                                <div class="w-16 shrink-0 border-r border-slate-200/50 {{ $date['isWeekend'] ? 'bg-slate-100/20' : '' }} {{ $date['isToday'] ? 'bg-blue-900/10' : '' }} h-14"></div>
                            @endforeach

                            {{-- Render Booking Blocks --}}
                            @if(isset($bookingsBySpace[$space->id]))
                                @foreach($bookingsBySpace[$space->id] as $booking)
                                    @php
                                        $itemStart = \Carbon\Carbon::parse($booking->start_time)->startOfDay();
                                        $itemEnd = \Carbon\Carbon::parse($booking->end_time)->startOfDay();
                                        
                                        $startOffsetDays = max(0, $start->diffInDays($itemStart, false));
                                        
                                        if ($itemStart->lt($start)) {
                                            $startOffsetDays = 0;
                                        }
                                        
                                        $effectiveStart = $itemStart->max($start);
                                        $effectiveEnd = $itemEnd->min($end->copy()->addDay());
                                        
                                        $durationDays = $effectiveStart->diffInDays($effectiveEnd);
                                        if ($durationDays <= 0) $durationDays = 1; // Minimum 1 cell width
                                        
                                        $leftPx = $startOffsetDays * 64; 
                                        $widthPx = $durationDays * 64;

                                        $status = $booking->status;
                                        $colorClass = 'bg-slate-600 border-slate-500'; // inquiry
                                        if ($status === 'confirmed') $colorClass = 'bg-blue-500/80 border-blue-400 hover:bg-blue-500';
                                        if ($status === 'completed') $colorClass = 'bg-emerald-500/80 border-emerald-400 hover:bg-emerald-500';
                                    @endphp
                                    
                                    <div class="absolute top-2 h-10 rounded-md border text-xs text-slate-900 p-1.5 overflow-hidden shadow-lg backdrop-blur-sm cursor-pointer transition-all {{ $colorClass }}"
                                         style="left: {{ $leftPx }}px; width: {{ $widthPx - 2 }}px; z-index: 5;"
                                         title="Client: {{ $booking->client_name }} | Type: {{ $booking->event_type }}">
                                        <div class="font-semibold whitespace-nowrap truncate">{{ $booking->client_name }}</div>
                                        <div class="text-[9px] uppercase tracking-wider opacity-80 truncate">{{ $booking->event_type }}</div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
                
                @if($spaces->isEmpty())
                    <div class="p-8 text-center text-slate-500 sticky left-0 w-full">
                        No Event Spaces found. Create one to view the calendar.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
