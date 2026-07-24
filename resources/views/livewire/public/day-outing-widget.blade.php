<div>
    @if($step === 4)
        <div class="bg-white rounded-3xl p-10 text-center shadow-xl border border-slate-100">
            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-3xl font-bold text-slate-800 mb-2">Day Pass Confirmed!</h2>
            <p class="text-slate-500 mb-8">Thank you, {{ $customer_name }}. Your day outing is booked.</p>
            <div class="bg-slate-50 rounded-2xl p-6 mb-8 text-left max-w-md mx-auto">
                <div class="grid grid-cols-2 gap-y-4 text-sm">
                    <div class="text-slate-500">Visit Date</div>
                    <div class="font-semibold text-slate-800 text-right">{{ \Carbon\Carbon::parse($visit_date)->format('M d, Y') }}</div>
                    <div class="text-slate-500">Number of Guests</div>
                    <div class="font-semibold text-slate-800 text-right">{{ $pax }} Pax</div>
                    <div class="text-slate-500">Amount Paid</div>
                    <div class="font-semibold text-slate-800 text-right">₹{{ number_format($total_amount, 2) }}</div>
                </div>
            </div>
            <button onclick="window.location.reload()" class="px-8 py-3 bg-brand-primary text-white font-semibold rounded-xl hover:opacity-90 transition">Book Another Pass</button>
        </div>
    @else
        <div class="flex flex-col md:flex-row gap-8">
            
            {{-- Main Form Area --}}
            <div class="flex-grow">
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100 relative overflow-hidden">
                    
                    {{-- Steps Header --}}
                    <div class="flex items-center justify-between mb-8 relative z-10">
                        @foreach(['Select Package', 'Your Details', 'Payment'] as $i => $label)
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
                            @if(!$loop->last)
                                <div class="flex-1 h-1 mx-2 {{ $step > $stepNum ? 'bg-brand-primary' : 'bg-slate-100' }} rounded-full transition-colors duration-300"></div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Step 1: Packages & Dates --}}
                    @if($step === 1)
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-slate-800">Choose a Day Package</h3>
                            
                            @if($packages->isEmpty())
                                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200">
                                    <p class="text-slate-500">No day packages are currently available.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($packages as $package)
                                        <div wire:click="selectPackage({{ $package->id }})" class="border-2 rounded-2xl p-5 cursor-pointer transition-all {{ $selected_package_id == $package->id ? 'border-brand-primary bg-brand-primary/5' : 'border-slate-100 hover:border-brand-primary/30' }}">
                                            <div class="flex justify-between items-start mb-2">
                                                <h4 class="font-bold text-slate-800">{{ $package->name }}</h4>
                                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $selected_package_id == $package->id ? 'border-brand-primary' : 'border-slate-300' }}">
                                                    @if($selected_package_id == $package->id)
                                                        <div class="w-2.5 h-2.5 rounded-full bg-brand-primary"></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-brand-primary font-semibold mb-2">₹{{ number_format($package->price, 2) }} <span class="text-xs text-slate-500 font-normal">/ pax</span></div>
                                            <p class="text-xs text-slate-500 mb-3">{{ $package->description }}</p>
                                            
                                            @if(!empty($package->inclusions))
                                                <div class="flex flex-wrap gap-1.5 mt-2 pt-3 border-t border-slate-100">
                                                    @foreach(array_slice($package->inclusions, 0, 3) as $inc)
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium bg-slate-100 text-slate-600 uppercase tracking-wider">
                                                            {{ $inc }}
                                                        </span>
                                                    @endforeach
                                                    @if(count($package->inclusions) > 3)
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium bg-slate-100 text-slate-600 uppercase tracking-wider">
                                                            +{{ count($package->inclusions) - 3 }} more
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @error('selected_package_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-6 border-t border-slate-100">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Date of Visit</label>
                                    <input wire:model="visit_date" type="date" min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800">
                                    @error('visit_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Number of Guests</label>
                                    <input wire:model.live.debounce.300ms="pax" type="number" min="1" max="50" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800">
                                    @error('pax') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="pt-6">
                                <button wire:click="goToDetails" class="w-full py-4 bg-brand-primary text-white text-lg font-semibold rounded-xl hover:opacity-90 transition shadow-lg shadow-brand-primary/30">Continue to Details</button>
                            </div>
                        </div>
                    @endif

                    {{-- Step 2: Customer Details --}}
                    @if($step === 2)
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-slate-800">Your Information</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                                    <input wire:model="customer_name" type="text" placeholder="John Doe" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800">
                                    @error('customer_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Phone Number</label>
                                    <input wire:model="customer_phone" type="text" placeholder="+1 234 567 8900" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800">
                                    @error('customer_phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                                    <input wire:model="customer_email" type="email" placeholder="john@example.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition text-slate-800">
                                    @error('customer_email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="pt-6 flex gap-4">
                                <button wire:click="$set('step', 1)" class="px-6 py-4 bg-slate-100 text-slate-600 font-semibold rounded-xl hover:bg-slate-200 transition">Back</button>
                                <button wire:click="goToPayment" class="flex-1 py-4 bg-brand-primary text-white text-lg font-semibold rounded-xl hover:opacity-90 transition shadow-lg shadow-brand-primary/30">Continue to Payment</button>
                            </div>
                        </div>
                    @endif

                    {{-- Step 3: Payment --}}
                    @if($step === 3)
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-slate-800 mb-4">Complete Payment</h3>
                            
                            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                                <div class="flex items-center gap-4 mb-6 text-slate-500">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    <div>
                                        <div class="font-medium text-slate-800">Secure Payment</div>
                                        <div class="text-xs">Your payment is encrypted and secure.</div>
                                    </div>
                                </div>
                                <p class="text-sm text-slate-600 mb-4">You are about to be redirected to our secure payment gateway to complete your purchase of ₹{{ number_format($total_amount, 2) }}.</p>
                            </div>

                            <div class="pt-6 flex gap-4">
                                <button wire:click="$set('step', 2)" class="px-6 py-4 bg-slate-100 text-slate-600 font-semibold rounded-xl hover:bg-slate-200 transition">Back</button>
                                <button wire:click="confirmPayment" class="flex-1 py-4 bg-brand-secondary text-white text-lg font-semibold rounded-xl hover:opacity-90 transition shadow-lg shadow-brand-secondary/30 flex items-center justify-center gap-2">
                                    <span wire:loading.remove wire:target="confirmPayment">Pay ₹{{ number_format($total_amount, 2) }}</span>
                                    <span wire:loading wire:target="confirmPayment">Processing...</span>
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Sidebar Summary --}}
            <div class="w-full md:w-80 shrink-0">
                <div class="bg-slate-900 rounded-3xl p-6 shadow-2xl sticky top-24 text-slate-300 border border-slate-800">
                    <h3 class="text-lg font-bold text-white mb-6">Booking Summary</h3>
                    
                    @if($selected_package_id && $visit_date)
                        @php
                            $selectedPackage = $packages->firstWhere('id', $selected_package_id);
                        @endphp
                        <div class="space-y-4 mb-6 pb-6 border-b border-slate-700/50">
                            <div>
                                <div class="text-xs text-slate-500 uppercase tracking-wider mb-1">Package</div>
                                <div class="font-medium text-white">{{ $selectedPackage->name }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500 uppercase tracking-wider mb-1">Visit Date</div>
                                <div class="font-medium text-white">{{ \Carbon\Carbon::parse($visit_date)->format('M d, Y') }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500 uppercase tracking-wider mb-1">Guests</div>
                                <div class="font-medium text-white">{{ $pax }} Pax</div>
                            </div>
                        </div>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span>Base Price ({{ $pax }} x ₹{{ number_format($selectedPackage->price, 2) }})</span>
                                <span class="font-medium text-white">₹{{ number_format($total_amount, 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-700/50 flex justify-between items-center">
                            <span class="font-semibold text-white">Total</span>
                            <span class="text-2xl font-bold text-brand-primary">₹{{ number_format($total_amount, 2) }}</span>
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <p class="text-sm">Select a package and date to see your summary.</p>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    @endif
</div>
