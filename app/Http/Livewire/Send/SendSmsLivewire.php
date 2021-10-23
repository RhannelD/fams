<?php

namespace App\Http\Livewire\Send;

use App\Models\User;
use App\Models\SmsSend;
use Livewire\Component;
use App\Models\SmsSendTo;
use App\Models\Scholarship;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SendSmsLivewire extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $scholarship_id;
    public $tab;
    public $show_row = 10;
    public $search;

    public $message;
    public $recipient = [];

    protected $listeners = [
        'refresh' => '$refresh'
    ];

    protected $rules = [
        'message' => 'required|min:5|max:150',
    ];

    public function getQueryString()
    {
        return [
            'tab' => ['except' => ''],
        ];
    }

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
        $this->authorize('sendSMSes', $this->get_scholarship());
        if ( !empty(request()->query('rid')) ) 
            $this->add_recipient(request()->query('rid'));
    }

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('sendSMSes', $this->get_scholarship()) ) 
            return redirect()->route('scholarship.send.email', [$this->scholarship_id]);
    }

    public function render()
    {
        return view('livewire.pages.send.send-sms-livewire', [
                'scholarship' => $this->get_scholarship(),
                'search_users' => $this->get_search_users(),
                'added_recipients' => $this->get_added_recipients(),
            ])
            ->extends('livewire.main.main-livewire');
    }

    protected function get_scholarship()
    {
        return Scholarship::find($this->scholarship_id);
    }

    protected function get_search_users()
    {
        $scholarship_id = $this->scholarship_id;
        $search = $this->search;
        return User::with(['scholarship_scholar' => function ($query) use ($scholarship_id) {
                $query->whereHas('category', function ($query) use ($scholarship_id) {
                    $query->where('scholarship_id', $scholarship_id);
                });
            }])
            ->whereNotIn('id', $this->recipient)
            ->whereScholarOf($this->scholarship_id)
            ->where(function ($query) use ($search) {
                $query->whereNameOrEmail($search)
                ->orWhere('phone', 'like', "%{$search}%");
            })
            ->paginate($this->show_row);
    }

    protected function get_added_recipients()
    {
        $scholarship_id = $this->scholarship_id;
        return User::whereIn('id', $this->recipient)
            ->whereScholarOf($this->scholarship_id)
            ->get();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        if ( $propertyName == 'search' ) 
            $this->page = 1;
    }

    public function add_recipient($user_id)
    {
        $user = User::where('id', $user_id)
            ->whereScholarOf($this->scholarship_id)
            ->exists();
        if ( $user ) {
            $this->recipient[] = $user_id;
        }
    }

    public function remove_recipient($user_id)
    {
        $this->recipient = array_diff($this->recipient, [$user_id]);
    }

    public function view_SMSes()
    {
        $this->tab = 'SMSes';
    }

    public function send()
    {
        if ( Auth::guest() || Auth::user()->cannot('sendSMSes', $this->get_scholarship()) ) 
            return;

        $this->validate();

        $user_recipients = $this->get_added_recipients();

        $sms_send = SmsSend::create([
                'scholarship_id' => $this->scholarship_id,
                'user_id' => Auth::id(),
                'message' => $this->message,
            ]);

        if ( !$sms_send )
            return;

        foreach ($user_recipients as $user_recipient) {
            $sent = $this->send_sms($user_recipient);

            SmsSendTo::create([
                'sms_send_id' => $sms_send->id,
                'user_id' => $user_recipient->id,
                'sent' => $sent,
            ]);
        }

        $this->reset('message');
        $this->reset('recipient');

        $this->view_SMSes();
        $this->emitTo('send.send-sms-list-livewire', 'refresh');
        $this->dispatchBrowserEvent('smses-tab');
        $this->dispatchBrowserEvent('view_set_sms_send', [
            'sms_send_id' => $sms_send->id,
        ]);
    }

    protected function send_sms($user_recipient)
    {
        try {
            // $user_recipient->notify(new ScholarshipSendMailNotification($details));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
