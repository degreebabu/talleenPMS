<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\BookingCharge;
use Livewire\Component;

class ChargePoster extends Component
{
    public $searchQuery = '';
    public $selectedBookingId = null;
    public $department = 'spa'; // 'spa', 'gift_shop', 'room_service', 'other'
    public $description = '';
    public $amount = '';

    public function searchBookings()
    {
        if (strlen($this->searchQuery) < 2) {
            return [];
        }

        return Booking::where('hotel_id', auth()->user()->hotel_id)
            ->where('status', 'checked_in')
            ->where(function($q) {
                $q->where('booking_number', 'like', "%{$this->searchQuery}%")
                  ->orWhereHas('guest', function($g) {
                      $g->where('name', 'like', "%{$this->searchQuery}%");
                  });
            })
            ->with('guest')
            ->take(5)
            ->get();
    }

    public function selectBooking($id)
    {
        $this->selectedBookingId = $id;
        $this->searchQuery = Booking::find($id)->guest->name . ' (' . Booking::find($id)->booking_number . ')';
    }

    public function clearSelection()
    {
        $this->selectedBookingId = null;
        $this->searchQuery = '';
    }

    public function postCharge()
    {
        $this->validate([
            'selectedBookingId' => 'required',
            'department' => 'required|string',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $booking = Booking::where('hotel_id', auth()->user()->hotel_id)
            ->findOrFail($this->selectedBookingId);

        BookingCharge::create([
            'booking_id' => $booking->id,
            'description' => $this->description,
            'amount' => $this->amount,
            'charge_type' => $this->department,
        ]);

        $booking->increment('total_amount', $this->amount);

        $this->reset(['selectedBookingId', 'searchQuery', 'description', 'amount']);
        session()->flash('success', 'Charge posted to folio successfully.');
    }

    public function render()
    {
        return view('livewire.admin.charge-poster', [
            'searchResults' => $this->selectedBookingId ? [] : $this->searchBookings()
        ])->layout('layouts.admin');
    }
}
