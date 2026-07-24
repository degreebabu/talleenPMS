<?php

namespace App\Livewire\Admin\Restaurant;

use App\Models\RestaurantMenuItem;
use Livewire\Component;

class MenuManager extends Component
{
    public $items;
    public $showForm = false;
    public $editingId = null;

    public $name = '';
    public $description = '';
    public $price = '';
    public $category = 'Food';
    public $is_available = true;

    public function mount()
    {
        $this->loadItems();
    }

    public function loadItems()
    {
        $this->items = RestaurantMenuItem::where('hotel_id', auth()->user()->hotel_id)->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $item = RestaurantMenuItem::findOrFail($id);
        $this->editingId = $item->id;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->price = $item->price;
        $this->category = $item->category;
        $this->is_available = $item->is_available;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
        ]);

        $data = [
            'hotel_id' => auth()->user()->hotel_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->category,
            'is_available' => $this->is_available,
        ];

        if ($this->editingId) {
            RestaurantMenuItem::where('id', $this->editingId)->update($data);
        } else {
            RestaurantMenuItem::create($data);
        }

        $this->showForm = false;
        $this->loadItems();
        session()->flash('success', 'Menu item saved successfully.');
    }

    public function delete($id)
    {
        RestaurantMenuItem::findOrFail($id)->delete();
        $this->loadItems();
    }

    public function toggleAvailability($id)
    {
        $item = RestaurantMenuItem::findOrFail($id);
        $item->update(['is_available' => !$item->is_available]);
        $this->loadItems();
    }

    public function resetForm()
    {
        $this->reset(['editingId', 'name', 'description', 'price', 'category']);
        $this->is_available = true;
    }

    public function render()
    {
        return view('livewire.admin.restaurant.menu-manager')->layout('layouts.admin');
    }
}
