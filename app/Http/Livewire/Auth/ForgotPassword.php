<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Mail\PasswordResetMail;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class ForgotPassword extends Component
{
    public $email;

    protected $rules = [
        'email' => 'required|email|exists:users,email',
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
        $this->validate();

        $token = Str::random(60);

        PasswordReset::where('email', $this->email)->delete();
        
        $passwordreset = new PasswordReset;
        $passwordreset->email = $this->email;
        $passwordreset->token = Hash::make($token);
        if ($passwordreset->save()) {
            $this->send_mail($token);
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
            session()->flash('message-success', 'Password reset link has been sent to your email.');
        } catch (\Exception $e) {
            session()->flash('message-error', "Email has not been sent!");
        }
    }
}
