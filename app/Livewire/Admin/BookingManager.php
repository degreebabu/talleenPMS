<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Guest;
use App\Models\Room;
use Livewire\Component;
use Livewire\WithPagination;

class BookingManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $showCreateModal = false;
    public $showDetailModal = false;
    public $selectedBooking = null;

    // New booking form
    public $guest_name = '', $guest_email = '', $guest_phone = '';
    public $room_id = '', $check_in = '', $check_out = '';
    public $adults = 1, $children = 0, $notes = '';

    protected $queryString = ['statusFilter', 'search'];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function openCreate()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function resetForm()
    {
        $this->guest_name = '';
        $this->guest_email = '';
        $this->guest_phone = '';
        $this->room_id = '';
        $this->check_in = '';
        $this->check_out = '';
        $this->adults = 1;
        $this->children = 0;
        $this->notes = '';
    }

    public function createBooking()
    {
        $this->validate([
            'guest_name'  => 'required|string|max:255',
            'guest_phone' => 'required|string|max:30',
            'room_id'     => 'required|exists:rooms,id',
            'check_in'    => 'required|date',
            'check_out'   => 'required|date|after:check_in',
            'adults'      => 'required|integer|min:1',
        ]);

        $hotel = auth()->user()->hotel;

        // Find or create guest
        $guest = Guest::firstOrCreate(
            ['hotel_id' => $hotel->id, 'phone' => $this->guest_phone],
            ['name' => $this->guest_name, 'email' => $this->guest_email]
        );

        $room = Room::findOrFail($this->room_id);
        $checkIn  = \Carbon\Carbon::parse($this->check_in);
        $checkOut = \Carbon\Carbon::parse($this->check_out);
        $nights   = max(1, $checkIn->diffInDays($checkOut));
        $roomPrice = $room->category->base_price * $nights;
        $tax = round($roomPrice * 0.12, 2); // 12% GST

        $booking = Booking::create([
            'hotel_id'     => $hotel->id,
            'guest_id'     => $guest->id,
            'user_id'      => auth()->id(),
            'status'       => 'confirmed',
            'booking_type' => 'room',
            'total_amount' => $roomPrice + $tax,
            'tax_amount'   => $tax,
            'adults'       => $this->adults,
            'children'     => $this->children,
            'notes'        => $this->notes,
        ]);

        BookingItem::create([
            'booking_id' => $booking->id,
            'item_type'  => Room::class,
            'item_id'    => $room->id,
            'start_date' => $checkIn,
            'end_date'   => $checkOut,
            'price'      => $roomPrice,
            'tax'        => $tax,
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('success', 'Booking #' . $booking->booking_number . ' created successfully.');
    }

    public function viewBooking($id)
    {
        $this->selectedBooking = Booking::with(['guest', 'items.item.category', 'createdByUser'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function checkIn($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'checked_in', 'checked_in_at' => now()]);
        if ($this->selectedBooking?->id === $id) {
            $this->selectedBooking->refresh();
        }
    }

    public function checkOut($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'checked_out', 'checked_out_at' => now()]);
        if ($this->selectedBooking?->id === $id) {
            $this->selectedBooking->refresh();
        }
    }

    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'cancelled']);
        if ($this->selectedBooking?->id === $id) {
            $this->selectedBooking->refresh();
        }
    }

    public function render()
    {
        $hotel = auth()->user()->hotel;

        $bookings = Booking::with(['guest'])
            ->where('hotel_id', $hotel->id)
            ->when($this->search, fn($q) => $q->whereHas('guest', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%')->orWhere('phone', 'like', '%' . $this->search . '%'))->orWhere('booking_number', 'like', '%' . $this->search . '%'))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);

        $rooms = Room::where('hotel_id', $hotel->id)
            ->where('status', 'available')
            ->with('category')
            ->get();

        return view('livewire.admin.booking-manager', compact('bookings', 'rooms'));
    }
}
