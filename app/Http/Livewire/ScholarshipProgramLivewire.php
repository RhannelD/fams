<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Scholarship;
use App\Models\ScholarshipRequirement;
use Illuminate\Support\Facades\Auth;

class ScholarshipProgramLivewire extends Component
{
    public $tab = 'home';
    public $scholarship_id;
    public $requirement_id;

    protected $listeners = [
        'view_requirement' => 'view_requirement',
        'changetab' => 'changetab'
    ];

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function verifyScholarship()
    {
        if( !Scholarship::find($this->scholarship_id) ){
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount($id, $tab='home', $requirement_id=null)
    {
        $this->scholarship_id = $id;

        if ($this->verifyScholarship()) return;

        $this->tab = $tab;

        if (!empty($requirement_id)) {
            if(!$this->verify_requirement($requirement_id)){
                $requirement_id = null;
            }
        }

        $this->requirement_id = $requirement_id;
        $this->update_url($requirement_id);
    }

    public function render()
    {
        $scholarship = Scholarship::find($this->scholarship_id);

        return view('livewire.pages.scholarship-program.scholarship-program-livewire', [
                'scholarship' => $scholarship,
            ])
            ->extends('livewire.main.main-livewire');
    }

    public function changetab($tab)
    {
        if ($this->verifyUser()) return;
        if ($this->verifyScholarship()) return;

        $this->tab = $tab;
        $this->requirement_id = null;
        $this->update_url();
    }
    
    public function view_requirement($requirement_id)
    {
        if ($this->verifyUser()) return;
        if ($this->verifyScholarship()) return;

        if(!$this->verify_requirement($requirement_id)){
            $requirement_id = null;
        }

        $this->tab = 'requirement';
        $this->requirement_id = $requirement_id;
        $this->update_url($requirement_id);
    }

    protected function verify_requirement($requirement_id)
    {
        $requirement = ScholarshipRequirement::where('scholarship_requirements.id', $requirement_id)
            ->where('scholarship_requirements.scholarship_id', $this->scholarship_id)
            ->first();
        
        return ($requirement);
    }

    protected function update_url($requirement_id = null){
        $this->emit('url_update', route('scholarship.program', [$this->scholarship_id, $this->tab, $requirement_id]));
    }
}
