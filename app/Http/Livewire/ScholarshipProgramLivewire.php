<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Scholarship;
use Illuminate\Support\Facades\Auth;

class ScholarshipProgramLivewire extends Component
{
    public $tab = 'home';
    public $scholarship;

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('dashboard');
            return true;
        }
        return false;
    }

    
    public function mount($id, $tab='home')
    {
        $scholarship = Scholarship::find($id);
        if(!$scholarship){
            return redirect()->route('login.index');
        }
        $this->scholarship = $scholarship;
        $this->tab = $tab;
        $this->update_url();
    }

    public function render()
    {
        return view('livewire.pages.scholarship-program.scholarship-program-livewire')
            ->extends('livewire.main.main-livewire');
    }

    public function changetab($tab)
    {
        if ($this->verifyUser()) return;

        $this->tab = $tab;
        $this->update_url();
    }
    
    protected function update_url(){
        $this->emit('url_update', route('scholarship.program', [$this->scholarship->id, $this->tab]));
    }
}
