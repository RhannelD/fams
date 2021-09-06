<?php

namespace App\Http\Livewire\ScholarshipOfficer;

use Livewire\Component;
use App\Models\User;
use App\Models\ScholarshipOfficerInvite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScholarshipOfficerInviteLinkLivewire extends Component
{
    public $invite_token;

    public $is_verify_email = false;
    public $verification_code; // Temporary public
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
        'user.birthday' => 'required|before:5 years ago',
        'user.birthplace' => 'required|max:200',
        'user.religion' => 'max:200',
        'user.email' => "required|unique:users,email",
        'password' => 'required|min:9',
        'password_confirm' => 'required|min:9|same:password',
    ];

    public function mount($invite_token)
    {
        $this->invite_token = $invite_token;
        $this->user = new User;
        $this->user->gender = 'male';
        $this->verification_code = rand(111111, 999999);
    }

    public function hydrate()
    {
        $this->user->email = $this->get_invite()? $this->get_invite()->email: null;
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
        // Resend code via email
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

        if ( $this->verification_code == $this->code ) {
            $this->is_verify_email = !$this->is_verify_email;
            return;
        }
        $this->addError('code', 'Code does not matched!');
    }

    public function save()
    {
        $this->validate();

        if ( $this->user->save() ) {
            $this->user = new User;
            $this->user_id = true;
        }
    }
}
