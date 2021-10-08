<?php

namespace App\Http\Livewire\AddIns;

use App\Models\User;
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
        return view('livewire.pages.scholarship-program.scholarship-program-livewire', [
                'scholarship' => $this->get_scholarship(),
                'is_scholar' => $this->get_if_user_is_scholar(),
            ]);
    }

    protected function get_scholarship()
    {
        return Scholarship::find($this->scholarship_id);
    }

    protected function get_if_user_is_scholar()
    {
        $scholarship_id = $this->scholarship_id;
        return User::where('id', Auth::id())
            ->where(function($query) use ($scholarship_id) {
                $query->whereAdmin()
                    ->orWhere(function($query) use ($scholarship_id) {
                        $query->whereOfficerOf($scholarship_id);
                    })
                    ->orWhere(function($query) use ($scholarship_id) {
                        $query->whereScholarOf($scholarship_id);
                    });
            })
            ->exists();
    }
}
