<div>

    @php $categories = collect($integrations)->groupBy('category') @endphp
    
    @foreach($categories as $cat => $items)
    <div class="mb-8">
        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">{{ $cat }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($items as $integration)
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-start justify-between group">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-2xl flex-shrink-0">{{ $integration['icon'] }}</div>
                    <div>
                        <div class="font-bold text-slate-900">{{ $integration['name'] }}</div>
                        <div class="text-sm text-slate-500 mt-1">{{ $integration['description'] }}</div>
                    </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                    @if($integration['status'] === 'connected')
                        <span class="px-2.5 py-1.5 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold">ACTIVE</span>
                    @elseif($integration['status'] === 'coming_soon')
                        <span class="px-2.5 py-1.5 rounded-lg bg-slate-100 text-slate-500 text-xs font-semibold">Coming Soon</span>
                    @else
                        <button class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition">Connect</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- API Key Section --}}
    <div class="bg-slate-900 rounded-2xl p-6 text-white">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-xl">🔌</div>
            <div>
                <h3 class="font-bold text-lg">TalleenPMS API</h3>
                <p class="text-sm text-slate-400">Use our REST API to build custom integrations</p>
            </div>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-xl p-4 font-mono text-sm text-slate-300 mb-4">
            <div class="text-slate-500 text-xs mb-1">Your API Key (click to reveal)</div>
            <div class="tracking-widest">••••••••••••••••••••••••••••••••</div>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 bg-white text-slate-900 font-semibold text-sm rounded-xl hover:bg-slate-100 transition">View API Docs</button>
            <button class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-semibold text-sm rounded-xl transition">Regenerate Key</button>
        </div>
    </div>
</div>
