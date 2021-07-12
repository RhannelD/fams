<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;
use Illuminate\Support\Facades\Auth;

class ScholarshipRequirementEditItemLivewire extends Component
{
    
    public $item;
    public $options;

    protected $listeners = [
        'get_options' => 'get_options',
        'save_all' => 'save_all',
    ];

    protected $rules = [
        'item.item' => 'required|string|min:6|max:240',
        'item.note' => 'max:5000',
        'item.type' => 'required',
    ];


    public function mount(ScholarshipRequirementItem $id)
    {
        $this->item = $id;
        $this->get_options();
    }

    public function render()
    {
        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-edit-item-livewire');
    }

    public function get_options()
    {
        $this->options = ScholarshipRequirementItemOption::select('id', 'option')
            ->where('item_id', $this->item->id)->get();
    }
    
    public function updated($propertyName)
    {
        $this->validate();

        if($propertyName == 'item.type') {
            $this->change_item_type();
            $this->get_options();
        }
        $this->save();
    }

    public function save()
    {
        $this->item->save();
    }
    
    public function save_all()
    {
        $this->validate();
        $this->save();
    }

    public function change_item_type()
    {
        if(!in_array($this->item->type, array('radio', 'check'))) {
            $options = ScholarshipRequirementItemOption::where('item_id', $this->item->id)->delete();
            return;
        }
        
        $temp_item = ScholarshipRequirementItem::find($this->item->id);
        if (in_array($temp_item->type, array('radio', 'check'))) 
            return;

        $this->add_item_option();
    }

    public function add_item_option()
    {
        $option = new ScholarshipRequirementItemOption;
        $option->item_id = $this->item->id;
        $option->option = 'Enter Option Here';
        $option->save();
        $this->get_options();
    }
}
