<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Scholarship;
use App\Models\ScholarshipOfficer;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;

class ScholarshipLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $scholarship_program_id;

    public $scholarship_id;
    public $scholarship;

    protected $listeners = [
        'refresh' => '$refresh',
    ];
    
    function rules() {
        return [
            'scholarship' => 'required|min:5|max:255',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('dashboard');
            return true;
        }
        return false;
    }


    public function render()
    {
        $search = $this->search;

        $scholarships = Scholarship::select('scholarships.*')
            ->where('scholarship', 'like', "%$search%");

        if (Auth::user()->usertype != 'admin') {
            if (Auth::user()->usertype == 'officer') {
                $scholarships = $scholarships
                    ->join('scholarship_officers', 'scholarships.id', '=', 'scholarship_officers.scholarship_id')
                    ->where('scholarship_officers.user_id', Auth::id());
            }
            if (Auth::user()->usertype == 'scholar') {
                $scholarships = $scholarships
                    ->join('scholarship_categories', 'scholarships.id', '=', 'scholarship_categories.scholarship_id')
                    ->join('scholarship_scholars', 'scholarship_categories.id', '=', 'scholarship_scholars.category_id')
                    ->where('scholarship_scholars.user_id', Auth::id());
            }
        }   
        
        $scholarships = $scholarships->paginate(15);

        $scholarship_program = Scholarship::find($this->scholarship_program_id);

        return view('livewire.pages.scholarship.scholarship-livewire', [
                'scholarships' => $scholarships,
                'scholarship_program' =>$scholarship_program,
            ])
            ->extends('livewire.main.main-livewire');
    }
    
    public function info($id)
    {
        if ($this->verifyUser()) return;

        if ( is_null(Scholarship::find($id)) ) {
            return;
        }

        $this->scholarship_program_id = $id;

        $this->dispatchBrowserEvent('scholarship-info', ['action' => 'show']);
    }

    public function confirm_delete()
    {
        if ($this->verifyUser()) return;
        
        if ( is_null(Scholarship::find($this->scholarship_program_id)) ) {
            return;
        }

        if ($this->cannotbedeleted()) {
            return;
        }

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_scholarship', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this Scholarship Program!',
            'function' => "delete"
        ]);
    }

    public function delete()
    {
        if ($this->verifyUser()) return;
        
        if ( is_null(Scholarship::find($this->scholarship_program_id)) ) {
            return;
        }

        if ($this->cannotbedeleted()) {
            return;
        }
        
        $user = Scholarship::findorfail($this->scholarship_program_id);
        
        if (!$user->delete()) {
            return;
        }

        $this->scholarship_program = null;

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Scholarship Program Deleted', 
            'text' => 'Scholarship Program has been successfully deleted'
        ]);

        $this->dispatchBrowserEvent('scholarship-info', ['action' => 'hide']);
    }

    protected function cannotbedeleted(){
        $checker = ScholarshipOfficer::select('id')
            ->where('scholarship_id', $this->scholarship_program_id)
            ->exists();

        if ($checker) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Cannot be Deleted', 
                'text' => 'Scholarship has Already Scholarship Officers'
            ]);
        }
        
        return $checker;
    }

    public function nullinputs()
    {
        $this->scholarship_id = null;
        $this->scholarship    = null;
    }

    public function edit()
    {
        if ($this->verifyUser()) return;

        $data = Scholarship::find($this->scholarship_program_id);
        if ( is_null($data) ) {
            $this->nullinputs();
            return;
        }

        $this->scholarship_id = $data->id;
        $this->scholarship    = $data->scholarship;
    }
    
    public function save()
    {
        if ($this->verifyUser()) return;
        
        $data = $this->validate();
        
        $scholarship = Scholarship::updateOrCreate(
            ['id' => $this->scholarship_id],
            $data
        );

        if($scholarship->wasRecentlyCreated){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Scholarship Program Created', 
                'text' => 'Scholarship Program has been successfully created'
            ]);
            $this->info($scholarship->id);
            $this->dispatchBrowserEvent('scholarship-form', ['action' => 'hide']);
            return;
        } elseif (!$scholarship->wasRecentlyCreated && $scholarship->wasChanged()){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Scholarship Program Updated', 
                'text' => 'Scholarship Program has been successfully updated'
            ]);
            $this->info($this->scholarship_id);
            $this->dispatchBrowserEvent('scholarship-form', ['action' => 'hide']);
            return;
        } elseif (!$scholarship->wasRecentlyCreated && !$scholarship->wasChanged()){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Nothing has been changed', 
                'text' => ''
            ]);
            $this->edit($this->scholarship_id);
            return;
        }
 
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'error',  
            'message' => 'Runtime error!', 
            'text' => ''
        ]);
    }
}
