<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 flex items-center justify-center p-4">

    {{-- Background decoration --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-2xl">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl shadow-lg shadow-blue-500/30 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white">Set Up Your Hotel</h1>
            <p class="text-slate-400 mt-1">Complete the wizard to launch your booking engine</p>
        </div>

        {{-- Progress Steps --}}
        <div class="flex items-center justify-center mb-8 gap-2">
            @foreach(['Property Info', 'Brand & Identity', 'Operations'] as $i => $label)
                @php $stepNum = $i + 1; @endphp
                <div class="flex items-center">
                    <div class="flex flex-col items-center">
                        <div @class([
                            'w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300',
                            'bg-blue-600 text-white shadow-lg shadow-blue-500/40' => $step >= $stepNum,
                            'bg-slate-700 text-slate-400' => $step < $stepNum,
                        ])>
                            @if($step > $stepNum)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $stepNum }}
                            @endif
                        </div>
                        <span @class([
                            'text-xs mt-1 whitespace-nowrap transition-colors',
                            'text-blue-400' => $step >= $stepNum,
                            'text-slate-500' => $step < $stepNum,
                        ])>{{ $label }}</span>
                    </div>
                    @if($i < 2)
                        <div @class([
                            'h-0.5 w-16 mx-2 mt-[-12px] transition-all duration-300',
                            'bg-blue-600' => $step > $stepNum,
                            'bg-slate-700' => $step <= $stepNum,
                        ])></div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
            <form wire:submit="submit">
                {{-- Step 1: Basic Info --}}
                @if($step === 1)
                <div>
                    <h2 class="text-xl font-semibold text-white mb-6">Property Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1.5">Hotel Name *</label>
                            <input wire:model="name" type="text" placeholder="e.g. The Grand Palace Hotel"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1.5">Booking URL Subdomain *</label>
                            <div class="flex items-center rounded-xl border border-white/10 overflow-hidden bg-white/5 focus-within:ring-2 focus-within:ring-blue-500">
                                <input wire:model="subdomain" type="text" placeholder="grandpalace"
                                       class="flex-1 bg-transparent px-4 py-3 text-white placeholder-slate-500 focus:outline-none">
                                <span class="px-3 py-3 text-slate-400 text-sm border-l border-white/10 whitespace-nowrap">.talleen.in</span>
                            </div>
                            @error('subdomain') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">Contact Email *</label>
                                <input wire:model="contact_email" type="email" placeholder="info@hotel.com"
                                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                @error('contact_email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">Contact Phone *</label>
                                <input wire:model="contact_phone" type="tel" placeholder="+91 9876543210"
                                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                @error('contact_phone') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1.5">Full Address *</label>
                            <textarea wire:model="address" rows="3" placeholder="123 Main Street, Mumbai, Maharashtra 400001"
                                      class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none"></textarea>
                            @error('address') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                @endif

                {{-- Step 2: Brand & Identity --}}
                @if($step === 2)
                <div>
                    <h2 class="text-xl font-semibold text-white mb-6">Brand & Identity</h2>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1.5">GSTIN (optional)</label>
                            <input wire:model="gst_number" type="text" placeholder="27AAAAA0000A1Z5"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition uppercase">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">Primary Color</label>
                                <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl px-4 py-2.5">
                                    <input wire:model="primary_color" type="color" class="w-9 h-9 rounded-lg cursor-pointer border-0 bg-transparent p-0">
                                    <span class="text-slate-300 text-sm font-mono">{{ $primary_color }}</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">Accent Color</label>
                                <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl px-4 py-2.5">
                                    <input wire:model="secondary_color" type="color" class="w-9 h-9 rounded-lg cursor-pointer border-0 bg-transparent p-0">
                                    <span class="text-slate-300 text-sm font-mono">{{ $secondary_color }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Brand preview --}}
                        <div class="rounded-xl p-4 flex items-center gap-3 border border-white/10 bg-white/5">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white text-sm font-bold" style="background-color: {{ $primary_color }}">
                                {{ strtoupper(substr($name ?: 'H', 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-white">{{ $name ?: 'Your Hotel Name' }}</div>
                                <div class="text-xs text-slate-400">Brand Preview</div>
                            </div>
                            <div class="ml-auto">
                                <span class="text-xs px-2 py-1 rounded-full font-medium text-white" style="background-color: {{ $secondary_color }}">Book Now</span>
                            </div>
                        </div>

                        {{-- Logo Upload --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1.5">Hotel Logo <span class="text-slate-500">(PNG/JPG, max 2MB)</span></label>
                            <div class="relative border-2 border-dashed border-white/10 rounded-xl p-4 hover:border-blue-500/50 transition cursor-pointer" onclick="document.getElementById('logo-upload').click()">
                                <input id="logo-upload" wire:model="logo" type="file" accept="image/*" class="hidden">
                                @if($logo)
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $logo->temporaryUrl() }}" class="w-16 h-16 object-cover rounded-lg">
                                        <p class="text-slate-300 text-sm">{{ $logo->getClientOriginalName() }}</p>
                                    </div>
                                @else
                                    <div class="text-center py-2">
                                        <svg class="w-8 h-8 text-slate-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <p class="text-slate-400 text-sm">Click to upload logo</p>
                                    </div>
                                @endif
                            </div>
                            @error('logo') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Cover Upload --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1.5">Cover Photo <span class="text-slate-500">(PNG/JPG, max 5MB)</span></label>
                            <div class="relative border-2 border-dashed border-white/10 rounded-xl p-4 hover:border-blue-500/50 transition cursor-pointer" onclick="document.getElementById('cover-upload').click()">
                                <input id="cover-upload" wire:model="cover" type="file" accept="image/*" class="hidden">
                                @if($cover)
                                    <img src="{{ $cover->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg">
                                @else
                                    <div class="text-center py-4">
                                        <svg class="w-8 h-8 text-slate-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        <p class="text-slate-400 text-sm">Click to upload cover photo</p>
                                    </div>
                                @endif
                            </div>
                            @error('cover') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                @endif

                {{-- Step 3: Operations --}}
                @if($step === 3)
                <div>
                    <h2 class="text-xl font-semibold text-white mb-6">Operations Setup</h2>
                    <div class="space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">Standard Check-in Time</label>
                                <input wire:model="check_in_time" type="time"
                                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                @error('check_in_time') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">Standard Check-out Time</label>
                                <input wire:model="check_out_time" type="time"
                                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                @error('check_out_time') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Summary Card --}}
                        <div class="bg-white/5 rounded-2xl p-5 border border-white/10 space-y-3">
                            <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider">Setup Summary</h3>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="text-slate-500">Hotel Name</div>
                                <div class="text-white font-medium">{{ $name }}</div>
                                <div class="text-slate-500">Booking URL</div>
                                <div class="text-blue-400 font-mono text-xs">{{ $subdomain }}.talleen.in</div>
                                <div class="text-slate-500">Contact</div>
                                <div class="text-white">{{ $contact_email }}</div>
                                <div class="text-slate-500">GSTIN</div>
                                <div class="text-white">{{ $gst_number ?: '(Not provided)' }}</div>
                            </div>
                        </div>

                        <div class="bg-green-500/10 border border-green-500/20 rounded-xl p-4">
                            <p class="text-green-400 text-sm">
                                <span class="font-semibold">🎉 Almost done!</span> Clicking "Launch Hotel" will create your property.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Navigation Buttons --}}
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-white/10">
                    @if($step > 1)
                        <button type="button" wire:click="prevStep" class="flex items-center gap-2 px-5 py-2.5 text-slate-300 hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Back
                        </button>
                    @else
                        <div></div>
                    @endif

                    <button
                        type="submit"
                        class="flex items-center gap-2 px-6 py-2.5 {{ $step < $totalSteps ? 'bg-blue-600 hover:bg-blue-500 shadow-blue-500/30' : 'bg-green-600 hover:bg-green-500 shadow-green-500/30' }} text-white font-semibold rounded-xl shadow-lg transition active:scale-95 disabled:opacity-50">
                        
                        <span wire:loading.remove wire:target="submit">
                            @if($step < $totalSteps)
                                Continue
                                <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            @else
                                🚀 Launch Hotel
                            @endif
                        </span>
                        
                        <span wire:loading wire:target="submit">
                            Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-slate-600 text-sm mt-6">TalleenPMS &copy; {{ date('Y') }}</p>
    </div>
</div>
