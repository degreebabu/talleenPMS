<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingCancelledNotification;
use Illuminate\Support\Facades\Notification;

class BookingCancellationController extends Controller
{
    public function cancel(Request $request, $booking_number)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $booking = Booking::where('booking_number', $booking_number)->firstOrFail();

        if ($booking->status === 'cancelled') {
            return response("Booking is already cancelled.", 200);
        }

        $booking->update(['status' => 'cancelled']);

        // Fetch Tenant Admins and Front Office users for this hotel
        $admins = User::where('hotel_id', $booking->hotel_id)
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['tenant_admin', 'front_office']);
            })->get();

        Notification::send($admins, new BookingCancelledNotification($booking));

        return response("Your booking has been successfully cancelled. The hotel has been notified.", 200);
    }
}
