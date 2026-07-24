<div class="space-y-6 w-full">
    
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Hotel Profile & Settings</h2>
                <p class="text-sm text-slate-500 mt-1">Manage your hotel details, branding, and check-in times.</p>
            </div>
            <button wire:click="save" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl text-sm transition">
                Save Changes
            </button>
        </div>

        <div class="p-8 space-y-8">
            {{-- Branding --}}
            <section>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Branding</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Logo</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden shrink-0">
                                @if($logo)
                                    <img src="{{ $logo->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif($current_logo)
                                    <img src="{{ Storage::url($current_logo) }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                            </div>
                            <input wire:model="logo" type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Brand Color</label>
                        <div class="flex items-center gap-3">
                            <input wire:model="primary_color" type="color" class="w-10 h-10 rounded cursor-pointer border-0 p-0">
                            <input wire:model="primary_color" type="text" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:ring-blue-500/20 focus:border-blue-500 uppercase font-mono">
                        </div>
                    </div>
                </div>
            </section>

            {{-- Details --}}
            <section>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">General Details</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Property Name *</label>
                        <input wire:model="name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Contact Email *</label>
                            <input wire:model="contact_email" type="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Contact Phone *</label>
                            <input wire:model="contact_phone" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Address</label>
                        <textarea wire:model="address" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">GST Number</label>
                        <input wire:model="gst_number" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Registration Number</label>
                        <input wire:model="registration_number" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                </div>
            </section>

            {{-- Compliance & Legal Documents --}}
            <section>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Compliance & Legal Documents</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Upload New Documents (PDF/JPG/PNG)</label>
                    <input type="file" wire:model="document_files" multiple class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('document_files.*') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                @if(count($current_documents) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                    @foreach($current_documents as $doc)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <svg class="w-8 h-8 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <div class="truncate">
                                    <div class="text-sm font-medium text-slate-700 truncate">{{ $doc['name'] }}</div>
                                    <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($doc['uploaded_at'])->format('M d, Y') }}</div>
                                </div>
                            </div>
                            <a href="{{ Storage::url($doc['path']) }}" target="_blank" class="text-blue-600 hover:text-blue-700 text-sm font-medium px-2 py-1 rounded hover:bg-blue-50 transition-colors">View</a>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="text-sm text-slate-500 italic p-4 bg-slate-50 rounded-xl border border-slate-200 text-center">
                    No documents uploaded yet.
                </div>
                @endif
            </section>

            {{-- Timings --}}
            <section>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Policies & Timings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Standard Check-In Time</label>
                        <input wire:model="check_in_time" type="time" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Standard Check-Out Time</label>
                        <input wire:model="check_out_time" type="time" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
