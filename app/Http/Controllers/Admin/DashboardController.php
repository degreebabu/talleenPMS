<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->hasRole('group_manager')) {
            return redirect()->route('admin.corporate-overview');
        }

        $hotelId = $user->hotel_id;
        
        $totalRooms = Room::where('hotel_id', $hotelId)->count();
        $occupiedRooms = Room::where('hotel_id', $hotelId)->where('status', 'occupied')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;
        
        $totalRevenue = Booking::where('hotel_id', $hotelId)->where('status', 'confirmed')->sum('total_amount');
        $revPAR = $totalRooms > 0 ? ($totalRevenue / $totalRooms) : 0;

        $stats = [
            'total_rooms'    => $totalRooms,
            'available_rooms'=> Room::where('hotel_id', $hotelId)->where('status', 'available')->count(),
            'today_checkins' => Booking::where('hotel_id', $hotelId)->where('status', 'confirmed')->whereDate('created_at', today())->count(),
            'total_bookings' => Booking::where('hotel_id', $hotelId)->count(),
            'occupancy_rate' => $occupancyRate,
            'revpar'         => $revPAR,
            'total_revenue'  => $totalRevenue,
            'banquet_revenue'=> \App\Models\BanquetBooking::where('hotel_id', $hotelId)->whereIn('status', ['confirmed', 'completed'])->sum('expected_pax') * 500, // Dummy calc
            'outing_revenue' => \App\Models\DayPass::where('hotel_id', $hotelId)->whereIn('status', ['confirmed', 'used'])->sum('total_amount'),
        ];
        return view('admin.dashboard', compact('stats'));
    }

    public function rooms()
    {
        return view('admin.rooms.categories');
    }

    public function roomList()
    {
        return view('admin.rooms.index');
    }
}
