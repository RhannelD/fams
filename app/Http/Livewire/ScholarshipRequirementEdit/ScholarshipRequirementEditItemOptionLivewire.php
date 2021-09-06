<?php

namespace App\Http\Livewire\ScholarshipRequirementEdit;

use Livewire\Component;
use App\Models\ScholarshipRequirementItemOption;
use Illuminate\Support\Facades\Auth;

class ScholarshipRequirementEditItemOptionLivewire extends Component
{
    public $option_id; 
    public $option; 
    public $type;

    protected $listeners = ['save_all' => 'save_all'];

    protected $rules = [
        'option.option' => 'required|string|min:1|max:240',
    ];

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount($option_id, $type)
    {
        if ($this->verifyUser()) return;

        $this->option_id = $option_id;
        $this->option = new ScholarshipRequirementItemOption;
        $this->type = $type;
    }
    
    public function updated($propertyName)
    {
        if ($this->verifyUser()) return;

        $this->validate();
        $this->save();
    }

    public function render()
    {
        $item_option = ScholarshipRequirementItemOption::find($this->option_id);

        if ( isset($item_option) ) {
            $this->option->option = $item_option->option;
        }

        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-edit-item-option-livewire', ['item_option' => $item_option]);
    }

    protected function save()
    {
        $item_option = ScholarshipRequirementItemOption::find($this->option_id);
        if ( is_null($item_option) )
            return;

        $item_option->option = $this->option->option;
        $item_option->save();
    }

    public function delete_option()
    {
        if ($this->verifyUser()) return;

        $item_option = ScholarshipRequirementItemOption::find($this->option_id);
        if ( is_null($item_option) )
            return;

        if ( ScholarshipRequirementItemOption::where('item_id', $item_option->item_id)->count() <= 1 ) {
            $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'There must be atleast one option.']);
            return;
        }

        $item_option->delete();
        $this->dispatchBrowserEvent('delete_option_div', [
            'div_class' => $this->option_id,  
        ]);
        $this->emitUp('get_options');
    }

    public function save_all()
    {
        if ($this->verifyUser()) return;
        
        $this->validate();
        $this->save();
    }
}
