<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPassword extends Component
{
    public $email;
    public $sent = false;
    
    public $verify_code = false;
    public $code;
    public $new_password;
    public $confirm_password;

    protected $rules = [
        'email' => 'required|email|exists:users,email|regex:/^[a-zA-Z0-9._%+-]+\@g.batstate-u.edu.ph$/i',
        'code' => 'required|min:6|max:6',
        'new_password' => 'required|min:9',
        'confirm_password' => 'required|min:9|same:new_password',
    ];

    protected $messages = [
        'email.required' => 'The Email Address cannot be empty.',
        'email.email' => 'The Email Address format is not valid.',
        'email.exists' => 'This email address does not exist.',
    ];
    
    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function search()
    {
        $this->validateOnly('email');

        $token = rand(111111, 999999);

        PasswordReset::where('email', $this->email)->delete();
        
        $passwordreset = new PasswordReset;
        $passwordreset->email = $this->email;
        $passwordreset->token = Hash::make($token);
        if ($passwordreset->save()) {
            $this->sent = $this->send_mail($token);
        }
    }
    
    protected function send_mail($token)
    {
        $details = [
            'email' => $this->email,
            'token' => $token,
        ];

        try {
            Mail::to($this->email)->send(new PasswordResetMail($details));
            session()->flash('message-success', 'Password reset code has been sent to your email.');
            return true;
        } catch (\Exception $e) {
            session()->flash('message-error', "Email has not been sent!");
        }
        return false;
    }

    protected function get_user()
    {
        return User::where('email', $this->email)->first();
    }

    public function verify_code()
    {
        $this->validateOnly('code');

        $this->verify_code = $this->get_password_reset();
        if ( !$this->verify_code ) {
            $this->addError('code', 'Entered code is incorrect!');
        }
    }
    
    protected function get_password_reset()
    {
        $user = $this->get_user();
        if ( !$user ) 
            return false;
        
        $password_reset = PasswordReset::where('email', $this->email)
            ->first();
        if ( is_null($password_reset) ) 
            return false;
        
        return Hash::check($this->code, $password_reset->token)? $password_reset: null;
    }

    public function save()
    {
        $this->validate();

        $password_reset = $this->get_password_reset();
        if ( !($password_reset) ) 
            return;

        $user =  $this->get_user();
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
