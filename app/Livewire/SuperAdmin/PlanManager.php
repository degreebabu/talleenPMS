<?php

namespace App\Livewire\SuperAdmin;

use App\Models\SubscriptionPlan;
use Livewire\Component;
use Livewire\Attributes\Layout;

class PlanManager extends Component
{
    public $plans;
    
    // Form fields
    public $plan_id, $name, $description, $price_monthly, $price_yearly, $max_rooms;
    public $features = [];
    public $newFeature = '';
    public $is_active = true;

    public $showModal = false;

    public function mount()
    {
        $this->loadPlans();
    }

    public function loadPlans()
    {
        $this->plans = SubscriptionPlan::orderBy('price_monthly')->get();
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $this->plan_id = $plan->id;
        $this->name = $plan->name;
        $this->description = $plan->description;
        $this->price_monthly = $plan->price_monthly;
        $this->price_yearly = $plan->price_yearly;
        $this->max_rooms = $plan->max_rooms;
        $this->features = $plan->features ?? [];
        $this->is_active = $plan->is_active;
        $this->showModal = true;
    }

    public function addFeature()
    {
        if (!empty(trim($this->newFeature))) {
            $this->features[] = trim($this->newFeature);
            $this->newFeature = '';
        }
    }

    public function removeFeature($index)
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_rooms' => 'required|integer|min:0',
        ]);

        SubscriptionPlan::updateOrCreate(
            ['id' => $this->plan_id],
            [
                'name' => $this->name,
                'description' => $this->description,
                'price_monthly' => $this->price_monthly,
                'price_yearly' => $this->price_yearly,
                'max_rooms' => $this->max_rooms,
                'features' => $this->features,
                'is_active' => $this->is_active,
            ]
        );

        $this->showModal = false;
        $this->loadPlans();
    }

    public function resetForm()
    {
        $this->plan_id = null;
        $this->name = '';
        $this->description = '';
        $this->price_monthly = 0;
        $this->price_yearly = 0;
        $this->max_rooms = 0;
        $this->features = [];
        $this->newFeature = '';
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.super-admin.plan-manager')->layout('layouts.super-admin');
    }
}
