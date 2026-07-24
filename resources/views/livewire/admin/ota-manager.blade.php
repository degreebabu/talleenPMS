<div>
    <div class="flex items-center justify-end mb-6">
        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm rounded-xl transition">+ Connect Channel</button>
    </div>

    {{-- Channel Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        @foreach($channels as $channel)
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-2xl">{{ $channel['logo'] }}</div>
                <div>
                    <div class="font-bold text-slate-900">{{ $channel['name'] }}</div>
                    <div class="text-xs text-slate-500">{{ $channel['rooms_synced'] }} rooms synced</div>
                </div>
            </div>
            <div>
                @if($channel['status'] === 'connected')
                    <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold">LIVE</span>
                @else
                    <button class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition">Connect</button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Rate Parity Panel --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-900">Rate Parity Check</h3>
                <p class="text-xs text-slate-500 mt-0.5">Ensure your direct rate is always competitive</p>
            </div>
            <div class="p-6 text-center py-12">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </div>
                <p class="text-slate-500 text-sm">Connect your OTA channels to enable rate parity monitoring.</p>
            </div>
        </div>

        {{-- Sync Log --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-900">Sync Log</h3>
            </div>
            <div class="p-6 space-y-3">
                @foreach($syncLog as $log)
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-slate-300 mt-2 flex-shrink-0"></div>
                    <div>
                        <p class="text-sm text-slate-700">{{ $log['message'] }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $log['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
