<?php

namespace App\Http\Livewire\ScholarshipRequirement;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementItem;
use Carbon\Carbon;

class ScholarshipRequirementLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $scholarship_id;
    public $search;
    public $show_row = 10;
    public $promote = '';

    protected $listeners = [
        'refresh' => '$refresh',
    ];
    
    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }
    
    public function getQueryString()
    {
        return [];
    }
    
    public function mount($scholarship_id)
    {
        if ($this->verifyUser()) return;

        $this->scholarship_id = $scholarship_id;
    }
    
    public function updated($name)
    {
        if ('show_row') {
            $this->page = 1;
        }
    }

    public function render()
    {
        if ($this->verifyUser()) 
            return view('livewire.pages.scholarship-requirement.scholarship-requirement-livewire');

        $search = $this->search;
        $requirements = ScholarshipRequirement::where('scholarship_requirements.requirement', 'like', "%$search%")
            ->where('scholarship_requirements.scholarship_id', $this->scholarship_id)
            ->orderBy('scholarship_requirements.id', 'desc');
        if ($this->promote != '') {
            $requirements = $requirements->where('scholarship_requirements.promote', $this->promote);
        }
        $requirements = $requirements
            ->paginate($this->show_row);

        return view('livewire.pages.scholarship-requirement.scholarship-requirement-livewire', ['requirements' => $requirements])
            ->extends('livewire.main.main-livewire');
    }

    public function create_requirement()
    {
        $new_requirement = new ScholarshipRequirement;
        $new_requirement->scholarship_id = $this->scholarship_id;
        $new_requirement->requirement = 'Requirement Title';
        $new_requirement->description = 'Requirement Description';
        $new_requirement->promote = false;
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
        $item_Grade->item = 'Previuos Semester Grades';
        $item_Grade->note = 'Upload in PDF file';
        $item_Grade->type = 'grade';
        $item_Grade->position = $position++;
        $item_Grade->save();

        redirect()->route('requirement.edit', [$new_requirement->id]);
    }
}
