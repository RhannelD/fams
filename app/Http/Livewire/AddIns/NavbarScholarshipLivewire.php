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
        return view('livewire.main.navbar-scholarship-livewire', ['scholarships' => $this->get_scholarships()]);
    }

    protected function get_scholarships()
    {
        return Scholarship::when( Auth::user()->is_officer(), function ($query) {
                $query->whereHas('officers', function ($query) {
                    $query->where('user_id', Auth::id());
                });
            })
            ->when( Auth::user()->is_scholar(), function ($query) {
                $query->whereHas('categories', function ($query) {
                    $query->whereHas('scholars', function ($query) {
                        $query->where('user_id', Auth::id());
                    });
                });
            })
            ->orderBy('scholarship')
            ->get();
    }
    
}
