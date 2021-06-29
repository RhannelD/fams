<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Scholarship;

class ScholarshipLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $scholarship_program;
    public $scholarship_id_delete;

    public $scholarship_id;
    public $scholarship;

    
    function rules() {
        return [
            'scholarship' => 'required|min:5|max:255',
        ];
    }


    public function render()
    {
        $search = $this->search;

        $scholarships = Scholarship::where('scholarship', 'like', "%$search%")->paginate(15);

        return view('livewire.pages.scholarship.scholarship-livewire', ['scholarships' => $scholarships]);
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function info($id)
    {
        $this->scholarship_program = Scholarship::find($id);

        $this->dispatchBrowserEvent('scholarship-info', ['action' => 'show']);
    }

    public function confirm_delete($id)
    {
        $this->scholarship_id_delete = $id;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_scholarship', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this Scholarship Program!',
            'function' => "delete"
        ]);
    }

    public function delete()
    {
        $user = Scholarship::findorfail($this->scholarship_id_delete);
        
        if (!$user->delete()) {
            return;
        }

        $this->scholarship_program = null;

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Scholar Account Deleted', 
            'text' => 'Scholar account has been successfully deleted'
        ]);

        $this->dispatchBrowserEvent('scholarship-info', ['action' => 'hide']);
    }

    public function nullinputs()
    {
        $this->scholarship_id = null;
        $this->scholarship    = null;
    }

    public function edit($id)
    {
        $data = Scholarship::findorfail($id);

        $this->scholarship_id = $data->id;
        $this->scholarship    = $data->scholarship;
    }
    
    public function save()
    {
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
