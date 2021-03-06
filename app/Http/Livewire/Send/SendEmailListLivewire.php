<?php

namespace App\Http\Livewire\Send;

use Livewire\Component;
use App\Models\EmailSend;
use App\Models\Scholarship;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class SendEmailListLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public $scholarship_id;
    public $search;
    public $show_row = 8;

    protected $listeners = [
        'refresh' => '$refresh'
    ];

    public function getQueryString()
    {
        return [];
    }

    public function mount($scholarship_id)
    {
        $this->scholarship_id = $scholarship_id;
    }

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('sendEmails', $this->get_scholarship()) ) 
            $this->emitUp('refresh');
    }

    public function render()
    {
        return view('livewire.pages.send.send-email-list-livewire', [
            'scholarship' => $this->get_scholarship(),
            'send_email_list' => $this->get_send_email_list(),
            'has_more' => $this->get_has_more(),
        ]);
    }
    
    protected function get_scholarship()
    {
        return Scholarship::find($this->scholarship_id);
    }

    protected function get_send_email_list()
    {
        return $this->set_send_email_list()
            ->orderBy('id', 'desc')
            ->paginate($this->show_row);
    }

    protected function get_has_more()
    {
        return $this->show_row < $this->set_send_email_list()->count();
    }

    protected function set_send_email_list()
    {
        $search = $this->search;
        return EmailSend::where('scholarship_id', $this->scholarship_id)
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('message', 'like', "%{$search}%")
                ->orWhereHas('user', function ($query) use ($search) {
                    $query->whereNameOrEmail($search);
                });
            });
    }
    
    public function updated($propertyName)
    {
        if ( $propertyName == 'search' ) 
            $this->page = 1;
    }

    protected function get_send_email($send_email_id)
    {
        return EmailSend::find($send_email_id);
    }

    protected function cant_delete_sent_email()
    {
        return Auth::guest() || Auth::user()->cannot('deleteSendEmails', $this->get_scholarship());
    }

    public function delete_send_email_confirm($send_email_id)
    {
        if ( $this->cant_delete_sent_email() || empty($this->get_send_email($send_email_id)) ) 
            return;
        
        $this->dispatchBrowserEvent('swal:confirm:delete_send_email', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this sent Email!',
            'send_email_id' => $send_email_id
        ]);
    }

    public function delete_send_email($send_email_id)
    {
        $send_email = $this->get_send_email($send_email_id);
        if ( $this->cant_delete_sent_email() || empty($send_email) ) 
            return;
    
        if ( $send_email->delete() ) 
            session()->flash('message-success', 'Sent Email successfully deleted.');
    }
}
