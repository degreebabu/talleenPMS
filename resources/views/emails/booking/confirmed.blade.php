<x-mail::message>
# Booking Confirmed

Dear **{{ $booking->guest->name }}**,

Your booking at **{{ $booking->hotel->name }}** is confirmed. We are excited to host you!

### Booking Details
- **Booking Number:** {{ $booking->booking_number }}
- **Check-in:** {{ $booking->bookingItems->min('start_date')->format('M d, Y') }}
- **Check-out:** {{ $booking->bookingItems->max('end_date')->format('M d, Y') }}
- **Guests:** {{ $booking->adults }} Adults, {{ $booking->children }} Children
- **Total Amount:** ₹{{ number_format($booking->total_amount, 2) }}

If you need to cancel your booking for any reason, you can do so by clicking the button below:

<x-mail::button :url="$cancelUrl" color="error">
Cancel Booking
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
