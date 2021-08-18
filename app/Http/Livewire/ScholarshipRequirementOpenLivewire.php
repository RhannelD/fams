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

        return view('livewire.pages.scholarship-requirement-open.scholarship-requirement-open-livewire', ['requirement' => $requirement]);
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

        ScholarshipRequirement::find($this->requirement_id)->delete();
        
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Requirement Deleted', 
            'text' => 'Scholarship\'s Requirement has been successfully deleted'
        ]);

        $this->emitUp('changetab', 'requirement');
    }
}
