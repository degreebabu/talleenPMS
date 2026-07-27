<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\RoomCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class CategoryIndex extends Component
{
    use WithPagination, WithFileUploads;

    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $description = '';
    public string $base_price = '';
    public int $max_adults = 2;
    public int $max_children = 0;
    public array $amenities = [];
    public $newImages = [];

    public string $newAmenity = '';

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'base_price'  => 'required|numeric|min:0',
            'max_adults'  => 'required|integer|min:1',
            'max_children'=> 'required|integer|min:0',
            'amenities'   => 'array',
            'newImages.*' => 'image|max:3072',
        ];
    }

    public function addAmenity(): void
    {
        if (trim($this->newAmenity)) {
            $this->amenities[] = trim($this->newAmenity);
            $this->newAmenity = '';
        }
    }

    public function removeAmenity(int $index): void
    {
        array_splice($this->amenities, $index, 1);
    }

    public function openCreate(): void
    {
        $this->reset(['name', 'description', 'base_price', 'max_adults', 'max_children', 'amenities', 'newImages', 'editingId']);
        $this->max_adults = 2;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $cat = RoomCategory::where('hotel_id', auth()->user()->hotel_id)->findOrFail($id);
        $this->editingId    = $cat->id;
        $this->name         = $cat->name;
        $this->description  = $cat->description ?? '';
        $this->base_price   = $cat->base_price;
        $this->max_adults   = $cat->max_adults;
        $this->max_children = $cat->max_children;
        $this->amenities    = $cat->amenities_json ?? [];
        $this->showModal    = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'hotel_id'      => auth()->user()->hotel_id,
            'name'          => $this->name,
            'description'   => $this->description,
            'base_price'    => $this->base_price,
            'max_adults'    => $this->max_adults,
            'max_children'  => $this->max_children,
            'amenities_json'=> $this->amenities,
        ];

        if ($this->editingId) {
            $cat = RoomCategory::where('hotel_id', auth()->user()->hotel_id)->findOrFail($this->editingId);
            $cat->update($data);
        } else {
            $cat = RoomCategory::create($data);
        }

        foreach ($this->newImages as $image) {
            if (env('CLOUDINARY_URL')) {
                $path = cloudinary()->upload($image->getRealPath(), [
                    'folder' => "hotels/{$cat->hotel_id}/categories/{$cat->id}"
                ])->getSecurePath();
            } else {
                $path = $image->store("hotels/{$cat->hotel_id}/categories/{$cat->id}", 'public');
            }
            $cat->images()->create(['image_path' => $path]);
        }

        $this->showModal = false;
        $this->dispatch('notify', message: 'Room category saved!');
        $this->reset(['name', 'description', 'base_price', 'max_adults', 'max_children', 'amenities', 'newImages', 'editingId']);
    }

    public function deleteCategory(int $id): void
    {
        RoomCategory::where('hotel_id', auth()->user()->hotel_id)->findOrFail($id)->delete();
    }

    public function render()
    {
        $categories = RoomCategory::with('images')
            ->where('hotel_id', auth()->user()->hotel_id)
            ->paginate(10);

        return view('livewire.admin.rooms.category-index', compact('categories'))
            ->layout('layouts.admin');
    }
}
