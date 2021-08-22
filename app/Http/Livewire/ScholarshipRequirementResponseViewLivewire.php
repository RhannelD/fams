<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ScholarResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScholarshipRequirementResponseViewLivewire extends Component
{
    public $response_id;

    protected $listeners = [
        'comment_updated' => '$refresh'
    ];

    public function mount($response_id)
    {
        $this->response_id = $response_id;
    }

    public function render()
    {
        $scholar_response = ScholarResponse::find($this->response_id);

        return view('livewire.pages.scholarship-requirement-response.scholarship-requirement-response-view-livewire', [
                'scholar_response' => $scholar_response
            ]);
    }

    public function response_approve()
    {
        $scholar_response = ScholarResponse::find($this->response_id);
        if ( is_null($scholar_response) ) 
            return;

        if ( is_null($scholar_response->approval) ) {
            $scholar_response->approval = true;
            if ( $scholar_response->save() ) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',  
                    'message' => 'Successfully Approved!', 
                    'text' => 'Scholar\'s response was approved successfully!'
                ]);
            }
        }
    }

    public function response_deny()
    {
        $scholar_response = ScholarResponse::find($this->response_id);
        if ( is_null($scholar_response) ) 
            return;

        if ( is_null($scholar_response->approval) ) {
            $scholar_response->approval = false;
            if ( $scholar_response->save() ) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',  
                    'message' => 'Successfully Denied!', 
                    'text' => 'Scholar\'s response was denied successfully!'
                ]);
            }
        }
    }

    public function response_delete_confirm()
    {
        $scholar_response = ScholarResponse::find($this->response_id);
        if ( is_null($scholar_response) ) 
            return;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:response_delete', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'Removing response!',
            'function' => "response_delete"
        ]);
    }

    public function response_delete()
    {
        $scholar_response = ScholarResponse::find($this->response_id);
        if ( is_null($scholar_response) ) 
            return;

        if ( isset($scholar_response->approval) ) {
            $scholar_response->approval = null;
            if ( $scholar_response->save() ) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',  
                    'message' => 'Approval Removed!', 
                    'text' => 'Response approval was removed successfully!'
                ]);
            }
        }
    }
}
