<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScholarshipOfficerInviteLivewire extends Component
{
    public $scholarship_id;
    public $name_email;

    protected $rules = [
        'name_email' => 'required|email',
    ];
    
    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }
    
    public function mount($scholarship_id)
    {
        if ($this->verifyUser()) return;
        
        $this->scholarship_id = $scholarship_id;
    }
    
    public function render()
    {
        return view('livewire.pages.scholarship-officer.scholarship-officer-invite-livewire', [
                'search_officers' => $this->get_search_name_email()
            ]);
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected function get_search_name_email()
    {
        if ( empty($this->name_email) )
            return null;

        return User::whereOfficer('usertype', 'officer')
            ->whereNameOrEmail($this->name_email)
            ->whereNotOfficerOf($this->scholarship_id)
            ->limit(5)
            ->get();
    }
}
