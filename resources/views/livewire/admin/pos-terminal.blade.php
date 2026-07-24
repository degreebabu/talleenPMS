<div>

    @if(session()->has('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl font-medium">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Menu & New Order --}}
        <div class="lg:col-span-2 space-y-6">
            @if(!$currentOrder)
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Start New Order</h3>
                <div class="flex gap-3">
                    <input wire:model="tableNumber" type="text" placeholder="Table # or Counter..." class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    <button wire:click="newOrder" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl transition">Open Order</button>
                </div>
            </div>
            @endif

            @if($currentOrder)
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900">Menu</h3>
                        <p class="text-xs text-slate-500">Click an item to add it to Order {{ $currentOrder->order_number }}</p>
                    </div>
                </div>
                <div class="p-6">
                    @php $categories = collect($menuItems)->groupBy('category') @endphp
                    @foreach($categories as $cat => $items)
                    <div class="mb-5">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">{{ $cat }}</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($items as $item)
                            <button wire:click="addMenuItem('{{ $item['name'] }}', {{ $item['price'] }})" class="text-left p-3 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 rounded-xl transition group">
                                <div class="text-sm font-semibold text-slate-900 group-hover:text-blue-700">{{ $item['name'] }}</div>
                                <div class="text-xs text-slate-500 mt-1">₹{{ $item['price'] }}</div>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    <div class="border-t border-slate-200 pt-4 mt-2">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Custom Item</p>
                        <div class="flex gap-2">
                            <input wire:model="newItemName" type="text" placeholder="Item name" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <input wire:model="newItemQty" type="number" min="1" placeholder="Qty" class="w-16 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <input wire:model="newItemPrice" type="number" placeholder="Price" class="w-24 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <button wire:click="addCustomItem" class="px-4 py-2 bg-slate-800 text-white text-sm font-semibold rounded-xl hover:bg-slate-700 transition">Add</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Right: Current Order / Open Orders --}}
        <div class="space-y-6">
            @if($currentOrder)
            {{-- Active Order Bill --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 bg-slate-900 text-white flex items-center justify-between">
                    <div>
                        <div class="font-bold">{{ $currentOrder->order_number }}</div>
                        <div class="text-xs text-slate-400">Table: {{ $currentOrder->table_number }}</div>
                    </div>
                    <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $currentOrder->status === 'kot_sent' ? 'bg-amber-500' : 'bg-emerald-500' }} text-white">
                        {{ strtoupper($currentOrder->status) }}
                    </span>
                </div>

                <div class="p-5 space-y-2 max-h-60 overflow-y-auto">
                    @forelse($currentOrder->items as $item)
                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <span class="font-semibold text-slate-900">{{ $item->quantity }}× {{ $item->item_name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-900">₹{{ number_format($item->total_price, 0) }}</span>
                            <button wire:click="removeItem({{ $item->id }})" class="text-red-400 hover:text-red-600 text-xs">×</button>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-slate-400 text-sm py-4">No items added yet.</p>
                    @endforelse
                </div>

                <div class="px-5 py-4 border-t border-slate-100 space-y-1 text-sm">
                    <div class="flex justify-between text-slate-600"><span>Subtotal</span><span>₹{{ number_format($currentOrder->subtotal, 0) }}</span></div>
                    <div class="flex justify-between text-slate-600"><span>GST (5%)</span><span>₹{{ number_format($currentOrder->tax_amount, 0) }}</span></div>
                    <div class="flex justify-between font-black text-slate-900 text-base pt-2 border-t border-slate-200"><span>Total</span><span>₹{{ number_format($currentOrder->total_amount, 0) }}</span></div>
                </div>

                <div class="px-5 pb-4 space-y-3">
                    <button wire:click="sendKot" class="w-full py-2 bg-amber-500 hover:bg-amber-400 text-white font-semibold rounded-xl transition">
                        🍳 Send KOT to Kitchen
                    </button>

                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Settle Order</p>
                        <div class="grid grid-cols-2 gap-2">
                            <button wire:click="settleOrder('cash')" class="py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition">💵 Cash</button>
                            <button wire:click="settleOrder('card')" class="py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl transition">💳 Card</button>
                            <button wire:click="settleOrder('upi')" class="py-2 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-xl transition">📱 UPI</button>
                            <div class="col-span-2">
                                <select wire:model="linkedBookingId" class="w-full mb-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs">
                                    <option value="">-- Select Room --</option>
                                    @foreach($checkedInBookings as $b)
                                    <option value="{{ $b->id }}">{{ $b->guest->name }} ({{ $b->booking_number }})</option>
                                    @endforeach
                                </select>
                                <button wire:click="settleOrder('room_posting')" class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-xl transition">🏨 Post to Room</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            {{-- Open Orders List --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900">Open Orders</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($openOrders as $order)
                    <div wire:click="loadOrder({{ $order->id }})" class="px-5 py-3 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition">
                        <div>
                            <div class="font-semibold text-sm text-slate-900 group-hover:text-blue-600">{{ $order->order_number }} — Table {{ $order->table_number }}</div>
                            <div class="text-xs text-slate-500">{{ $order->items->count() }} items · ₹{{ number_format($order->total_amount, 0) }}</div>
                        </div>
                        <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $order->status === 'kot_sent' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ strtoupper($order->status) }}
                        </span>
                    </div>
                    @empty
                    <div class="px-5 py-8 text-center text-slate-400 text-sm">No open orders. Start a new order above.</div>
                    @endforelse
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
