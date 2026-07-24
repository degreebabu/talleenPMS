<?php

namespace App\Livewire\Admin\Pms;

use App\Models\HousekeepingTask;
use App\Models\Room;
use Livewire\Component;

class HousekeepingManager extends Component
{
    public $filter = 'all'; // all, clean, dirty, maintenance

    public function updateRoomStatus($roomId, $status)
    {
        $room = Room::findOrFail($roomId);
        $room->housekeeping_status = $status;
        $room->save();

        if ($status === 'dirty' || $status === 'maintenance') {
            // Create a pending task
            HousekeepingTask::firstOrCreate([
                'hotel_id' => auth()->user()->hotel_id,
                'room_id' => $room->id,
                'status' => 'pending'
            ]);
        } else if ($status === 'clean') {
            // Mark tasks as completed
            HousekeepingTask::where('room_id', $room->id)
                ->where('status', '!=', 'completed')
                ->update(['status' => 'completed']);
        }
    }

    public function render()
    {
        $rooms = Room::where('hotel_id', auth()->user()->hotel_id)
            ->with(['category'])
            ->when($this->filter !== 'all', function ($query) {
                $query->where('housekeeping_status', $this->filter);
            })
            ->orderBy('number')
            ->get();

        $stats = [
            'total' => Room::where('hotel_id', auth()->user()->hotel_id)->count(),
            'clean' => Room::where('hotel_id', auth()->user()->hotel_id)->where('housekeeping_status', 'clean')->count(),
            'dirty' => Room::where('hotel_id', auth()->user()->hotel_id)->where('housekeeping_status', 'dirty')->count(),
            'maintenance' => Room::where('hotel_id', auth()->user()->hotel_id)->where('housekeeping_status', 'maintenance')->count(),
        ];

        return view('livewire.admin.pms.housekeeping-manager', [
            'rooms' => $rooms,
            'stats' => $stats
        ]);
    }
}
