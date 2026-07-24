<div>
    @if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl font-medium">{{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Staff & Roles</h2>
            <p class="text-sm text-slate-500 mt-1">Manage users and assign role-based access for your hotel.</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="createRole" class="px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl transition shadow-sm border border-slate-200">
                + New Role
            </button>
            <button wire:click="create" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl transition shadow-sm border border-slate-700">
                + Invite Staff
            </button>
        </div>
    </div>

    @if($showRoleForm)
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm mb-8">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Create Custom Role</h3>
        <div class="max-w-md">
            <label class="block text-sm font-bold text-slate-700 mb-1">Role Name <span class="text-red-500">*</span></label>
            <input wire:model="new_role_name" type="text" placeholder="e.g. night_auditor" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
            @error('new_role_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="mt-6 flex gap-3">
            <button wire:click="$set('showRoleForm', false)" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition">Cancel</button>
            <button wire:click="saveRole" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-sm transition">Save Role</button>
        </div>
    </div>
    @endif

    @if($showForm)
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm mb-8">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Invite New Staff</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input wire:model="name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input wire:model="email" type="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Temporary Password <span class="text-red-500">*</span></label>
                <input wire:model="password" type="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Role <span class="text-red-500">*</span></label>
                <select wire:model="role" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}">{{ ucwords(str_replace('_', ' ', $r->name)) }}</option>
                    @endforeach
                </select>
                @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Phone Number</label>
                <input wire:model="phone" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">ID Card Number</label>
                <input wire:model="id_card_number" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                @error('id_card_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-1">Upload Govt ID (Image/PDF)</label>
                <input wire:model="govt_id" type="file" accept=".jpg,.jpeg,.png,.pdf" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <div wire:loading wire:target="govt_id" class="text-xs text-blue-600 mt-1 font-semibold animate-pulse">Uploading file...</div>
                @error('govt_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <button wire:click="$set('showForm', false)" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition">Cancel</button>
            <button wire:click="save" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-sm transition" wire:loading.attr="disabled">Create Account</button>
        </div>
    </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">User</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact & ID</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($staff as $user)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-700">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div>{{ $user->name }}</div>
                                <div class="text-xs text-slate-500 font-normal">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        <div class="text-xs">
                            @if($user->phone) <div class="font-semibold">{{ $user->phone }}</div> @else <span class="text-slate-400 italic">No Phone</span> @endif
                            @if($user->id_card_number) <div>ID: {{ $user->id_card_number }}</div> @endif
                            @if($user->govt_id_path)
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($user->govt_id_path) }}" target="_blank" class="text-blue-600 hover:underline inline-flex items-center gap-1 mt-1 font-semibold">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    View Govt ID
                                </a>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @foreach($user->roles as $r)
                        <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                            {{ ucwords(str_replace('_', ' ', $r->name)) }}
                        </span>
                        @endforeach
                        @if($user->roles->isEmpty())
                        <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-500">None</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button wire:click="delete({{ $user->id }})" class="text-red-500 hover:text-red-700 text-sm font-semibold px-2" wire:confirm="Are you sure you want to revoke access and delete this user?">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                        <p>No extra staff members added yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
