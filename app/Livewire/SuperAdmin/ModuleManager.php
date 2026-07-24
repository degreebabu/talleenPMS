<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\DynamicModule;
use App\Models\Hotel;

class ModuleManager extends Component
{
    public $showAssignModal = false;
    public $selectedModule = null; // ['slug' => '...', 'name' => '...', 'is_dynamic' => false, 'features' => []]
    public $tenants = [];
    
    // Holds the status for each tenant for the selected module and its features
    // e.g. [tenant_id => ['_main' => 'active', 'subfeature1' => 'paused']]
    public $tenantStatuses = []; 

    // For toggling tenant accordion in the UI
    public $expandedTenantId = null;

    public function mount()
    {
        $this->tenants = Hotel::all();
    }

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

    public function openAssignModal($slug, $name, $isDynamic = false)
    {
        $features = [];
        if (!$isDynamic) {
            $coreModules = collect($this->getCoreModules());
            $module = $coreModules->firstWhere('slug', $slug);
            if ($module) {
                $features = $module['features'] ?? [];
            }
        }

        $this->selectedModule = [
            'slug' => $slug,
            'name' => $name,
            'is_dynamic' => $isDynamic,
            'features' => $features
        ];
        
        $this->tenantStatuses = [];
        $this->expandedTenantId = null;
        
        foreach ($this->tenants as $tenant) {
            $tenantFeatures = $tenant->features ?? [];
            
            // Get main module status
            $mainStatus = $tenantFeatures[$slug] ?? 'disabled';
            if ($mainStatus === true) $mainStatus = 'active';
            if ($mainStatus === false) $mainStatus = 'disabled';
            
            $this->tenantStatuses[$tenant->id]['_main'] = $mainStatus;

            // Get sub-feature statuses
            foreach ($features as $fSlug => $fName) {
                $fKey = $slug . '_' . $fSlug;
                $fStatus = $tenantFeatures[$fKey] ?? 'disabled';
                if ($fStatus === true) $fStatus = 'active';
                if ($fStatus === false) $fStatus = 'disabled';
                
                $this->tenantStatuses[$tenant->id][$fSlug] = $fStatus;
            }
        }

        $this->showAssignModal = true;
    }

    public function toggleTenantExpand($tenantId)
    {
        if ($this->expandedTenantId === $tenantId) {
            $this->expandedTenantId = null;
        } else {
            $this->expandedTenantId = $tenantId;
        }
    }

    public function updateTenantStatus($tenantId, $status, $featureSlug = null)
    {
        if (!$this->selectedModule) return;
        
        $tenant = Hotel::find($tenantId);
        if ($tenant) {
            $features = $tenant->features ?? [];
            
            if ($featureSlug) {
                $fKey = $this->selectedModule['slug'] . '_' . $featureSlug;
                $features[$fKey] = $status;
                $this->tenantStatuses[$tenantId][$featureSlug] = $status;
            } else {
                $features[$this->selectedModule['slug']] = $status;
                $this->tenantStatuses[$tenantId]['_main'] = $status;
            }
            
            $tenant->features = $features;
            $tenant->save();
            
            session()->flash('success', "Updated status for {$tenant->name}.");
        }
    }

    public function toggleDynamicStatus($moduleId)
    {
        $module = DynamicModule::find($moduleId);
        if ($module) {
            $module->update(['is_active' => !$module->is_active]);
        }
    }

    public function render()
    {
        $dynamicModules = DynamicModule::withCount('fields')->get();
        $coreModules = collect($this->getCoreModules());

        // Pre-calculate active tenant counts for display
        $coreUsage = [];
        $dynamicUsage = [];
        
        foreach ($this->tenants as $tenant) {
            $features = $tenant->features ?? [];
            foreach ($coreModules as $cm) {
                if (($features[$cm['slug']] ?? false) === 'active' || ($features[$cm['slug']] ?? false) === true) {
                    $coreUsage[$cm['slug']] = ($coreUsage[$cm['slug']] ?? 0) + 1;
                }
            }
            foreach ($dynamicModules as $dm) {
                if (($features[$dm->slug] ?? false) === 'active' || ($features[$dm->slug] ?? false) === true) {
                    $dynamicUsage[$dm->slug] = ($dynamicUsage[$dm->slug] ?? 0) + 1;
                }
            }
        }

        return view('livewire.super-admin.module-manager', compact('dynamicModules', 'coreModules', 'coreUsage', 'dynamicUsage'))->layout('layouts.super-admin');
    }
}
