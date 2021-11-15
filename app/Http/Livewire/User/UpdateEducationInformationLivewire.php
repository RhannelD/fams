<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use App\Rules\SrCode;
use Livewire\Component;
use App\Models\ScholarInfo;
use App\Models\ScholarCourse;
use Illuminate\Support\Facades\Auth;

class UpdateEducationInformationLivewire extends Component
{
    public $user_info;

    protected $listeners = [
        'reset_values' => 'reset_values',
    ];

    function rules()
    {
        $user = User::find(Auth::id());
        $scholar_info_id = (isset($user) && isset($user->scholar_info))? $user->scholar_info->id: null;

        return [
            'user_info.srcode' => ['required', 'unique:scholar_infos,srcode'.((isset($scholar_info_id))?",".$scholar_info_id:''), new SrCode],
            'user_info.course_id' => 'exists:scholar_courses,id',
            'user_info.year' => 'required|max:10|min:1',
            'user_info.semester' => 'required|max:3|min:1',
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

    public function mount()
    {
        $this->reset_values();
    }

    public function hydrate()
    {
        if ( Auth::guest() || !Auth::user()->is_scholar() ) {
            $this->dispatchBrowserEvent('remove:modal-backdrop');
            $this->emitUp('refresh');
            return;
        }
    }

    public function render()
    {
        return view('livewire.pages.user.update-education-information-livewire', [
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

    public function reset_values()
    {
        if ( Auth::check() ) {
            $user_info = ScholarInfo::where('user_id', Auth::id())->first();
            $this->user_info =  new ScholarInfo;
            $this->user_info->srcode    = $user_info->srcode;
            $this->user_info->course_id = $user_info->course_id;
            $this->user_info->year      = $user_info->year;
            $this->user_info->semester  = $user_info->semester;
        }
    }

    public function save()
    {
        if ( Auth::guest() || !Auth::user()->is_scholar() ) 
            return;

        $this->validate();

        $user_info = ScholarInfo::where('user_id', Auth::id())->first();
        $user_info->srcode    = $this->user_info->srcode;
        $user_info->course_id = $this->user_info->course_id;
        $user_info->year      = $this->user_info->year;
        $user_info->semester  = $this->user_info->semester;

        $user_info->save();
        if ( $user_info->wasChanged() ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Family information updated.', 
                'text' => ''
            ]);
        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Nothing has been changed', 
                'text' => ''
            ]);
        }
        
        $this->reset_values();
        $this->dispatchBrowserEvent('update-education-info-form', ['action' => 'hide']);
        $this->emitUp('refresh');
    }
}
