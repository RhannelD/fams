<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;
use App\Traits\YearSemTrait;
use Illuminate\Support\Facades\Auth;

class MyAccountLivewire extends Component
{
    use YearSemTrait;
    
    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function hydrate()
    {
        if ( Auth::guest() ) {
            return redirect()->route('my.account'); 
        }
    }

    public function render()
    {
        return view('livewire.pages.user.my-account-livewire', [
                'user' => $this->get_user(),
            ])
            ->extends('livewire.main.main-livewire');
    }

    protected function get_user()
    {
        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();

        return User::where('id', Auth::id())
            ->with([
                'scholarship_scholars' => function ($query) use ($acad_year, $acad_sem) {
                    $query->where('acad_year', $acad_year)
                    ->where('acad_sem', $acad_sem);
                }
            ])
            ->first();
    }
}
