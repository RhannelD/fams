<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Scholarship;

class ScholarshipCategoryLivewire extends Component
{
    public $scholarship_id;
    
    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount($scholarship_id)
    {
        // if ($this->verifyUser()) return;

        $this->scholarship_id = $scholarship_id;
    }
    
    public function render()
    {
        $scholarship = Scholarship::find($this->scholarship_id);

        return view('livewire.pages.scholarship-category.scholarship-category-livewire', [
                'scholarship' => $scholarship
            ])
            ->extends('livewire.main.main-livewire');
    }
}
