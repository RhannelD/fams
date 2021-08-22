<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ScholarResponse;
use App\Models\ScholarshipScholar;
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
        return view('livewire.pages.scholarship-requirement-response.scholarship-requirement-response-view-livewire', [
                'scholar_response' => $this->get_scholar_response()
            ]);
    }

    protected function get_scholar_response()
    {
        return ScholarResponse::find($this->response_id);
    }

    protected function is_scholar($scholar_response)
    {
        return $scholar_response->user->is_scholar($scholar_response->requirement->scholarship_id);
    }

    public function response_approve()
    {
        $scholar_response = $this->get_scholar_response();
        if ( is_null($scholar_response) ) 
            return;

        if ( $this->is_scholar($scholar_response) ) {
            $this->response_approval();
            return;
        }

        $confirm = $this->dispatchBrowserEvent('swal:confirm:approve', [
            'type' => 'info',  
            'message' => 'Add to scholars?', 
            'text' => 'Sender is not yet a scholar here.',
            'function' => "response_approval"
        ]);
    }

    public function response_approval()
    {
        $scholar_response = $this->get_scholar_response();
        if ( is_null($scholar_response) ) 
            return;

        if ( is_null($scholar_response->approval) ) {
            $scholar_response->approval = true;
            if ( !$scholar_response->save() ) {
                return;
            }

            $this->add_scholar_to_scholarship($scholar_response);

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Successfully Approved!', 
                'text' => 'Scholar\'s response was approved successfully!'
            ]);
        }
    }

    protected function add_scholar_to_scholarship($scholar_response)
    {
        if ( !$this->is_scholar($scholar_response) ) {
            $requirement_category = $scholar_response->requirement->categories->first();
            if ( $requirement_category ) {
                $scholarship_scholar = new ScholarshipScholar;
                $scholarship_scholar->user_id = $scholar_response->user_id;
                $scholarship_scholar->category_id = $requirement_category->category_id;
                $scholarship_scholar->save();
            }
        }
    }

    public function response_deny()
    {
        $scholar_response = $this->get_scholar_response();
        if ( is_null($scholar_response) ) 
            return;

        if ( !$this->is_scholar($scholar_response) ) {
            $this->response_denial();
            return;
        }

        $confirm = $this->dispatchBrowserEvent('swal:confirm:response_delete', [
            'type' => 'warning',  
            'message' => 'Deny and removed to scholars?', 
            'text' => 'Sender will be remove as scholar upon denying.',
            'function' => "response_denial"
        ]);
    }

    public function response_denial()
    {
        $scholar_response = $this->get_scholar_response();
        if ( is_null($scholar_response) ) 
            return;

        if ( is_null($scholar_response->approval) ) {
            $scholar_response->approval = false;
            if ( $scholar_response->save() ) {
                $this->remove_scholar_to_scholarship($scholar_response);

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',  
                    'message' => 'Successfully Denied!', 
                    'text' => 'Scholar\'s response was denied successfully!'
                ]);
            }
        }
    }

    protected function remove_scholar_to_scholarship($scholar_response)
    {
        $scholarship_scholar = $scholar_response->user->get_scholarship_scholar($scholar_response->requirement->scholarship_id);
        if ( isset($scholarship_scholar) )
            $scholarship_scholar->delete();  
    }

    public function response_delete_confirm()
    {
        $scholar_response = $this->get_scholar_response();
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
        $scholar_response = $this->get_scholar_response();
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
