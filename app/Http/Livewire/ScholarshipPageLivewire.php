<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ScholarshipPageLivewire extends Component
{
    
    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('dashboard');
            return true;
        }
        return false;
    }
    
    public function render()
    {
        return view('livewire.pages.scholarship-page-livewire.scholarship-page-livewire');
    }
}
