<?php

namespace App\Livewire\Admin;

use App\Models\Company;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\Room;
use Livewire\Component;

class CorporateOverview extends Component
{
    public $company;
    public $hotels;
    
    // Stats
    public $totalHotels = 0;
    public $totalRooms = 0;
    public $totalOccupancy = 0;
    public $totalRevenue = 0;
    
    public function mount()
    {
        $user = auth()->user();
        if (!$user->hasRole('group_manager') || !$user->company_id) {
            abort(403, 'Unauthorized access.');
        }

        $this->company = Company::findOrFail($user->company_id);
        $this->hotels = $this->company->hotels;

        $this->calculateStats();
    }
    
    public function calculateStats()
    {
        $hotelIds = $this->hotels->pluck('id')->toArray();
        $this->totalHotels = count($hotelIds);
        
        $this->totalRooms = Room::whereIn('hotel_id', $hotelIds)->count();
        $occupiedRooms = Room::whereIn('hotel_id', $hotelIds)->where('status', 'occupied')->count();
        $this->totalOccupancy = $this->totalRooms > 0 ? round(($occupiedRooms / $this->totalRooms) * 100) : 0;
        
        $this->totalRevenue = Booking::whereIn('hotel_id', $hotelIds)->where('status', 'confirmed')->sum('total_amount');
    }

    public function render()
    {
        return view('livewire.admin.corporate-overview')->layout('layouts.admin');
    }
}
