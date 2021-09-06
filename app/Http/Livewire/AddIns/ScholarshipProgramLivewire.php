<?php

namespace App\Http\Livewire\AddIns;

use Livewire\Component;
use App\Models\Scholarship;
use Illuminate\Support\Facades\Auth;

class ScholarshipProgramLivewire extends Component
{
    public $scholarship_id;

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
    }

    public function render()
    {
        $scholarship = Scholarship::find($this->scholarship_id);

        return view('livewire.pages.scholarship-program.scholarship-program-livewire', [
                'scholarship' => $scholarship,
            ]);
    }
}
