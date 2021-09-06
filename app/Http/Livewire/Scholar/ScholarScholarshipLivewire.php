<?php

namespace App\Http\Livewire\Scholar;

use Livewire\Component;
use App\Models\Scholarship;
use App\Models\ScholarshipScholar;
use Illuminate\Support\Facades\Auth;


class ScholarScholarshipLivewire extends Component
{
    public $user_id;
    public $delete_scholarship;
    
    protected function verifyUser()
    {
        if (!Auth::check() || Auth::user()->usertype != 'admin') {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount($user_id)
    {
        if ($this->verifyUser()) return;

        $this->user_id = $user_id;
    }
    
    public function render()
    {
        $scholar_scholarships = ScholarshipScholar::where('scholarship_scholars.user_id', $this->user_id)
            ->get();

        return view('livewire.pages.scholar.scholar-scholarship-livewire', ['scholar_scholarships' => $scholar_scholarships]);
    }

    public function confirm_delete($id)
    {
        if ($this->verifyUser()) return;

        $scholarship = ScholarshipScholar::find($id);
        if ( is_null($scholarship) ) {
            return;
        }
        
        $this->delete_scholarship = $id;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_scholarship', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this account!',
            'function' => "delete"
        ]);
    }

    public function delete()
    {
        if ($this->verifyUser()) return;
        
        $scholarship = ScholarshipScholar::find($this->delete_scholarship);
        if ( is_null($scholarship) ) {
            return;
        }

        if (!$scholarship->delete()) {
            return;
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Scholar\'s Scholarship Deleted', 
            'text' => 'Scholar\'s Scholarship has been successfully deleted'
        ]);
    }
}
