<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Setting;
use Livewire\Component;

class PlatformSettings extends Component
{
    public $platform_name;
    public $support_email;
    public $support_phone;
    public $stripe_public_key;
    public $stripe_secret_key;
    public $currency;

    public function mount()
    {
        $this->platform_name = Setting::get('platform_name', 'TalleenPMS');
        $this->support_email = Setting::get('support_email', 'support@talleen.in');
        $this->support_phone = Setting::get('support_phone', '');
        $this->stripe_public_key = Setting::get('stripe_public_key', '');
        $this->stripe_secret_key = Setting::get('stripe_secret_key', '');
        $this->currency = Setting::get('currency', 'INR');
    }

    public function save()
    {
        $this->validate([
            'platform_name' => 'required|string|max:255',
            'support_email' => 'required|email|max:255',
            'support_phone' => 'nullable|string|max:30',
            'currency' => 'required|string|max:10',
            'stripe_public_key' => 'nullable|string',
            'stripe_secret_key' => 'nullable|string',
        ]);

        Setting::set('platform_name', $this->platform_name);
        Setting::set('support_email', $this->support_email);
        Setting::set('support_phone', $this->support_phone);
        Setting::set('currency', $this->currency);
        Setting::set('stripe_public_key', $this->stripe_public_key);
        Setting::set('stripe_secret_key', $this->stripe_secret_key);

        session()->flash('success', 'Global settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.super-admin.platform-settings')->layout('layouts.super-admin');
    }
}
