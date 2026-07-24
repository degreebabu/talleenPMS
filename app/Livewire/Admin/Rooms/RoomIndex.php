<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\Room;
use App\Models\RoomCategory;
use Livewire\Component;
use Livewire\WithPagination;

class RoomIndex extends Component
{
    use WithPagination;

    public bool $showModal = false;
    public ?int $editingId = null;

    public string $room_number = '';
    public string $floor = '';
    public string $status = 'available';
    public ?int $room_category_id = null;

    protected function rules(): array
    {
        return [
            'room_number'      => 'required|string|max:20',
            'floor'            => 'nullable|string|max:20',
            'status'           => 'required|in:available,occupied,maintenance,dirty',
            'room_category_id' => 'required|integer|exists:room_categories,id',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['room_number', 'floor', 'status', 'room_category_id', 'editingId']);
        $this->status = 'available';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $room = Room::where('hotel_id', auth()->user()->hotel_id)->findOrFail($id);
        $this->editingId        = $room->id;
        $this->room_number      = $room->room_number;
        $this->floor            = $room->floor ?? '';
        $this->status           = $room->status;
        $this->room_category_id = $room->room_category_id;
        $this->showModal        = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'hotel_id'         => auth()->user()->hotel_id,
            'room_category_id' => $this->room_category_id,
            'room_number'      => $this->room_number,
            'floor'            => $this->floor ?: null,
            'status'           => $this->status,
        ];

        if ($this->editingId) {
            Room::where('hotel_id', auth()->user()->hotel_id)->findOrFail($this->editingId)->update($data);
        } else {
            Room::create($data);
        }

        $this->showModal = false;
        $this->reset(['room_number', 'floor', 'status', 'room_category_id', 'editingId']);
    }

    public function updateStatus(int $id, string $status): void
    {
        Room::where('hotel_id', auth()->user()->hotel_id)
            ->findOrFail($id)
            ->update(['status' => $status]);
    }

    public function render()
    {
        $hotelId = auth()->user()->hotel_id;
        $rooms      = Room::with('category')
            ->where('hotel_id', $hotelId)
            ->orderBy('room_number')
            ->paginate(20);
        $categories = RoomCategory::where('hotel_id', $hotelId)->get();

        return view('livewire.admin.rooms.room-index', compact('rooms', 'categories'))
            ->layout('layouts.admin');
    }
}
