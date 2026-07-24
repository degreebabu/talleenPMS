<?php

namespace App\Livewire\Public;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\Payment;
use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;

class BookingWidget extends Component
{
    public Hotel $hotel;

    public $step = 1;

    // Step 1: Dates & Guests
    public $check_in_date;
    public $check_out_date;
    public $adults = 1;
    public $children = 0;

    // Step 2: Room Selection
    public $selected_category_id = null;
    public $available_categories = [];
    public $total_days = 0;

    // Step 3: Guest Details
    public $guest_name = '';
    public $guest_email = '';
    public $guest_phone = '';

    // Step 4: Payment Summary
    public $total_amount = 0;
    public $tax_amount = 0;
    public $grand_total = 0;

    // UI state
    public $submitting = false;

    public function mount(Hotel $hotel)
    {
        $this->hotel = $hotel;
        $this->check_in_date = Carbon::today()->format('Y-m-d');
        $this->check_out_date = Carbon::tomorrow()->format('Y-m-d');
    }

    public function searchRooms()
    {
        $this->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'required|integer|min:0|max:10',
        ]);

        $checkIn = Carbon::parse($this->check_in_date);
        $checkOut = Carbon::parse($this->check_out_date);
        $this->total_days = $checkIn->diffInDays($checkOut);

        // Find available rooms for the dates
        // A room is available if it has no overlapping booking items.
        // Overlap condition: start_date < requested_end AND end_date > requested_start
        
        $bookedRoomIds = BookingItem::where('item_type', Room::class)
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->where('start_date', '<', $checkOut)
                      ->where('end_date', '>', $checkIn);
            })
            ->pluck('item_id')
            ->toArray();

        // Get categories that have at least one room NOT in bookedRoomIds and match capacity
        $this->available_categories = RoomCategory::where('hotel_id', $this->hotel->id)
            ->where('max_adults', '>=', $this->adults)
            ->where('max_children', '>=', $this->children)
            ->with(['images'])
            ->whereHas('rooms', function($query) use ($bookedRoomIds) {
                $query->whereNotIn('id', $bookedRoomIds)
                      ->whereNotIn('status', ['maintenance']); // exclude maintenance rooms
            })
            ->get();

        if ($this->available_categories->isEmpty()) {
            $this->addError('search', 'No rooms available for these dates and guest counts.');
            return;
        }

        $this->step = 2;
    }

    public function selectCategory($categoryId)
    {
        $this->selected_category_id = $categoryId;
        
        $category = $this->available_categories->firstWhere('id', $categoryId);
        $this->total_amount = $category->base_price * $this->total_days;
        $this->tax_amount = $this->total_amount * 0.18; // Mock 18% GST
        $this->grand_total = $this->total_amount + $this->tax_amount;

        $this->step = 3;
    }

    public function submitGuestDetails()
    {
        $this->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
        ]);

        $this->step = 4;
    }

    public function processPayment()
    {
        // Mock Razorpay payment
        // In a real scenario, this would generate an order ID and return it to the frontend for the Razorpay checkout script.
        // Here, we simulate a successful callback from Razorpay.

        DB::beginTransaction();

        try {
            // Find an available room in the selected category
            $checkIn = Carbon::parse($this->check_in_date);
            $checkOut = Carbon::parse($this->check_out_date);
            
            $bookedRoomIds = BookingItem::where('item_type', Room::class)
                ->where(function($query) use ($checkIn, $checkOut) {
                    $query->where('start_date', '<', $checkOut)
                          ->where('end_date', '>', $checkIn);
                })
                ->pluck('item_id')
                ->toArray();

            $availableRoom = Room::where('room_category_id', $this->selected_category_id)
                ->whereNotIn('id', $bookedRoomIds)
                ->whereNotIn('status', ['maintenance'])
                ->first();

            if (!$availableRoom) {
                DB::rollBack();
                $this->addError('payment', 'Sorry, this room category just sold out.');
                return;
            }

            // Create Guest
            $guest = Guest::firstOrCreate(
                ['email' => $this->guest_email, 'hotel_id' => $this->hotel->id],
                ['name' => $this->guest_name, 'phone' => $this->guest_phone]
            );

            // Create Booking
            $booking = Booking::create([
                'hotel_id' => $this->hotel->id,
                'guest_id' => $guest->id,
                'status' => 'confirmed',
                'total_amount' => $this->grand_total,
                'tax_amount' => $this->tax_amount,
                'booking_type' => 'room',
            ]);

            // Create Booking Item
            BookingItem::create([
                'booking_id' => $booking->id,
                'item_type' => Room::class,
                'item_id' => $availableRoom->id,
                'start_date' => $checkIn,
                'end_date' => $checkOut,
                'price' => $this->total_amount,
                'tax' => $this->tax_amount,
            ]);

            // Create Payment
            Payment::create([
                'booking_id' => $booking->id,
                'gateway' => 'razorpay_mock',
                'transaction_id' => 'pay_mock_' . time(),
                'amount' => $this->grand_total,
                'status' => 'successful',
            ]);

            DB::commit();

            $this->step = 5; // Success Step

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('payment', 'An error occurred during booking: ' . $e->getMessage());
        }
    }

    public function goBack()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function render()
    {
        return view('livewire.public.booking-widget');
    }
}
