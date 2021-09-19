<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangeEmailLivewire extends Component
{
    public $verified = false;

    public $password;
    public $email;

    protected $rules = [
        'password' => 'required|min:9',
        'email' => "required|unique:users,email",
    ];
    
    public function render()
    {
        return view('livewire.pages.user.change-email-livewire', [
            'verified' => $this->get_verified()
        ]);
    }
    
    protected function get_verified()
    {
        if ( empty($this->password) || !$this->verified ) 
            return false;
        
        if ( Hash::check($this->password, Auth::user()->password) ) 
            return true;
        
        $this->addError('password', 'Wrong Password');
        $this->verified = false;
        return false;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function verify()
    {
        $this->validateOnly('password');
        $this->verified = true;
    }

    
}
