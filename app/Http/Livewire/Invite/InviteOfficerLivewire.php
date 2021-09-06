<?php

namespace App\Http\Livewire\Invite;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ScholarshipOfficer;
use App\Models\ScholarshipOfficerInvite;

class InviteOfficerLivewire extends Component
{
    public $invite_id;

    protected function verifyUser()
    {
        if (!Auth::check() || !Auth::user()->is_officer() ) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    protected function verifyInvite($invite_id)
    {
        return ScholarshipOfficerInvite::where('id', $invite_id)
            ->where('email', Auth::user()->email )
            ->whereNull('respond')
            ->first();
    }

    public function render()
    {
        return view('livewire.pages.invite-officer.invite-officer-livewire', [
                'invites' => $this->get_invites()
            ])
            ->extends('livewire.main.main-livewire');
    }
    
    protected function get_invites()
    {
        return User::find(Auth::id())->scholarship_invites;
    }

    public function approve($invite_id)
    {
        if ( $this->verifyUser() ) return;
        
        $invite = $this->verifyInvite($invite_id);
        if ( is_null($invite) ) 
            return;
        
        $invite->respond = true;
        if ( $invite->save() ) {
            ScholarshipOfficer::firstOrCreate([
                'user_id' => Auth::id(),
                'scholarship_id' => $invite->scholarship_id,
                'position_id' => 2,
            ]);
            $this->emitTo('add-ins.navbar-scholarship-livewire', 'refresh');
        }
    }

    public function deny_confirm($invite_id)
    {
        if ( $this->verifyUser() ) return;
        
        $invite = $this->verifyInvite($invite_id);
        if ( is_null($invite) ) 
            return;
        
        $this->invite_id = $invite_id;
        
        $this->dispatchBrowserEvent('swal:confirm:deny_invite', [
            'type' => 'warning',  
            'message' => 'Deny Invite?', 
            'text' => '',
            'function' => "deny"
        ]);
    }

    public function deny()
    {
        if ( $this->verifyUser() ) return;
        if ( is_null($this->invite_id) ) return;

        $invite = $this->verifyInvite($this->invite_id);
        if ( is_null($invite) ) 
            return;
        
        $invite->respond = false;
        $invite->save();
        $this->invite_id = null;
    }
}
