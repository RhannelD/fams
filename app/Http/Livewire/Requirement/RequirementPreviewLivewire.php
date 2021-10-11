<?php

namespace App\Http\Livewire\Requirement;

use Livewire\Component;
use App\Models\ScholarResponse;
use App\Models\ScholarshipScholar;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarResponseComment;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RequirementPreviewLivewire extends Component
{
    use AuthorizesRequests;
    
    public $requirement_id;

    protected $listeners = [
        'comment_updated' => '$refresh'
    ];

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('preview', $this->get_requirement()) ) {
            return redirect()->route('requirement.view', [$this->requirement_id]);
        }
    }

    public function mount($requirement_id)
    {
        $this->requirement_id = $requirement_id;
        $this->authorize('preview', $this->get_requirement());
    }

    public function render()
    {
        $requirement = $this->get_requirement();

        $scholar_response = $this->get_scholar_response();

        // access is true if user is under the requirements categories
        $access = $this->get_access_under_category($requirement);

        // is_scholar is true if your under this scholarship program
        $is_scholar = $this->get_access_as_scholar($requirement);

        return view('livewire.pages.requirement.requirement-preview-livewire', [
            'requirement' => $requirement,
            'scholar_response' => $scholar_response,
            'access' => $access,
            'is_scholar' => $is_scholar,
        ])->extends('livewire.main.main-livewire');
    }

    protected function get_requirement()
    {
        return ScholarshipRequirement::find($this->requirement_id);
    }

    protected function get_scholar_response()
    {
        return ScholarResponse::where('requirement_id', $this->requirement_id)->where('user_id', Auth::id())->first();
    }

    protected function get_access_under_category($requirement)
    {
        return Auth::check() && Auth::user()->can('access_under_category', $requirement);
    }

    protected function get_access_as_scholar($requirement)
    {
        return Auth::check() && Auth::user()->can('access_as_scholar', $requirement);
    }

    public function delete_response_confirmation()
    {
        $response = $this->get_scholar_response();
        if ( Auth::check() && Auth::user()->can('delete', $response) ) {
            $this->dispatchBrowserEvent('swal:confirm:delete_response_'.$response->id, [
                'type' => 'warning',  
                'message' => 'Are you sure?', 
                'text' => 'If deleted, you will not be able to recover this response!',
                'function' => "delete_response"
            ]);
        }
    }

    public function delete_response()
    {
        $response = $this->get_scholar_response();
        if ( Auth::guest() || Auth::user()->cannot('delete', $response) ) 
            return;

        if ( $response->delete() ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Response Deleted Successfully', 
                'text' => ''
            ]);
        }
    }
}
