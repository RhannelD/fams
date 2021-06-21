<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function render()
    {
        return view('livewire.auth.login');
    }

    public function signin()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            redirect()->route('try');
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'error',  
            'message' => '', 
            'text' => 'Email and Password does not match'
        ]);
    }
}
