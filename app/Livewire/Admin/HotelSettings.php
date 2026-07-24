<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class HotelSettings extends Component
{
    use WithFileUploads;

    public $name, $address, $contact_email, $contact_phone, $gst_number, $registration_number;
    public $primary_color, $secondary_color;
    public $check_in_time, $check_out_time;
    public $logo, $cover;
    public $document_files = [];
    
    public $current_logo, $current_cover, $current_documents;

    public function mount()
    {
        $hotel = auth()->user()->hotel;
        $this->name = $hotel->name;
        $this->address = $hotel->address;
        $this->contact_email = $hotel->contact_email;
        $this->contact_phone = $hotel->contact_phone;
        $this->gst_number = $hotel->gst_number;
        $this->registration_number = $hotel->registration_number;
        $this->primary_color = $hotel->primary_color ?? '#1d4ed8';
        $this->secondary_color = $hotel->secondary_color ?? '#f1f5f9';
        $this->check_in_time = $hotel->check_in_time ? date('H:i', strtotime($hotel->check_in_time)) : '14:00';
        $this->check_out_time = $hotel->check_out_time ? date('H:i', strtotime($hotel->check_out_time)) : '11:00';
        $this->current_logo = $hotel->logo_path;
        $this->current_cover = $hotel->cover_path;
        $this->current_documents = $hotel->documents ?? [];
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'logo' => 'nullable|image|max:2048',
            'cover' => 'nullable|image|max:4096',
            'document_files.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $hotel = auth()->user()->hotel;

        if ($this->logo) {
            if ($hotel->logo_path) Storage::disk('public')->delete($hotel->logo_path);
            $hotel->logo_path = $this->logo->store('hotels/logos', 'public');
            $this->current_logo = $hotel->logo_path;
        }

        if ($this->cover) {
            if ($hotel->cover_path) Storage::disk('public')->delete($hotel->cover_path);
            $hotel->cover_path = $this->cover->store('hotels/covers', 'public');
            $this->current_cover = $hotel->cover_path;
        }

        $docs = $this->current_documents;
        if (!empty($this->document_files)) {
            foreach ($this->document_files as $file) {
                $docs[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $file->store('hotels/documents', 'public'),
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            }
            $hotel->documents = $docs;
        }

        $hotel->update([
            'name' => $this->name,
            'address' => $this->address,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'gst_number' => $this->gst_number,
            'registration_number' => $this->registration_number,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
        ]);

        session()->flash('success', 'Settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.hotel-settings');
    }
}
