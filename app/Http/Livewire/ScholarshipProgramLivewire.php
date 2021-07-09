<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Scholarship;

class ScholarshipProgramLivewire extends Component
{
    public $tab = 'home';
    public $scholarship;

    public function mount($id)
    {
        $scholarship = Scholarship::find($id);
        if(!$scholarship){
            return redirect()->route('login.index');
        }
        $this->scholarship = $scholarship;
    }

    public function render()
    {
        return view('livewire.pages.scholarship-program.scholarship-program-livewire')
            ->extends('livewire.main.main-livewire');
    }

    public function changetab($tab)
    {
        $this->tab = $tab;
    }
}
