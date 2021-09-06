<?php

namespace App\Http\Livewire\AddIns;

use Livewire\Component;
use App\Models\Scholarship;
use Illuminate\Support\Facades\Auth;

class NavbarScholarshipLivewire extends Component
{
    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function render()
    {
        $scholarships = [];

        if (Auth::user()->usertype == 'officer') {
            $scholarships = Scholarship::select('scholarships.id', 'scholarship')
                ->join('scholarship_officers', 'scholarships.id', '=', 'scholarship_officers.scholarship_id')
                ->join('users', 'scholarship_officers.user_id', '=', 'users.id')
                ->where('users.id',  Auth::id())
                ->get();
        } else {
            $scholarships = Scholarship::select('scholarships.id', 'scholarship')
                ->join('scholarship_categories', 'scholarships.id', '=', 'scholarship_categories.scholarship_id')
                ->join('scholarship_scholars', 'scholarship_categories.id', '=', 'scholarship_scholars.category_id')
                ->join('users', 'scholarship_scholars.user_id', '=', 'users.id')
                ->where('users.id',  Auth::id())
                ->get();
        }
        
        return view('livewire.main.navbar-scholarship-livewire', ['scholarships' => $scholarships]);
    }
}
