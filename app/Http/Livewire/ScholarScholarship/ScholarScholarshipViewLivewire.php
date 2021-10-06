<?php

namespace App\Http\Livewire\ScholarScholarship;

use Livewire\Component;
use App\Models\Scholarship;
use Illuminate\Support\Facades\Auth;

class ScholarScholarshipViewLivewire extends Component
{
    public function render()
    {
        return view('livewire.pages.scholar-scholarship.scholar-scholarship-view-livewire', [
                'scholarships' => $this->get_scholarships(),
            ])
            ->extends('livewire.main.main-livewire');
    }

    protected function get_scholarships()
    {
        return Scholarship::with(['categories' => function ($query) {
                $query->whereHas('scholars', function ($query) {
                    $query->where('user_id', Auth::id());
                });
            }])
            ->whereHas('categories', function ($query) {
                $query->whereHas('scholars', function ($query) {
                    $query->where('user_id', Auth::id());
                });
            })
            ->get();
    }
}
