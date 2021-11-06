<?php

namespace App\Http\Livewire\Course;

use Livewire\Component;
use App\Models\ScholarCourse;
use Illuminate\Support\Facades\Auth;

class CourseEditLivewire extends Component
{
    public $course_id;
    public $course;

    protected $listeners = [
        'edit'   => 'set_course',
        'create' => 'unset_course'
    ];

    function rules() {
        return [
            'course.course' => "required|unique:scholar_courses,course".((isset($this->course_id))? ",{$this->course_id}": ''),
        ];
    }

    public function mount()
    {
        $this->course = new ScholarCourse;
    }

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('viewAny', [ScholarCourse::class]) ) {
            $this->emitUp('refresh');
        }
    }

    public function set_course($course_id)
    {
        $course = ScholarCourse::find($course_id);
        if ( Auth::guest() || Auth::user()->cannot('update', $course) )
            return;

        $this->course_id = $course_id;
        $this->course->course = $course->course;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function unset_course()
    {
        if ( Auth::guest() || Auth::user()->cannot('create', [ScholarCourse::class]) ) 
            return;
        
        $this->course_id = null;
        $this->course = new ScholarCourse;
        $this->resetErrorBag();
        $this->resetValidation();
    }
    
    public function render()
    {
        return view('livewire.course.course-edit-livewire');
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        $course = $this->course;
        if ( isset($this->course_id) ) {
            $course = ScholarCourse::find($this->course_id);
            if ( Auth::guest() || Auth::user()->cannot('update', $course) ) 
                return;

            $course->course = $this->course->course;
        } elseif ( Auth::guest() || Auth::user()->cannot('create', [ScholarCourse::class]) ) {
            return;
        }

        if ( !$course->save() ) 
            return;

        if ( $course->wasRecentlyCreated ) {
            $this->unset_course();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Course Created', 
                'text' => 'Course has been successfully created'
            ]);
            $this->emitUp('refresh');
            $this->dispatchBrowserEvent('course-modal', ['action' => 'hide']);
            return;

        } elseif (!$course->wasRecentlyCreated && $course->wasChanged()){
            $this->unset_course();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Course Updated', 
                'text' => 'Course has been successfully updated'
            ]);
            $this->emitUp('refresh');
            $this->dispatchBrowserEvent('course-modal', ['action' => 'hide']);
            return;
            
        } elseif (!$course->wasRecentlyCreated && !$course->wasChanged()){
            $this->unset_course();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Nothing has been changed', 
                'text' => ''
            ]);
            $this->dispatchBrowserEvent('course-modal', ['action' => 'hide']);
            return;
        }
    }
}
