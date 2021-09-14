<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordResetLivewire extends Component
{
    public $token;
    public $email;
    
    public $new_password;
    public $confirm_password;

    
    protected $rules = [
        'email' => 'required|email|exists:users,email',
        'new_password' => 'required|min:9',
        'confirm_password' => 'required|min:9|same:new_password',
    ];
    
    public function mount($token, $email){
        $this->token = $token;
        $this->email = $email;
    }

    public function render()
    {
        return view('livewire.auth.password-reset-livewire', [
                'password_reset' => $this->get_password_reset() !== null
            ])
            ->extends('layouts.empty');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected function get_password_reset()
    {
        $user =  User::where('email', $this->email)->exists();
        if ( !$user ) 
            return;
        
        $password_reset = PasswordReset::where('email', $this->email)
            ->first();
        if ( is_null($password_reset) ) 
            return;
        
        return Hash::check($this->token, $password_reset->token)? $password_reset: null;
    }

    public function submit()
    {
        $this->validate();

        $password_reset = $this->get_password_reset();
        if ( is_null($password_reset) ) 
            return;

        $user =  User::where('email', $this->email)->first();
        if ( Hash::check($this->new_password, $user->password) ) 
            return $this->addError('new_password', 'This is your old password.');

        $user->password = Hash::make($this->new_password);

        if ( $user->save() ) {    
            PasswordReset::where('email', $this->email)->delete();
            
            if (Auth::attempt(['email' => $this->email, 'password' => $this->new_password])) {
                if (Auth::user()->usertype == 'scholar') {
                    return redirect()->route('scholarship');
                } else {
                    return redirect()->route('dashboard');
                }
            }
        }
    }
}
