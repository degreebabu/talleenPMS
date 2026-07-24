<?php

namespace App\Livewire\Admin;

use App\Models\Room;
use App\Models\BookingItem;
use Livewire\Component;
use Illuminate\Support\Carbon;

class Calendar extends Component
{
    public $startDate;
    public $daysToShow = 14;

    public function mount()
    {
        $this->startDate = Carbon::today()->format('Y-m-d');
    }

    public function nextPeriod()
    {
        $this->startDate = Carbon::parse($this->startDate)->addDays(7)->format('Y-m-d');
    }

    public function previousPeriod()
    {
        $this->startDate = Carbon::parse($this->startDate)->subDays(7)->format('Y-m-d');
    }

    public function today()
    {
        $this->startDate = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        $hotel = auth()->user()->hotel;
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = $start->copy()->addDays($this->daysToShow - 1)->endOfDay();

        // Generate date array for headers
        $dates = [];
        for ($i = 0; $i < $this->daysToShow; $i++) {
            $dates[] = $start->copy()->addDays($i);
        }

        // Fetch rooms grouped by category
        $rooms = Room::with('category')
            ->where('hotel_id', $hotel->id)
            ->orderBy('room_number')
            ->get();

        // Fetch overlapping bookings
        // A booking overlaps if its start_date is <= grid end AND end_date >= grid start
        $roomIds = $rooms->pluck('id');
        
        $bookingItems = BookingItem::with(['booking.guest'])
            ->where('item_type', Room::class)
            ->whereIn('item_id', $roomIds)
            ->where(function($q) use ($start, $end) {
                $q->where('start_date', '<=', $end)
                  ->where('end_date', '>=', $start);
            })
            ->whereHas('booking', function($q) {
                $q->whereNotIn('status', ['cancelled']);
            })
            ->get();

        // Format bookings for the grid
        // Array keyed by room_id, containing an array of bookings
        $gridData = [];
        foreach ($rooms as $room) {
            $gridData[$room->id] = [];
        }

        foreach ($bookingItems as $item) {
            // Calculate span within our current view
            $itemStart = Carbon::parse($item->start_date)->startOfDay();
            $itemEnd = Carbon::parse($item->end_date)->startOfDay();

            // If it starts before our grid, cap the start
            $displayStart = $itemStart->copy();
            if ($itemStart->lt($start)) {
                $displayStart = $start->copy();
            }

            // If it ends after our grid, cap the end
            $displayEnd = $itemEnd->copy();
            if ($itemEnd->gt($end)) {
                $displayEnd = $end->copy(); // It goes out of bounds, but for the grid we cap it at end of period
            }

            // Difference in days for the block width
            // e.g. check in 10th, check out 12th = 2 nights (width = 2)
            $colStart = $start->diffInDays($displayStart) + 1; // 1-indexed for CSS grid
            $colSpan = $displayStart->diffInDays($displayEnd);
            
            // If checkout is same day as checkin (shouldn't happen for rooms usually, but just in case)
            if ($colSpan <= 0) $colSpan = 1;

            $statusColor = match($item->booking->status) {
                'confirmed' => 'bg-blue-500 border-blue-600',
                'checked_in' => 'bg-emerald-500 border-emerald-600',
                'checked_out' => 'bg-slate-400 border-slate-500',
                default => 'bg-indigo-500 border-indigo-600',
            };

            $gridData[$item->item_id][] = [
                'id' => $item->booking->id,
                'guest' => $item->booking->guest->name ?? 'Unknown',
                'ref' => $item->booking->booking_number,
                'status' => $item->booking->status,
                'colStart' => $colStart,
                'colSpan' => $colSpan,
                'color' => $statusColor,
                'fullStartDate' => $itemStart->format('M d'),
                'fullEndDate' => $itemEnd->format('M d'),
            ];
        }

        return view('livewire.admin.calendar', compact('dates', 'rooms', 'gridData'));
    }
}
