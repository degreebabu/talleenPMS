<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Hotel;
use App\Models\DynamicModule;

class TenantManager extends Component
{
    public $tenants;
    public $companies;
    public $selectedTenant = null;
    public $showModuleModal = false;
    
    // Property Creation
    public $showPropertyModal = false;
    public $propCompanyId = null;
    public $propName;
    public $propSubdomain;
    public $propEmail;
    public $propPhone;
    public $propGst;
    public $propRegistration;
    
    // Core Modules schema (shared with ModuleManager)
    public function getCoreModules()
    {
        return [
            [
                'slug' => 'core_pms', 'name' => 'Front Desk & PMS', 'description' => 'Core property management and reservations.',
                'features' => ['reservations' => 'Reservations', 'housekeeping' => 'Housekeeping', 'folio' => 'Folio Billing', 'guests' => 'Guest Profiles']
            ],
            [
                'slug' => 'core_pos', 'name' => 'Restaurant POS', 'description' => 'Point of sale for F&B operations.',
                'features' => ['menus' => 'Menu Manager', 'tables' => 'Table Management', 'orders' => 'Order Processing']
            ],
            [
                'slug' => 'core_booking', 'name' => 'Booking Engine', 'description' => 'Direct website booking engine integration.',
                'features' => ['widget' => 'Website Widget', 'payments' => 'Payment Gateway', 'promotions' => 'Promo Codes']
            ],
            [
                'slug' => 'core_channel', 'name' => 'Channel Manager', 'description' => 'OTA sync and distribution.',
                'features' => ['sync' => '2-Way Sync', 'rates' => 'Rate Multiplier', 'logs' => 'Sync Logs']
            ],
            [
                'slug' => 'core_staff', 'name' => 'Staff & Payroll', 'description' => 'Employee management and RBAC.',
                'features' => ['roles' => 'Role Management', 'attendance' => 'Attendance Tracking', 'payroll' => 'Payroll Processing']
            ],
        ];
    }

    public function mount()
    {
        $this->loadTenants();
    }

    public function openPropertyModal($companyId = null)
    {
        $this->resetValidation();
        $this->reset(['propName', 'propSubdomain', 'propEmail', 'propPhone', 'propGst', 'propRegistration']);
        $this->propCompanyId = $companyId;
        $this->showPropertyModal = true;
    }

    public function saveProperty()
    {
        $this->validate([
            'propName' => 'required|string|max:255',
            'propSubdomain' => 'required|string|max:255|unique:hotels,subdomain',
            'propEmail' => 'nullable|email',
            'propPhone' => 'nullable|string',
            'propGst' => 'nullable|string',
            'propRegistration' => 'nullable|string',
        ]);

        $hotel = Hotel::create([
            'company_id' => $this->propCompanyId,
            'name' => $this->propName,
            'subdomain' => strtolower($this->propSubdomain),
            'contact_email' => $this->propEmail,
            'contact_phone' => $this->propPhone,
            'gst_number' => $this->propGst,
            'registration_number' => $this->propRegistration,
            'is_active' => true,
        ]);

        $this->showPropertyModal = false;
        $this->loadTenants();
        session()->flash('success', 'Property created successfully.');
    }

    public function loadTenants()
    {
        $this->companies = \App\Models\Company::with(['hotels.subscriptionPlan'])->latest()->get();
        $this->tenants = Hotel::whereNull('company_id')->with('subscriptionPlan')->latest()->get();
    }

    public function toggleActive($id)
    {
        $tenant = Hotel::findOrFail($id);
        $tenant->update(['is_active' => !$tenant->is_active]);
        $this->loadTenants();
    }

    public function manageModules($id)
    {
        $this->selectedTenant = Hotel::findOrFail($id);
        
        // Ensure features is an associative array (migrate legacy indexed array if needed)
        $features = $this->selectedTenant->features ?? [];
        if (!is_array($features)) $features = [];
        
        // Convert any legacy indexed strings to associative active states
        $migrated = false;
        foreach ($features as $key => $val) {
            if (is_int($key)) {
                unset($features[$key]);
                $features[$val] = 'active';
                $migrated = true;
            }
        }
        
        if ($migrated) {
            $this->selectedTenant->features = $features;
            $this->selectedTenant->save();
        }

        $this->showModuleModal = true;
    }

    public function updateModuleStatus($slug, $status, $subFeature = null)
    {
        if (!$this->selectedTenant) return;
        
        $features = $this->selectedTenant->features ?? [];
        
        if ($subFeature) {
            $fKey = $slug . '_' . $subFeature;
            $features[$fKey] = $status;
        } else {
            $features[$slug] = $status;
        }
        
        $this->selectedTenant->features = $features;
        $this->selectedTenant->save();
    }

    public function render()
    {
        $coreModules = $this->getCoreModules();
        $dynamicModules = DynamicModule::where('is_active', true)->get();
        return view('livewire.super-admin.tenant-manager', compact('coreModules', 'dynamicModules'))->layout('layouts.super-admin');
    }
}
