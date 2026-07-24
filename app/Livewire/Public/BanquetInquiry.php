<?php

namespace App\Livewire\Public;

use App\Models\BanquetBooking;
use App\Models\EventSpace;
use App\Models\Hotel;
use Livewire\Component;

class BanquetInquiry extends Component
{
    public Hotel $hotel;
    
    public $event_space_id = '';
    public $client_name = '';
    public $client_email = '';
    public $client_phone = '';
    public $event_type = 'wedding';
    public $start_date = '';
    public $end_date = '';
    public $expected_pax = 100;
    public $notes = '';

    public $isSubmitted = false;

    protected $rules = [
        'event_space_id' => 'required|exists:event_spaces,id',
        'client_name' => 'required|string|max:255',
        'client_email' => 'required|email|max:255',
        'client_phone' => 'required|string|max:20',
        'event_type' => 'required|in:wedding,corporate,party,other',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
        'expected_pax' => 'required|integer|min:1',
        'notes' => 'nullable|string|max:1000',
    ];

    public function submit()
    {
        $this->validate();

        BanquetBooking::create([
            'hotel_id' => $this->hotel->id,
            'event_space_id' => $this->event_space_id,
            'client_name' => $this->client_name,
            'client_email' => $this->client_email,
            'client_phone' => $this->client_phone,
            'event_type' => $this->event_type,
            'start_time' => $this->start_date . ' 09:00:00', // Defaulting time for inquiry
            'end_time' => $this->end_date . ' 18:00:00',
            'expected_pax' => $this->expected_pax,
            'status' => 'inquiry',
            'notes' => $this->notes,
        ]);

        $this->isSubmitted = true;
    }

    public function render()
    {
        $spaces = EventSpace::where('hotel_id', $this->hotel->id)->where('status', 'active')->get();

        return view('livewire.public.banquet-inquiry', [
            'spaces' => $spaces
        ]);
    }
}
