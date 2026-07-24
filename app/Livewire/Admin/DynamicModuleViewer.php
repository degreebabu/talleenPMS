<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\DynamicModule;
use App\Models\DynamicRecord;
use App\Models\DynamicValue;
use App\Models\DynamicField;

class DynamicModuleViewer extends Component
{
    public $slug;
    public $module;
    
    public $showModal = false;
    public $isEditing = false;
    public $recordId = null;

    // dynamic form state
    public $formData = [];

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->module = DynamicModule::with('fields')->where('slug', $slug)->firstOrFail();
    }

    public function createRecord()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function editRecord($id)
    {
        $this->resetForm();
        $this->isEditing = true;
        $this->recordId = $id;

        $record = DynamicRecord::with('values.field')->where('hotel_id', auth()->user()->hotel_id)->findOrFail($id);

        foreach ($record->values as $value) {
            $field = $value->field;
            if ($field->type == 'number') $this->formData[$field->id] = $value->value_number;
            elseif ($field->type == 'boolean') $this->formData[$field->id] = $value->value_boolean;
            elseif ($field->type == 'date') $this->formData[$field->id] = $value->value_date;
            else $this->formData[$field->id] = $value->value_text;
        }

        $this->showModal = true;
    }

    public function deleteRecord($id)
    {
        $record = DynamicRecord::where('hotel_id', auth()->user()->hotel_id)->find($id);
        if ($record) {
            $record->delete();
            session()->flash('success', 'Record deleted successfully.');
        }
    }

    public function saveRecord()
    {
        // Simple validation: check required fields
        foreach ($this->module->fields as $field) {
            if ($field->is_required && empty($this->formData[$field->id])) {
                $this->addError('formData.'.$field->id, 'This field is required.');
                return;
            }
        }

        $hotelId = auth()->user()->hotel_id;

        if ($this->isEditing) {
            $record = DynamicRecord::where('hotel_id', $hotelId)->findOrFail($this->recordId);
        } else {
            $record = DynamicRecord::create([
                'dynamic_module_id' => $this->module->id,
                'hotel_id' => $hotelId,
                'user_id' => auth()->id(),
            ]);
        }

        // Save values
        foreach ($this->module->fields as $field) {
            $val = $this->formData[$field->id] ?? null;

            DynamicValue::updateOrCreate(
                ['dynamic_record_id' => $record->id, 'dynamic_field_id' => $field->id],
                [
                    'value_text' => $field->type == 'text' ? $val : null,
                    'value_number' => $field->type == 'number' ? $val : null,
                    'value_boolean' => $field->type == 'boolean' ? (bool)$val : null,
                    'value_date' => $field->type == 'date' ? $val : null,
                ]
            );
        }

        $this->showModal = false;
        session()->flash('success', 'Record saved successfully.');
    }

    public function resetForm()
    {
        $this->formData = [];
        $this->recordId = null;
        foreach ($this->module->fields as $field) {
            $this->formData[$field->id] = $field->type == 'boolean' ? false : '';
        }
    }

    public function render()
    {
        $records = DynamicRecord::with('values.field')
            ->where('dynamic_module_id', $this->module->id)
            ->where('hotel_id', auth()->user()->hotel_id)
            ->latest()
            ->get();

        return view('livewire.admin.dynamic-module-viewer', compact('records'))->layout('layouts.admin');
    }
}
