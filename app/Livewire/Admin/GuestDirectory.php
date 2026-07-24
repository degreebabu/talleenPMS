<?php

namespace App\Livewire\Admin;

use App\Models\Guest;
use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class GuestDirectory extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedGuest = null;
    public $showModal = false;

    protected $queryString = ['search'];

    public function updatingSearch() { $this->resetPage(); }

    public function viewGuest($id)
    {
        $hotel = auth()->user()->hotel;
        $this->selectedGuest = Guest::where('hotel_id', $hotel->id)
            ->with(['bookings' => fn($q) => $q->latest()->take(5)])
            ->findOrFail($id);
        $this->showModal = true;
    }

    public function render()
    {
        $hotel = auth()->user()->hotel;

        $guests = Guest::where('hotel_id', $hotel->id)
            ->when($this->search, fn($q) => $q
                ->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
            )
            ->withCount('bookings')
            ->latest()
            ->paginate(20);

        return view('livewire.admin.guest-directory', compact('guests'));
    }
}
