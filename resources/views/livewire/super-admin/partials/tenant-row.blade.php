<tr class="hover:bg-slate-50 transition">
    <td class="px-6 py-4">
        <div class="flex items-center gap-3 {{ $isChild ? 'ml-6' : '' }}">
            @if($isChild)
            <div class="w-6 h-6 border-l-2 border-b-2 border-slate-300 rounded-bl-xl mr-2 -mt-4"></div>
            @endif
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center font-bold text-blue-700 border border-blue-200">
                {{ strtoupper(substr($tenant->name, 0, 1)) }}
            </div>
            <div>
                <div class="font-bold text-slate-900 text-base">{{ $tenant->name }}</div>
                <div class="flex items-center gap-2 mt-0.5 text-xs">
                    <span class="text-slate-500">{{ $tenant->subdomain }}.talleen.com</span>
                    <a href="https://{{ $tenant->subdomain }}.talleen.com/login" target="_blank" class="text-blue-500 hover:text-blue-700 flex items-center gap-1 font-medium transition" title="Open Tenant Login Page">
                        <span>Portal</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4">
        <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold border border-slate-200">
            {{ $tenant->subscriptionPlan->name ?? 'Custom Plan' }}
        </span>
        <div class="text-xs font-medium text-slate-500 mt-2">
            {{ count($tenant->features ?? ($tenant->subscriptionPlan->features ?? [])) }} Modules Active
        </div>
    </td>
    <td class="px-6 py-4">
        <button wire:click="toggleActive({{ $tenant->id }})" class="relative inline-flex items-center cursor-pointer group">
            <div class="w-11 h-6 rounded-full transition-colors {{ $tenant->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}">
                <div class="w-5 h-5 bg-white rounded-full shadow-sm transform transition-transform duration-300 {{ $tenant->is_active ? 'translate-x-5' : 'translate-x-1' }} mt-0.5"></div>
            </div>
            <span class="ml-3 text-xs font-bold {{ $tenant->is_active ? 'text-emerald-600' : 'text-slate-500' }}">
                {{ $tenant->is_active ? 'Active' : 'Suspended' }}
            </span>
        </button>
    </td>
    <td class="px-6 py-4 text-right space-x-3">
        @php
            $hasAdmin = $tenant->users()->whereHas('roles', function($q) {
                $q->where('name', 'hotel_admin');
            })->exists();
        @endphp

        @if($hasAdmin)
            <a href="{{ route('super-admin.impersonate', $tenant->id) }}" class="text-slate-500 hover:text-slate-700 font-semibold transition text-xs bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                Impersonate
            </a>
        @else
            <button wire:click="openAdminModal({{ $tenant->id }})" class="text-emerald-600 hover:text-emerald-700 font-semibold transition text-xs bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                Create Admin
            </button>
        @endif
        <button wire:click="manageModules({{ $tenant->id }})" class="text-blue-600 hover:text-blue-700 font-semibold transition text-xs bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">
            Manage Access
        </button>
    </td>
</tr>
