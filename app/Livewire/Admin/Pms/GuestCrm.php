<?php

namespace App\Livewire\Admin\Pms;

use App\Models\Guest;
use Livewire\Component;
use Livewire\WithPagination;

class GuestCrm extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $guests = Guest::where('hotel_id', auth()->user()->hotel_id)
            ->withCount('bookings')
            ->withSum('bookings', 'total_amount')
            ->when($this->search, function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.admin.pms.guest-crm', [
            'guests' => $guests
        ]);
    }
}
