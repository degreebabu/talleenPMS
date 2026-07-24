<div>
    @if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl font-medium">{{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Restaurant Menu</h2>
            <p class="text-sm text-slate-500 mt-1">Manage items, prices, and availability for the POS terminal.</p>
        </div>
        <button wire:click="create" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition shadow-sm border border-blue-700">
            + Add Menu Item
        </button>
    </div>

    @if($showForm)
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm mb-8">
        <h3 class="text-lg font-bold text-slate-900 mb-4">{{ $editingId ? 'Edit Item' : 'New Menu Item' }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Item Name <span class="text-red-500">*</span></label>
                <input wire:model="name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Price (₹) <span class="text-red-500">*</span></label>
                <input wire:model="price" type="number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select wire:model="category" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="Food">Food</option>
                    <option value="Beverage">Beverage</option>
                    <option value="Dessert">Dessert</option>
                    <option value="Alcohol">Alcohol</option>
                </select>
                @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Availability</label>
                <label class="flex items-center gap-2 mt-3 cursor-pointer">
                    <input wire:model="is_available" type="checkbox" class="w-5 h-5 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                    <span class="text-sm font-medium text-slate-700">Currently Available</span>
                </label>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-1">Description (Optional)</label>
                <textarea wire:model="description" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <button wire:click="resetForm(); $set('showForm', false)" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition">Cancel</button>
            <button wire:click="save" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl text-sm transition">Save Item</button>
        </div>
    </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Item Name</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Price</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($items as $item)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900">{{ $item->name }}</div>
                        @if($item->description)
                        <div class="text-xs text-slate-500 truncate max-w-xs">{{ $item->description }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                            {{ $item->category }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-900">₹{{ number_format($item->price, 0) }}</td>
                    <td class="px-6 py-4">
                        <button wire:click="toggleAvailability({{ $item->id }})" class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg text-xs font-semibold {{ $item->is_available ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-rose-100 text-rose-700 hover:bg-rose-200' }} transition">
                            <span class="w-1.5 h-1.5 rounded-full {{ $item->is_available ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            {{ $item->is_available ? 'Available' : 'Out of Stock' }}
                        </button>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button wire:click="edit({{ $item->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-semibold px-2">Edit</button>
                        <button wire:click="delete({{ $item->id }})" class="text-red-500 hover:text-red-700 text-sm font-semibold px-2" wire:confirm="Are you sure you want to delete this menu item?">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        <p>No menu items added yet. Click above to start building your restaurant menu.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
