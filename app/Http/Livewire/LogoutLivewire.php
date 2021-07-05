<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LogoutLivewire extends Component
{
    public function render()
    {
        return view('livewire.main.logout-livewire');
    }
    
    public function signout(){
        Auth::logout();

        redirect()->route('login.index');
    }
}
