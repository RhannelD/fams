<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use App\Rules\SrCode;
use Livewire\Component;
use App\Models\ScholarInfo;
use App\Models\ScholarCourse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ScholarVerificationCodeMail;

class SignUpLivewire extends Component
{
    public $user_id = false;
    public $user;
    public $user_info;
    public $password;
    public $password_confirm;

    public $verification_code;
    public $code;

    function rules() {
        return [
            'user.firstname' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user.middlename' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user.lastname' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user.gender' => 'required|in:male,female',
            'user.phone' => "required|unique:users,phone|regex:/(09)[0-9]\d{8}$/",
            'user.birthday' => 'required|before:10 years ago|after:100 years ago',
            'user.birthplace' => 'required|max:200',
            'user.religion' => 'max:200',
            'user.address' => 'max:200',
            'user.email' => "required|unique:users,email|regex:/^[a-zA-Z0-9._%+-]+\@g.batstate-u.edu.ph$/i",
            'password' => 'required|min:9',
            'password_confirm' => 'required|min:9|same:password',
    
            'user_info.srcode' => ['required', 'unique:scholar_infos,srcode', new SrCode],
            'user_info.course_id' => 'exists:scholar_courses,id',
            'user_info.year' => 'required|max:10|min:1',
            'user_info.semester' => 'required|max:3|min:1',
            'user_info.mother_name' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user_info.mother_birthday' => 'required|before:20 years ago|after:100 years ago',
            'user_info.mother_occupation' => 'required|max:200',
            'user_info.mother_living' => 'required',
            'user_info.mother_educational_attainment' => 'required|max:200',
            'user_info.father_name' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user_info.father_birthday' => 'required|before:20 years ago|after:100 years ago',
            'user_info.father_occupation' => 'required|max:200',
            'user_info.father_living' => 'required',
            'user_info.father_educational_attainment' => 'required|max:200',
        ];
    }

    protected $messages = [
        'user_info.course_id.exists' => 'The selected Course is invalid.',
        'user_info.year.required' => 'Invalid year level.',
        'user_info.year.min' => 'Invalid year level.',
        'user_info.year.max' => 'Invalid year level.',
        'user_info.semester.required' => 'Invalid semester.',
        'user_info.semester.min' => 'Invalid semester.',
        'user_info.semester.max' => 'Invalid semester.',
        'user_info.srcode.required' => 'The SR-Code field is required.',
        'user_info.srcode.unique' => 'The SR-Code has already been taken.',
    ];
 
    public function mount(){
        $this->user = new User;
        $this->user_info = new ScholarInfo;
        $this->user->gender = 'male';
    }

    public function render()
    {
        return view('livewire.auth.sign-up-livewire', [
                'courses' => $this->get_courses(),
            ])
            ->extends('layouts.empty');
    }

    protected function get_courses()
    {
        return ScholarCourse::orderBy('course')->get();
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
    }

    protected function send_code()
    {
        $this->validateOnly('user.email');

        $details = [
            'code' => $this->verification_code,
        ];

        try {
            Mail::to($this->user->email)->send(new ScholarVerificationCodeMail($details));
            session()->flash('message-success', 'The verification code has been sent on your email.');
        } catch (\Exception $e) {
            session()->flash('message-error', "Email has not been sent!");
        }
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

        $email = $this->user->email;

        $this->user->password = Hash::make($this->password);
        $this->user->usertype = 'scholar';

        if ( !$this->user->save() ) 
            return;
        
        $user_id = $this->user->id;
        $this->user_info->user_id = $user_id;
        $this->user_info->save();
        
        $this->user = new User;
        $this->user->gender = 'male';
        $this->user_info = new ScholarInfo;

        if (Auth::attempt(['email' => $email, 'password' => $this->password])) {
            if (Auth::user()->usertype == 'scholar') {
                redirect()->route('scholar.scholarship');
            } else {
                redirect()->route('scholarship');
            }
            return;
        }

        redirect()->route('index');
    }
}
