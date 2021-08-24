<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;
use App\Models\ScholarshipRequirementCategory;
use App\Models\ScholarshipPostLinkRequirement;
use App\Models\ScholarResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScholarshipRequirementOpenLivewire extends Component
{
    public $requirement_id;
    
    protected function verifyUser()
    {
        if ( !Auth::check() || Auth::user()->usertype=='scholar' ) {
            redirect()->route('index');
            return true;
        }
        return false;
    }
    
    public function mount($requirement_id)
    {
        if ($this->verifyUser()) return;

        $this->requirement_id = $requirement_id;
    }
    
    public function render()
    {
        $requirement = ScholarshipRequirement::find($this->requirement_id);

        return view('livewire.pages.scholarship-requirement-open.scholarship-requirement-open-livewire', ['requirement' => $requirement])
            ->extends('livewire.main.main-livewire');
    }

    public function delete_confirmation()
    {
        if ($this->verifyUser()) return;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_requirement', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this requirement!',
            'function' => "delete_requirement"
        ]);
    }

    public function delete_requirement()
    {
        if ($this->verifyUser()) return;

        $requirement = ScholarshipRequirement::find($this->requirement_id);
        if ( is_null($requirement) )
            return;
        
        $scholarship_id = $requirement->scholarship_id;
        if ($requirement->delete()) {
            session()->flash('deleted', 'Requirement successfully deleted.');
            redirect()->route('scholarship.requirement', [$scholarship_id]);
        }
    }
}
