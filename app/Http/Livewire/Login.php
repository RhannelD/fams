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
        'password' => 'required|min:9',
    ];

    protected $messages = [
        'email.required' => 'Email Address cannot be empty.',
        'email.email' => 'Email Address format is not valid.',
        'password.required' => 'Password cannot be empty.',
        'password.min' => 'Password must be at least 9 characters.',
    ];


    public function render()
    {
        return view('livewire.auth.login');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function signin()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            redirect()->route('main');
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'error',  
            'message' => '', 
            'text' => 'Email and Password does not match'
        ]);
    }
}
