<?php

namespace App\Livewire\Admin;

use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\Booking;
use App\Models\BookingCharge;
use Livewire\Component;

class PosTerminal extends Component
{
    public $currentOrder = null;
    public $tableNumber = '';
    public $linkedBookingId = '';
    public $newItemName = '';
    public $newItemQty = 1;
    public $newItemPrice = '';

    // Menu items are loaded dynamically now

    public function newOrder()
    {
        $hotel = auth()->user()->hotel;
        $this->currentOrder = PosOrder::create([
            'hotel_id' => $hotel->id,
            'table_number' => $this->tableNumber ?: 'Counter',
            'status' => 'open',
        ]);
        $this->tableNumber = '';
    }

    public function addMenuItem($name, $price)
    {
        if (!$this->currentOrder) return;

        $existing = $this->currentOrder->items()->where('item_name', $name)->first();
        if ($existing) {
            $existing->increment('quantity');
            $existing->total_price = $existing->quantity * $existing->unit_price;
            $existing->save();
        } else {
            PosOrderItem::create([
                'pos_order_id' => $this->currentOrder->id,
                'item_name' => $name,
                'quantity' => 1,
                'unit_price' => $price,
                'total_price' => $price,
            ]);
        }
        $this->recalculateTotals();
    }

    public function addCustomItem()
    {
        $this->validate([
            'newItemName' => 'required|string',
            'newItemQty' => 'required|integer|min:1',
            'newItemPrice' => 'required|numeric|min:0',
        ]);

        if (!$this->currentOrder) return;

        PosOrderItem::create([
            'pos_order_id' => $this->currentOrder->id,
            'item_name' => $this->newItemName,
            'quantity' => $this->newItemQty,
            'unit_price' => $this->newItemPrice,
            'total_price' => $this->newItemQty * $this->newItemPrice,
        ]);
        $this->recalculateTotals();
        $this->reset(['newItemName', 'newItemQty', 'newItemPrice']);
    }

    public function removeItem($itemId)
    {
        PosOrderItem::where('pos_order_id', $this->currentOrder->id)->findOrFail($itemId)->delete();
        $this->recalculateTotals();
    }

    private function recalculateTotals()
    {
        $this->currentOrder->refresh();
        $subtotal = $this->currentOrder->items->sum('total_price');
        $tax = round($subtotal * 0.05, 2); // 5% GST on food
        $this->currentOrder->update([
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'total_amount' => $subtotal + $tax,
        ]);
        $this->currentOrder->refresh();
    }

    public function sendKot()
    {
        if (!$this->currentOrder) return;
        $this->currentOrder->update(['status' => 'kot_sent']);
        session()->flash('success', 'KOT sent to kitchen!');
    }

    public function settleOrder($paymentMethod)
    {
        if (!$this->currentOrder) return;

        if ($paymentMethod === 'room_posting' && $this->linkedBookingId) {
            $booking = Booking::where('hotel_id', auth()->user()->hotel_id)
                ->where('id', $this->linkedBookingId)
                ->first();
            if ($booking) {
                BookingCharge::create([
                    'booking_id' => $booking->id,
                    'description' => 'POS Order #' . $this->currentOrder->order_number,
                    'amount' => $this->currentOrder->total_amount,
                    'charge_type' => 'food',
                ]);
                $booking->increment('total_amount', $this->currentOrder->total_amount);
            }
        }

        $this->currentOrder->update([
            'status' => 'settled',
            'payment_method' => $paymentMethod,
        ]);

        $this->currentOrder = null;
        $this->linkedBookingId = '';
        session()->flash('success', 'Order settled successfully.');
    }

    public function loadOrder($id)
    {
        $this->currentOrder = PosOrder::where('hotel_id', auth()->user()->hotel_id)->with('items')->findOrFail($id);
    }

    public function render()
    {
        $hotel = auth()->user()->hotel;
        $openOrders = PosOrder::where('hotel_id', $hotel->id)
            ->whereIn('status', ['open', 'kot_sent'])
            ->with('items')
            ->latest()
            ->get();
        
        $checkedInBookings = \App\Models\Booking::where('hotel_id', $hotel->id)
            ->where('status', 'checked_in')
            ->with('guest')
            ->get();

        $menuItems = \App\Models\RestaurantMenuItem::where('hotel_id', $hotel->id)
            ->where('is_available', true)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->name,
                    'price' => $item->price,
                    'category' => $item->category,
                ];
            })->toArray();

        return view('livewire.admin.pos-terminal', compact('openOrders', 'checkedInBookings', 'menuItems'));
    }
}
