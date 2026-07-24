<x-public-layout :hotel="$hotel">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ tab: 'rooms' }">
        
        {{-- Tabs --}}
        <div class="flex justify-center mb-8">
            <div class="bg-white/50 backdrop-blur-md p-1.5 rounded-2xl inline-flex border border-slate-200/50 shadow-sm">
                <button @click="tab = 'rooms'" :class="{ 'bg-white shadow-md text-brand-primary font-semibold': tab === 'rooms', 'text-slate-500 hover:text-slate-700 font-medium': tab !== 'rooms' }" class="px-6 py-2.5 rounded-xl text-sm transition-all duration-300 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Book a Room
                </button>
                <button @click="tab = 'events'" :class="{ 'bg-white shadow-md text-brand-primary font-semibold': tab === 'events', 'text-slate-500 hover:text-slate-700 font-medium': tab !== 'events' }" class="px-6 py-2.5 rounded-xl text-sm transition-all duration-300 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    Plan an Event
                </button>
                <button @click="tab = 'outings'" :class="{ 'bg-white shadow-md text-brand-primary font-semibold': tab === 'outings', 'text-slate-500 hover:text-slate-700 font-medium': tab !== 'outings' }" class="px-6 py-2.5 rounded-xl text-sm transition-all duration-300 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                    Day Outing
                </button>
            </div>
        </div>

        {{-- Tab Content --}}
        <div x-show="tab === 'rooms'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            @livewire('public.booking-widget', ['hotel' => $hotel])
        </div>

        <div x-show="tab === 'events'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;" class="max-w-3xl mx-auto">
            @livewire('public.banquet-inquiry', ['hotel' => $hotel])
        </div>

        <div x-show="tab === 'outings'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            @livewire('public.day-outing-widget', ['hotel' => $hotel])
        </div>

    </div>
</x-public-layout>
