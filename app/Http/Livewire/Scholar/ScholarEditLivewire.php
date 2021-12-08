<?php

namespace App\Http\Livewire\Scholar;

use App\Models\User;
use App\Rules\SrCode;
use Livewire\Component;
use App\Models\ScholarInfo;
use App\Models\ScholarCourse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ScholarEditLivewire extends Component
{
    public $user_id;
    public $user;
    public $user_info;
    public $password;

    protected $listeners = [
        'edit'   => 'set_user',
        'create' => 'unset_user'
    ];

    function rules() {
        $user = User::find($this->user_id);
        $scholar_info_id = (isset($user) && isset($user->scholar_info))? $user->scholar_info->id: null;

        return [
            'user.firstname' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user.middlename' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user.lastname' => 'required|regex:/^[a-z ,.\'-]+$/i',
            'user.gender' => 'required|in:male,female',
            'user.phone' => "required|unique:users,phone".((isset($this->user_id))?",".$this->user_id:'')."|regex:/(09)[0-9]\d{8}$/",
            'user.birthday' => 'required|before:10 years ago|after:100 years ago',
            'user.birthplace' => 'max:200',
            'user.barangay' => 'required|max:200',
            'user.municipality' => 'required|max:200',
            'user.province' => 'required|max:200',
            'user.religion' => 'max:200',
            'user.email' => "required|unique:users,email".((isset($this->user_id))?",".$this->user_id:'')."|regex:/^[a-zA-Z0-9._%+-]+\@g.batstate-u.edu.ph$/i",
            'password' => 'required|min:9',

            'user_info.srcode' => ['required', 'unique:scholar_infos,srcode'.((isset($scholar_info_id))?",".$scholar_info_id:''), new SrCode],
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

    public function hydrate()
    {
        if ( $this->is_not_admin() ) {
            return redirect()->route('scholar', ['user' => $this->user_id]);
        }
    }

    protected function is_not_admin()
    {
        return Auth::guest() || !Auth::user()->is_admin();
    }

    public function mount()
    {
        $this->user = new User;
        $this->user->gender = 'male';
        $this->user_info = new ScholarInfo;
    }
    
    public function set_user($user_id)
    {
        if ($this->is_not_admin()) return;

        $user = User::find($user_id);
        if ( is_null($user) ) {
            return;
        }

        $this->user_id  = $user_id;
        $this->user     = $user->replicate();
        $this->password = $user->password;

        $user_info = ScholarInfo::where('user_id', $user_id)->first();
        if ( isset($user_info) ) {
            $this->user_info = $user_info->replicate();
        }
        
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function unset_user()
    {
        if ($this->is_not_admin()) return;
        
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
            'courses' => $this->get_courses(),
        ]);
    }

    protected function get_courses()
    {
        return ScholarCourse::orderBy('course')->get();
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        if ($this->is_not_admin()) return;

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
            $user->barangay     = $this->user->barangay;
            $user->municipality = $this->user->municipality;
            $user->province     = $this->user->province;
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
                    'srcode'                        => $user_info->srcode,
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
