<?php

namespace App\Http\Livewire\Send;

use App\Models\SmsSend;
use Livewire\Component;
use App\Models\Scholarship;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class SendSmsListLivewire extends Component
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
        if ( Auth::guest() || Auth::user()->cannot('sendSMSes', $this->get_scholarship()) ) 
            $this->emitUp('refresh');
    }

    public function render()
    {
        return view('livewire.pages.send.send-sms-list-livewire', [
            'scholarship' => $this->get_scholarship(),
            'send_sms_list' => $this->get_send_sms_list(),
            'has_more' => $this->get_has_more(),
        ]);
    }
    
    protected function get_scholarship()
    {
        return Scholarship::find($this->scholarship_id);
    }

    protected function get_send_sms_list()
    {
        return $this->set_sms_email_list()
            ->orderBy('id', 'desc')
            ->paginate($this->show_row);
    }

    protected function get_has_more()
    {
        return $this->show_row < $this->set_sms_email_list()->count();
    }

    protected function set_sms_email_list()
    {
        $search = $this->search;
        return SmsSend::where('scholarship_id', $this->scholarship_id)
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

    protected function get_send_sms($send_sms_id)
    {
        return SmsSend::find($send_sms_id);
    }

    protected function cant_delete_sent_sms()
    {
        return Auth::guest() || Auth::user()->cannot('deleteSendSMSes', $this->get_scholarship());
    }

    public function delete_send_sms_confirm($send_sms_id)
    {
        if ( $this->cant_delete_sent_sms() || empty($this->get_send_sms($send_sms_id)) ) 
            return;
        
        $this->dispatchBrowserEvent('swal:confirm:delete_send_sms', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this sent SMS!',
            'send_sms_id' => $send_sms_id
        ]);
    }

    public function delete_send_sms($send_sms_id)
    {
        $send_sms = $this->get_send_sms($send_sms_id);
        if ( $this->cant_delete_sent_sms() || empty($send_sms) ) 
            return;
        
        if ( $send_sms->delete() ) 
            session()->flash('message-success', 'Sent SMS successfully deleted.');
    }
}
