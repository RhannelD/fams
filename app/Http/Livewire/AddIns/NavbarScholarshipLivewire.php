<?php

namespace App\Http\Livewire\AddIns;

use Livewire\Component;
use App\Models\Scholarship;
use App\Traits\YearSemTrait;
use Illuminate\Support\Facades\Auth;

class NavbarScholarshipLivewire extends Component
{
    use YearSemTrait;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.main.navbar-scholarship-livewire', ['scholarships' => $this->get_scholarships()]);
    }

    protected function get_scholarships()
    {
        
        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();

        return Scholarship::when( Auth::user()->is_scholar(), function ($query) use ($acad_year, $acad_sem) {
                $query->whereHas('categories', function ($query) use ($acad_year, $acad_sem) {
                    $query->whereHas('scholars', function ($query) use ($acad_year, $acad_sem) {
                        $query->where('user_id', Auth::id())
                            ->where('acad_year', $acad_year)
                            ->where('acad_sem', $acad_sem);
                    });
                });
            })
            ->orderBy('scholarship')
            ->get();
    }
    
}
