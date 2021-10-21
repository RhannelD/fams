<?php

namespace App\Http\Livewire\Send;

use App\Models\User;
use Livewire\Component;
use App\Models\EmailSend;
use App\Models\EmailSendTo;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ScholarshipSendMailNotification;

class SendEmailViewLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public $email_send_id;

    public $search;
    public $show_row = 10;
    
    public function getQueryString()
    {
        return [];
    }

    public function set_email_send($email_send_id)
    {
        $this->email_send_id = $email_send_id;
    }

    public function hydrate()
    {
        $email_send = $this->get_email_send();
        if ( Auth::guest() || Auth::user()->cannot('sendEmails', ($email_send? $email_send->scholarship: null)) ) {
            $this->emitUp('refresh');
            if ( isset($this->email_send_id) ) {
                $this->dispatchBrowserEvent('remove:modal-backdrop');
            }
        }
    }

    public function render()
    {
        return view('livewire.pages.send.send-email-view-livewire', [
            'email_send' => $this->get_email_send(),
            'search_users' => $this->get_search_users(),
        ]);
    }

    protected function get_email_send()
    {
        return EmailSend::find($this->email_send_id);
    }

    protected function get_search_users()
    {
        $email_send_id = $this->email_send_id;
        $email_send = $this->get_email_send();
        $scholarship_id =  isset($email_send)? $email_send->scholarship_id :null;
        return User::whereScholarOf($scholarship_id)
            ->whereNameOrEmail($this->search)
            ->whereDoesntHave('email_sent_to', function ($query) use ($email_send_id) {
                $query->where('email_send_id', $email_send_id);
            })
            ->paginate($this->show_row);
    }
    
    public function updated($propertyName)
    {
        $this->page = 1;
    }
    
    public function send($user_id)
    {
        $email_send = $this->get_email_send();
        $user_recipient = User::find($user_id);
        if ( Auth::guest() || is_null($user_recipient) || Auth::user()->cannot('sendEmails', ($email_send? $email_send->scholarship: null)) ) 
            return;

        $sent = $this->sending_email($user_recipient, $email_send);

        EmailSendTo::updateOrCreate(
            [
                'email_send_id' => $email_send->id,
                'email' => $user_recipient->email,
            ], [
                'sent' => $sent,
            ]
        );

        $this->emitTo('send.send-email-list-livewire', 'refresh');
    }

    protected function sending_email($user_recipient, $email_send)
    {
        $details = [
            'scholarship' => $email_send->scholarship->scholarship,
            'sender' => Auth::user()->flname(),
            'message' => $email_send->message,
        ];

        try {
            $user_recipient->notify(new ScholarshipSendMailNotification($details));
            return true;
        } catch (\Exception $e) {
        }
        return false;
    }
}
