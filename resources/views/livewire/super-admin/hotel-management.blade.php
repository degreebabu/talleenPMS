<div class="space-y-4">
    
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2 mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">All Tenants</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">Manage and monitor all hotel accounts on the platform.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search hotels..."
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-fuchsia-500/20 focus:border-fuchsia-500 transition">
                </div>
                <button wire:click="openCreateModal" class="px-4 py-2 bg-fuchsia-600 hover:bg-fuchsia-500 text-white text-sm font-semibold rounded-xl transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Tenant
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Hotel Name</th>
                        <th class="px-6 py-4">Subdomain</th>
                        <th class="px-6 py-4">Contact Email</th>
                        <th class="px-6 py-4">Plan</th>
                        <th class="px-6 py-4">Metrics</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($hotels as $hotel)
                        <tr class="hover:bg-slate-50/80 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-sm" style="background-color: {{ $hotel->primary_color }}">
                                        {{ strtoupper(substr($hotel->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $hotel->name }}</div>
                                        <div class="text-xs text-slate-400">Added {{ $hotel->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="http://127.0.0.1:8000/book/{{ $hotel->subdomain }}" target="_blank" class="text-fuchsia-600 font-medium hover:text-fuchsia-700 hover:underline text-sm">
                                    {{ $hotel->subdomain }}.talleen.in
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ $hotel->contact_email }}</td>
                            <td class="px-6 py-4">
                                @if($hotel->subscriptionPlan)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $hotel->subscriptionPlan->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-500">
                                        No Plan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1 bg-slate-50 border border-slate-200 px-2 py-1 rounded-md text-xs text-slate-600 font-medium">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        {{ $hotel->rooms_count }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 bg-slate-50 border border-slate-200 px-2 py-1 rounded-md text-xs text-slate-600 font-medium">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $hotel->bookings_count }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($hotel->subscription_status === 'suspended')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-700 border border-red-200">Suspended</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('super-admin.impersonate', $hotel->id) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-100 rounded-lg transition" title="Login As Tenant">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                    </a>
                                    <button wire:click="viewDetails({{ $hotel->id }})" class="p-1.5 text-slate-400 hover:text-fuchsia-600 hover:bg-fuchsia-50 border border-transparent hover:border-fuchsia-100 rounded-lg transition" title="Manage Subscription">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button wire:click="toggleStatus({{ $hotel->id }})"
                                            class="text-slate-400 hover:text-amber-600 bg-white hover:bg-amber-50 border border-slate-200 hover:border-amber-200 p-1.5 rounded-lg transition"
                                            title="{{ $hotel->subscription_status === 'suspended' ? 'Activate' : 'Suspend' }}">
                                        @if($hotel->subscription_status === 'suspended')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        @endif
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                                </div>
                                <p class="font-medium text-slate-600">No hotels found matching your search.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($hotels->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $hotels->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    @if($showDetailModal && $selectedHotel)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" wire:click.self="$set('showDetailModal', false)">
        <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-lg shadow-xl">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold" style="background-color: {{ $selectedHotel->primary_color }}">
                        {{ strtoupper(substr($selectedHotel->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">{{ $selectedHotel->name }}</h3>
                        <p class="text-xs text-slate-500">{{ $selectedHotel->subdomain }}.talleen.in</p>
                    </div>
                </div>
                <button wire:click="$set('showDetailModal', false)" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                {{-- Status & Actions --}}
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200">
                        <div class="text-2xl font-black text-slate-900">{{ $selectedHotel->rooms_count }}</div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Rooms</div>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200">
                        <div class="text-2xl font-black text-slate-900">{{ $selectedHotel->bookings_count }}</div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Bookings</div>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200">
                        <div class="text-sm font-black {{ $selectedHotel->subscription_status === 'suspended' ? 'text-red-600' : 'text-emerald-600' }} uppercase mt-1">
                            {{ $selectedHotel->subscription_status }}
                        </div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Status</div>
                    </div>
                </div>

                {{-- Assign Plan --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Assign Subscription Plan</label>
                    @if(session('plan_success'))
                        <p class="text-emerald-600 text-xs font-medium mb-2">✓ {{ session('plan_success') }}</p>
                    @endif
                    <div class="flex gap-2">
                        <select wire:model="selectedPlanId" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-fuchsia-500/20 focus:border-fuchsia-500">
                            <option value="">-- No Plan --</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} (₹{{ number_format($plan->price_monthly, 0) }}/mo)</option>
                            @endforeach
                        </select>
                        <button wire:click="assignPlan({{ $selectedHotel->id }})" class="px-4 py-2 bg-fuchsia-600 hover:bg-fuchsia-500 text-white text-sm font-semibold rounded-xl transition">
                            Save
                        </button>
                    </div>
                </div>

                {{-- Suspend/Activate action --}}
                <div class="pt-2 border-t border-slate-100">
                    <button wire:click="toggleStatus({{ $selectedHotel->id }})"
                            class="w-full py-2 text-sm font-semibold rounded-xl transition border {{ $selectedHotel->subscription_status === 'suspended' ? 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'border-red-200 bg-red-50 text-red-700 hover:bg-red-100' }}">
                        {{ $selectedHotel->subscription_status === 'suspended' ? '✓ Activate This Tenant' : '⊘ Suspend This Tenant' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Create Tenant Modal --}}
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" wire:click.self="$set('showCreateModal', false)">
        <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-md shadow-xl">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Create New Tenant</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Manually onboard a new hotel.</p>
                </div>
                <button wire:click="$set('showCreateModal', false)" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Hotel Name *</label>
                    <input wire:model="newHotelName" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-fuchsia-500/20 focus:border-fuchsia-500">
                    @error('newHotelName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Admin Email *</label>
                    <input wire:model="newHotelEmail" type="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-fuchsia-500/20 focus:border-fuchsia-500">
                    @error('newHotelEmail') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Subdomain *</label>
                    <div class="flex relative rounded-xl shadow-sm border border-slate-200 bg-slate-50 overflow-hidden">
                        <input wire:model="newHotelSubdomain" type="text" class="flex-1 bg-transparent border-none px-4 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-fuchsia-500/20 focus:border-transparent">
                        <div class="px-3 flex items-center bg-slate-100 text-slate-500 text-sm font-medium border-l border-slate-200">
                            .talleen.in
                        </div>
                    </div>
                    @error('newHotelSubdomain') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Assign Plan</label>
                    <select wire:model="newHotelPlanId" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-fuchsia-500/20 focus:border-fuchsia-500">
                        <option value="">-- No Plan --</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} (${{ $plan->price_monthly }}/mo)</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50 rounded-b-2xl flex justify-end gap-3">
                <button wire:click="$set('showCreateModal', false)" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 rounded-xl transition">Cancel</button>
                <button wire:click="createTenant" class="px-5 py-2 bg-fuchsia-600 hover:bg-fuchsia-500 text-white text-sm font-semibold rounded-xl transition">
                    Create Tenant
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
