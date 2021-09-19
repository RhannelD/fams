<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordLivewire extends Component
{
    public $verified = false;

    public $password;
    public $new_password;
    public $confirm_password;

    protected $listeners = [
        'reset_values' => 'reset_values',
    ];

    protected $rules = [
        'password' => 'required|min:9',
        'new_password' => 'required|min:9|different:password',
        'confirm_password' => 'required|min:9|same:new_password',
    ];
    
    protected $messages = [
        'new_password.different' => 'The old password and new password must be different.',
    ];

    public function render()
    {
        return view('livewire.pages.user.change-password-livewire', [
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

    public function change_password()
    {
        $this->validate();
        if ( !$this->get_verified() ) 
            return;

        $user = User::find(Auth::id());
        if ( is_null($user) ) 
            return;
        
        $user->password = Hash::make($this->new_password);
        if ( $user->save() ) {
            $this->dispatchBrowserEvent('change-password-form', ['action' => 'hide']);
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Password Change Successfully', 
                'text' => ''
            ]);
            $this->reset_values();
        }
    }

    public function reset_values()
    {
        $this->password         = '';
        $this->new_password     = '';
        $this->confirm_password = '';
        $this->verified         = false;
    }
}
