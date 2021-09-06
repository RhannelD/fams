<?php

namespace App\Http\Livewire\ScholarshipOfficer;

use Livewire\Component;
use App\Models\User;
use App\Models\ScholarshipOfficer;
use App\Models\ScholarshipOfficerInvite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ScholarshipOfficerInviteLivewire extends Component
{
    public $scholarship_id;
    public $name_email;

    protected $rules = [
        'name_email' => 'required|email|unique:users,email',
    ];
    
    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }
    
    protected function verifyUserIfOfficer()
    {
        if ( Auth::user()->is_admin() )
            return false;

        $if_officer = ScholarshipOfficer::where('user_id', Auth::id())
            ->where('scholarship_id', $this->scholarship_id)
            ->exists();

        if (!$if_officer) 
            redirect()->route('index');
        
        return !$if_officer;
    }
    
    public function mount($scholarship_id)
    {
        if ($this->verifyUser()) return;
        
        $this->scholarship_id = $scholarship_id;
        if ( $this->verifyUserIfOfficer() ) return;
    }

    public function render()
    {
        return view('livewire.pages.scholarship-officer.scholarship-officer-invite-livewire', [
                'pending_invites' => $this->get_pending_invites(),
                'accepted_invites' => $this->get_accepted_invites(),
                'rejected_invites' => $this->get_rejected_invites(),
                'search_officers' => $this->get_search_name_email(),
            ]);
    }

    protected function get_pending_invites()
    {
        return ScholarshipOfficerInvite::where('scholarship_id', $this->scholarship_id)->whereNull('respond')->get();
    }

    protected function get_accepted_invites()
    {
        return ScholarshipOfficerInvite::where('scholarship_id', $this->scholarship_id)->where('respond', true)->get();
    }

    protected function get_rejected_invites()
    {
        return ScholarshipOfficerInvite::where('scholarship_id', $this->scholarship_id)->where('respond', false)->get();
    }

    protected function get_search_name_email()
    {
        if ( empty($this->name_email) )
            return null;

        $scholarship_id = $this->scholarship_id;
        return User::whereOfficer()
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
        if ($this->verifyUser()) return;
        if ( $this->verifyUserIfOfficer() ) return;

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
        
        if ( $invite->wasRecentlyCreated ) {
            session()->flash('message-success', "$email has been added to pending invites");
        }
    }

    public function cancel_invite($invite_id)
    {
        if ($this->verifyUser()) return;
        if ( $this->verifyUserIfOfficer() ) return;
        
        ScholarshipOfficerInvite::where('id', $invite_id)->delete();
    }
    
    public function cancel_all_invite_confirm()
    {
        $this->dispatchBrowserEvent('swal:confirm:delete_something', [
            'type' => 'warning',
            'message' => 'Cancel all invites?', 
            'text' => '',
            'function' => 'cancel_all_invites'
        ]);
    }
    
    public function cancel_all_invites()
    {
        if ($this->verifyUser()) return;
        if ( $this->verifyUserIfOfficer() ) return;
        
        ScholarshipOfficerInvite::where('scholarship_id', $this->scholarship_id)
            ->whereNull('respond')
            ->delete();
    }
    
    public function clear_all_accepted_invite_confirm()
    {
        $this->dispatchBrowserEvent('swal:confirm:delete_something', [
            'type' => 'info',
            'message' => 'Clear accepted invites list?', 
            'text' => 'This will only clear the list.',
            'function' => 'clear_all_accepted_invite'
        ]);
    }

    public function clear_all_accepted_invite()
    {
        if ($this->verifyUser()) return;
        if ( $this->verifyUserIfOfficer() ) return;
        
        ScholarshipOfficerInvite::where('scholarship_id', $this->scholarship_id)
            ->where('respond', true)
            ->delete();
    }
    
    public function clear_all_rejected_invite_confirm()
    {
        $this->dispatchBrowserEvent('swal:confirm:delete_something', [
            'type' => 'info',
            'message' => 'Clear rejected invites list?', 
            'text' => 'This will only clear the list.',
            'function' => 'clear_all_rejected_invite'
        ]);
    }

    public function clear_all_rejected_invite()
    {
        if ($this->verifyUser()) return;
        if ( $this->verifyUserIfOfficer() ) return;
        
        ScholarshipOfficerInvite::where('scholarship_id', $this->scholarship_id)
            ->where('respond', false)
            ->delete();
    }
    
    public function resend_all_rejected_invite_confirm()
    {
        $this->dispatchBrowserEvent('swal:confirm:delete_something', [
            'type' => 'info',
            'message' => 'Resend all rejected invites?', 
            'text' => 'This will appear at pending invites.',
            'function' => 'resend_all_rejected_invite'
        ]);
    }

    public function resend_all_rejected_invite()
    {
        if ($this->verifyUser()) return;
        if ( $this->verifyUserIfOfficer() ) return;
        
        ScholarshipOfficerInvite::where('scholarship_id', $this->scholarship_id)
            ->where('respond', false)
            ->update(['respond' => null]);
    }

    public function resend_rejected_invite($invite_id)
    {
        if ($this->verifyUser()) return;
        if ( $this->verifyUserIfOfficer() ) return;
        
        ScholarshipOfficerInvite::where('id', $invite_id)
            ->where('respond', false)
            ->update(['respond' => null]);
    }
}
