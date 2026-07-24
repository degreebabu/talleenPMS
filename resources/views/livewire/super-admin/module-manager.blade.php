<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-wide">Platform Module Manager</h2>
            <p class="text-slate-500 mt-1">Manage core platform features and custom AI-built modules across all tenants.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('super-admin.module-builder') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                AI Module Builder
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl font-medium flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Core Modules -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm mb-8">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-lg font-bold text-slate-900">Core Platform Modules</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Module</th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Active Tenants</th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($coreModules as $cm)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-base">{{ $cm['name'] }}</div>
                                    <div class="text-xs text-slate-500">{{ $cm['description'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-bold text-xs border border-blue-100">
                                {{ $coreUsage[$cm['slug']] ?? 0 }} / {{ count($tenants) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="openAssignModal('{{ $cm['slug'] }}', '{{ $cm['name'] }}', false)" class="text-indigo-600 hover:text-indigo-700 font-semibold transition text-xs bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 shadow-sm">
                                Manage Access
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Custom Modules -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-lg font-bold text-slate-900">Custom Dynamic Modules</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Module</th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Fields</th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Active Tenants</th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px]">Global Status</th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[11px] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dynamicModules as $module)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 border border-purple-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $module->icon }}"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-base">{{ $module->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $module->slug }} &middot; {{ $module->description }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-700">
                            {{ $module->fields_count }} Fields
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 font-bold text-xs border border-purple-100">
                                {{ $dynamicUsage[$module->slug] ?? 0 }} / {{ count($tenants) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="toggleDynamicStatus({{ $module->id }})" class="relative inline-flex items-center cursor-pointer group">
                                <div class="w-11 h-6 rounded-full transition-colors {{ $module->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                    <div class="w-5 h-5 bg-white rounded-full shadow-sm transform transition-transform duration-300 {{ $module->is_active ? 'translate-x-5' : 'translate-x-1' }} mt-0.5"></div>
                                </div>
                                <span class="ml-3 text-xs font-bold {{ $module->is_active ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ $module->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="openAssignModal('{{ $module->slug }}', '{{ $module->name }}', true)" class="text-indigo-600 hover:text-indigo-700 font-semibold transition text-xs bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 shadow-sm">
                                Manage Access
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-medium">
                            <div class="mb-4">
                                <svg class="w-12 h-12 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            No custom modules have been built yet. <br>
                            <a href="{{ route('super-admin.module-builder') }}" class="text-indigo-600 font-bold hover:underline mt-2 inline-block">Build your first module</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tenant Assignment Modal -->
    @if($showAssignModal && $selectedModule)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-3xl overflow-hidden shadow-2xl transform transition-all">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Manage Access: {{ $selectedModule['name'] }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Toggle status for each tenant. Changes save instantly.</p>
                </div>
                <button wire:click="$set('showAssignModal', false)" class="text-slate-400 hover:text-slate-600 transition bg-white rounded-full p-1 border border-slate-200 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-6 max-h-[60vh] overflow-y-auto bg-slate-50">
                <div class="space-y-4">
                    @foreach($tenants as $tenant)
                    @php 
                        $mainStatus = $tenantStatuses[$tenant->id]['_main'] ?? 'disabled'; 
                        $isExpanded = $expandedTenantId === $tenant->id;
                        $hasFeatures = !empty($selectedModule['features']);
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:border-indigo-300 transition-colors">
                        <!-- Main Module Toggle Row -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 cursor-pointer" wire:click="toggleTenantExpand({{ $tenant->id }})">
                            <div class="flex items-center gap-3 mb-3 sm:mb-0">
                                @if($hasFeatures)
                                <div class="text-slate-400 transition-transform {{ $isExpanded ? 'rotate-90' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                                @endif
                                <div>
                                    <div class="font-bold text-slate-900">{{ $tenant->name }}</div>
                                    <div class="text-xs font-medium text-slate-500">{{ $tenant->subdomain }}.talleen.com</div>
                                </div>
                            </div>
                            
                            <div class="flex bg-slate-100 p-1 rounded-lg" wire:click.stop>
                                <button wire:click="updateTenantStatus({{ $tenant->id }}, 'active')" class="px-3 py-1.5 text-xs font-bold rounded-md transition {{ $mainStatus === 'active' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">
                                    Active
                                </button>
                                <button wire:click="updateTenantStatus({{ $tenant->id }}, 'paused')" class="px-3 py-1.5 text-xs font-bold rounded-md transition {{ $mainStatus === 'paused' ? 'bg-amber-500 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">
                                    Paused
                                </button>
                                <button wire:click="updateTenantStatus({{ $tenant->id }}, 'disabled')" class="px-3 py-1.5 text-xs font-bold rounded-md transition {{ $mainStatus === 'disabled' ? 'bg-slate-800 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">
                                    Disabled
                                </button>
                            </div>
                        </div>

                        <!-- Sub-features Dropdown -->
                        @if($hasFeatures && $isExpanded)
                        <div class="border-t border-slate-100 bg-slate-50/50 p-4 space-y-3">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Internal Features Configuration</p>
                            
                            @foreach($selectedModule['features'] as $fSlug => $fName)
                            @php 
                                $fStatus = $tenantStatuses[$tenant->id][$fSlug] ?? 'disabled'; 
                            @endphp
                            <div class="flex items-center justify-between pl-4">
                                <div class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    {{ $fName }}
                                </div>
                                <div class="flex bg-slate-200/50 p-1 rounded-md">
                                    <button wire:click="updateTenantStatus({{ $tenant->id }}, 'active', '{{ $fSlug }}')" class="px-2 py-1 text-[10px] uppercase tracking-wider font-bold rounded transition {{ $fStatus === 'active' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-300' }}">
                                        ON
                                    </button>
                                    <button wire:click="updateTenantStatus({{ $tenant->id }}, 'paused', '{{ $fSlug }}')" class="px-2 py-1 text-[10px] uppercase tracking-wider font-bold rounded transition {{ $fStatus === 'paused' ? 'bg-amber-500 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-300' }}">
                                        PAUSE
                                    </button>
                                    <button wire:click="updateTenantStatus({{ $tenant->id }}, 'disabled', '{{ $fSlug }}')" class="px-2 py-1 text-[10px] uppercase tracking-wider font-bold rounded transition {{ $fStatus === 'disabled' ? 'bg-slate-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-300' }}">
                                        OFF
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button wire:click="$set('showAssignModal', false)" class="px-5 py-2.5 rounded-xl font-bold bg-slate-800 hover:bg-slate-900 text-white shadow-sm transition">Close Window</button>
            </div>
        </div>
    </div>
    @endif
</div>
