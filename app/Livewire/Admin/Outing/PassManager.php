<?php

namespace App\Livewire\Admin\Outing;

use App\Models\DayPass;
use Livewire\Component;
use Livewire\WithPagination;

class PassManager extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateStatus($id, $status)
    {
        DayPass::where('id', $id)->update(['status' => $status]);
    }

    public function render()
    {
        $passes = DayPass::where('hotel_id', auth()->user()->hotel_id)
            ->with('package')
            ->when($this->search, function ($query) {
                $query->where('customer_name', 'like', '%' . $this->search . '%')
                      ->orWhere('customer_email', 'like', '%' . $this->search . '%')
                      ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
            })
            ->orderBy('visit_date', 'desc')
            ->paginate(10);

        return view('livewire.admin.outing.pass-manager', [
            'passes' => $passes
        ]);
    }
}
