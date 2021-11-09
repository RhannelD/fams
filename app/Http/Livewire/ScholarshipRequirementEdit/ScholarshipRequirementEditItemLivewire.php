<?php

namespace App\Http\Livewire\ScholarshipRequirementEdit;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;

class ScholarshipRequirementEditItemLivewire extends Component
{
    public $item_id;
    public $item;

    protected $listeners = [
        'get_options' => '$refresh',
        'save_all' => 'save_all',
    ];

    protected $rules = [
        'item.item' => 'required|string|min:6|max:240',
        'item.note' => 'max:5000',
        'item.type' => 'required',
    ];

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('update', $this->get_requirement_item()) ) {
            $this->emitUp('refresh');
        }
    }

    public function mount($item_id)
    {
        $this->item = new ScholarshipRequirementItem;
        $this->item_id = $item_id;
    }

    public function render()
    {
        $requirement_item = $this->get_requirement_item();

        if ( isset($requirement_item) ) {
            $this->item->item = $requirement_item->item;
            $this->item->note = $requirement_item->note;
            $this->item->type = $requirement_item->type;
        }

        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-edit-item-livewire', ['requirement_item' => $requirement_item]);
    }

    protected function get_requirement_item()
    {
        return ScholarshipRequirementItem::find($this->item_id);
    }
    
    public function updated($propertyName)
    {
        $requirement_item = $this->get_requirement_item();
        if ( Auth::guest() || Auth::user()->cannot('update', $requirement_item) )
            return;

        $this->validate();

        if($propertyName == 'item.type') {
            if ( in_array($this->item->type, array('cor', 'units', 'grade', 'gwa')) )
                $this->emitTo('scholarship-requirement-edit.scholarship-requirement-edit-item-livewire', 'get_options');
            if ( in_array($this->item->type, array('units', 'gwa')) )
                $this->emitUp('refresh');
            $this->change_item_type();
        }
        $this->save();
    }

    public function save()
    {
        $requirement_item = $this->get_requirement_item();
        if ( Auth::guest() || Auth::user()->cannot('update', $requirement_item) )
            return;
        
        $requirement_item->item = $this->item->item;
        $requirement_item->note = $this->item->note;
        $requirement_item->type = $this->item->type;

        $requirement_item->save();
    }
    
    public function save_all()
    {
        $this->validate();
        $this->save();
    }

    public function delete_confirmation()
    {
        $item = $this->get_requirement_item();
        if ( Auth::check() && Auth::user()->can('update', $item) ) {
            if ( $item->is_for_analytics() ) {
                $this->dispatchBrowserEvent('swal:confirm:delete_confirmation_'.$this->item_id, [
                    'type' => 'warning',  
                    'message' => 'Are you sure?', 
                    'text' => 'If deleted, you will not be able to recover this Item and will not be able to use analytics!',
                    'function' => "delete_item"
                ]);
            } else {
                $this->dispatchBrowserEvent('swal:confirm:delete_confirmation_'.$this->item_id, [
                    'type' => 'warning',  
                    'message' => 'Are you sure?', 
                    'text' => 'If deleted, you will not be able to recover this Item!',
                    'function' => "delete_item"
                ]);
            }
            
        }
    }

    public function delete_item()
    {
        if ( Auth::guest() || Auth::user()->cannot('delete', $this->get_requirement_item()) )
            return;
        
        $type = $this->item->type;
        if ( !ScholarshipRequirementItem::where('id', $this->item_id)->delete() ) 
            return;
        
        if ( in_array($type, array('cor', 'units', 'grade', 'gwa')) )
            $this->emitTo('scholarship-requirement-edit.scholarship-requirement-edit-item-livewire', 'get_options');

        $this->dispatchBrowserEvent('delete_item_div', [
            'div_class' => $this->item_id,  
        ]);
    }

    public function change_item_type()
    {
        if ( Auth::guest() || Auth::user()->cannot('update', $this->get_requirement_item()) )
            return;

        if(!in_array($this->item->type, array('radio', 'check'))) {
            $options = ScholarshipRequirementItemOption::where('item_id', $this->item_id)->delete();
            $this->set_detail_on_special_items();
            return;
        }
        
        $temp_item = $this->get_requirement_item();
        if (in_array($temp_item->type, array('radio', 'check'))) 
            return;

        $this->add_item_option();
    }

    protected function set_detail_on_special_items()
    {
        if ( !in_array($this->item->type, array('cor', 'units', 'grade', 'gwa')) ) 
            return;

        $requirement_item = $this->get_requirement_item();
        if ( !$requirement_item || ScholarshipRequirement::where('id', $requirement_item->requirement_id)->whereHasItemWithType($this->item->type)->exists() ) 
            return;
        
        switch ($this->item->type) {
            case 'cor':
                $this->item->item = 'Certificate of Registration';
                $this->item->note = 'Upload in PDF file';
                break;
            case 'units':
                $this->item->item = 'No. of units taken';
                $this->item->note = 'Specify the current no. of units taken';
                break;
            case 'grade':
                $this->item->item = 'Previuos Semester Grades';
                $this->item->note = 'Upload in PDF file';
                break;
            case 'gwa':
                $this->item->item = 'GWA of previuos semester';
                $this->item->note = 'GWA in 1, 1.25, ..., 5 format only';
                break;
        }
    }

    public function add_item_option()
    {
        if ( Auth::guest() || Auth::user()->cannot('update', $this->get_requirement_item()) )
            return;
        
        $option = new ScholarshipRequirementItemOption;
        $option->item_id = $this->item_id;
        $option->option = 'Enter Option Here';
        $option->save();
    }
}
