<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\BookingCharge;
use Livewire\Component;

class FolioManager extends Component
{
    public $booking;
    public $description = '';
    public $amount = '';
    public $charge_type = 'food';

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        
        // Ensure user belongs to this hotel
        if ($this->booking->hotel_id !== auth()->user()->hotel_id) {
            abort(403);
        }
    }

    public function addCharge()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'charge_type' => 'required|string|in:food,spa,laundry,other',
        ]);

        BookingCharge::create([
            'booking_id' => $this->booking->id,
            'description' => $this->description,
            'amount' => $this->amount,
            'charge_type' => $this->charge_type,
        ]);

        // Update total amount on booking
        $this->booking->total_amount += $this->amount;
        $this->booking->save();

        $this->reset(['description', 'amount', 'charge_type']);
        $this->charge_type = 'food';

        session()->flash('success', 'Charge added successfully.');
    }

    public function deleteCharge($id)
    {
        $charge = BookingCharge::where('booking_id', $this->booking->id)->findOrFail($id);
        
        // Deduct from booking total
        $this->booking->total_amount -= $charge->amount;
        $this->booking->save();

        $charge->delete();

        session()->flash('success', 'Charge removed successfully.');
    }

    public function render()
    {
        return view('livewire.admin.folio-manager', [
            'charges' => $this->booking->charges()->latest()->get(),
        ]);
    }
}
