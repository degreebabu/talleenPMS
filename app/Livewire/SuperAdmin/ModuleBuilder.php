<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\DynamicModule;
use App\Models\DynamicField;
use Illuminate\Support\Str;

class ModuleBuilder extends Component
{
    public $prompt = '';
    public $isGenerating = false;

    public $moduleName = '';
    public $moduleDescription = '';
    public $fields = []; // array of ['name' => '', 'type' => 'text', 'is_required' => false]

    public function mount()
    {
        // Start with one empty field
        $this->addField();
    }

    public function addField()
    {
        $this->fields[] = ['name' => '', 'type' => 'text', 'is_required' => false];
    }

    public function removeField($index)
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields); // reindex
    }

    public function generateFromPrompt()
    {
        $this->validate([
            'prompt' => 'required|string|min:5'
        ]);

        $this->isGenerating = true;

        // Simulated AI response based on keywords. 
        // In production, this would call OpenAI or Anthropic API to return a structured JSON schema.
        $promptLower = strtolower($this->prompt);
        
        if (str_contains($promptLower, 'spa') || str_contains($promptLower, 'massage')) {
            $this->moduleName = 'Spa Services';
            $this->moduleDescription = 'Manage spa treatments, bookings, and therapist assignments.';
            $this->fields = [
                ['name' => 'Treatment Name', 'type' => 'text', 'is_required' => true],
                ['name' => 'Price', 'type' => 'number', 'is_required' => true],
                ['name' => 'Duration (Minutes)', 'type' => 'number', 'is_required' => true],
                ['name' => 'Is Available', 'type' => 'boolean', 'is_required' => false],
            ];
        } elseif (str_contains($promptLower, 'car') || str_contains($promptLower, 'vehicle') || str_contains($promptLower, 'rental')) {
            $this->moduleName = 'Vehicle Rentals';
            $this->moduleDescription = 'Manage fleet inventory and guest vehicle rentals.';
            $this->fields = [
                ['name' => 'Vehicle Model', 'type' => 'text', 'is_required' => true],
                ['name' => 'License Plate', 'type' => 'text', 'is_required' => true],
                ['name' => 'Daily Rate', 'type' => 'number', 'is_required' => true],
                ['name' => 'Available', 'type' => 'boolean', 'is_required' => false],
            ];
        } elseif (str_contains($promptLower, 'gym') || str_contains($promptLower, 'fitness')) {
            $this->moduleName = 'Gym Management';
            $this->moduleDescription = 'Track fitness classes and personal training sessions.';
            $this->fields = [
                ['name' => 'Class Name', 'type' => 'text', 'is_required' => true],
                ['name' => 'Instructor', 'type' => 'text', 'is_required' => true],
                ['name' => 'Max Capacity', 'type' => 'number', 'is_required' => true],
                ['name' => 'Date', 'type' => 'date', 'is_required' => true],
            ];
        } else {
            // Generic fallback
            $this->moduleName = ucwords($this->prompt) . ' Management';
            $this->moduleDescription = 'Custom module generated from AI prompt.';
            $this->fields = [
                ['name' => 'Title', 'type' => 'text', 'is_required' => true],
                ['name' => 'Amount', 'type' => 'number', 'is_required' => false],
                ['name' => 'Active Status', 'type' => 'boolean', 'is_required' => false],
                ['name' => 'Date Added', 'type' => 'date', 'is_required' => false],
            ];
        }

        $this->isGenerating = false;
        session()->flash('ai_success', 'AI has successfully generated a starting schema for your module!');
    }

    public function saveModule()
    {
        $this->validate([
            'moduleName' => 'required|string|max:255',
            'fields' => 'required|array|min:1',
            'fields.*.name' => 'required|string',
            'fields.*.type' => 'required|in:text,number,boolean,date',
        ]);

        $slug = Str::slug($this->moduleName);
        
        // Ensure slug is unique
        if (DynamicModule::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $module = DynamicModule::create([
            'name' => $this->moduleName,
            'slug' => $slug,
            'description' => $this->moduleDescription,
        ]);

        foreach ($this->fields as $index => $field) {
            DynamicField::create([
                'dynamic_module_id' => $module->id,
                'name' => $field['name'],
                'type' => $field['type'],
                'is_required' => $field['is_required'],
                'order' => $index,
            ]);
        }

        session()->flash('success', 'Custom module created successfully! You can now assign it to tenants.');
        return redirect()->route('super-admin.module-manager');
    }

    public function render()
    {
        return view('livewire.super-admin.module-builder')->layout('layouts.super-admin');
    }
}
