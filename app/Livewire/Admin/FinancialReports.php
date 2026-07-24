<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use Livewire\Component;
use Illuminate\Support\Carbon;

class FinancialReports extends Component
{
    public $dateRange = 'this_month';
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->updateDateRange();
    }

    public function updatedDateRange()
    {
        $this->updateDateRange();
    }

    public function updateDateRange()
    {
        $now = Carbon::now();
        switch ($this->dateRange) {
            case 'today':
                $this->startDate = $now->copy()->startOfDay();
                $this->endDate = $now->copy()->endOfDay();
                break;
            case 'this_week':
                $this->startDate = $now->copy()->startOfWeek();
                $this->endDate = $now->copy()->endOfWeek();
                break;
            case 'this_month':
                $this->startDate = $now->copy()->startOfMonth();
                $this->endDate = $now->copy()->endOfMonth();
                break;
            case 'last_month':
                $this->startDate = $now->copy()->subMonth()->startOfMonth();
                $this->endDate = $now->copy()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $this->startDate = $now->copy()->startOfYear();
                $this->endDate = $now->copy()->endOfYear();
                break;
        }
    }

    public function render()
    {
        $hotel = auth()->user()->hotel;

        // Base query for the selected period
        $baseQuery = Booking::where('hotel_id', $hotel->id)
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        // Key Metrics
        $totalRevenue = (clone $baseQuery)->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])->sum('total_amount');
        $totalBookings = (clone $baseQuery)->count();
        $confirmedBookings = (clone $baseQuery)->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])->count();
        $cancelledBookings = (clone $baseQuery)->where('status', 'cancelled')->count();

        // Revenue by Booking Type
        $revenueByType = (clone $baseQuery)
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->selectRaw('booking_type, SUM(total_amount) as total')
            ->groupBy('booking_type')
            ->pluck('total', 'booking_type')
            ->toArray();

        // Recent Transactions (Bookings in this period)
        $recentTransactions = (clone $baseQuery)
            ->with('guest')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('livewire.admin.financial-reports', [
            'totalRevenue' => $totalRevenue,
            'totalBookings' => $totalBookings,
            'confirmedBookings' => $confirmedBookings,
            'cancelledBookings' => $cancelledBookings,
            'revenueByType' => $revenueByType,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
