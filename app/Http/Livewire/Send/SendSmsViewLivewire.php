<?php

namespace App\Http\Livewire\Send;

use App\Models\User;
use App\Models\SmsSend;
use Livewire\Component;
use App\Jobs\SmsSendJob;
use App\Models\SmsSendTo;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SmsSendNotification;

class SendSmsViewLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public $sms_send_id;

    public $search;
    public $show_row = 10;
    
    public function getQueryString()
    {
        return [];
    }

    public function set_sms_send($sms_send_id)
    {
        $this->sms_send_id = $sms_send_id;
    }

    public function hydrate()
    {
        $sms_send = $this->get_sms_send();
        if ( Auth::guest() || Auth::user()->cannot('sendEmails', ($sms_send? $sms_send->scholarship: null)) ) {
            $this->emitUp('refresh');
            if ( isset($this->sms_send_id) ) {
                $this->dispatchBrowserEvent('remove:modal-backdrop');
            }
        }
    }

    public function render()
    {
        return view('livewire.pages.send.send-sms-view-livewire', [
            'sms_send' => $this->get_sms_send(),
            'search_users' => $this->get_search_users(),
        ]);
    }

    protected function get_sms_send()
    {
        return SmsSend::find($this->sms_send_id);
    }

    protected function get_search_users()
    {
        $sms_send_id = $this->sms_send_id;
        $sms_send = $this->get_sms_send();
        $scholarship_id =  isset($sms_send)? $sms_send->scholarship_id :null;
        return User::whereScholarOf($scholarship_id)
            ->whereNameOrEmail($this->search)
            ->whereDoesntHave('sms_sent_to', function ($query) use ($sms_send_id) {
                $query->where('sms_send_id', $sms_send_id);
            })
            ->paginate($this->show_row);
    }
    
    public function updated($propertyName)
    {
        $this->page = 1;
    }
    
    public function send($user_id)
    {
        $sms_send = $this->get_sms_send();
        $user_recipient = User::find($user_id);
        if ( Auth::guest() || is_null($user_recipient) || Auth::user()->cannot('sendSMSes', ($sms_send? $sms_send->scholarship: null)) ) 
            return;

        $send_to = SmsSendTo::updateOrCreate(
            [
                'sms_send_id' => $sms_send->id,
                'user_id' => $user_recipient->id,
            ], [
                'sent' => null,
            ]
        );

        SmsSendJob::dispatch($send_to);

        $this->emitTo('send.send-sms-list-livewire', 'refresh');
    }
}
