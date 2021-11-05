<?php

namespace App\Http\Livewire\AddIns;

use App\Models\User;
use Livewire\Component;
use App\Models\Scholarship;
use Illuminate\Support\Facades\Auth;

class ScholarshipProgramLivewire extends Component
{
    public $scholarship_id;
    public $active;

    public function mount($scholarship_id, $active = null)
    {
        $this->scholarship_id = $scholarship_id;
        $this->active = $active;
    }

    public function render()
    {
        return view('livewire.pages.scholarship-program.scholarship-program-livewire', [
                'scholarship' => $this->get_scholarship(),
            ]);
    }

    protected function get_scholarship()
    {
        return Scholarship::find($this->scholarship_id);
    }
}
