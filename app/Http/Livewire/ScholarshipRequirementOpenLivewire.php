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
        $requirement_items = [];

        if (isset($this->requirement->id)) {
            $requirement_items =  ScholarshipRequirementItem::where('requirement_id', $this->requirement->id)
                ->orderBy('position')
                ->get();
    
            foreach ($requirement_items as $key => $requirement_item) {
                if (in_array($requirement_item->type, array('radio', 'check'))) {
                    $options = ScholarshipRequirementItemOption::where('item_id', $requirement_item->id)->get();
    
                    $requirement_items[$key]['options'] = $options;
                }
            }
        }

        return view('livewire.pages.scholarship-requirement-open.scholarship-requirement-open-livewire', [
            'requirement_items' => $requirement_items
        ]);
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
        
        $requirement_items =  ScholarshipRequirementItem::where('requirement_id', $this->requirement->id)
            ->get();

        foreach ($requirement_items as $requirement_item) {
            $options = ScholarshipRequirementItemOption::where('item_id', $requirement_item->id)->delete();
        }

        ScholarshipRequirementCategory::where('requirement_id', $this->requirement->id)
            ->delete();

        ScholarshipPostLinkRequirement::where('requirement_id', $this->requirement->id)
            ->delete();

        ScholarResponse::where('requirement_id', $this->requirement->id)
            ->delete();

        $requirement_items =  ScholarshipRequirementItem::where('requirement_id', $this->requirement->id)
            ->delete();

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
