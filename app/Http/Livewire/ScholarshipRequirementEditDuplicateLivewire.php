<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;

class ScholarshipRequirementEditDuplicateLivewire extends Component
{
    public $requirement_id;
    public $scholarship_id;
    public $duplicate_requirement_id;

    public $search;
    public $show_row = 10;

    protected $listeners = [
        'refresh' => '$refresh',
    ];
    
    public function mount($requirement_id, $scholarship_id)
    {
        $this->requirement_id = $requirement_id;
        $this->scholarship_id = $scholarship_id;
    }

    public function render()
    {
        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-edit-duplicate-livewire', [
                'requirements' => $this->get_scholarship_requirements(),
                'duplicate_requirement' => $this->get_duplicate_requirement(),
            ]);
    }

    protected function get_scholarship_requirements()
    {
        $search = $this->search;
        return ScholarshipRequirement::where('scholarship_id', $this->scholarship_id)
            ->where('id', '!=', $this->requirement_id)
            ->where('scholarship_requirements.requirement', 'like', "%$search%")
            ->take($this->show_row)
            ->get();
    }

    protected function get_duplicate_requirement()
    {
        return ScholarshipRequirement::find($this->duplicate_requirement_id);
    }

    public function view_requirement($requirement_id)
    {
        if ( !ScholarshipRequirement::where('id', $requirement_id)->exists() )
            return;
        
        $this->duplicate_requirement_id = $requirement_id;
        $this->dispatchBrowserEvent('change:tab.selected');  
    }

    public function duplication_confirm()
    {
        if ( is_null($this->get_duplicate_requirement()) )
            return;
        
        $this->dispatchBrowserEvent('swal:confirm:duplicate_requirement', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'All progress will be deleted before duplication.',
            'function' => "requirement_duplication"
        ]);
    }

    public function requirement_duplication()
    {
        $duplicate_requirement = $this->get_duplicate_requirement();
        $requirement = ScholarshipRequirement::find($this->requirement_id);
        if ( is_null($duplicate_requirement) || is_null($requirement) )
            return;

        ScholarshipRequirementItem::where('requirement_id', $this->requirement_id)->delete();

        $requirement->requirement = $duplicate_requirement->requirement;
        $requirement->description = $duplicate_requirement->description;
        $requirement->save();

        foreach ($duplicate_requirement->items as $duplicate_item) {
            $item = $duplicate_item->replicate();
            $item->requirement_id = $this->requirement_id;
            $item->save();

            foreach ($duplicate_item->options as $duplicate_option) {
                $option = $duplicate_option->replicate();
                $option->item_id = $item->id;
                $option->save();
            }
        }

        $this->emitUp('refresh');
        $this->dispatchBrowserEvent('duplicate-form', ['action' => 'hide']);
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Requirement Duplicated', 
            'text' => 'Requirement has been successfully duplicated'
        ]);
    }
}
