<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Scholarship;
use App\Models\ScholarshipOfficer;
use Illuminate\Support\Facades\Auth;

class OfficerScholarshipLivewire extends Component
{
    public $user_id;
    public $scholarships;
    public $delete_scholarship;
    
    protected function verifyUser()
    {
        if (!Auth::check() || Auth::user()->usertype != 'admin') {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount($id)
    {
        $this->user_id = $id;

        $this->load_scholarships();
    }
    
    public function load_scholarships()
    {
        $this->scholarships = ScholarshipOfficer::select('scholarship_officers.*', 'scholarships.scholarship', 'officer_positions.position')
            ->join('scholarships', 'scholarship_officers.scholarship_id', '=', 'scholarships.id')
            ->join('officer_positions', 'scholarship_officers.position_id', '=', 'officer_positions.id')
            ->where('user_id', $this->user_id)
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.officer.officer-scholarship-livewire');
    }

    public function confirm_delete($id)
    {
        if ( $this->verifyUser() ) return;

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
        if ( $this->verifyUser() ) return;

        $scholarship = ScholarshipOfficer::where('user_id', $this->user_id)
            ->where('id', $this->delete_scholarship);

        if ( !$scholarship->delete() ) {
            return;
        }

        $this->load_scholarships();

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Officer\'s Access Deleted', 
            'text' => 'Officer\'s scholarship access has been successfully deleted'
        ]);
    }
}
