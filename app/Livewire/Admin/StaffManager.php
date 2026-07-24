<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class StaffManager extends Component
{
    use WithFileUploads;

    public $staff;
    public $roles;
    public $showForm = false;
    public $showRoleForm = false;

    // Staff Fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'restaurant_manager';
    public $phone = '';
    public $id_card_number = '';
    public $govt_id; // For file upload

    // Role Fields
    public $new_role_name = '';

    public function mount()
    {
        // Ensure standard roles exist globally
        if (!Role::where('name', 'restaurant_manager')->exists()) {
            Role::create(['name' => 'restaurant_manager', 'guard_name' => 'web']);
        }
        if (!Role::where('name', 'receptionist')->exists()) {
            Role::create(['name' => 'receptionist', 'guard_name' => 'web']);
        }

        $this->loadRoles();
        $this->loadStaff();
    }

    public function loadRoles()
    {
        $this->roles = Role::all();
    }

    public function loadStaff()
    {
        $this->staff = User::where('hotel_id', auth()->user()->hotel_id)
            ->where('id', '!=', auth()->id())
            ->with('roles')
            ->get();
    }

    public function create()
    {
        $this->reset(['name', 'email', 'password', 'phone', 'id_card_number', 'govt_id']);
        $this->role = 'restaurant_manager';
        $this->showForm = true;
        $this->showRoleForm = false;
    }

    public function createRole()
    {
        $this->reset(['new_role_name']);
        $this->showRoleForm = true;
        $this->showForm = false;
    }

    public function saveRole()
    {
        $this->validate([
            'new_role_name' => 'required|string|max:255|unique:roles,name'
        ]);

        Role::create(['name' => $this->new_role_name, 'guard_name' => 'web']);

        $this->showRoleForm = false;
        $this->loadRoles();
        $this->role = $this->new_role_name; // select it
        $this->create(); // jump back to creating staff
        
        session()->flash('success', 'New role created successfully.');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
            'phone' => 'nullable|string|max:20',
            'id_card_number' => 'nullable|string|max:50',
            'govt_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $govtIdPath = null;
        if ($this->govt_id) {
            $govtIdPath = $this->govt_id->store('staff-ids', 'public');
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'hotel_id' => auth()->user()->hotel_id,
            'phone' => $this->phone,
            'id_card_number' => $this->id_card_number,
            'govt_id_path' => $govtIdPath,
        ]);

        $user->assignRole(Role::where('name', $this->role)->first());

        $this->showForm = false;
        $this->loadStaff();
        session()->flash('success', 'Staff member created successfully.');
    }

    public function delete($id)
    {
        User::where('hotel_id', auth()->user()->hotel_id)->findOrFail($id)->delete();
        $this->loadStaff();
    }

    public function render()
    {
        return view('livewire.admin.staff-manager')->layout('layouts.admin');
    }
}
