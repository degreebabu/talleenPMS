<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class OtaManager extends Component
{
    public $channels = [
        ['name' => 'Booking.com', 'logo' => '🔵', 'status' => 'disconnected', 'rooms_synced' => 0],
        ['name' => 'Agoda', 'logo' => '🔴', 'status' => 'disconnected', 'rooms_synced' => 0],
        ['name' => 'Expedia', 'logo' => '🟡', 'status' => 'disconnected', 'rooms_synced' => 0],
        ['name' => 'MakeMyTrip', 'logo' => '🟠', 'status' => 'disconnected', 'rooms_synced' => 0],
        ['name' => 'Airbnb', 'logo' => '🩷', 'status' => 'disconnected', 'rooms_synced' => 0],
        ['name' => 'Goibibo', 'logo' => '🟢', 'status' => 'disconnected', 'rooms_synced' => 0],
    ];

    public $syncLog = [
        ['time' => '2 hours ago', 'channel' => 'Ready to connect', 'message' => 'No OTA channels are connected. Add your credentials to begin syncing.'],
    ];

    public function render()
    {
        return view('livewire.admin.ota-manager');
    }
}
