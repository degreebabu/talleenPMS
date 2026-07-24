<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;

class ActivityLogs extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = ActivityLog::with(['hotel', 'user'])
            ->where('description', 'like', '%' . $this->search . '%')
            ->orWhereHas('hotel', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(20);

        return view('livewire.super-admin.activity-logs', compact('logs'))->layout('layouts.super-admin');
    }
}
