<?php

namespace App\Livewire\Admin\Banquet;

use App\Models\BanquetBooking;
use App\Models\EventSpace;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class BookingCalendar extends Component
{
    public $startDate;
    public $endDate;
    public $days = 14;

    public function mount()
    {
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

        $spaces = EventSpace::where('hotel_id', $hotelId)->orderBy('type')->orderBy('name')->get();

        $bookings = BanquetBooking::where('hotel_id', $hotelId)
            ->where('start_time', '<=', $end->copy()->endOfDay())
            ->where('end_time', '>=', $start->copy()->startOfDay())
            ->get();

        $bookingsBySpace = [];
        foreach ($bookings as $booking) {
            $spaceId = $booking->event_space_id;
            if (!isset($bookingsBySpace[$spaceId])) {
                $bookingsBySpace[$spaceId] = [];
            }
            $bookingsBySpace[$spaceId][] = $booking;
        }

        return view('livewire.admin.banquet.booking-calendar', [
            'dates' => $dates,
            'spaces' => $spaces,
            'bookingsBySpace' => $bookingsBySpace,
            'start' => $start,
            'end' => $end,
        ]);
    }
}
