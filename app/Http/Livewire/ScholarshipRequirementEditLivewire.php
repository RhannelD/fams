<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Scholarship;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use Illuminate\Support\Facades\Auth;

class ScholarshipRequirementEditLivewire extends Component
{
    public $requirement;
    public $scholarship;
    public $items;

    protected $rules = [
        'requirement.requirement' => 'required|string|min:6',
        'requirement.description' => 'required|string|max:500',
    ];

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('dashboard');
            return true;
        }
        return false;
    }


    public function mount(ScholarshipRequirement $id)
    {
        $this->requirement = $id;
        $this->scholarship = Scholarship::find($id->scholarship_id);
        $this->items = ScholarshipRequirementItem::select('id')
            ->where('requirement_id', $id->id)
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-edit-livewire')
            ->extends('livewire.main.main-livewire');
    }

    public function updated($propertyName)
    {
        $this->save();
    }

    public function add_item()
    {
        $item = new ScholarshipRequirementItem;
        $item->requirement_id = $this->requirement->id;
        $item->item = 'Enter Item Title Here';
        $item->type = 'question';
        $item->note = '';
        $item->save();
        $this->items[count($this->items)] = $item;
    }

    public function save()
    {
        $this->validate();

        $this->requirement->save();
    }
    
    public function save_all()
    {
        $this->save();
        $this->emit('save_all');
    }
}
