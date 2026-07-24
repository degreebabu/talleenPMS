<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Hotel;
use App\Models\User;
use App\Models\Booking;
use App\Models\ActivityLog;

class Dashboard extends Component
{
    public $totalHotels;
    public $totalUsers;
    public $totalRevenue;
    public $recentLogs;
    public $topTenants = [];
    public $aiInsights = '';
    public $chartLabels = [];
    public $chartData = [];

    public function mount()
    {
        $this->totalHotels = Hotel::count();
        $this->totalUsers = User::count();
        $this->totalRevenue = Booking::sum('total_amount');
        $this->recentLogs = ActivityLog::with(['hotel', 'user'])->latest()->take(10)->get();

        // Calculate Top Tenants by Revenue
        $this->topTenants = Hotel::withCount(['bookings as total_revenue' => function($query) {
            $query->select(\DB::raw('sum(total_amount)'));
        }])->orderByDesc('total_revenue')->take(5)->get();

        foreach($this->topTenants as $tenant) {
            $this->chartLabels[] = $tenant->name;
            $this->chartData[] = $tenant->total_revenue ?? 0;
        }

        $this->generateAiInsights();
    }

    private function generateAiInsights()
    {
        $activePercentage = $this->totalHotels > 0 ? round((Hotel::where('is_active', true)->count() / $this->totalHotels) * 100) : 0;
        $revenueFormatted = number_format($this->totalRevenue);
        
        $this->aiInsights = "Platform health is optimal. You have {$this->totalHotels} onboarded tenants, with {$activePercentage}% actively operating. Total Gross Merchandise Value (GMV) across all properties is currently at ₹{$revenueFormatted}. ";
        
        if (count($this->topTenants) > 0) {
            $top = $this->topTenants->first();
            $this->aiInsights .= "The highest performing property is {$top->name} driving significant booking volume. ";
        }

        $this->aiInsights .= "Recommendation: Focus on engaging the bottom 20% of tenants to boost overall platform GMV.";
    }

    public function render()
    {
        return view('livewire.super-admin.dashboard')->layout('layouts.super-admin');
    }
}
