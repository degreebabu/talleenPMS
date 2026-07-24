<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class IntegrationsHub extends Component
{
    public $integrations = [
        ['name' => 'Tally ERP', 'description' => 'Sync invoices & payments to Tally for accounting', 'icon' => '📊', 'category' => 'Accounting', 'status' => 'available'],
        ['name' => 'WhatsApp Business', 'description' => 'Send booking confirmations & reminders via WhatsApp', 'icon' => '💬', 'category' => 'Communication', 'status' => 'available'],
        ['name' => 'Smart Door Locks', 'description' => 'Issue digital keys on check-in (Assa Abloy, Dormakaba)', 'icon' => '🔐', 'category' => 'Automation', 'status' => 'coming_soon'],
        ['name' => 'Payment Gateway', 'description' => 'Accept online payments via Razorpay / Stripe', 'icon' => '💳', 'category' => 'Payments', 'status' => 'available'],
        ['name' => 'GST Filing', 'description' => 'Auto-generate GSTR-1 & GSTR-3B reports', 'icon' => '📋', 'category' => 'Accounting', 'status' => 'available'],
        ['name' => 'Google Reviews', 'description' => 'Auto-send review request via SMS post-checkout', 'icon' => '⭐', 'category' => 'Marketing', 'status' => 'available'],
        ['name' => 'Aadhaar eKYC', 'description' => 'Digitally verify guest identity on check-in', 'icon' => '🪪', 'category' => 'Compliance', 'status' => 'coming_soon'],
        ['name' => 'REST API', 'description' => 'Connect your own tools using the TalleenPMS API', 'icon' => '🔌', 'category' => 'Developer', 'status' => 'available'],
    ];

    public function render()
    {
        return view('livewire.admin.integrations-hub');
    }
}
