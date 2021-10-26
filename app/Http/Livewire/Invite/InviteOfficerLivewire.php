<?php

namespace App\Http\Livewire\Invite;

use App\Models\User;
use Livewire\Component;
use App\Models\ScholarshipOfficer;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipOfficerInvite;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InviteOfficerLivewire extends Component
{
    use AuthorizesRequests;
    
    public $invite_id;

    protected function verifyInvite($invite_id)
    {
        return ScholarshipOfficerInvite::where('id', $invite_id)
            ->where('email', Auth::user()->email )
            ->whereNull('respond')
            ->first();
    }
    
    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('viewUserInvite', [ScholarshipOfficerInvite::class]) ) {
            return redirect()->route('invite.officer');
        }
    }

    public function mount()
    {
        $this->authorize('viewUserInvite', [ScholarshipOfficerInvite::class]);
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
        $invite = $this->verifyInvite($invite_id);
        if ( Auth::guest() || Auth::user()->cannot('update', $invite) ) 
            return;
        
        $invite->respond = true;
        if ( $invite->save() ) {
            ScholarshipOfficer::firstOrCreate([
                'user_id' => Auth::id(),
                'scholarship_id' => $invite->scholarship_id,
                'position_id' => $invite->scholarship->officers->count()? 2: 1,
            ]);
            $this->emitTo('add-ins.navbar-scholarship-livewire', 'refresh');
        }
    }

    public function deny_confirm($invite_id)
    {
        $invite = $this->verifyInvite($invite_id);
        if ( Auth::guest() || Auth::user()->cannot('update', $invite) ) 
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
        if ( is_null($this->invite_id) ) return;

        $invite = $this->verifyInvite($this->invite_id);
        if ( Auth::guest() || Auth::user()->cannot('update', $invite) ) 
            return;
        
        $invite->respond = false;
        $invite->save();
        $this->invite_id = null;
    }
}
