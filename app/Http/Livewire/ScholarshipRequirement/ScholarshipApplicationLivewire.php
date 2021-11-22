<?php

namespace App\Http\Livewire\ScholarshipRequirement;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Scholarship;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarshipApplicationLivewire extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public $scholarship_id;
    public $search;
    public $show_row = 10;

    public function getQueryString()
    {
        return [];
    }

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
        abort_if(!$this->get_scholarship(), '404');
        $this->authorize('viewAny', [ScholarshipRequirement::class]);
    }

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('viewAny', [ScholarshipRequirement::class]) ) {
            return redirect()->route('scholarship.requirement', [$this->scholarship_id]);
        }
    }

    public function render()
    {
        return view('livewire.pages.scholarship-requirement.scholarship-application-livewire', [
                'requirements' => $this->get_requirements()
            ])
            ->extends('livewire.main.scholarship');
    }
    
    public function updated($name)
    {
        $this->page = 1;
    }

    protected function get_scholarship()
    {
        return Scholarship::find($this->scholarship_id);
    }

    protected function get_requirements()
    {
        return ScholarshipRequirement::where('requirement', 'like', "%{$this->search}%")
            ->where('scholarship_id', $this->scholarship_id)
            ->where('promote', true)
            ->orderBy('id', 'desc')
            ->paginate($this->show_row);
    }

    public function create_requirement()
    {
        if ( Auth::guest() || Auth::user()->cannot('create', [ScholarshipRequirement::class]) ) 
            return;

        $scholarship = $this->get_scholarship();
        if ( !isset($scholarship) || !$scholarship->categories->count() ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',  
                'message' => '', 
                'text' => 'Please create one category first!'
            ]);
            return;
        }

        $new_requirement = new ScholarshipRequirement;
        $new_requirement->scholarship_id = $this->scholarship_id;
        $new_requirement->requirement = 'Application Form Title';
        $new_requirement->description = 'Application Description';
        $new_requirement->promote = true;
        $new_requirement->enable = null;
        $new_requirement->start_at = Carbon::now()->format('Y-m-d H:i:s');
        $new_requirement->end_at = Carbon::now()->addDay()->format('Y-m-d H:i:s');
        $new_requirement->save();

        $position = 1;

        $item_COR = new ScholarshipRequirementItem;
        $item_COR->requirement_id  = $new_requirement->id;
        $item_COR->item = 'Certificate of Registration';
        $item_COR->note = 'Upload in PDF file';
        $item_COR->type = 'cor';
        $item_COR->position = $position++;
        $item_COR->save();
        
        $item_Grade = new ScholarshipRequirementItem;
        $item_Grade->requirement_id  = $new_requirement->id;
        $item_Grade->item = 'No. of units taken';
        $item_Grade->note = 'Specify the current no. of units taken';
        $item_Grade->type = 'units';
        $item_Grade->position = $position++;
        $item_Grade->save();
        
        $item_Grade = new ScholarshipRequirementItem;
        $item_Grade->requirement_id  = $new_requirement->id;
        $item_Grade->item = 'Previous Semester Grades';
        $item_Grade->note = 'Upload in PDF file';
        $item_Grade->type = 'grade';
        $item_Grade->position = $position++;
        $item_Grade->save();

        $item_Grade = new ScholarshipRequirementItem;
        $item_Grade->requirement_id  = $new_requirement->id;
        $item_Grade->item = 'GWA of previous semester';
        $item_Grade->note = 'GWA in 1, 1.25, ..., 5 format only';
        $item_Grade->type = 'gwa';
        $item_Grade->position = $position++;
        $item_Grade->save();

        $category = new ScholarshipRequirementCategory;
        $category->requirement_id  = $new_requirement->id;
        $category->category_id  = $scholarship->categories[0]->id;
        $category->save();

        redirect()->route('scholarship.requirement.edit', [$new_requirement->id]);
    }
}
