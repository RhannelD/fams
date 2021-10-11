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
        'end_at' => 'required|date_format:Y-m-d\TH:i|after:start_at',
    ];

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('update', $this->get_scholarship_requirement()) ) {
            $this->emitUp('refresh');
        }
    }

    public function updated($propertyName)
    {
        $requirement = $this->get_scholarship_requirement();
        if ( Auth::guest() || Auth::user()->cannot('update', $requirement) )
            return;

        $this->validate();

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
        $this->requirement_id = $requirement_id;
    }

    public function render()
    {
        $requirement = $this->get_scholarship_requirement();

        $this->start_at = Carbon::parse($requirement->start_at)->format('Y-m-d\TH:i');
        $this->end_at   = Carbon::parse($requirement->end_at)->format('Y-m-d\TH:i');

        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-activate-livewire', ['requirement' => $requirement]);
    }
    
    protected function get_scholarship_requirement()
    {
        return ScholarshipRequirement::find($this->requirement_id);
    }

    public function toggle_disable_at_end()
    {
        $requirement = $this->get_scholarship_requirement();
        if ( Auth::guest() || Auth::user()->cannot('update', $requirement) )
            return;

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
        $requirement = $this->get_scholarship_requirement();
        if ( Auth::guest() || Auth::user()->cannot('update', $requirement) )
            return;

        $requirement->enable = (!$requirement->enable);
        $requirement->save();

        $message = 'Disable Form Requirement ...';
        if ($requirement->enable) {
            $message = 'Enable Form Requirement ...';
        }

        $this->dispatchBrowserEvent('toggle_enable_form', ['message' => $message]);
    }
}
