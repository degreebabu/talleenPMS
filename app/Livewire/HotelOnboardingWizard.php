<?php

namespace App\Livewire;

use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class HotelOnboardingWizard extends Component
{
    use WithFileUploads;

    public $step = 1;
    public $totalSteps = 3;

    // Step 1: Basic Info
    #[Validate('required|string|max:100')]
    public $name = '';
    
    #[Validate('required|string|max:50|regex:/^[a-z0-9\-]+$/|unique:hotels,subdomain')]
    public $subdomain = '';
    
    #[Validate('required|email|max:150')]
    public $contact_email = '';
    
    #[Validate('required|string|max:20')]
    public $contact_phone = '';
    
    #[Validate('required|string|max:500')]
    public $address = '';

    // Step 2: Brand & Identity
    #[Validate('nullable|string|max:15')]
    public $gst_number = '';
    
    #[Validate('required|string|max:7')]
    public $primary_color = '#1e40af';
    
    #[Validate('required|string|max:7')]
    public $secondary_color = '#f59e0b';
    
    #[Validate('nullable|image|max:2048')]
    public $logo = null;
    
    #[Validate('nullable|image|max:5120')]
    public $cover = null;

    // Step 3: Operations
    #[Validate('required')]
    public $check_in_time = '14:00';
    
    #[Validate('required')]
    public $check_out_time = '11:00';

    public $completed = false;

    public function updatedName($value)
    {
        if (empty($this->subdomain) && !empty($value)) {
            $this->subdomain = Str::slug($value);
        }
    }

    public function submit(): void
    {
        if ($this->step < $this->totalSteps) {
            $this->nextStep();
        } else {
            $this->save();
        }
    }

    public function nextStep(): void
    {
        // Validate only fields for the current step
        if ($this->step === 1) {
            $this->validateOnly('name');
            $this->validateOnly('subdomain');
            $this->validateOnly('contact_email');
            $this->validateOnly('contact_phone');
            $this->validateOnly('address');
        } elseif ($this->step === 2) {
            $this->validateOnly('gst_number');
            $this->validateOnly('primary_color');
            $this->validateOnly('secondary_color');
            $this->validateOnly('logo');
            $this->validateOnly('cover');
        }

        $this->step++;
    }

    public function prevStep(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function save(): void
    {
        $this->validate();

        $logoPath  = $this->logo  ? $this->logo->store('hotels/logos', 'public')  : null;
        $coverPath = $this->cover ? $this->cover->store('hotels/covers', 'public') : null;

        $hotel = Hotel::create([
            'name'            => $this->name,
            'subdomain'       => $this->subdomain,
            'address'         => $this->address,
            'gst_number'      => $this->gst_number,
            'primary_color'   => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'logo_path'       => $logoPath,
            'cover_path'      => $coverPath,
            'contact_email'   => $this->contact_email,
            'contact_phone'   => $this->contact_phone,
            'check_in_time'   => $this->check_in_time,
            'check_out_time'  => $this->check_out_time,
        ]);

        $user = auth()->user();
        $user->update(['hotel_id' => $hotel->id]);
        $user->assignRole('hotel_admin');

        $this->completed = true;
        $this->redirect(route('admin.dashboard'));
    }

    public function render()
    {
        return view('livewire.hotel-onboarding-wizard');
    }
}
