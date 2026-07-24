<div>
    <div class="mb-8">
        <a href="{{ route('super-admin.module-manager') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Module Manager
        </a>
        <h2 class="text-2xl font-bold text-slate-900 tracking-wide">AI Module Builder</h2>
        <p class="text-slate-500 mt-1">Generate a fully functional custom module instantly using AI scaffolding, or build it manually.</p>
    </div>

    <!-- AI Prompt Interface -->
    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-[1px] rounded-2xl mb-8 shadow-md">
        <div class="bg-white rounded-[15px] p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center flex-shrink-0 text-indigo-600 shadow-sm border border-indigo-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-slate-900">Describe the module you need</h3>
                    <p class="text-sm text-slate-500 mb-4">E.g., "I need a Spa Services module to track treatments and prices" or "Create a Gym management system".</p>
                    
                    <div class="flex gap-3 relative">
                        <input wire:model="prompt" wire:keydown.enter="generateFromPrompt" type="text" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-inner placeholder-slate-400" placeholder="Type your requirements here...">
                        <button wire:click="generateFromPrompt" wire:loading.attr="disabled" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-md transition whitespace-nowrap flex items-center gap-2">
                            <span wire:loading.remove wire:target="generateFromPrompt">Generate Schema</span>
                            <span wire:loading wire:target="generateFromPrompt">Generating...</span>
                        </button>
                    </div>
                    
                    @error('prompt') <span class="text-xs text-red-500 font-medium mt-2 block">{{ $message }}</span> @enderror
                    
                    @if(session('ai_success'))
                    <div class="mt-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('ai_success') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Manual / Generated Builder Form -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
            <h3 class="text-lg font-bold text-slate-900">Module Configuration</h3>
        </div>
        
        <div class="p-6">
            <!-- Basic Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Module Name</label>
                    <input wire:model="moduleName" type="text" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500" placeholder="e.g. Spa Services">
                    @error('moduleName') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Description (Optional)</label>
                    <input wire:model="moduleDescription" type="text" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Short description of what this does">
                </div>
            </div>

            <!-- Fields Builder -->
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h4 class="text-base font-bold text-slate-900">Database Fields</h4>
                    <p class="text-xs text-slate-500">Define the schema for this module. The UI will be generated automatically.</p>
                </div>
                <button wire:click="addField" type="button" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Field
                </button>
            </div>

            <div class="space-y-4">
                @foreach($fields as $index => $field)
                <div class="flex flex-col md:flex-row items-start md:items-center gap-4 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Field Name</label>
                        <input wire:model="fields.{{ $index }}.name" type="text" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500" placeholder="e.g. Price">
                        @error('fields.'.$index.'.name') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="w-full md:w-48">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Type</label>
                        <select wire:model="fields.{{ $index }}.type" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="text">Short Text</option>
                            <option value="number">Number / Currency</option>
                            <option value="boolean">Toggle / Boolean</option>
                            <option value="date">Date picker</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 mt-4 md:mt-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input wire:model="fields.{{ $index }}.is_required" type="checkbox" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                            <span class="text-sm font-semibold text-slate-700">Required</span>
                        </label>
                    </div>

                    <button wire:click="removeField({{ $index }})" type="button" class="mt-4 md:mt-6 p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Remove Field">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                @endforeach
                @if(empty($fields))
                <div class="text-center py-8 text-slate-400 text-sm font-medium border-2 border-dashed border-slate-200 rounded-xl">
                    No fields defined. Click "Add Field" or use the AI prompt to generate a schema.
                </div>
                @endif
            </div>
            
            @error('fields') <span class="text-xs text-red-500 font-medium mt-2 block">{{ $message }}</span> @enderror
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button wire:click="saveModule" class="px-6 py-3 rounded-xl font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/20 transition transform hover:-translate-y-0.5">
                Save & Publish Module
            </button>
        </div>
    </div>
</div>
