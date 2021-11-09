<?php

namespace App\Http\Livewire\ScholarshipScholar;

use App\Models\User;
use Livewire\Component;
use App\Models\ScholarshipOfficer;
use Illuminate\Support\Facades\DB;
use App\Mail\ScholarInvitationMail;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Jobs\ProcessScholarInviteJob;
use App\Models\ScholarshipScholarInvite;

class ScholarshipScholarInviteLivewire extends Component
{
    public $scholarship_id;
    public $name_email;
    public $category_id = 0;

    protected $rules = [
        'name_email' => 'required|email|unique:users,email',
        'category_id' => 'required|exists:scholarship_categories,id',
    ];
    
    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('viewAny', [ScholarshipScholarInvite::class, $this->scholarship_id]) ) {
            return redirect()->route('scholarship.scholar', [$this->scholarship_id]);
        }
    }

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;

        $categories = $this->get_categories();
        if ( isset($categories[0]->id) ) {
            $this->category_id = $categories[0]->id;
        }
    }

    public function render()
    {
        return view('livewire.pages.scholarship-scholar.scholarship-scholar-invite-livewire', [
                'categories' => $this->get_categories(),
                'pending_invites' => $this->get_pending_invites(),
                'accepted_invites' => $this->get_accepted_invites(),
                'rejected_invites' => $this->get_rejected_invites(),
                'search_officers' => $this->get_search_name_email(),
            ]);
    }

    protected function get_categories()
    {
        return ScholarshipCategory::where('scholarship_id', $this->scholarship_id)->get();
    }

    protected function get_pending_invites()
    {
        return ScholarshipScholarInvite::whereScholarship($this->scholarship_id)->whereNull('respond')->get();
    }

    protected function get_accepted_invites()
    {
        return ScholarshipScholarInvite::whereScholarship($this->scholarship_id)->where('respond', true)->get();
    }

    protected function get_rejected_invites()
    {
        return ScholarshipScholarInvite::whereScholarship($this->scholarship_id)->where('respond', false)->get();
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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->if_invited();
    }

    protected function if_invited()
    {
        $scholarship_id = $this->scholarship_id;
        $invite = ScholarshipScholarInvite::where('email', $this->name_email)
            ->whereHas('category', function($query) use ($scholarship_id) {
                $query->where('scholarship_id', $scholarship_id);
            })
            ->exists();
        if ( $invite ) {
            $this->addError('name_email', '.');
        }
    }

    public function invite_email($email)
    {
        if ( Auth::guest() || Auth::user()->cannot('create', [ScholarshipScholarInvite::class, $this->scholarship_id]) ) 
            return;

        $this->validateOnly('category_id');

        $invite = ScholarshipScholarInvite::updateOrCreate([
                'email' => $email,
                'category_id' => $this->category_id,
            ], [
            ]);

        if ( $invite->wasRecentlyCreated ) {
            $this->send_mail($invite->id);
            session()->flash('message-success', "Sending invite to $email");
        }
        $this->if_invited();
    }

    public function cancel_invite($invite_id)
    {
        $invite = ScholarshipScholarInvite::find($invite_id);
        if ( Auth::check() && Auth::user()->can('delete', $invite) ) 
            $invite->delete();
    }
    
    public function cancel_all_invite_confirm()
    {
        if ( $this->if_can_delete_many_invite() ) {
            $this->dispatchBrowserEvent('swal:confirm:delete_something', [
                'type' => 'warning',
                'message' => 'Cancel all invites?', 
                'text' => '',
                'function' => 'cancel_all_invites'
            ]);
        }
    }
    
    protected function if_can_delete_many_invite()
    {
        return Auth::check() && Auth::user()->can('deleteMany', [ScholarshipScholarInvite::class, $this->scholarship_id]);
    }

    public function cancel_all_invites()
    {
        if ( $this->if_can_delete_many_invite() ) {
            ScholarshipScholarInvite::whereScholarship($this->scholarship_id)
                ->whereNull('respond')
                ->delete();
        }
    }
    
    public function clear_all_accepted_invite_confirm()
    {
        if ( $this->if_can_delete_many_invite() ) {
            $this->dispatchBrowserEvent('swal:confirm:delete_something', [
                'type' => 'info',
                'message' => 'Clear accepted invites list?', 
                'text' => 'This will only clear the list.',
                'function' => 'clear_all_accepted_invite'
            ]);
        }
    }

    public function clear_all_accepted_invite()
    {
        if ( $this->if_can_delete_many_invite() ) {
            ScholarshipScholarInvite::whereScholarship($this->scholarship_id)
                ->where('respond', true)
                ->delete();
        }
    }
    
    public function clear_all_rejected_invite_confirm()
    {
        if ( $this->if_can_delete_many_invite() ) {
            $this->dispatchBrowserEvent('swal:confirm:delete_something', [
                'type' => 'info',
                'message' => 'Clear rejected invites list?', 
                'text' => 'This will only clear the list.',
                'function' => 'clear_all_rejected_invite'
            ]);
        }
    }

    public function clear_all_rejected_invite()
    {
        if ( $this->if_can_delete_many_invite() ) {
            ScholarshipScholarInvite::whereScholarship($this->scholarship_id)
                ->where('respond', false)
                ->delete();
        }
    }
    
    public function resend_all_rejected_invite_confirm()
    {
        if ( $this->if_can_update_many_invite() ) {
            $this->dispatchBrowserEvent('swal:confirm:delete_something', [
                'type' => 'info',
                'message' => 'Resend all rejected invites?', 
                'text' => 'This will appear at pending invites.',
                'function' => 'resend_all_rejected_invite'
            ]);
        }
    }

    protected function if_can_update_many_invite()
    {
        return Auth::check() && Auth::user()->can('updateMany', [ScholarshipScholarInvite::class, $this->scholarship_id]);
    }

    public function resend_all_rejected_invite()
    {
        if ( $this->if_can_update_many_invite() ) {
            $invites = ScholarshipScholarInvite::whereScholarship($this->scholarship_id)
                ->where('respond', false)
                ->get();

            foreach ($invites as $invite) {
                $invite->respond = null;
                if ( $invite->save() ) {
                    $this->send_mail($invite->id);
                }
            }
        }
    }

    public function resend_rejected_invite($invite_id)
    {
        $invite = ScholarshipScholarInvite::find($invite_id);
        if ( Auth::guest() || Auth::user()->cannot('resend', $invite) ) 
            return;
        
        $invitation = ScholarshipScholarInvite::where('id', $invite->id)
            ->where(function ($query) {
                $query->where('respond', false)
                    ->orWhere('sent', false);
            })
            ->first();

        if ( $invitation ) {
            $invitation->respond = null;
            $invitation->sent = null;
            $invitation->save();
        }

        $this->send_mail($invite->id);
    }
    
    protected function send_mail($invite_id)
    {
        $invitation = ScholarshipScholarInvite::find($invite_id);
        if ( is_null($invitation) || isset($invitation->respond) )
            return;

        ProcessScholarInviteJob::dispatch($invitation);
    }
}
