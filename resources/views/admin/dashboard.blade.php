<x-admin-layout>
    <x-slot name="header">Business Intelligence Dashboard</x-slot>

    <div class="space-y-8">
        {{-- KPI Cards Row 1: Operations --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Occupancy Rate --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition duration-300">
                <div class="absolute -right-4 -top-4 p-8 bg-blue-50 text-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition duration-500 transform scale-50 group-hover:scale-100">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Occupancy Rate</div>
                    <div class="text-4xl font-black text-slate-900 mb-3 tracking-tight">{{ $stats['occupancy_rate'] }}%</div>
                    <div class="w-full bg-slate-100 rounded-full h-2 mb-3 overflow-hidden">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-1000 ease-out" style="width: {{ $stats['occupancy_rate'] }}%"></div>
                    </div>
                    <div class="text-xs font-medium text-slate-500">{{ $stats['total_rooms'] - $stats['available_rooms'] }} of {{ $stats['total_rooms'] }} rooms occupied</div>
                </div>
            </div>

            {{-- RevPAR --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition duration-300">
                <div class="absolute -right-4 -top-4 p-8 bg-emerald-50 text-emerald-500 rounded-full opacity-0 group-hover:opacity-100 transition duration-500 transform scale-50 group-hover:scale-100">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">RevPAR</div>
                    <div class="text-4xl font-black text-slate-900 mb-3 tracking-tight">₹{{ number_format($stats['revpar'], 0) }}</div>
                    <div class="flex items-center gap-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50 w-fit px-2.5 py-1 rounded-md border border-emerald-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        Revenue per available room
                    </div>
                </div>
            </div>

            {{-- Today Check-ins --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition duration-300">
                <div class="absolute -right-4 -top-4 p-8 bg-amber-50 text-amber-500 rounded-full opacity-0 group-hover:opacity-100 transition duration-500 transform scale-50 group-hover:scale-100">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Today's Arrivals</div>
                    <div class="text-4xl font-black text-slate-900 mb-3 tracking-tight">{{ $stats['today_checkins'] }}</div>
                    <div class="text-xs font-medium text-slate-500">Guests checking in today</div>
                </div>
            </div>

            {{-- Total Bookings --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden group hover:shadow-md transition duration-300">
                <div class="absolute -right-4 -top-4 p-8 bg-purple-50 text-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition duration-500 transform scale-50 group-hover:scale-100">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Total Bookings</div>
                    <div class="text-4xl font-black text-slate-900 mb-3 tracking-tight">{{ $stats['total_bookings'] }}</div>
                    <div class="text-xs font-medium text-slate-500">All-time confirmed bookings</div>
                </div>
            </div>
        </div>

        {{-- Revenue Breakdown --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-8 border-b border-slate-100 pb-4">Revenue Streams Breakdown</h3>
                
                <div class="space-y-8">
                    @php
                        $totalRev = $stats['total_revenue'] + $stats['banquet_revenue'] + $stats['outing_revenue'];
                        $roomsPct = $totalRev > 0 ? ($stats['total_revenue'] / $totalRev) * 100 : 0;
                        $banquetPct = $totalRev > 0 ? ($stats['banquet_revenue'] / $totalRev) * 100 : 0;
                        $outingPct = $totalRev > 0 ? ($stats['outing_revenue'] / $totalRev) * 100 : 0;
                    @endphp

                    <div>
                        <div class="flex justify-between items-end mb-3">
                            <span class="text-slate-700 font-bold flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full bg-blue-500 shadow-sm border border-blue-600"></span> 
                                Room Stays
                            </span>
                            <span class="text-slate-900 font-bold text-xl">₹{{ number_format($stats['total_revenue'], 0) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 shadow-inner">
                            <div class="bg-blue-500 h-3 rounded-full shadow-sm" style="width: {{ $roomsPct }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-end mb-3">
                            <span class="text-slate-700 font-bold flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full bg-purple-500 shadow-sm border border-purple-600"></span> 
                                Banquet & Events
                            </span>
                            <span class="text-slate-900 font-bold text-xl">₹{{ number_format($stats['banquet_revenue'], 0) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 shadow-inner">
                            <div class="bg-purple-500 h-3 rounded-full shadow-sm" style="width: {{ $banquetPct }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-end mb-3">
                            <span class="text-slate-700 font-bold flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full bg-amber-500 shadow-sm border border-amber-600"></span> 
                                Day Outing Passes
                            </span>
                            <span class="text-slate-900 font-bold text-xl">₹{{ number_format($stats['outing_revenue'], 0) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 shadow-inner">
                            <div class="bg-amber-500 h-3 rounded-full shadow-sm" style="width: {{ $outingPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm flex flex-col justify-center items-center text-center relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
                <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>

                <div class="w-32 h-32 rounded-full border-[8px] border-slate-50 flex items-center justify-center mb-6 relative z-10 shadow-sm bg-white backdrop-blur-sm">
                    <svg class="absolute inset-0 w-full h-full text-emerald-500 transform -rotate-90" viewBox="0 0 36 36">
                        <path class="stroke-current" fill="none" stroke-width="3" stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="text-2xl font-black text-slate-900 tracking-tight">₹{{ number_format($totalRev / 1000, 1) }}k</div>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-2 relative z-10">Total Revenue</h4>
                <p class="text-sm text-slate-500 font-medium relative z-10 max-w-[200px]">Combined gross revenue across all operational modules.</p>
            </div>

        </div>

        {{-- Quick Links --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <a href="{{ route('admin.rooms.index') }}" class="bg-white border border-slate-200 hover:border-blue-300 hover:shadow-md rounded-2xl p-6 transition duration-300 flex items-center gap-5 group">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 group-hover:-rotate-3 transition duration-300 border border-blue-100 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-lg">Manage Rooms</div>
                    <div class="text-sm font-medium text-slate-500 mt-1 group-hover:text-blue-600 transition">View inventory &rarr;</div>
                </div>
            </a>
            
            <a href="{{ route('admin.banquet.calendar') }}" class="bg-white border border-slate-200 hover:border-purple-300 hover:shadow-md rounded-2xl p-6 transition duration-300 flex items-center gap-5 group">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition duration-300 border border-purple-100 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-lg">Event Calendar</div>
                    <div class="text-sm font-medium text-slate-500 mt-1 group-hover:text-purple-600 transition">View bookings &rarr;</div>
                </div>
            </a>
            
            <a href="{{ route('admin.pms.housekeeping') }}" class="bg-white border border-slate-200 hover:border-emerald-300 hover:shadow-md rounded-2xl p-6 transition duration-300 flex items-center gap-5 group">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 group-hover:-rotate-3 transition duration-300 border border-emerald-100 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-lg">Housekeeping</div>
                    <div class="text-sm font-medium text-slate-500 mt-1 group-hover:text-emerald-600 transition">Update status &rarr;</div>
                </div>
            </a>
        </div>
        
        {{-- Booking Engine Integration --}}
        @if(auth()->user()->hotel)
        <div class="mt-8 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        Website Booking Engine
                    </h3>
                    <p class="text-sm text-slate-500 mt-1">Embed the direct booking widget on your own hotel website.</p>
                </div>
                <a href="{{ route('book', auth()->user()->hotel->subdomain) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg text-sm hover:bg-blue-700 transition flex items-center gap-2 shadow-sm">
                    Open Booking Page
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
            <div class="p-6">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Embed Code (Copy & Paste into your website HTML)</label>
                <div class="relative">
                    <textarea readonly class="w-full bg-slate-900 text-emerald-400 font-mono text-sm p-4 rounded-xl border border-slate-700 focus:ring-0 focus:border-slate-700 h-32 resize-none" onclick="this.select()">
<!-- Talleen PMS Booking Widget -->
<iframe src="{{ route('book', auth()->user()->hotel->subdomain) }}" width="100%" height="800px" frameborder="0" style="border:none; border-radius:12px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);"></iframe>
<script>window.addEventListener("message",(e)=>{if(e.data.type==="talleen-resize"){document.querySelector("iframe[src*='talleen']").style.height=e.data.height+"px"}});</script></textarea>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-admin-layout>
