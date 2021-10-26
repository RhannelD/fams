<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\ScholarInfo;
use App\Models\ScholarCourse;
use App\Models\ScholarSchool;
use Illuminate\Support\Facades\Auth;

class UpdateEducationInformationLivewire extends Component
{
    public $user_info;

    protected $listeners = [
        'reset_values' => 'reset_values',
    ];

    protected $rules = [
        'user_info.school_id' => 'exists:scholar_schools,id',
        'user_info.course_id' => 'exists:scholar_courses,id',
        'user_info.year' => 'required|max:10|min:1',
        'user_info.semester' => 'required|max:3|min:1',
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
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function reset_values()
    {
        if ( Auth::check() ) {
            $user_info = ScholarInfo::where('user_id', Auth::id())->first();
            $this->user_info =  new ScholarInfo;
            $this->user_info->school_id = $user_info->school_id;
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
        $user_info->school_id = $this->user_info->school_id;
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
