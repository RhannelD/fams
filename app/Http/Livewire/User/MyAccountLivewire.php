<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MyAccountLivewire extends Component
{
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
                'user' => Auth::user()
            ])
            ->extends('livewire.main.main-livewire');
    }
}
