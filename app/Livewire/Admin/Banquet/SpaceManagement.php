<?php

namespace App\Livewire\Admin\Banquet;

use App\Models\EventSpace;
use Livewire\Component;

class SpaceManagement extends Component
{
    public $spaces;
    public $showModal = false;
    public $isEditing = false;
    public $spaceId;

    public $name = '';
    public $type = 'hall';
    public $capacity = 0;
    public $price_per_hour = null;
    public $price_per_day = null;
    public $description = '';
    public $status = 'active';

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:hall,lawn,meeting_room',
        'capacity' => 'required|integer|min:1',
        'price_per_hour' => 'nullable|numeric|min:0',
        'price_per_day' => 'nullable|numeric|min:0',
        'description' => 'nullable|string',
        'status' => 'required|in:active,maintenance',
    ];

    public function mount()
    {
        $this->loadSpaces();
    }

    public function loadSpaces()
    {
        $this->spaces = EventSpace::where('hotel_id', auth()->user()->hotel_id)->get();
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['name', 'type', 'capacity', 'price_per_hour', 'price_per_day', 'description', 'status', 'spaceId', 'isEditing']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $space = EventSpace::findOrFail($id);
        $this->spaceId = $space->id;
        $this->name = $space->name;
        $this->type = $space->type;
        $this->capacity = $space->capacity;
        $this->price_per_hour = $space->price_per_hour;
        $this->price_per_day = $space->price_per_day;
        $this->description = $space->description;
        $this->status = $space->status;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'hotel_id' => auth()->user()->hotel_id,
            'name' => $this->name,
            'type' => $this->type,
            'capacity' => $this->capacity,
            'price_per_hour' => $this->price_per_hour,
            'price_per_day' => $this->price_per_day,
            'description' => $this->description,
            'status' => $this->status,
        ];

        if ($this->isEditing) {
            EventSpace::where('id', $this->spaceId)->update($data);
        } else {
            EventSpace::create($data);
        }

        $this->showModal = false;
        $this->loadSpaces();
    }

    public function delete($id)
    {
        EventSpace::findOrFail($id)->delete();
        $this->loadSpaces();
    }

    public function render()
    {
        return view('livewire.admin.banquet.space-management');
    }
}
