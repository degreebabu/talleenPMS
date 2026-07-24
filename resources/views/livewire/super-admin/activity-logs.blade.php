<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-wide">Activity Stream</h2>
            <p class="text-slate-500 mt-1">Real-time audit logs across the entire platform.</p>
        </div>
        <div>
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search logs or tenants..." class="w-full md:w-80 bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-blue-500 placeholder-slate-400 shadow-sm transition">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50/50 text-slate-500 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Timestamp</th>
                    <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Tenant</th>
                    <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">User</th>
                    <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Activity</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($logs as $log)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 text-slate-500 font-medium whitespace-nowrap">
                        {{ $log->created_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($log->hotel)
                        <span class="inline-block px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold border border-blue-100">
                            {{ $log->hotel->name }}
                        </span>
                        @else
                        <span class="text-slate-400 italic text-xs font-semibold">System</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold text-slate-700">
                        {{ $log->user->name ?? 'System' }}
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium">
                        {{ $log->description }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-500 font-medium">
                        No logs found matching your search.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
