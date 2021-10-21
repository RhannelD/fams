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

}
