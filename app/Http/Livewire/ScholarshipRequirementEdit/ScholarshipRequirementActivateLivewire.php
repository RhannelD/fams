<?php

namespace App\Http\Livewire\ScholarshipRequirementEdit;

use Livewire\Component;
use App\Models\ScholarshipRequirement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ScholarshipRequirementActivateLivewire extends Component
{
    public $requirement_id;

    public $start_at;
    public $end_at;

    protected $rules = [
        'start_at' => 'required|date_format:Y-m-d\TH:i',
        'end_at' => 'required|date_format:Y-m-d\TH:i|after:requirement.start_at',
    ];

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }
    
    public function updated($propertyName)
    {
        if ($this->verifyUser()) return;

        $this->validate();

        $requirement = ScholarshipRequirement::find($this->requirement_id);
        if ( is_null($requirement) ) {
            return;
        }

        $requirement->start_at = Carbon::parse($this->start_at)->format('Y-m-d H:i:s');
        $requirement->end_at   = Carbon::parse($this->end_at)->format('Y-m-d H:i:s');

        if ( $requirement->save() ) {
            if ($propertyName == 'start_at') {
                $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'Start Date Successfully Changed']);
            } elseif ($propertyName == 'end_at') {
                $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'End Date Successfully Changed']);
            }
        }
    }

    public function mount($requirement_id)
    {
        if ($this->verifyUser()) return;

        $this->requirement_id = $requirement_id;
    }

    public function render()
    {
        $requirement = ScholarshipRequirement::find($this->requirement_id);

        $this->start_at = Carbon::parse($requirement->start_at)->format('Y-m-d\TH:i');
        $this->end_at   = Carbon::parse($requirement->end_at)->format('Y-m-d\TH:i');

        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-activate-livewire', ['requirement' => $requirement]);
    }
    
    public function toggle_disable_at_end()
    {
        if ($this->verifyUser()) return;

        $requirement = ScholarshipRequirement::find($this->requirement_id);
        if ( is_null($requirement) ) {
            return;
        }

        if ( isset($requirement->enable) ) {
            $requirement->enable = null;
            $requirement->save();
            $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'Disable Form At End Date']);
            return;
        }
        $requirement->enable = false;
        $requirement->save();
        $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'Disable Form Requirement ...']);
    }

    public function toggle_enable_form()
    {
        if ($this->verifyUser()) return;

        $requirement = ScholarshipRequirement::find($this->requirement_id);
        if ( is_null($requirement) ) {
            return;
        }

        $requirement->enable = (!$requirement->enable);
        $requirement->save();

        $message = 'Disable Form Requirement ...';
        if ($requirement->enable) {
            $message = 'Enable Form Requirement ...';
        }

        $this->dispatchBrowserEvent('toggle_enable_form', ['message' => $message]);
    }
}
