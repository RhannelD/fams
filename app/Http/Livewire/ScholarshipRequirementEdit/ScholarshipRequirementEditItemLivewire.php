<?php

namespace App\Http\Livewire\ScholarshipRequirementEdit;

use Livewire\Component;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;
use Illuminate\Support\Facades\Auth;

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
        if ( Auth::guest() || Auth::user()->cannot('update', $this->get_requirement_item()) )
            return;

        $this->validate();

        if($propertyName == 'item.type') {
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
        if ( Auth::check() && Auth::user()->can('update', $this->get_requirement_item()) ) {
            $this->dispatchBrowserEvent('swal:confirm:delete_confirmation_'.$this->item_id, [
                'type' => 'warning',  
                'message' => 'Are you sure?', 
                'text' => 'If deleted, you will not be able to recover this Item!',
                'function' => "delete_item"
            ]);
        }
    }

    public function delete_item()
    {
        if ( Auth::guest() || Auth::user()->cannot('delete', $this->get_requirement_item()) )
            return;

        if ( !ScholarshipRequirementItem::where('id', $this->item_id)->delete() ) 
            return;

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
            return;
        }
        
        $temp_item = $this->get_requirement_item();
        if (in_array($temp_item->type, array('radio', 'check'))) 
            return;

        $this->add_item_option();
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
