<div class="fixed bottom-6 right-6 z-50">
    {{-- Chat Button --}}
    <button wire:click="toggle" class="w-14 h-14 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-full shadow-2xl flex items-center justify-center text-white hover:scale-105 transition-transform">
        @if($isOpen)
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        @else
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg>
        @endif
    </button>

    {{-- Chat Window --}}
    @if($isOpen)
    <div class="absolute bottom-16 right-0 w-80 md:w-96 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden flex flex-col" style="height: 500px;">
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-4 flex items-center justify-between">
            <div>
                <h3 class="text-white font-bold text-sm">Talleen AI Assistant</h3>
                <p class="text-xs text-slate-300">Powered by Intelligence</p>
            </div>
            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
        </div>
        
        <div class="flex-1 p-4 overflow-y-auto bg-slate-50 space-y-4">
            @foreach($messages as $msg)
                @if($msg['role'] === 'assistant')
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-none p-3 text-sm text-slate-700 shadow-sm">
                        {{ $msg['content'] }}
                    </div>
                </div>
                @else
                <div class="flex gap-3 justify-end">
                    <div class="bg-blue-600 text-white rounded-2xl rounded-tr-none p-3 text-sm shadow-sm">
                        {{ $msg['content'] }}
                    </div>
                </div>
                @endif
            @endforeach
            <div wire:loading wire:target="sendMessage" class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-none p-3 text-sm text-slate-500 shadow-sm flex items-center gap-1">
                    <div class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"></div>
                    <div class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        </div>

        <div class="p-3 border-t border-slate-200 bg-white">
            <form wire:submit.prevent="sendMessage" class="flex gap-2">
                <input wire:model="userInput" type="text" placeholder="Ask about occupancy, rates..." class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white p-2 rounded-xl transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
