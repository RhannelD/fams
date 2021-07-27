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

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }
    

    public function mount(ScholarshipRequirementItem $id)
    {
        if ($this->verifyUser()) return;

        $this->item = $id;
        $this->get_options();
    }

    public function render()
    {
        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-edit-item-livewire');
    }

    public function get_options()
    {
        $this->options = ScholarshipRequirementItemOption::select('id')
            ->where('item_id', $this->item->id)->get();
    }
    
    public function updated($propertyName)
    {
        if ($this->verifyUser()) return;

        $this->validate();

        if($propertyName == 'item.type') {
            $this->change_item_type();
            $this->get_options();
        }
        $this->save();
    }

    public function save()
    {
        if ($this->verifyUser()) return;

        $this->item->save();
    }
    
    public function save_all()
    {
        if ($this->verifyUser()) return;

        $this->validate();
        $this->save();
    }

    public function delete_confirmation()
    {
        if ($this->verifyUser()) return;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_confirmation_'.$this->item->id, [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this Item!',
            'function' => "delete_item"
        ]);
    }

    public function delete_item()
    {
        if ($this->verifyUser()) return;

        $this->options = [];

        if (!$this->item->delete()) {
            return;
        }

        $this->dispatchBrowserEvent('delete_item_div', [
            'div_class' => $this->item->id,  
        ]);
    }

    public function change_item_type()
    {
        if ($this->verifyUser()) return;

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
        if ($this->verifyUser()) return;
        
        $option = new ScholarshipRequirementItemOption;
        $option->item_id = $this->item->id;
        $option->option = 'Enter Option Here';
        $option->save();
        $this->get_options();
    }
}
