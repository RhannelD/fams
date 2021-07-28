<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Scholarship;
use App\Models\ScholarshipScholar;
use Illuminate\Support\Facades\Auth;


class ScholarScholarshipLivewire extends Component
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
    
    public function render()
    {
        return view('livewire.pages.scholar.scholar-scholarship-livewire');
    }

    public function load_scholarships()
    {
        if ($this->verifyUser()) return;

        $scholar_scholarships = ScholarshipScholar::select('scholarship_scholars.id', 'scholarship', 'category', 'amount', 'scholarship_scholars.created_at')
            ->join('scholarship_categories', 'scholarship_scholars.category_id', '=', 'scholarship_categories.id')
            ->join('scholarships', 'scholarships.id', '=', 'scholarship_categories.scholarship_id')
            ->where('scholarship_scholars.user_id', $this->user_id)
            ->get();

        $this->scholarships = $scholar_scholarships;
    }

    public function confirm_delete($id)
    {
        if ($this->verifyUser()) return;
        
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

        if (!$scholarship->delete()) {
            return;
        }
        
        $this->load_scholarships();

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Scholar\'s Scholarship Deleted', 
            'text' => 'Scholar\'s Scholarship has been successfully deleted'
        ]);
    }
}
