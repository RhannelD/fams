<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SignUpLivewire extends Component
{
    public $user_id = false;
    public $user;
    public $password;
    public $password_confirm;

    public $verification_code; // Temporary public
    public $code;

    protected $rules = [
        'user.firstname' => 'required|regex:/^[a-z ,.\'-]+$/i',
        'user.middlename' => 'required|regex:/^[a-z ,.\'-]+$/i',
        'user.lastname' => 'required|regex:/^[a-z ,.\'-]+$/i',
        'user.gender' => 'required|in:male,female',
        'user.phone' => "required|unique:users,phone|regex:/(09)[0-9]{9}/",
        'user.birthday' => 'required|before:5 years ago',
        'user.birthplace' => 'required|max:200',
        'user.religion' => 'max:200',
        'user.email' => "required|unique:users,email",
        'password' => 'required|min:9',
        'password_confirm' => 'required|min:9|same:password',
    ];

    public function mount(){
        $this->user = new User;
        $this->user->gender = 'male';
    }

    public function render()
    {
        return view('livewire.auth.sign-up-livewire')
            ->extends('layouts.empty');
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function next()
    {
        $this->validate();

        $tab = 'verify';
        $this->change_tab($tab);
        $this->verification_code = rand(111111, 999999);
        $this->send_code();
        session()->flash('message-success', 'The verification code has been sent on your email.');
    }

    public function back()
    {
        $tab = 'form';
        $this->change_tab($tab);
    }

    protected function change_tab($tab)
    {
        $this->dispatchBrowserEvent('change:tab', [
            'tab' => $tab,  
        ]);  
    }
    
    public function resend_code()
    {
        $this->send_code();
        session()->flash('message-success', 'The verification code has been re-sent on your email.');
    }

    protected function send_code()
    {
        // Resend code via email
    }

    public function save()
    {
        $this->validate();
        $this->validate(
            ['code' => 'required|min:6|max:6'],
            [
                'code.required' => 'The code cannot be empty.',
                'code.min' => 'The code must be 6 characters',
                'code.max' => 'The code must be 6 characters',
            ]
        );

        if ( $this->verification_code != $this->code ) {
            $this->addError('code', 'Code does not matched!');
            return;
        }

        $this->user->password = Hash::make($this->password);
        $this->user->usertype = 'scholar';
        $this->user->save();
        $this->user = new User;
        $this->user->gender = 'male';
        redirect()->route('index');
    }
}
