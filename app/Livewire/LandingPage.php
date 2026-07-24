<?php

namespace App\Livewire;

use App\Models\Hotel;
use Livewire\Component;

class LandingPage extends Component
{
    public $hotel;
    public $website;

    public function mount(Hotel $hotel)
    {
        $this->hotel = $hotel;
        $this->website = $this->hotel->website;

        $isOwner = auth()->check() && (auth()->user()->hotel_id === $this->hotel->id || auth()->user()->role === 'super_admin');

        if (!$this->website || (!$this->website->is_published && !$isOwner)) {
            // Fallback if no website configured or not published
            abort(404, 'This hotel landing page is not yet available.');
        }
    }

    public function render()
    {
        return view('livewire.landing-page')->layout('layouts.public', ['hotel' => $this->hotel]);
    }
}
