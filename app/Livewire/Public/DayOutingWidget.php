<?php

namespace App\Livewire\Public;

use App\Models\DayPackage;
use App\Models\DayPass;
use App\Models\Hotel;
use Livewire\Component;

class DayOutingWidget extends Component
{
    public Hotel $hotel;
    
    public $step = 1;
    public $selected_package_id = null;
    public $visit_date = '';
    public $pax = 1;
    
    public $customer_name = '';
    public $customer_email = '';
    public $customer_phone = '';

    public $total_amount = 0;

    protected $rules = [
        'selected_package_id' => 'required|exists:day_packages,id',
        'visit_date' => 'required|date|after_or_equal:today',
        'pax' => 'required|integer|min:1|max:50',
        'customer_name' => 'required|string|max:255',
        'customer_email' => 'required|email|max:255',
        'customer_phone' => 'required|string|max:20',
    ];

    public function selectPackage($id)
    {
        $this->selected_package_id = $id;
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        if ($this->selected_package_id) {
            $package = DayPackage::find($this->selected_package_id);
            if ($package) {
                $this->total_amount = $package->price * $this->pax;
            }
        }
    }

    public function updatedPax()
    {
        $this->calculateTotal();
    }

    public function goToDetails()
    {
        $this->validate([
            'selected_package_id' => 'required',
            'visit_date' => 'required|date|after_or_equal:today',
            'pax' => 'required|integer|min:1',
        ]);
        $this->step = 2;
    }

    public function goToPayment()
    {
        $this->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
        ]);
        $this->step = 3;
    }

    public function confirmPayment()
    {
        // Mock payment flow
        $pass = DayPass::create([
            'hotel_id' => $this->hotel->id,
            'day_package_id' => $this->selected_package_id,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'visit_date' => $this->visit_date,
            'pax' => $this->pax,
            'total_amount' => $this->total_amount,
            'status' => 'confirmed',
        ]);

        $this->step = 4;
    }

    public function render()
    {
        $packages = DayPackage::where('hotel_id', $this->hotel->id)->where('is_active', true)->get();

        return view('livewire.public.day-outing-widget', [
            'packages' => $packages
        ]);
    }
}
