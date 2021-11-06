<?php

namespace App\Http\Livewire\Course;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ScholarCourse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourseLivewire extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public $search = '';

    
    protected $listeners = [
        'refresh'   => '$refresh',
    ];

    public function mount()
    {
        $this->authorize('viewAny', [ScholarCourse::class]);
    }

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('viewAny', [ScholarCourse::class]) ) {
            return redirect()->route('course');
        }
    }

    public function render()
    {
        return view('livewire.pages.course.course-livewire', [
                'courses' => $this->get_courses(),
            ])
            ->extends('livewire.main.main-livewire');
    }

    public function get_courses()
    {
        $search = trim($this->search);
        return ScholarCourse::when(!empty($search), function ($query) use ($search) {
                $query->where('course', 'like', "%{$search}%");
            })
            ->orderBy('course')
            ->paginate(15);
    }
    
    public function updated($propertyName)
    {
        if ( $propertyName == 'search' ) {
            $this->page = 0;
        }
    }

    public function delete_confirm($course_id)
    {
        $course = ScholarCourse::find($course_id);
        if ( Auth::guest() || Auth::user()->cannot('delete', $course) || $this->cannot_delete_course($course) ) 
            return;

        $this->dispatchBrowserEvent('swal:confirm:delete_course', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this course!',
            'course_id' => $course_id,
        ]);
    }

    public function delete_course($course_id)
    {
        $course = ScholarCourse::find($course_id);
        if ( Auth::guest() || Auth::user()->cannot('delete', $course) || $this->cannot_delete_course($course) ) 
            return;

        if ( $course->delete() ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Course Deleted', 
                'text' => 'Course has been successfully deleted'
            ]);
        }
    }

    protected function cannot_delete_course($course)
    {
        if ( !$course ) 
            return true;
        
        if ( $course->scholars->count() > 0 ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Cannot be Deleted', 
                'text' => 'Course has Scholars already'
            ]);
            return true;
        }
        return false;
    }
}
