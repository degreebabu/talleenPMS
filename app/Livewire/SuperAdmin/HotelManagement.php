<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Hotel;
use App\Models\SubscriptionPlan;
use Livewire\Component;
use Livewire\WithPagination;

class HotelManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $showDetailModal = false;
    public $showCreateModal = false;
    public $selectedHotel = null;
    public $selectedPlanId = null;

    // Create Tenant Form
    public $newHotelName = '';
    public $newHotelEmail = '';
    public $newHotelSubdomain = '';
    public $newHotelPlanId = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewDetails($hotelId)
    {
        $this->selectedHotel = Hotel::with(['subscriptionPlan'])->withCount(['rooms', 'bookings'])->findOrFail($hotelId);
        $this->selectedPlanId = $this->selectedHotel->subscription_plan_id;
        $this->showDetailModal = true;
    }

    public function toggleStatus($hotelId)
    {
        $hotel = Hotel::findOrFail($hotelId);
        $hotel->subscription_status = $hotel->subscription_status === 'suspended' ? 'active' : 'suspended';
        $hotel->save();
        
        if ($this->selectedHotel?->id === $hotelId) {
            $this->selectedHotel->refresh();
        }
    }

    public function assignPlan($hotelId)
    {
        $hotel = Hotel::findOrFail($hotelId);
        $hotel->subscription_plan_id = $this->selectedPlanId ?: null;
        $hotel->save();
        $this->selectedHotel = $hotel->fresh(['subscriptionPlan'])->loadCount(['rooms', 'bookings']);
        session()->flash('plan_success', 'Plan updated successfully.');
    }

    public function openCreateModal()
    {
        $this->reset(['newHotelName', 'newHotelEmail', 'newHotelSubdomain', 'newHotelPlanId']);
        $this->showCreateModal = true;
    }

    public function createTenant()
    {
        $this->validate([
            'newHotelName' => 'required|string|max:255',
            'newHotelEmail' => 'required|email|unique:users,email',
            'newHotelSubdomain' => 'required|string|alpha_dash|unique:hotels,subdomain',
        ]);

        $user = \App\Models\User::create([
            'name' => 'Admin - ' . $this->newHotelName,
            'email' => $this->newHotelEmail,
            'password' => \Illuminate\Support\Facades\Hash::make('password123'), // Default password
            'role' => 'hotel_admin',
        ]);

        $hotel = Hotel::create([
            'name' => $this->newHotelName,
            'subdomain' => $this->newHotelSubdomain,
            'contact_email' => $this->newHotelEmail,
            'subscription_plan_id' => $this->newHotelPlanId ?: null,
            'subscription_status' => 'active',
            'subscription_ends_at' => now()->addYear(),
        ]);

        $user->update(['hotel_id' => $hotel->id]);

        $this->showCreateModal = false;
        session()->flash('success', "Tenant created successfully. Default password is 'password123'");
    }

    public function render()
    {
        $hotels = Hotel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('subdomain', 'like', '%' . $this->search . '%')
                      ->orWhere('contact_email', 'like', '%' . $this->search . '%');
            })
            ->withCount(['rooms', 'bookings'])
            ->latest()
            ->paginate(10);

        $plans = SubscriptionPlan::where('is_active', true)->get();

        return view('livewire.super-admin.hotel-management', [
            'hotels' => $hotels,
            'plans'  => $plans,
        ]);
    }
}
