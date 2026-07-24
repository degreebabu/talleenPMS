<?php

namespace App\Livewire\Admin;

use App\Models\BookingItem;
use App\Models\Room;
use App\Models\RoomCategory;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class BookingCalendar extends Component
{
    public $startDate;
    public $endDate;
    public $days = 14; // Default to 14 days

    public function mount()
    {
        // Start from today, show next 14 days
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->addDays($this->days - 1)->format('Y-m-d');
    }

    public function previousPeriod()
    {
        $this->startDate = Carbon::parse($this->startDate)->subDays($this->days)->format('Y-m-d');
        $this->endDate = Carbon::parse($this->endDate)->subDays($this->days)->format('Y-m-d');
    }

    public function nextPeriod()
    {
        $this->startDate = Carbon::parse($this->startDate)->addDays($this->days)->format('Y-m-d');
        $this->endDate = Carbon::parse($this->endDate)->addDays($this->days)->format('Y-m-d');
    }

    public function render()
    {
        $hotelId = auth()->user()->hotel_id;

        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        // Generate array of dates for the headers
        $period = CarbonPeriod::create($start, $end);
        $dates = [];
        foreach ($period as $date) {
            $dates[] = [
                'full' => $date->format('Y-m-d'),
                'day' => $date->format('d'),
                'dayName' => $date->format('D'),
                'isToday' => $date->isToday(),
                'isWeekend' => $date->isWeekend(),
            ];
        }

        // Fetch categories and their rooms
        $categories = RoomCategory::where('hotel_id', $hotelId)
            ->with(['rooms' => function ($query) {
                $query->orderBy('room_number');
            }])
            ->get();

        // Fetch booking items that overlap with this period
        // An item overlaps if it starts before the period ends AND ends after the period starts
        $bookingItems = BookingItem::with(['booking.guest'])
            ->where('item_type', Room::class)
            ->whereHas('booking', function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId);
            })
            ->where('start_date', '<=', $end->copy()->endOfDay())
            ->where('end_date', '>=', $start->copy()->startOfDay())
            ->get();

        // Organize bookings by room ID
        $bookingsByRoom = [];
        foreach ($bookingItems as $item) {
            $roomId = $item->item_id;
            if (!isset($bookingsByRoom[$roomId])) {
                $bookingsByRoom[$roomId] = [];
            }
            $bookingsByRoom[$roomId][] = $item;
        }

        return view('livewire.admin.booking-calendar', [
            'dates' => $dates,
            'categories' => $categories,
            'bookingsByRoom' => $bookingsByRoom,
            'start' => $start,
            'end' => $end,
        ]);
    }
}
