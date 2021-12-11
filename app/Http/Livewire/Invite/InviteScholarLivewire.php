<?php

namespace App\Http\Livewire\Invite;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ScholarshipScholar;
use App\Models\ScholarshipScholarInvite;

class InviteScholarLivewire extends Component
{
    public $invite_id;
    
    public function hydrate()
    {
        if ( $this->is_not_scholar() ) {
            return redirect()->route('invite.scholar');
        }
    }

    public function is_not_scholar()
    {
        return Auth::guest() || !Auth::user()->is_scholar();
    }

    protected function verifyInvite($invite_id)
    {
        return ScholarshipScholarInvite::where('id', $invite_id)
            ->where('email', Auth::user()->email )
            ->whereNull('respond')
            ->first();
    }

    public function render()
    {
        return view('livewire.pages.invite-scholar.invite-scholar-livewire', [
                'invites' => $this->get_invites()
            ])
            ->extends('livewire.main.main-livewire');
    }

    protected function get_invites()
    {
        return User::find(Auth::id())->scholars_invites;
    }

    public function approve($invite_id)
    {
        if ( $this->is_not_scholar() ) 
            return;
        
        $invite = $this->verifyInvite($invite_id);
        if ( is_null($invite) ) 
            return;
        
        $invite->respond = true;
        if ( $invite->save() ) {
            ScholarshipScholar::updateOrCreate([
                'user_id' => Auth::id(),
                'category_id' => $invite->category_id
            ], [
                'acad_year'  => $invite->acad_year,
                'acad_sem'   => $invite->acad_sem,
            ]);
            $this->emitTo('add-ins.navbar-scholarship-livewire', 'refresh');
        }
    }

    public function deny_confirm($invite_id)
    {
        if ( $this->is_not_scholar() ) 
            return;
        
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
        if ( $this->is_not_scholar() || is_null($this->invite_id) ) 
            return;

        $invite = $this->verifyInvite($this->invite_id);
        if ( is_null($invite) ) 
            return;
        
        $invite->respond = false;
        $invite->save();
        $this->invite_id = null;
    }
}
