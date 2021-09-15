<?php

namespace App\Http\Livewire\ScholarshipOfficer;

use Livewire\Component;
use App\Mail\OfficerVerificationCodeMail;
use App\Models\User;
use App\Models\ScholarshipOfficerInvite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class ScholarshipOfficerInviteLinkLivewire extends Component
{
    public $invite_token;

    public $is_verify_email = false;
    public $code;

    public $user_id = false;
    public $disable_email = true;

    public $user;
    public $password;
    public $password_confirm;

    protected $rules = [
        'user.firstname' => 'required|regex:/^[a-z ,.\'-]+$/i',
        'user.middlename' => 'required|regex:/^[a-z ,.\'-]+$/i',
        'user.lastname' => 'required|regex:/^[a-z ,.\'-]+$/i',
        'user.gender' => 'required|in:male,female',
        'user.phone' => "required|unique:users,phone|regex:/(09)[0-9]{9}/",
        'user.birthday' => 'required|before:10 years ago|after:100 years ago',
        'user.birthplace' => 'required|max:200',
        'user.religion' => 'max:200',
        'user.email' => "required|unique:users,email",
        'password' => 'required|min:9',
        'password_confirm' => 'required|min:9|same:password',
    ];

    public function mount($invite_token)
    {
        $this->invite_token = $invite_token;
        if ( is_null($this->get_invite()) ) 
            return redirect()->route('index');
        
        $this->user = new User;
        $this->user->gender = 'male';
        $this->set_code();
        if (!Auth::check()) {
            $this->resend_code();
        }
    }

    protected function set_code()
    {
        $invitation =  $this->get_invite();
        if ( is_null($invitation) ) 
            return;

        $invitation->code = strval(rand(111111, 999999));
        $invitation->save();
    }

    public function hydrate()
    {
        $invite =  $this->get_invite();
        $this->user->email = $invite? $invite->email: null;
        $this->user->usertype = 'officer';
    }

    public function render()
    {
        $this->user->email = $this->get_invite()->email;
        return view('livewire.pages.invite.scholarship-officer-invite-link-livewire', [
                'invite' => $this->get_invite(),
            ])
            ->extends('layouts.empty');
    }

    protected function get_invite()
    {
        return ScholarshipOfficerInvite::whereToken($this->invite_token)->first();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resend_code()
    {
        $invitation =  $this->get_invite();
        if ( is_null($invitation) )
            return;
            
        $details = [
            'code' => $invitation->code,
        ];

        try {
            Mail::to($invitation->email)->send(new OfficerVerificationCodeMail($details));
            session()->flash('message-success', 'The verification code has been sent on your email.');
        } catch (\Exception $e) {
            session()->flash('message-error', "Email has not been sent!");
        }
    }

    public function verify_code()
    {
        $this->validate(
            ['code' => 'required|min:6|max:6'],
            [
                'code.required' => 'The code cannot be empty.',
                'code.min' => 'The code must be 6 characters',
                'code.max' => 'The code must be 6 characters',
            ]
        );

        $invite = ScholarshipOfficerInvite::whereToken($this->invite_token)
            ->where('code', $this->code)
            ->first();

        if ( isset($invite) ) {
            $this->is_verify_email = !$this->is_verify_email;
            return;
        }
        $this->addError('code', 'Code does not matched!');
    }

    public function save()
    {
        $this->validate();

        $this->user->password = Hash::make($this->password);
        if ( $this->user->save() ) {
            if (Auth::attempt(['email' => $this->user->email, 'password' => $this->password])) {
                if (Auth::user()->usertype == 'scholar') {
                    redirect()->route('scholarship');
                } else {
                    redirect()->route('dashboard');
                }
                return;
            }    

            $this->user = new User;
            $this->user_id = true;
        }
    }
}
