<x-admin-layout>
    <x-slot name="header">Guest Folio — {{ $booking->booking_number }}</x-slot>
    @livewire('admin.folio-manager', ['booking' => $booking])
</x-admin-layout>
