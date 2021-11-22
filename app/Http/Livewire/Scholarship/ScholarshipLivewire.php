<?php

namespace App\Http\Livewire\Scholarship;

use Livewire\Component;
use App\Models\Scholarship;
use App\Models\ScholarshipScholar;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarshipLivewire extends Component
{
    use AuthorizesRequests;

    public $search = '';
    public $scholarship_program_id;

    public $scholarship_id;
    public $scholarship;
    public $link;

    protected $listeners = [
        'refresh' => '$refresh',
    ];
    
    function rules() {
        return [
            'scholarship' => 'required|min:5|max:255',
            'link' => 'active_url',
        ];
    }

    public function hydrate()
    {
        if ( Auth::guest() ) {
            $this->dispatchBrowserEvent('remove:modal-backdrop');
        }
        if ( $this->is_not_officer() ) {
            return redirect()->route('scholarship');
        }
    }

    public function is_not_officer()
    {
        return Auth::guest() || Auth::user()->cannot('viewAny', [Scholarship::class]);
    }

    public function mount()
    {
        $this->authorize('viewAny', [Scholarship::class]);
    }

    public function render()
    {
        return view('livewire.pages.scholarship.scholarship-livewire', [
                'scholarships' => $this-> get_scholarships(),
            ])
            ->extends('livewire.main.main-livewire');
    }

    protected function get_scholarships()
    {
        return Scholarship::where('scholarship', 'like', "%{$this->search}%")
            ->orderBy('scholarship')
            ->get();
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function info($id)
    {
        if ( Auth::guest() || Auth::user()->cannot('view', Scholarship::find($id)) ) 
            return;

        $this->scholarship_program_id = $id;

        $this->dispatchBrowserEvent('scholarship-info', ['action' => 'show']);
    }

    public function confirm_delete($scholarship_program_id)
    {
        $this->scholarship_program_id = $scholarship_program_id;
        if ( Auth::guest() || Auth::user()->cannot('delete', Scholarship::find($this->scholarship_program_id)) ) 
            return;
        
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
        if ( Auth::guest() || Auth::user()->cannot('delete', Scholarship::find($this->scholarship_program_id)) ) 
            return;
        
        if ($this->cannotbedeleted()) 
            return;
        
        $user = Scholarship::findorfail($this->scholarship_program_id);
        
        if (!$user->delete()) 
            return;

        $this->scholarship_program = null;

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Scholarship Program Deleted', 
            'text' => 'Scholarship Program has been successfully deleted'
        ]);

        $this->dispatchBrowserEvent('scholarship-info', ['action' => 'hide']);
    }

    protected function cannotbedeleted(){
        $scholarship_program_id = $this->scholarship_program_id;
        $checker = ScholarshipScholar::whereHas('category', function ($query) {
                $query->where('scholarship_id', $this->scholarship_program_id);
            })->exists();

        if ($checker) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',  
                'message' => 'Cannot be Deleted', 
                'text' => 'Scholarship has Already Scholars'
            ]);
        }
        
        return $checker;
    }

    public function nullinputs()
    {
        $this->scholarship_id = null;
        $this->scholarship    = null;
        $this->link           = null;
    }

    public function edit($scholarship_program_id)
    {
        $this->scholarship_program_id = $scholarship_program_id;
        $scholarship = Scholarship::find($scholarship_program_id);
        if ( Auth::guest() || Auth::user()->cannot('update', $scholarship) ) {
            $this->nullinputs();
            return;
        }

        $this->scholarship_id = $scholarship->id;
        $this->scholarship    = $scholarship->scholarship;
        $this->link           = $scholarship->link;
    }
    
    public function save()
    {
        $scholarship = Scholarship::find($this->scholarship_id);
        if ( isset($this->scholarship_id) && ( Auth::guest() || Auth::user()->cannot('update', $scholarship) ) ) 
            return;

        if ( is_null($this->scholarship_id) && ( Auth::guest() || Auth::user()->cannot('create', [Scholarship::class]) ) ) 
            return;
        
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
            session()->flash("border-success-{$scholarship->id}", 'success');
            return;
        } elseif (!$scholarship->wasRecentlyCreated && $scholarship->wasChanged()){
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Scholarship Program Updated', 
                'text' => 'Scholarship Program has been successfully updated'
            ]);
            $this->info($this->scholarship_id);
            $this->dispatchBrowserEvent('scholarship-form', ['action' => 'hide']);
            session()->flash("border-success-{$scholarship->id}", 'primary');
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
