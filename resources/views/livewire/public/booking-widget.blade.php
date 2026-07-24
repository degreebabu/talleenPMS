<div>
    @if($step === 5)
        <div class="bg-white rounded-3xl p-10 text-center shadow-xl border border-slate-100">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-3xl font-bold text-slate-800 mb-2">Booking Confirmed!</h2>
            <p class="text-slate-500 mb-8">Thank you, {{ $guest_name }}. Your booking at {{ $hotel->name }} is confirmed.</p>
            <div class="bg-slate-50 rounded-2xl p-6 mb-8 text-left max-w-md mx-auto">
                <div class="grid grid-cols-2 gap-y-4 text-sm">
                    <div class="text-slate-500">Check-in</div>
                    <div class="font-semibold text-slate-800 text-right">{{ \Carbon\Carbon::parse($check_in_date)->format('M d, Y') }}</div>
                    <div class="text-slate-500">Check-out</div>
                    <div class="font-semibold text-slate-800 text-right">{{ \Carbon\Carbon::parse($check_out_date)->format('M d, Y') }}</div>
                    <div class="text-slate-500">Amount Paid</div>
                    <div class="font-semibold text-slate-800 text-right">₹{{ number_format($grand_total, 2) }}</div>
                </div>
            </div>
            <button onclick="window.location.reload()" class="px-8 py-3 bg-brand-primary text-white font-semibold rounded-xl hover:opacity-90 transition">Book Another Room</button>
        </div>
    @else
        <div class="flex flex-col md:flex-row gap-8">
            
            {{-- Main Form Area --}}
            <div class="flex-grow">
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100 relative overflow-hidden">
                    
                    {{-- Steps Header --}}
                    <div class="flex items-center justify-between mb-8 relative z-10">
                        @foreach(['Dates', 'Room', 'Details', 'Payment'] as $i => $label)
                            @php $stepNum = $i + 1; @endphp
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition-colors duration-300 {{ $step >= $stepNum ? 'bg-brand-primary text-white' : 'bg-slate-100 text-slate-400' }}">
                                    @if($step > $stepNum)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        {{ $stepNum }}
                                    @endif
                                </div>
                                <span class="text-xs mt-2 {{ $step >= $stepNum ? 'text-brand-primary font-medium' : 'text-slate-400' }}">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Step 1: Dates & Guests --}}
                    @if($step === 1)
                        <form wire:submit.prevent="searchRooms" class="relative z-10 space-y-6">
                            @if($errors->has('search'))
                                <div class="p-4 bg-red-50 text-red-600 rounded-xl text-sm">{{ $errors->first('search') }}</div>
                            @endif
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Check-in Date</label>
                                    <input wire:model="check_in_date" type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 ring-brand-primary transition">
                                    @error('check_in_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Check-out Date</label>
                                    <input wire:model="check_out_date" type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 ring-brand-primary transition">
                                    @error('check_out_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Adults</label>
                                    <select wire:model="adults" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 ring-brand-primary transition">
                                        @for($i=1; $i<=10; $i++) <option value="{{$i}}">{{$i}}</option> @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Children</label>
                                    <select wire:model="children" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 ring-brand-primary transition">
                                        @for($i=0; $i<=10; $i++) <option value="{{$i}}">{{$i}}</option> @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="pt-4 flex justify-end">
                                <button type="submit" class="px-8 py-3 bg-brand-primary text-white font-semibold rounded-xl hover:opacity-90 transition shadow-lg shadow-brand-primary/30 flex items-center gap-2">
                                    <span wire:loading.remove wire:target="searchRooms">Search Rooms</span>
                                    <span wire:loading wire:target="searchRooms">Searching...</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </form>
                    @endif

                    {{-- Step 2: Room Selection --}}
                    @if($step === 2)
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold text-slate-800">Available Rooms</h3>
                            <div class="space-y-4">
                                @foreach($available_categories as $category)
                                    <div class="flex flex-col sm:flex-row border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg transition">
                                        @if($category->images->count())
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($category->images->first()->image_path) }}" class="w-full sm:w-48 h-48 sm:h-auto object-cover">
                                        @else
                                            <div class="w-full sm:w-48 h-48 sm:h-auto bg-slate-100 flex items-center justify-center text-slate-400">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                        <div class="p-5 flex-grow flex flex-col justify-between">
                                            <div>
                                                <h4 class="text-xl font-bold text-slate-800">{{ $category->name }}</h4>
                                                <p class="text-sm text-slate-500 mt-1">{{ \Illuminate\Support\Str::limit($category->description, 100) }}</p>
                                                @if($category->amenities_json)
                                                    <div class="flex flex-wrap gap-2 mt-3">
                                                        @foreach(array_slice($category->amenities_json, 0, 3) as $amenity)
                                                            <span class="px-2 py-1 bg-slate-100 text-slate-600 text-xs rounded-md font-medium">{{ $amenity }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="mt-4 flex items-end justify-between border-t border-slate-100 pt-4">
                                                <div>
                                                    <div class="text-xs text-slate-500">Price for {{ $total_days }} night(s)</div>
                                                    <div class="text-2xl font-bold text-brand-primary">₹{{ number_format($category->base_price * $total_days) }}</div>
                                                </div>
                                                <button wire:click="selectCategory({{ $category->id }})" class="px-5 py-2 bg-slate-900 text-white font-medium rounded-xl hover:bg-slate-800 transition">Select</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="pt-4 flex justify-start">
                                <button wire:click="goBack" class="px-6 py-2.5 text-slate-500 hover:text-slate-800 transition flex items-center gap-2 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Change Dates
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Step 3: Guest Details --}}
                    @if($step === 3)
                        <form wire:submit="submitGuestDetails" class="space-y-6">
                            <h3 class="text-lg font-bold text-slate-800 mb-4">Guest Details</h3>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Full Name *</label>
                                    <input wire:model="guest_name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 ring-brand-primary transition">
                                    @error('guest_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Email Address *</label>
                                        <input wire:model="guest_email" type="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 ring-brand-primary transition">
                                        @error('guest_email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Phone Number *</label>
                                        <input wire:model="guest_phone" type="tel" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:ring-2 ring-brand-primary transition">
                                        @error('guest_phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="pt-6 flex items-center justify-between border-t border-slate-100">
                                <button type="button" wire:click="goBack" class="px-6 py-2.5 text-slate-500 hover:text-slate-800 transition flex items-center gap-2 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Back
                                </button>
                                <button type="submit" class="px-8 py-3 bg-brand-primary text-white font-semibold rounded-xl hover:opacity-90 transition shadow-lg shadow-brand-primary/30">
                                    Proceed to Payment
                                </button>
                            </div>
                        </form>
                    @endif

                    {{-- Step 4: Payment Summary --}}
                    @if($step === 4)
                        <div class="space-y-6">
                            @if($errors->has('payment'))
                                <div class="p-4 bg-red-50 text-red-600 rounded-xl text-sm">{{ $errors->first('payment') }}</div>
                            @endif
                            <h3 class="text-lg font-bold text-slate-800 mb-4">Payment Summary</h3>
                            
                            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between text-slate-600">
                                        <span>Base Price ({{ $total_days }} nights)</span>
                                        <span class="font-medium text-slate-800">₹{{ number_format($total_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-slate-600">
                                        <span>Taxes (18% GST)</span>
                                        <span class="font-medium text-slate-800">₹{{ number_format($tax_amount, 2) }}</span>
                                    </div>
                                    <div class="pt-3 mt-3 border-t border-slate-200 flex justify-between font-bold text-lg text-brand-primary">
                                        <span>Grand Total</span>
                                        <span>₹{{ number_format($grand_total, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-brand-secondary/10 border border-brand-secondary/30 rounded-xl p-4 flex gap-3">
                                <svg class="w-6 h-6 text-brand-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm text-slate-700">This is a mocked payment gateway for testing. No real charges will be made. Clicking Pay will simulate a successful Razorpay transaction.</p>
                            </div>

                            <div class="pt-6 flex items-center justify-between border-t border-slate-100">
                                <button type="button" wire:click="goBack" class="px-6 py-2.5 text-slate-500 hover:text-slate-800 transition flex items-center gap-2 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Back
                                </button>
                                <button wire:click="processPayment" wire:loading.attr="disabled" class="px-8 py-3 bg-[#3399cc] hover:bg-[#2b88b5] text-white font-semibold rounded-xl transition shadow-lg shadow-blue-500/30 flex items-center gap-2">
                                    <span wire:loading.remove wire:target="processPayment">Pay with Razorpay</span>
                                    <span wire:loading wire:target="processPayment">Processing...</span>
                                    <svg wire:loading.remove wire:target="processPayment" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M22.46 9.29L12 1.57V4.76L19.23 10.1L12 15.42V18.6L22.46 10.87V9.29ZM1.54 14.71L12 22.43V19.24L4.77 13.9L12 8.58V5.4L1.54 13.13V14.71Z"/></svg>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar Summary --}}
            <div class="w-full md:w-80 shrink-0">
                <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl sticky top-24">
                    <h3 class="font-bold text-lg mb-4">Your Stay</h3>
                    
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between border-b border-slate-700 pb-3">
                            <span class="text-slate-400">Check-in</span>
                            <span class="font-medium">{{ $check_in_date ? \Carbon\Carbon::parse($check_in_date)->format('M d, Y') : '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-700 pb-3">
                            <span class="text-slate-400">Check-out</span>
                            <span class="font-medium">{{ $check_out_date ? \Carbon\Carbon::parse($check_out_date)->format('M d, Y') : '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-700 pb-3">
                            <span class="text-slate-400">Guests</span>
                            <span class="font-medium">{{ $adults }} Adults{{ $children > 0 ? ", $children Children" : '' }}</span>
                        </div>
                        
                        @if($step >= 3 && $selected_category_id)
                            @php $selCat = $available_categories->firstWhere('id', $selected_category_id); @endphp
                            <div class="pt-2">
                                <div class="text-brand-secondary font-bold mb-1">{{ $selCat->name ?? '' }}</div>
                                <div class="text-slate-400 text-xs">{{ $total_days }} Night(s)</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
