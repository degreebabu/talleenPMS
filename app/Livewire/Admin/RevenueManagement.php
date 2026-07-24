<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Room;
use Livewire\Component;

class RevenueManagement extends Component
{
    public $period = '30';

    public function render()
    {
        $hotel = auth()->user()->hotel;
        $days = (int)$this->period;

        // Revenue metrics
        $totalRevenue = Booking::where('hotel_id', $hotel->id)
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->where('created_at', '>=', now()->subDays($days))
            ->sum('total_amount');

        $totalBookings = Booking::where('hotel_id', $hotel->id)
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->where('created_at', '>=', now()->subDays($days))
            ->count();

        $totalRooms = Room::where('hotel_id', $hotel->id)->count();
        $occupiedRooms = Booking::where('hotel_id', $hotel->id)
            ->where('status', 'checked_in')
            ->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;

        $avgDailyRate = $totalBookings > 0 ? round($totalRevenue / $totalBookings, 2) : 0;
        $revPar = $totalRooms > 0 ? round($totalRevenue / ($totalRooms * $days), 2) : 0;

        // Revenue by day (last 14 days)
        $dailyRevenue = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $rev = Booking::where('hotel_id', $hotel->id)
                ->whereDate('created_at', $date)
                ->sum('total_amount');
            $dailyRevenue[] = ['date' => now()->subDays($i)->format('d M'), 'revenue' => $rev];
        }

        // AI Rate Recommendations (simulated)
        $recommendations = [
            ['room_type' => 'Standard', 'current_rate' => 2500, 'recommended_rate' => 2800, 'reason' => 'High weekend demand detected'],
            ['room_type' => 'Deluxe', 'current_rate' => 4000, 'recommended_rate' => 3600, 'reason' => 'Low occupancy — attract bookings'],
            ['room_type' => 'Suite', 'current_rate' => 8000, 'recommended_rate' => 9500, 'reason' => 'Festival season — premium opportunity'],
        ];

        // Fetch active rate rules
        $rules = \App\Models\RateRule::where('hotel_id', $hotel->id)->latest()->get();
        $roomCategories = \App\Models\RoomCategory::where('hotel_id', $hotel->id)->get();

        return view('livewire.admin.revenue-management', compact(
            'totalRevenue', 'totalBookings', 'occupancyRate', 'avgDailyRate', 'revPar', 'dailyRevenue', 'recommendations', 'rules', 'roomCategories'
        ));
    }

    // Rate Rule Form Properties
    public $showRuleForm = false;
    public $rule_name = '';
    public $rule_type = 'season';
    public $room_category_id = '';
    public $adjustment_type = 'percentage';
    public $adjustment_value = '';
    public $start_date = '';
    public $end_date = '';
    public $min_occupancy = '';

    public function createRule()
    {
        $this->reset(['rule_name', 'rule_type', 'room_category_id', 'adjustment_type', 'adjustment_value', 'start_date', 'end_date', 'min_occupancy']);
        $this->showRuleForm = true;
    }

    public function saveRule()
    {
        $this->validate([
            'rule_name' => 'required|string',
            'rule_type' => 'required|string',
            'adjustment_type' => 'required|string',
            'adjustment_value' => 'required|numeric',
        ]);

        \App\Models\RateRule::create([
            'hotel_id' => auth()->user()->hotel_id,
            'name' => $this->rule_name,
            'rule_type' => $this->rule_type,
            'room_category_id' => $this->room_category_id ?: null,
            'adjustment_type' => $this->adjustment_type,
            'adjustment_value' => $this->adjustment_value,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'min_occupancy_percent' => $this->min_occupancy ?: null,
            'is_active' => true,
        ]);

        $this->showRuleForm = false;
        session()->flash('success', 'Dynamic Rate Rule created successfully.');
    }

    public function toggleRule($id)
    {
        $rule = \App\Models\RateRule::findOrFail($id);
        $rule->update(['is_active' => !$rule->is_active]);
    }

    public function deleteRule($id)
    {
        \App\Models\RateRule::findOrFail($id)->delete();
    }
}
