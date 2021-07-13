<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScholarshipRequirementOpenLivewire extends Component
{
    public $requirement;
    
    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('dashboard');
            return true;
        }
        return false;
    }
    
    public function mount(ScholarshipRequirement $requirement)
    {
        $this->requirement = $requirement;
    }
    

    public function render()
    {
        $requirement_items =  ScholarshipRequirementItem::where('requirement_id', $this->requirement->id)
            ->orderBy('position')
            ->get();

        foreach ($requirement_items as $key => $requirement_item) {
            if (in_array($requirement_item->type, array('radio', 'check'))) {
                $options = ScholarshipRequirementItemOption::where('item_id', $requirement_item->id)->get();

                $requirement_items[$key]['options'] = $options;
            }
        }

        return view('livewire.pages.scholarship-requirement-open.scholarship-requirement-open-livewire', [
            'requirement_items' => $requirement_items
        ]);
    }
}