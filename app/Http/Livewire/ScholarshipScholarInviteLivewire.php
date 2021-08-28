<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ScholarshipScholarInvite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScholarshipScholarInviteLivewire extends Component
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
        return view('livewire.pages.scholarship-scholar.scholarship-scholar-invite-livewire', [
                'invites' => $this->get_scholarship_invites(),
                'search_officers' => $this->get_search_name_email(),
            ]);
    }

    protected function get_scholarship_invites()
    {
        return ScholarshipScholarInvite::whereScholarship($this->scholarship_id)->get();
    }

    protected function get_search_name_email()
    {
        if ( empty($this->name_email) )
            return null;

        $scholarship_id = $this->scholarship_id;
        return User::whereScholar()
            ->whereNameOrEmail($this->name_email)
            ->whereNotScholarOf($this->scholarship_id)
            ->whereDoesntHave('scholars_invites', function ($query) use ($scholarship_id) {
                $query->whereHas('category', function($query) use ($scholarship_id) {
                    $query->where('scholarship_id', $scholarship_id);
                });
            })
            ->limit(5)
            ->get();
    }

    public function invite_email($email)
    {
        $invite = ScholarshipScholarInvite::updateOrCreate([
                'email' => $email  
            ], [
                'category_id' => 1,
            ]);
    }

    public function cancel_invite($invite_id)
    {
        ScholarshipScholarInvite::where('id', $invite_id)->delete();
    }
}
