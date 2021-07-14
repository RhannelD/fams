<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ScholarshipRequirementItemOption;
use Illuminate\Support\Facades\Auth;

class ScholarshipRequirementEditItemOptionLivewire extends Component
{
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
    

    public function mount(ScholarshipRequirementItemOption $id, $type)
    {
        if ($this->verifyUser()) return;

        $this->option = $id;
        $this->type = $type;
    }
    
    public function updated($propertyName)
    {
        if ($this->verifyUser()) return;

        $this->validate();
        $this->option->save();
    }

    public function render()
    {
        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-edit-item-option-livewire');
    }

    public function delete_option()
    {
        if ($this->verifyUser()) return;

        $this->option->delete();
        $this->dispatchBrowserEvent('delete_option_div', [
            'div_class' => $this->option->id,  
        ]);
        $this->emitUp('get_options');
    }

    public function save_all()
    {
        if ($this->verifyUser()) return;
        
        $this->validate();
        $this->option->save();
    }
}
