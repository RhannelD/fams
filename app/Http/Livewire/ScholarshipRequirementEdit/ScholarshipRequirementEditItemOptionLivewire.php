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

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('update', $this->get_item_option()) ) {
            $this->emitUp('refresh');
        }
    }

    public function mount($option_id, $type)
    {
        $this->option_id = $option_id;
        $this->option = new ScholarshipRequirementItemOption;
        $this->type = $type;
    }
    
    public function render()
    {
        $item_option = $this->get_item_option();
        $this->option->option = isset($item_option)? $item_option->option: null;

        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-edit-item-option-livewire', ['item_option' => $item_option]);
    }

    protected function get_item_option()
    {
        return ScholarshipRequirementItemOption::find($this->option_id);
    }

    public function updated($propertyName)
    {
        if ( Auth::check() && Auth::user()->can('update', $this->get_item_option()) ) {
            $this->validate();
            $this->save();
        }
    }

    protected function save()
    {
        $item_option = $this->get_item_option();
        if ( Auth::guest() || Auth::user()->cannot('update', $item_option) )
            return;

        $item_option->option = $this->option->option;
        $item_option->save();
    }

    public function delete_option()
    {
        $item_option = $this->get_item_option();
        if ( Auth::guest() || Auth::user()->cannot('delete', $item_option) )
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
        $this->validate();
        $this->save();
    }
}
