<?php

namespace App\Http\Livewire\Scholar;

use App\Models\User;
use Livewire\Component;
use App\Models\ScholarInfo;
use App\Models\ScholarCourse;
use App\Models\ScholarSchool;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ScholarEditLivewire extends Component
{
    public $user_id;
    public $user;
    public $user_info;
    public $password;

    protected $listeners = [
        'edit' => 'set_user',
        'create' => 'unset_user'
    ];

    function rules() {
        return [
            'user.firstname' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user.middlename' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user.lastname' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user.gender' => 'required|in:male,female',
            'user.phone' => "required|unique:users,phone".((isset($this->user_id))?",".$this->user_id:'')."|regex:/(09)[0-9]{9}/",
            'user.birthday' => 'required|before:10 years ago|after:100 years ago',
            'user.birthplace' => 'max:200',
            'user.address' => 'max:200',
            'user.religion' => 'max:200',
            'user.email' => "required|unique:users,email".((isset($this->user_id))?",".$this->user_id:''),
            'password' => 'required|min:9',

            'user_info.school_id' => 'exists:scholar_schools,id',
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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected function verifyUser()
    {
        if (!Auth::check() || Auth::user()->usertype != 'admin') {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount()
    {
        $this->user = new User;
        $this->user->gender = 'male';
        $this->user_info = new ScholarInfo;
    }
    
    public function set_user($user_id)
    {
        if ($this->verifyUser()) return;

        $user = User::find($user_id);
        if ( is_null($user) ) {
            return;
        }

        $this->user_id            = $user_id;
        $this->user->firstname    = $user->firstname;
        $this->user->middlename   = $user->middlename;
        $this->user->lastname     = $user->lastname;
        $this->user->gender       = $user->gender;
        $this->user->address      = $user->address;
        $this->user->birthday     = $user->birthday;
        $this->user->birthplace   = $user->birthplace;
        $this->user->religion     = $user->religion;
        $this->user->phone        = $user->phone;
        $this->user->email        = $user->email;
        $this->password           = $user->password;

        $user_info = ScholarInfo::where('user_id', $user_id)->first();
        if ( isset($user_info) ) {
            $this->user_info->school_id                     = $user_info->school_id;
            $this->user_info->course_id                     = $user_info->course_id;
            $this->user_info->year                          = $user_info->year;
            $this->user_info->semester                      = $user_info->semester;
            $this->user_info->mother_name                   = $user_info->mother_name;
            $this->user_info->mother_birthday               = $user_info->mother_birthday;
            $this->user_info->mother_occupation             = $user_info->mother_occupation;
            $this->user_info->mother_living                 = $user_info->mother_living;
            $this->user_info->mother_educational_attainment = $user_info->mother_educational_attainment;
            $this->user_info->father_name                   = $user_info->father_name;
            $this->user_info->father_birthday               = $user_info->father_birthday;
            $this->user_info->father_occupation             = $user_info->father_occupation;
            $this->user_info->father_living                 = $user_info->father_living;
            $this->user_info->father_educational_attainment = $user_info->father_educational_attainment;
        }
        
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function unset_user()
    {
        if ($this->verifyUser()) return;
        
        $this->user_id = null;
        $this->user = new User;
        $this->user->gender = 'male';
        $this->user_info = new ScholarInfo;
        $this->password = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }
    
    public function render()
    {
        return view('livewire.pages.scholar.scholar-edit-livewire', [
            'schools' => $this->get_schools(),
            'courses' => $this->get_courses(),
        ]);
    }

    protected function get_schools()
    {
        return ScholarSchool::orderBy('school')->get();
    }

    protected function get_courses()
    {
        return ScholarCourse::orderBy('course')->get();
    }
    
    public function save()
    {
        if ($this->verifyUser()) return;

        $this->validate();

        if ( isset($this->user_id) ) {
            $user = User::find($this->user_id);

            if ( is_null($user) ) {
                $this->dispatchBrowserEvent('scholar-form', ['action' => 'hide']);
                $this->emitTo('scholar.scholar-info-livewire', 'refresh');
                return;
            }
            $user->firstname    = $this->user->firstname;
            $user->middlename   = $this->user->middlename;
            $user->lastname     = $this->user->lastname;
            $user->gender       = $this->user->gender;
            $user->address      = $this->user->address;
            $user->birthday     = $this->user->birthday;
            $user->birthplace   = $this->user->birthplace;
            $user->religion     = $this->user->religion;
            $user->phone        = $this->user->phone;
            $user->email        = $this->user->email;

            $this->user = $user;
        }
        
        $this->user->usertype = 'scholar';

        $create = false;
        if (!isset($this->user->id)) {
            $create = true;
            $this->user->password = Hash::make($this->password);
        }

        $user_info = null;
        if ($this->user->save()) {
            $user_info = $this->user_info;
            $user_info = ScholarInfo::updateOrCreate(
                [
                    'user_id' => $this->user_id
                ],
                [
                    'school_id'                     => $user_info->school_id,
                    'course_id'                     => $user_info->course_id,
                    'year'                          => $user_info->year,
                    'semester'                      => $user_info->semester,
                    'mother_name'                   => $user_info->mother_name,
                    'mother_birthday'               => $user_info->mother_birthday,
                    'mother_occupation'             => $user_info->mother_occupation,
                    'mother_living'                 => $user_info->mother_living,
                    'mother_educational_attainment' => $user_info->mother_educational_attainment,
                    'father_name'                   => $user_info->father_name,
                    'father_birthday'               => $user_info->father_birthday,
                    'father_occupation'             => $user_info->father_occupation,
                    'father_living'                 => $user_info->father_living,
                    'father_educational_attainment' => $user_info->father_educational_attainment,
                ]
            );
        }

        $this->password = '';

        if( $create && $this->user){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Scholar\'s Account Created', 
                'text' => 'Scholar\'s account has been successfully created'
            ]);
            $this->emitUp('info', $this->user->id);
            $this->unset_user();
            $this->dispatchBrowserEvent('scholar-form', ['action' => 'hide']);
            return;

        } elseif (!$create && $this->user->wasChanged() || $user_info->wasChanged()){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Scholar\'s Account Updated', 
                'text' => 'Scholar\'s account has been successfully updated'
            ]);
            $this->emitTo('scholar.scholar-info-livewire', 'refresh');
            $this->unset_user();
            $this->dispatchBrowserEvent('scholar-form', ['action' => 'hide']);
            return;
            
        } elseif (!$create && !$this->user->wasChanged()){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Nothing has been changed', 
                'text' => ''
            ]);
            $this->unset_user();
            $this->dispatchBrowserEvent('scholar-form', ['action' => 'hide']);
            return;
        }
 
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'error',  
            'message' => 'Runtime error!', 
            'text' => ''
        ]);
    }
}
