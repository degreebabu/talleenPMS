<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $module->name }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $module->description }}</p>
        </div>
        <button wire:click="createRecord" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow-sm transition">
            Add New Record
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500">
                    <tr>
                        @foreach($module->fields as $field)
                        <th class="px-6 py-4 font-semibold">{{ $field->name }}</th>
                        @endforeach
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($records as $record)
                    <tr class="hover:bg-slate-50 transition">
                        @foreach($module->fields as $field)
                        <td class="px-6 py-4 text-slate-700">
                            @php
                                $valObj = $record->values->where('dynamic_field_id', $field->id)->first();
                                $display = '-';
                                if ($valObj) {
                                    if ($field->type == 'boolean') $display = $valObj->value_boolean ? 'Yes' : 'No';
                                    elseif ($field->type == 'date') $display = $valObj->value_date ? \Carbon\Carbon::parse($valObj->value_date)->format('M d, Y') : '-';
                                    elseif ($field->type == 'number') $display = $valObj->value_number;
                                    else $display = $valObj->value_text;
                                }
                            @endphp
                            
                            @if($field->type == 'boolean')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $valObj && $valObj->value_boolean ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                    {{ $display }}
                                </span>
                            @else
                                {{ $display }}
                            @endif
                        </td>
                        @endforeach
                        <td class="px-6 py-4 text-right space-x-3">
                            <button wire:click="editRecord({{ $record->id }})" class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                            <button wire:click="deleteRecord({{ $record->id }})" class="text-red-600 hover:text-red-800 font-medium" onclick="return confirm('Are you sure?') || event.stopImmediatePropagation()">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $module->fields->count() + 1 }}" class="px-6 py-12 text-center text-slate-500 font-medium">
                            No records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900">{{ $isEditing ? 'Edit Record' : 'New Record' }}</h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                @foreach($module->fields as $field)
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        {{ $field->name }} @if($field->is_required) <span class="text-red-500">*</span> @endif
                    </label>
                    
                    @if($field->type == 'boolean')
                        <label class="flex items-center gap-2 mt-2 cursor-pointer">
                            <input wire:model="formData.{{ $field->id }}" type="checkbox" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500 border-slate-300">
                            <span class="text-sm font-medium text-slate-700">Yes / Enable</span>
                        </label>
                    @elseif($field->type == 'date')
                        <input wire:model="formData.{{ $field->id }}" type="date" class="w-full border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @elseif($field->type == 'number')
                        <input wire:model="formData.{{ $field->id }}" type="number" step="0.01" class="w-full border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @else
                        <input wire:model="formData.{{ $field->id }}" type="text" class="w-full border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    @endif
                    
                    @error('formData.'.$field->id) <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                @endforeach
            </div>
            
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800">Cancel</button>
                <button wire:click="saveRecord" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                    Save Record
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
