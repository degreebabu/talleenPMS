<div class="relative min-h-screen bg-slate-50 flex flex-col">
    
    {{-- Header --}}
    <header class="bg-white border-b border-slate-200 py-4 px-6 flex items-center justify-between z-10 sticky top-0">
        <div class="flex items-center gap-3">
            @if($hotel->logo_path)
                <img src="{{ Storage::url($hotel->logo_path) }}" alt="{{ $hotel->name }}" class="h-8 object-contain">
            @else
                <div class="w-8 h-8 bg-brand-primary rounded-lg flex items-center justify-center text-white font-bold text-lg">
                    {{ strtoupper(substr($hotel->name, 0, 1)) }}
                </div>
            @endif
            <h1 class="text-xl font-bold text-slate-900">{{ $hotel->name }}</h1>
        </div>
        
        <div class="text-sm font-semibold text-slate-500">
            Booking Portal
        </div>
    </header>

    <main class="flex-1 w-full max-w-5xl mx-auto px-4 py-8">
        
        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 font-medium">
                {{ session('error') }}
            </div>
        @endif

        {{-- STEP 1: Search & AI Filter --}}
        @if($step === 1)
        <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="text-center space-y-2">
                <h2 class="text-4xl font-black text-slate-900 tracking-tight">Book Your Stay</h2>
                <p class="text-slate-500 font-medium">Find the perfect room for your upcoming trip.</p>
            </div>

            {{-- The Smart Search Box (AI Feature) --}}
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl p-1 relative overflow-hidden shadow-lg shadow-indigo-200">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
                
                <div class="bg-white rounded-[22px] p-6 relative z-10">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">AI Room Recommender</h3>
                            <p class="text-sm text-slate-500">Describe what you're looking for, and our AI will find the best match!</p>
                            <input wire:model="aiSearchText" type="text" placeholder="e.g., 'A romantic suite with a view for 2 adults'" class="mt-3 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Check-in</label>
                            <input wire:model="checkIn" type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 font-semibold focus:ring-2 focus:ring-brand-primary">
                            @error('checkIn') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Check-out</label>
                            <input wire:model="checkOut" type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 font-semibold focus:ring-2 focus:ring-brand-primary">
                            @error('checkOut') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Rooms</label>
                                <input wire:model="rooms" type="number" min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 font-semibold focus:ring-2 focus:ring-brand-primary">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Adults</label>
                                <input wire:model="adults" type="number" min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 font-semibold focus:ring-2 focus:ring-brand-primary">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kids</label>
                                <input wire:model="children" type="number" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-900 font-semibold focus:ring-2 focus:ring-brand-primary">
                            </div>
                        </div>
                        <div class="flex items-end">
                            <button wire:click="searchRooms" class="w-full py-2.5 bg-brand-primary hover:opacity-90 text-white font-bold rounded-xl transition shadow-md flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Search Rooms
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- STEP 2: Select Room --}}
        @if($step === 2)
        <div class="space-y-6 animate-in fade-in slide-in-from-right-8 duration-500">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <button wire:click="back" class="text-sm font-semibold text-slate-500 hover:text-slate-900 flex items-center gap-1 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Back to Search
                    </button>
                    <h2 class="text-2xl font-bold text-slate-900 mt-2">Available Rooms</h2>
                    <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($checkIn)->format('M d') }} - {{ \Carbon\Carbon::parse($checkOut)->format('M d, Y') }} • {{ $adults }} Adults, {{ $children }} Children</p>
                </div>
            </div>

            @if(empty($availableCategories))
                <div class="bg-white border border-slate-200 rounded-3xl p-12 text-center shadow-sm">
                    <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="text-xl font-bold text-slate-900">No rooms available</h3>
                    <p class="text-slate-500 mt-2">Try adjusting your dates or guest count.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($availableCategories as $cat)
                    <div class="bg-white border {{ $aiMatchedCategoryId === $cat->id ? 'border-indigo-500 ring-4 ring-indigo-50 shadow-indigo-100' : 'border-slate-200' }} rounded-3xl overflow-hidden shadow-sm flex flex-col md:flex-row transition relative">
                        
                        @if($aiMatchedCategoryId === $cat->id)
                            <div class="absolute top-4 left-4 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-md z-10 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                AI Top Match
                            </div>
                        @endif

                        <div class="w-full md:w-1/3 bg-slate-100 relative min-h-[200px]">
                            @if($cat->images->count() > 0)
                                @php $imgPath = $cat->images->first()->image_path; @endphp
                                <img src="{{ str_starts_with($imgPath, 'http') ? $imgPath : Storage::url($imgPath) }}" class="absolute inset-0 w-full h-full object-cover">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-slate-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-6 md:p-8 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="text-2xl font-bold text-slate-900">{{ $cat->name }}</h3>
                                        <div class="flex items-center gap-4 text-xs font-semibold text-slate-500 mt-2">
                                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Sleeps {{ $cat->capacity }}</span>
                                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg> {{ $cat->size_sqm ?? '35' }} sqm</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Per Night</div>
                                        <div class="text-3xl font-black text-brand-primary">₹{{ number_format($cat->base_price * ($cat->requiredRooms ?? 1), 0) }}</div>
                                        @if(($cat->requiredRooms ?? 1) > 1)
                                            <div class="text-[10px] font-bold text-rose-500 uppercase tracking-wider mt-1">Requires {{ $cat->requiredRooms }} Rooms</div>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-sm text-slate-600 mt-4 leading-relaxed">{{ $cat->description }}</p>
                            </div>
                            
                            <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                                <div class="text-sm font-semibold text-emerald-600 flex items-center gap-1.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Available
                                </div>
                                <button wire:click="selectCategory({{ $cat->id }}, {{ $cat->requiredRooms ?? 1 }})" class="px-6 py-2.5 bg-brand-primary hover:opacity-90 text-white font-bold rounded-xl transition shadow-sm">
                                    Select Room
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endif

        {{-- STEP 3: Checkout --}}
        @if($step === 3)
        <div class="space-y-6 animate-in fade-in slide-in-from-right-8 duration-500">
            <button wire:click="back" class="text-sm font-semibold text-slate-500 hover:text-slate-900 flex items-center gap-1 transition mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Rooms
            </button>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Guest Details</h2>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Full Name *</label>
                                <input wire:model="guestName" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-brand-primary">
                                @error('guestName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Email Address *</label>
                                    <input wire:model="guestEmail" type="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-brand-primary">
                                    @error('guestEmail') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Phone Number *</label>
                                    <input wire:model="guestPhone" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-brand-primary">
                                    @error('guestPhone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Special Requests (Optional)</label>
                                <textarea wire:model="notes" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-brand-primary"></textarea>
                            </div>
                        </div>

                        <div class="mt-8 pt-8 border-t border-slate-100">
                            <button wire:click="confirmBooking" class="w-full py-4 bg-brand-primary hover:opacity-90 text-white font-black text-lg rounded-2xl transition shadow-lg flex items-center justify-center gap-2">
                                Confirm & Book Now
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                            <p class="text-center text-xs text-slate-500 font-medium mt-4">By clicking confirm, you agree to our terms and conditions. No payment is required until check-in.</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    @php
                        $cat = \App\Models\RoomCategory::find($this->selectedCategoryId);
                        $nights = \Carbon\Carbon::parse($checkIn)->diffInDays(\Carbon\Carbon::parse($checkOut));
                        $total = $cat ? $cat->base_price * $nights * $this->selectedRequiredRooms : 0;
                    @endphp
                    <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl sticky top-24">
                        <h3 class="text-lg font-bold text-white mb-6">Booking Summary</h3>
                        
                        <div class="space-y-4 mb-6 pb-6 border-b border-slate-700/50">
                            <div>
                                <div class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Room</div>
                                <div class="font-bold">{{ $this->selectedRequiredRooms }}x {{ $cat->name ?? '' }}</div>
                            </div>
                            <div class="flex justify-between">
                                <div>
                                    <div class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Check-in</div>
                                    <div class="font-bold">{{ \Carbon\Carbon::parse($checkIn)->format('M d, Y') }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Check-out</div>
                                    <div class="font-bold">{{ \Carbon\Carbon::parse($checkOut)->format('M d, Y') }}</div>
                                </div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Guests</div>
                                <div class="font-bold">{{ $adults }} Adults, {{ $children }} Children</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mb-2">
                            <div class="text-slate-400">₹{{ number_format($cat->base_price ?? 0, 0) }} x {{ $nights }} nights x {{ $this->selectedRequiredRooms }}</div>
                            <div class="font-bold text-white">₹{{ number_format($total, 0) }}</div>
                        </div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="text-slate-400">Taxes & Fees</div>
                            <div class="font-bold text-white">Included</div>
                        </div>

                        <div class="flex items-end justify-between pt-6 border-t border-slate-700/50">
                            <div class="text-sm text-slate-400 font-bold uppercase tracking-wider">Total</div>
                            <div class="text-4xl font-black text-brand-secondary">₹{{ number_format($total, 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- STEP 4: Confirmation --}}
        @if($step === 4)
        <div class="max-w-2xl mx-auto text-center py-12 animate-in zoom-in-95 duration-500">
            <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-4xl font-black text-slate-900 mb-4">Booking Confirmed!</h2>
            <p class="text-lg text-slate-600 mb-8">Thank you, {{ explode(' ', $guestName)[0] }}. Your reservation at {{ $hotel->name }} is confirmed.</p>
            
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm max-w-md mx-auto text-left">
                <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-1 text-center">Confirmation Number</div>
                <div class="text-3xl font-black text-slate-900 tracking-widest text-center mb-8">{{ $confirmedBookingNumber }}</div>
                
                <div class="space-y-4">
                    <div class="flex justify-between pb-4 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Check-in</span>
                        <span class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($checkIn)->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between pb-4 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Check-out</span>
                        <span class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($checkOut)->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-medium">Email sent to</span>
                        <span class="font-bold text-slate-900">{{ $guestEmail }}</span>
                    </div>
                </div>
            </div>

            <button wire:click="$set('step', 1)" class="mt-8 text-brand-primary font-bold hover:underline">Book another room</button>
        </div>
        @endif
    </main>

    {{-- AI Virtual Concierge Widget --}}
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
        
        {{-- Chat Window --}}
        @if($chatOpen)
        <div class="w-80 bg-white border border-slate-200 rounded-3xl shadow-2xl mb-4 overflow-hidden flex flex-col animate-in slide-in-from-bottom-8 fade-in duration-300" style="height: 450px;">
            <div class="bg-indigo-600 p-4 flex items-center justify-between text-white">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm">Virtual Concierge</div>
                        <div class="text-[10px] text-indigo-200 font-semibold uppercase tracking-wider">AI Powered</div>
                    </div>
                </div>
                <button wire:click="toggleChat" class="text-indigo-200 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="flex-1 p-4 overflow-y-auto bg-slate-50 space-y-4" id="chat-messages">
                @foreach($chatMessages as $msg)
                    @if($msg['role'] === 'bot')
                        <div class="flex gap-2">
                            <div class="w-6 h-6 rounded-full bg-indigo-100 flex-shrink-0 flex items-center justify-center mt-1">
                                <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div class="bg-white border border-slate-200 text-slate-700 text-sm p-3 rounded-2xl rounded-tl-sm shadow-sm">
                                {{ $msg['text'] }}
                            </div>
                        </div>
                    @else
                        <div class="flex justify-end">
                            <div class="bg-indigo-600 text-white text-sm p-3 rounded-2xl rounded-tr-sm shadow-sm max-w-[85%]">
                                {{ $msg['text'] }}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="p-3 bg-white border-t border-slate-200">
                <form wire:submit.prevent="sendMessage" class="flex items-center gap-2 relative">
                    <input wire:model="chatInput" type="text" placeholder="Ask a question..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <button type="submit" class="absolute right-1.5 p-1.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Chat Bubble Button --}}
        <button wire:click="toggleChat" class="w-14 h-14 bg-indigo-600 hover:bg-indigo-500 text-white rounded-full shadow-lg shadow-indigo-200 flex items-center justify-center transition hover:scale-105">
            @if($chatOpen)
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            @else
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            @endif
        </button>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', ({ el, component }) => {
                const container = document.getElementById('chat-messages');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        });
    </script>
</div>
