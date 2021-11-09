<?php

namespace App\Http\Livewire\Send;

use App\Models\User;
use Livewire\Component;
use App\Models\EmailSend;
use App\Models\EmailSendTo;
use App\Models\Scholarship;
use Livewire\WithPagination;
use App\Jobs\ScholarshipSendMailJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SendEmailLivewire extends Component
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
        'message' => 'required|min:5|max:999',
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
        $this->authorize('sendEmails', $this->get_scholarship());
        if ( !empty(request()->query('rid')) ) 
            $this->add_recipient(request()->query('rid'));
    }

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('sendEmails', $this->get_scholarship()) ) 
            return redirect()->route('scholarship.send.email', [$this->scholarship_id]);
    }

    public function render()
    {
        return view('livewire.pages.send.send-email-livewire', [
                'scholarship' => $this->get_scholarship(),
                'search_users' => $this->get_search_users(),
                'added_recipients' => $this->get_added_recipients(),
            ])
            ->extends('livewire.main.scholarship');
    }

    protected function get_scholarship()
    {
        return Scholarship::find($this->scholarship_id);
    }

    protected function get_search_users()
    {
        $scholarship_id = $this->scholarship_id;
        return User::with(['scholarship_scholar' => function ($query) use ($scholarship_id) {
                $query->whereHas('category', function ($query) use ($scholarship_id) {
                    $query->where('scholarship_id', $scholarship_id);
                });
            }])
            ->whereNotIn('id', $this->recipient)
            ->whereNameOrEmail($this->search)
            ->whereScholarOf($this->scholarship_id)
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
            ->first();
        if ( $user ) {
            $this->recipient[] = $user_id;
        }
    }

    public function remove_recipient($user_id)
    {
        $this->recipient = array_diff($this->recipient, [$user_id]);
    }

    public function view_emails()
    {
        $this->tab = 'emails';
    }

    public function send()
    {
        $scholarship = $this->get_scholarship();
        if ( Auth::guest() || Auth::user()->cannot('sendEmails', $scholarship) ) 
            return;

        $this->validate();

        $user_recipients = $this->get_added_recipients();

        $email_send = EmailSend::create([
                'scholarship_id' => $this->scholarship_id,
                'user_id' => Auth::id(),
                'message' => $this->message,
            ]);

        if ( !$email_send )
            return;

        foreach ($user_recipients as $user_recipient) {
            $send_to = EmailSendTo::create([
                'email_send_id' => $email_send->id,
                'email' => $user_recipient->email,
                'sent' => null,
            ]);
            
            ScholarshipSendMailJob::dispatch($send_to);
        }

        $this->reset('message');
        $this->reset('recipient');

        $this->view_emails();
        $this->emitTo('send.send-email-list-livewire', 'refresh');
        $this->dispatchBrowserEvent('emails-tab');
        $this->dispatchBrowserEvent('view_set_email_send', [
            'email_send_id' => $email_send->id,
        ]);
    }
}
