<?php

namespace App\Http\Livewire\Officer;

use Livewire\Component;
use App\Models\Scholarship;
use App\Models\ScholarshipOfficer;
use Illuminate\Support\Facades\Auth;

class OfficerScholarshipLivewire extends Component
{
    public $user_id;
    public $delete_scholarship;
    
    public function hydrate()
    {
        if ( $this->is_not_admin() ) {
            $this->emitTo('officer.officer-livewire', 'refresh');
        }
    }

    protected function is_not_admin()
    {
        return Auth::guest() || Auth::user()->cannot('admin', [ScholarshipOfficer::class]);
    }

    public function mount($user_id)
    {
        $this->user_id = $user_id;
    }
    
    public function render()
    {
        $scholarships = ScholarshipOfficer::where('user_id', $this->user_id)->get();

        return view('livewire.pages.officer.officer-scholarship-livewire', ['scholarships' => $scholarships]);
    }

    public function confirm_delete($id)
    {
        if ( $this->is_not_admin() ) 
            return;

        $this->delete_scholarship = $id;

        $this->dispatchBrowserEvent('swal:confirm:delete_scholarship', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'Deleting Officer\'s access to scholarship!',
            'function' => 'delete'
        ]);
    }

    public function delete()
    {
        if ( $this->is_not_admin() ) 
            return;

        $scholarship = ScholarshipOfficer::where('user_id', $this->user_id)
            ->where('id', $this->delete_scholarship);

        if ( !$scholarship->delete() ) {
            return;
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Officer\'s Access Deleted', 
            'text' => 'Officer\'s scholarship access has been successfully deleted'
        ]);
    }
}
