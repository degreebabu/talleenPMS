<?php

namespace App\Livewire\Admin\Outing;

use App\Models\DayPackage;
use Livewire\Component;

class PackageManagement extends Component
{
    public $packages;
    public $showModal = false;
    public $isEditing = false;
    public $packageId;

    public $name = '';
    public $description = '';
    public $price = '';
    public $start_time = '09:00';
    public $end_time = '18:00';
    public $inclusions = ''; // Comma separated for simplicity in form
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'start_time' => 'nullable|string',
        'end_time' => 'nullable|string',
        'inclusions' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadPackages();
    }

    public function loadPackages()
    {
        $this->packages = DayPackage::where('hotel_id', auth()->user()->hotel_id)->get();
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'price', 'start_time', 'end_time', 'inclusions', 'is_active', 'packageId', 'isEditing']);
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $package = DayPackage::findOrFail($id);
        $this->packageId = $package->id;
        $this->name = $package->name;
        $this->description = $package->description;
        $this->price = $package->price;
        $this->start_time = $package->start_time;
        $this->end_time = $package->end_time;
        $this->inclusions = implode(', ', $package->inclusions ?? []);
        $this->is_active = $package->is_active;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $inclusionsArray = array_map('trim', explode(',', $this->inclusions));
        $inclusionsArray = array_filter($inclusionsArray); // Remove empty

        $data = [
            'hotel_id' => auth()->user()->hotel_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'inclusions' => array_values($inclusionsArray),
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            DayPackage::where('id', $this->packageId)->update($data);
        } else {
            DayPackage::create($data);
        }

        $this->showModal = false;
        $this->loadPackages();
    }

    public function delete($id)
    {
        DayPackage::findOrFail($id)->delete();
        $this->loadPackages();
    }

    public function render()
    {
        return view('livewire.admin.outing.package-management');
    }
}
