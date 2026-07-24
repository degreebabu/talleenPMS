<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Company;

class CompanyManager extends Component
{
    public $companies;
    public $showModal = false;
    
    public $companyId;
    public $name;
    public $contact_email;
    public $contact_phone;
    public $address;
    public $gst_number;
    public $registration_number;
    public $is_active = true;

    public function mount()
    {
        $this->loadCompanies();
    }

    public function loadCompanies()
    {
        $this->companies = Company::withCount('hotels')->latest()->get();
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['companyId', 'name', 'contact_email', 'contact_phone', 'address', 'gst_number', 'registration_number', 'is_active']);
        $this->showModal = true;
    }

    public function editCompany($id)
    {
        $this->resetValidation();
        $company = Company::findOrFail($id);
        $this->companyId = $company->id;
        $this->name = $company->name;
        $this->contact_email = $company->contact_email;
        $this->contact_phone = $company->contact_phone;
        $this->address = $company->address;
        $this->gst_number = $company->gst_number;
        $this->registration_number = $company->registration_number;
        $this->is_active = $company->is_active;
        $this->showModal = true;
    }

    public function saveCompany()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'gst_number' => 'nullable|string|max:100',
            'registration_number' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        Company::updateOrCreate(
            ['id' => $this->companyId],
            [
                'name' => $this->name,
                'contact_email' => $this->contact_email,
                'contact_phone' => $this->contact_phone,
                'address' => $this->address,
                'gst_number' => $this->gst_number,
                'registration_number' => $this->registration_number,
                'is_active' => $this->is_active,
            ]
        );

        $this->showModal = false;
        $this->loadCompanies();
        
        session()->flash('success', $this->companyId ? 'Company updated successfully.' : 'Company created successfully.');
    }

    public function toggleActive($id)
    {
        $company = Company::findOrFail($id);
        $company->is_active = !$company->is_active;
        $company->save();
        $this->loadCompanies();
    }

    public function render()
    {
        return view('livewire.super-admin.company-manager')
            ->layout('layouts.super-admin');
    }
}
