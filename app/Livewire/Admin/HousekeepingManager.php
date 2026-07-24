<?php

namespace App\Livewire\Admin;

use App\Models\Room;
use Livewire\Component;
use Livewire\WithPagination;

class HousekeepingManager extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updateStatus($roomId, $newStatus)
    {
        $room = Room::findOrFail($roomId);
        if ($room->hotel_id !== auth()->user()->hotel_id) {
            abort(403);
        }

        $room->housekeeping_status = $newStatus;
        $room->save();

        session()->flash('success', "Room {$room->room_number} marked as " . ucfirst(str_replace('_', ' ', $newStatus)));
    }

    public function render()
    {
        $hotelId = auth()->user()->hotel_id;

        $roomsQuery = Room::where('hotel_id', $hotelId)->with('category');

        if ($this->search) {
            $roomsQuery->where('room_number', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter) {
            $roomsQuery->where('housekeeping_status', $this->statusFilter);
        }

        $rooms = $roomsQuery->orderBy('room_number')->paginate(12);

        // Stats
        $stats = [
            'clean' => Room::where('hotel_id', $hotelId)->where('housekeeping_status', 'clean')->count(),
            'dirty' => Room::where('hotel_id', $hotelId)->where('housekeeping_status', 'dirty')->count(),
            'inspect' => Room::where('hotel_id', $hotelId)->where('housekeeping_status', 'inspect')->count(),
            'out_of_order' => Room::where('hotel_id', $hotelId)->where('housekeeping_status', 'out_of_order')->count(),
        ];

        return view('livewire.admin.housekeeping-manager', compact('rooms', 'stats'));
    }
}
