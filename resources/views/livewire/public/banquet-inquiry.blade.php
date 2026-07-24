<div>
    @if($isSubmitted)
        <div class="bg-white rounded-3xl p-10 text-center shadow-xl border border-slate-100">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-3xl font-bold text-slate-800 mb-2">Inquiry Sent!</h2>
            <p class="text-slate-500 mb-8">Thank you for your interest. Our banquet team will contact you shortly.</p>
            <button wire:click="$set('isSubmitted', false)" class="px-8 py-3 bg-brand-primary text-white font-semibold rounded-xl hover:opacity-90 transition">Send Another Inquiry</button>
        </div>
    @else
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-800">Plan Your Event</h2>
                <p class="text-slate-500 mt-1">Request a quote for weddings, parties, or corporate events.</p>
            </div>

            <form wire:submit.prevent="submit" class="space-y-5">
                
                {{-- Event Space & Type --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Select Venue</label>
                        <select wire:model="event_space_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800">
                            <option value="">-- Choose an Event Space --</option>
                            @foreach($spaces as $space)
                                <option value="{{ $space->id }}">{{ $space->name }} (Up to {{ $space->capacity }} pax)</option>
                            @endforeach
                        </select>
                        @error('event_space_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Event Type</label>
                        <select wire:model="event_type" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800">
                            <option value="wedding">Wedding / Reception</option>
                            <option value="corporate">Corporate Event</option>
                            <option value="party">Birthday / Party</option>
                            <option value="other">Other</option>
                        </select>
                        @error('event_type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Dates & Pax --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Start Date</label>
                        <input wire:model="start_date" type="date" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800">
                        @error('start_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">End Date</label>
                        <input wire:model="end_date" type="date" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800">
                        @error('end_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Expected Guests</label>
                        <input wire:model="expected_pax" type="number" min="1" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800">
                        @error('expected_pax') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Contact Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-4 border-t border-slate-100">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                        <input wire:model="client_name" type="text" placeholder="John Doe" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800">
                        @error('client_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Phone Number</label>
                        <input wire:model="client_phone" type="text" placeholder="+1 234 567 8900" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800">
                        @error('client_phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                        <input wire:model="client_email" type="email" placeholder="john@example.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800">
                        @error('client_email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Additional Notes (Optional)</label>
                    <textarea wire:model="notes" rows="3" placeholder="Tell us about any specific requirements (catering, decorations, etc.)" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800 resize-none"></textarea>
                    @error('notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-brand-primary text-white text-lg font-semibold rounded-xl hover:opacity-90 transition shadow-lg shadow-brand-primary/30 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="submit">Send Inquiry</span>
                        <span wire:loading wire:target="submit">Sending...</span>
                        <svg wire:loading.remove wire:target="submit" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                    <p class="text-center text-xs text-slate-500 mt-4 flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Your information is secure and will only be used to contact you regarding your event.
                    </p>
                </div>
            </form>
        </div>
    @endif
</div>
