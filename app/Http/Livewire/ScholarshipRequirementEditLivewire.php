<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Scholarship;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementCategory;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;

class ScholarshipRequirementEditLivewire extends Component
{
    public $requirement;
    public $scholarship;
    public $items;
    public $categories;

    protected $rules = [
        'requirement.requirement' => 'required|string|min:6',
        'requirement.description' => 'required|string|max:500',
        'requirement.promote' => 'required',
    ];

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }


    public function mount(ScholarshipRequirement $id)
    {
        if ($this->verifyUser()) return;

        $this->requirement = $id;
        $this->scholarship = Scholarship::find($id->scholarship_id);
        $this->items = ScholarshipRequirementItem::select('id')
            ->where('requirement_id', $id->id)
            ->orderBy('position')
            ->get();
        $this->load_categories();
    }

    public function load_categories()
    {
        $this->categories = ScholarshipCategory::select('scholarship_categories.*', 'scholarship_requirement_categories.category_id')
            ->join('scholarship_requirements', 'scholarship_categories.scholarship_id', '=', 'scholarship_requirements.scholarship_id')
            ->leftJoin('scholarship_requirement_categories', function($join) {
                    $join->on('scholarship_categories.id', '=', 'scholarship_requirement_categories.category_id');
                    $join->on('scholarship_requirements.id', '=', 'scholarship_requirement_categories.requirement_id');
                })
            ->where('scholarship_requirements.id', $this->requirement->id)
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-edit-livewire')
            ->extends('livewire.main.main-livewire');
    }

    public function updated($propertyName)
    {
        if ($this->verifyUser()) return;
        
        $this->save();

        if ($propertyName == 'requirement.promote') {
            if ($this->requirement->promote) {
                $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'Requirement type changed for New Applicants']);
            } else {
                $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'Requirement type changed for Old Scholars']);
            }
        }
    }

    public function add_item()
    {
        if ($this->verifyUser()) return;

        $position = ScholarshipRequirementItem::where('requirement_id', 1)
            ->max('position');

        $item = new ScholarshipRequirementItem;
        $item->requirement_id = $this->requirement->id;
        $item->item = 'Enter Item Title Here';
        $item->type = 'question';
        $item->note = '';
        $item->position = $position+1;
        $item->save();
        $this->items[count($this->items)] = $item;
    }

    public function update_requirement_order($list)
    {
        if ($this->verifyUser()) return;

        foreach ($list as $item) {
            ScholarshipRequirementItem::find($item['value'])
                ->update(['position' => $item['order']]);
        }
        $this->items = ScholarshipRequirementItem::select('id')
            ->where('requirement_id', $this->requirement->id)
            ->orderBy('position')
            ->get();
    }

    public function save()
    {
        if ($this->verifyUser()) return;

        $this->validate();

        $this->requirement->save();
    }
    
    public function save_all()
    {
        if ($this->verifyUser()) return;

        $this->save();
        $this->emit('save_all');
    }

    public function toggle_category($category_id)
    {
        $delete = ScholarshipRequirementCategory::where('requirement_id', $this->requirement->id)
            ->where('category_id', $category_id)->delete();

        if ($delete) {
            $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'Category Removed Successfully']);
        } else {
            ScholarshipRequirementCategory::firstOrCreate([
                'requirement_id' =>  $this->requirement->id,
                'category_id' => $category_id
            ]);
            $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'Category Added Successfully']);
        }
        $this->load_categories();
    }
}
