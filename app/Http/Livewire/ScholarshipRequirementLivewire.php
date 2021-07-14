<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirement;

class ScholarshipRequirementLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $scholarship_id;
    public $search;
    public $show_row = 10;
    public $promote = '';

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
        $requirements = DB::table('scholarship_requirements')
            ->where('scholarship_requirements.requirement', 'like', "%$search%")
            ->where('scholarship_requirements.scholarship_id', $this->scholarship_id)
            ->orderBy('scholarship_requirements.end_at', 'desc');
        if ($this->promote != '') {
            $requirements = $requirements->where('scholarship_requirements.promote', $this->promote);
        }
        $requirements = $requirements
            ->paginate($this->show_row);

        return view('livewire.pages.scholarship-requirement.scholarship-requirement-livewire', ['requirements' => $requirements]);
    }

    public function view_requirement($requirement_id){
        $this->emit('view_requirement', $requirement_id);
    }
}
