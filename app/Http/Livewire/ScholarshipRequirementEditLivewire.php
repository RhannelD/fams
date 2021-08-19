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
    public $requirement_id;
    public $requirement;

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

    protected function verifyScholarship()
    {
        $scholarship_requirement = ScholarshipRequirement::find($this->requirement_id);
        if ( is_null($scholarship_requirement) ) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount($id)
    {
        if ($this->verifyUser()) return;

        $this->requirement = new ScholarshipRequirement;
        $this->requirement_id = $id;

        if ($this->verifyScholarship()) return;
    }

    public function render()
    {
        $scholarship_requirement = ScholarshipRequirement::find($this->requirement_id);

        if ( isset($scholarship_requirement) ) {
            $this->requirement->requirement = $scholarship_requirement->requirement;
            $this->requirement->description = $scholarship_requirement->description;
            $this->requirement->promote     = $scholarship_requirement->promote;
        }

        $categories = ScholarshipCategory::select('scholarship_categories.*', 'scholarship_requirement_categories.category_id')
            ->join('scholarship_requirements', 'scholarship_categories.scholarship_id', '=', 'scholarship_requirements.scholarship_id')
            ->leftJoin('scholarship_requirement_categories', function($join) {
                    $join->on('scholarship_categories.id', '=', 'scholarship_requirement_categories.category_id');
                    $join->on('scholarship_requirements.id', '=', 'scholarship_requirement_categories.requirement_id');
                })
            ->where('scholarship_requirements.id', $this->requirement_id)
            ->get();

        return view('livewire.pages.scholarship-requirement-edit.scholarship-requirement-edit-livewire', [
                'scholarship_requirement' => $scholarship_requirement,
                'categories' => $categories
            ])
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
        if ($this->verifyScholarship()) return;

        $position = ScholarshipRequirementItem::where('requirement_id', $this->requirement_id)
            ->max('position');

        $item = new ScholarshipRequirementItem;
        $item->requirement_id = $this->requirement_id;
        $item->item = 'Enter Item Title Here';
        $item->type = 'question';
        $item->note = '';
        $item->position = $position+1;
        $item->save();
    }

    public function update_requirement_order($list)
    {
        if ($this->verifyUser()) return;
        if ($this->verifyScholarship()) return;

        foreach ($list as $item) {
            $requirement_item = ScholarshipRequirementItem::find($item['value']);
            if ( isset($requirement_item) ) {
                $requirement_item->update(['position' => $item['order']]);
            } 
        }
    }

    public function save()
    {
        if ($this->verifyUser()) return;

        $scholarship_requirement = ScholarshipRequirement::find($this->requirement_id);
        if ( is_null($scholarship_requirement) ) {
            return;
        }

        $this->validate();

        $scholarship_requirement->requirement = $this->requirement->requirement;
        $scholarship_requirement->description = $this->requirement->description;
        $scholarship_requirement->promote     = $this->requirement->promote;
        $scholarship_requirement->save();
    }
    
    public function save_all()
    {
        if ($this->verifyUser()) return;

        $this->save();
        $this->emit('save_all');
    }

    public function toggle_category($category_id)
    {
        if ($this->verifyUser()) return;
        
        $requirement_catergory = ScholarshipRequirementCategory::where('requirement_id', $this->requirement_id)
            ->where('category_id', $category_id)
            ->first();
        if ( is_null($requirement_catergory) ) {
            return;
        }

        if ( $requirement_catergory->delete() ) {
            $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'Category Removed Successfully']);
        } else {
            ScholarshipRequirementCategory::firstOrCreate([
                'requirement_id' =>  $this->requirement_id,
                'category_id' => $category_id
            ]);
            $this->dispatchBrowserEvent('toggle_enable_form', ['message' => 'Category Added Successfully']);
        }
    }
}
