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
    public $requirement;
    
    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }
    
    public function mount(ScholarshipRequirement $requirement)
    {
        if ($this->verifyUser()) return;

        $this->requirement = $requirement;
    }
    

    public function render()
    {
        return view('livewire.pages.scholarship-requirement-open.scholarship-requirement-open-livewire');
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
        
        $requirement_id = $this->requirement->id;

        $this->requirement = [];

        ScholarshipRequirement::find($requirement_id)->delete();

        
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Requirement Deleted', 
            'text' => 'Scholarship\'s Requirement has been successfully deleted'
        ]);

        $this->emitUp('changetab', 'requirement');
    }
}
