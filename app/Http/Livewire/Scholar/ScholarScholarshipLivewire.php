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
    
    public function hydrate()
    {
        if ( $this->is_not_admin() ) {
            return redirect()->route('scholar', ['user' => $this->user_id]);
        }
    }

    protected function is_not_admin()
    {
        return Auth::guest() || !Auth::user()->is_admin();
    }

    public function mount($user_id)
    {
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
        if ($this->is_not_admin()) 
            return;

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
        if ($this->is_not_admin()) 
            return;
        
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
