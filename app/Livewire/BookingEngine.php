<?php

namespace App\Livewire;

use App\Models\Hotel;
use App\Models\RoomCategory;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Booking;
use App\Models\BookingItem;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingEngine extends Component
{
    public $hotel;
    
    // Step tracking: 1 = Search, 2 = Select Room, 3 = Checkout, 4 = Confirmation
    public $step = 1;

    // Search Params
    public $checkIn;
    public $checkOut;
    public $adults = 1;
    public $children = 0;
    public $aiSearchText = '';
    
    // Availability
    public $availableCategories = [];
    public $aiMatchedCategoryId = null;

    // Checkout
    public $selectedCategoryId = null;
    public $guestName = '';
    public $guestEmail = '';
    public $guestPhone = '';
    public $notes = '';
    
    // Confirmation
    public $confirmedBookingNumber = '';

    // AI Chat Widget
    public $chatOpen = false;
    public $chatMessages = [
        ['role' => 'bot', 'text' => 'Hello! I am your AI concierge. How can I help you today?']
    ];
    public $chatInput = '';

    public function mount(Hotel $hotel)
    {
        $this->hotel = $hotel;
        
        $this->checkIn = Carbon::today()->format('Y-m-d');
        $this->checkOut = Carbon::tomorrow()->format('Y-m-d');
    }

    public function searchRooms()
    {
        $this->validate([
            'checkIn' => 'required|date|after_or_equal:today',
            'checkOut' => 'required|date|after:checkIn',
            'adults' => 'required|integer|min:1',
        ]);

        // Process AI Search text (Simple keyword matching)
        $this->aiMatchedCategoryId = null;
        if (!empty($this->aiSearchText)) {
            $keywords = explode(' ', strtolower($this->aiSearchText));
            // Basic matching algorithm
            $categories = RoomCategory::where('hotel_id', $this->hotel->id)->get();
            $bestMatch = null;
            $highestScore = 0;
            
            foreach ($categories as $cat) {
                $score = 0;
                $desc = strtolower($cat->name . ' ' . $cat->description);
                foreach ($keywords as $word) {
                    if (strlen($word) > 3 && str_contains($desc, $word)) {
                        $score++;
                    }
                }
                if ($score > $highestScore) {
                    $highestScore = $score;
                    $bestMatch = $cat->id;
                }
            }
            if ($bestMatch) {
                $this->aiMatchedCategoryId = $bestMatch;
            }
        }

        // Check availability
        $in = Carbon::parse($this->checkIn)->startOfDay();
        $out = Carbon::parse($this->checkOut)->startOfDay();

        // Get all categories for the hotel
        $categories = RoomCategory::where('hotel_id', $this->hotel->id)->with('images')->get();

        $this->availableCategories = [];

        foreach ($categories as $cat) {
            // Count total rooms in category (ignore current status, as it might be occupied today but available next week)
            $totalRooms = Room::where('hotel_id', $this->hotel->id)
                ->where('room_category_id', $cat->id)
                ->count();

            // Count booked rooms in that category overlapping dates
            $bookedRooms = BookingItem::whereHas('booking', function ($q) {
                    $q->where('hotel_id', $this->hotel->id)
                      ->whereNotIn('status', ['cancelled', 'checked_out']); // Depending on checkout logic
                })
                ->where('item_type', Room::class)
                ->whereIn('item_id', Room::where('room_category_id', $cat->id)->pluck('id'))
                ->where(function($q) use ($in, $out) {
                    // Overlaps
                    $q->where('start_date', '<', $out)
                      ->where('end_date', '>', $in);
                })
                ->count();

            if ($totalRooms - $bookedRooms > 0) {
                // Check if category can accommodate the requested number of guests
                if ($cat->max_adults >= $this->adults && $cat->max_children >= $this->children) {
                    $this->availableCategories[] = $cat;
                }
            }
        }

        $this->step = 2;
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategoryId = $categoryId;
        $this->step = 3;
    }

    public function confirmBooking()
    {
        $this->validate([
            'guestName' => 'required|string|max:255',
            'guestEmail' => 'required|email|max:255',
            'guestPhone' => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            // Re-verify availability
            $cat = RoomCategory::findOrFail($this->selectedCategoryId);
            $in = Carbon::parse($this->checkIn)->startOfDay();
            $out = Carbon::parse($this->checkOut)->startOfDay();

            $availableRooms = Room::where('hotel_id', $this->hotel->id)
                ->where('room_category_id', $cat->id)
                ->where('status', 'available')
                ->whereDoesntHave('bookingItems', function ($query) use ($in, $out) {
                    $query->where(function($q) use ($in, $out) {
                        $q->where('start_date', '<', $out)
                          ->where('end_date', '>', $in);
                    })->whereHas('booking', function($q2) {
                        $q2->whereNotIn('status', ['cancelled']);
                    });
                })->get();

            if ($availableRooms->isEmpty()) {
                throw new \Exception("Sorry, no rooms are available for these dates.");
            }

            $room = $availableRooms->first();

            // Create Guest
            $guest = Guest::firstOrCreate(
                ['email' => $this->guestEmail, 'hotel_id' => $this->hotel->id],
                ['name' => $this->guestName, 'phone' => $this->guestPhone]
            );

            // Create Booking
            $booking = Booking::create([
                'hotel_id' => $this->hotel->id,
                'guest_id' => $guest->id,
                'status' => 'confirmed',
                'source' => 'direct',
                'total_amount' => 0, // calculate below
                'amount_paid' => 0,
                'adults' => $this->adults,
                'children' => $this->children,
                'notes' => $this->notes,
            ]);

            $nights = $in->diffInDays($out);
            $totalAmount = $cat->base_price * $nights;

            BookingItem::create([
                'booking_id' => $booking->id,
                'item_type' => Room::class,
                'item_id' => $room->id,
                'start_date' => $in,
                'end_date' => $out,
                'price' => $totalAmount,
            ]);

            $booking->update(['total_amount' => $totalAmount]);

            DB::commit();

            $this->confirmedBookingNumber = $booking->booking_number;
            $this->step = 4;
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function back()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    // AI Chat Widget
    public function toggleChat()
    {
        $this->chatOpen = !$this->chatOpen;
    }

    public function sendMessage()
    {
        if (empty(trim($this->chatInput))) return;

        $this->chatMessages[] = ['role' => 'user', 'text' => $this->chatInput];
        $input = strtolower($this->chatInput);
        $this->chatInput = '';

        // Simulated AI logic
        $response = "I'm a virtual concierge. Please ask me about our amenities, check-in times, or policies!";
        if (str_contains($input, 'check in') || str_contains($input, 'time')) {
            $response = "Check-in time is usually at 3:00 PM, and check-out is at 11:00 PM. We do offer early check-in subject to availability!";
        } elseif (str_contains($input, 'parking')) {
            $response = "Yes, we offer complimentary on-site parking for all our registered guests.";
        } elseif (str_contains($input, 'pool') || str_contains($input, 'swim')) {
            $response = "We have a beautiful temperature-controlled swimming pool open from 7 AM to 9 PM daily.";
        }

        $this->chatMessages[] = ['role' => 'bot', 'text' => $response];
    }

    public function render()
    {
        return view('livewire.booking-engine')->layout('layouts.public', ['hotel' => $this->hotel]); 
    }
}
