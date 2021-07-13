<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ScholarshipRequirement;
use Carbon\Carbon;

class ScholarshipRequirementActivateLivewire extends Component
{
    public $requirement;

    
    public $start_at;
    public $end_at;

    protected $rules = [
        'start_at' => 'required|date_format:Y-m-d\Th:i',
        'end_at' => 'required|date_format:Y-m-d\Th:i|after:requirement.start_at',
    ];

    public function updated($propertyName)
    {
        $this->validate();

        $this->requirement->start_at = Carbon::parse($this->start_at)->format('Y-m-d H:i:s');
        $this->requirement->end_at   = Carbon::parse($this->end_at)->format('Y-m-d H:i:s');

        $this->requirement->save();
        
        if ($propertyName == 'start_at') {
            $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'Start Date Successfully Changed']);
        } elseif ($propertyName == 'end_at') {
            $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'End Date Successfully Changed']);
        }
    }
    
    public function mount(ScholarshipRequirement $id)
    {
        $this->requirement = $id;
        $this->start_at = Carbon::parse($this->requirement->start_at)->format('Y-m-d\Th:i');
        $this->end_at   = Carbon::parse($this->requirement->end_at)->format('Y-m-d\Th:i');
    }

    public function render()
    {
        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-activate-livewire');
    }
    
    public function toggle_disable_at_end()
    {
        if (isset($this->requirement->enable)) {
            $this->requirement->enable = null;
            $this->requirement->save();
            $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'Disable Form At End Date']);
            return;
        }
        $this->requirement->enable = false;
        $this->requirement->save();
        $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'Disable Form Requirement ...']);
    }

    public function toggle_enable_form()
    {
        $this->requirement->enable = (!$this->requirement->enable);
        $this->requirement->save();

        $message = 'Disable Form Requirement ...';
        if ($this->requirement->enable) {
            $message = 'Enable Form Requirement ...';
        }

        $this->dispatchBrowserEvent('toggle_enable_form', ['message' => $message]);
    }
}
