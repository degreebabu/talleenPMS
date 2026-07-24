<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

class Reports extends Component
{
    public $dateRange = 'all'; // all, month, year

    public function render()
    {
        $tenants = Hotel::withCount('users')
            ->withCount(['bookings as total_revenue' => function($query) {
                $query->select(DB::raw('sum(total_amount)'));
            }])
            ->withCount('bookings')
            ->get();

        $platformTotalRevenue = $tenants->sum('total_revenue');
        $platformTotalBookings = $tenants->sum('bookings_count');
        $platformTotalUsers = $tenants->sum('users_count');

        return view('livewire.super-admin.reports', compact('tenants', 'platformTotalRevenue', 'platformTotalBookings', 'platformTotalUsers'))->layout('layouts.super-admin');
    }
}
