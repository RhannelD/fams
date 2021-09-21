<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;
use App\Models\EmailUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UpdateEmailVerificationCodeMail;

class ChangeEmailLivewire extends Component
{
    public $verified = false;
    public $valid_email = false;

    public $password;
    public $email;
    public $code;

    protected $listeners = [
        'reset_values' => 'reset_values',
    ];

    protected $rules = [
        'password' => 'required|min:9',
        'email' => "required|unique:users,email",
        'code' => 'required|min:6|max:6',
    ];
    
    protected $messages = [
        'code.required' => 'The code cannot be empty.',
        'code.min' => 'The code must be 6 characters',
        'code.max' => 'The code must be 6 characters',
    ];

    public function render()
    {
        return view('livewire.pages.user.change-email-livewire', [
            'verified' => $this->get_verified(),
            'valid_email' => $this->get_verified_email()
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

    protected function get_verified_email()
    {
        if ( empty($this->email) || !$this->valid_email ) 
            return false;
        
        if ( !User::where('email', $this->email)->exists() ) 
            return true;
        
        $this->valid_email = false;
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

    public function save()
    {
        $this->validateOnly('email');

        EmailUpdate::updateOrCreate([
            'user_id' => Auth::id()
        ],[
            'code' => rand(111111, 999999)
        ]);
        $this->valid_email = true;
        $this->send_code();
    }

    public function verify_email()
    {
        $this->validate();

        $verify_email = EmailUpdate::where('user_id', Auth::id())
            ->where('code', $this->code)
            ->exists();
        if ( $verify_email ) {
            $user = Auth::user();
            $user->email = $this->email;
            if ( $user->save() ) {
                $this->reset_values();
                $this->emitUp('refresh');
                $this->dispatchBrowserEvent('update-email-form', ['action' => 'hide']);
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',  
                    'message' => 'Email Updated Successfully', 
                    'text' => ''
                ]);
                EmailUpdate::where('user_id', Auth::id())->delete();
            }
        }
    }

    public function send_code()
    {
        $email_update = EmailUpdate::where('user_id', Auth::id())->first();
        if ( is_null($email_update) ) {
            return;
        }

        $details = [
            'code' => $email_update->code
        ];

        try {
            Mail::to(Auth::user()->email)->send(new UpdateEmailVerificationCodeMail($details));
            session()->flash('message-success', 'The verification code has been sent on your email.');
        } catch (\Exception $e) {
            session()->flash('message-error', "Email has not been sent!");
        }
    }
    
    public function reset_values()
    {
        $this->password = '';
        $this->email = '';
        $this->code = '';
        $this->verified = false;
        $this->valid_email = false;
    }
}
