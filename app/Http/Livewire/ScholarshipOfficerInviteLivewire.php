<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ScholarshipOfficerInvite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
                'invites' => $this->get_scholarship_invites(),
                'search_officers' => $this->get_search_name_email(),
            ]);
    }

    protected function get_scholarship_invites()
    {
        return ScholarshipOfficerInvite::where('scholarship_id', $this->scholarship_id)->get();
    }

    protected function get_search_name_email()
    {
        if ( empty($this->name_email) )
            return null;

        $scholarship_id = $this->scholarship_id;
        return User::whereOfficer('usertype', 'officer')
            ->whereNameOrEmail($this->name_email)
            ->whereNotOfficerOf($this->scholarship_id)
            ->whereDoesntHave('scholarship_invites', function ($query) use ($scholarship_id) {
                $query->where('scholarship_id', $scholarship_id);
            })
            ->limit(5)
            ->get();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if ( ScholarshipOfficerInvite::where('email', $this->name_email)->where('scholarship_id', $this->scholarship_id)->exists() ) {
            $this->addError('name_email', '.');
        }
    }

    public function invite_email($email)
    {
        $token = null;
        do {
            $token = Str::random(rand(200, 250));
        } while ( ScholarshipOfficerInvite::whereToken('token', $token)->exists() );

        $invite = ScholarshipOfficerInvite::updateOrCreate([
                'scholarship_id' => $this->scholarship_id,
                'email' => $email  
            ], [
                'token' => $token
            ]);
    }

    public function cancel_invite($invite_id)
    {
        ScholarshipOfficerInvite::where('id', $invite_id)->delete();
    }
}
