<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementCategory;
use App\Models\ScholarshipScholar;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseComment;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;

class RequirementPreviewLivewire extends Component
{
    public $requirement_id;
    public $response_id;

    protected $listeners = [
        'comment_updated' => '$refresh'
    ];

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount($requirement_id)
    {
        if ($this->verifyUser()) return;

        $this->requirement_id = $requirement_id;
    }

    public function render()
    {
        $requirement = ScholarshipRequirement::find($this->requirement_id);

        $response = ScholarResponse::where('requirement_id', $this->requirement_id)
            ->where('user_id', Auth::id())
            ->first();

        $this->response_id = (isset($response->id))? $response->id: null;

        // access is true if user is under the requirements categories
        $access = ScholarshipRequirementCategory::where('requirement_id', $this->requirement_id)
            ->whereIn('scholarship_requirement_categories.category_id', function($query){
                $query->select('scholarship_scholars.category_id')
                    ->from(with(new ScholarshipScholar)->getTable())
                    ->where('scholarship_scholars.user_id', Auth::id());
            })
            ->exists();

        // is_scholar is true if your under this scholarship program
        $is_scholar = false;
        if ( isset($requirement) ) {
            $is_scholar = ScholarshipScholar::whereHas('category', function ($query) use ($requirement) {
                    $query->where('scholarship_id', $requirement->scholarship_id);
                })
                ->where('user_id', Auth::id())
                ->exists();
        }

        $can_respond = ScholarshipScholar::where('user_id', Auth::id())
            ->whereIn('category_id', function($query){
                $query->select('category_id')
                ->from(with(new ScholarshipRequirementCategory)->getTable())
                ->where('requirement_id', $this->requirement_id);
            })->exists();

        return view('livewire.pages.requirement.requirement-preview-livewire', [
            'requirement' => $requirement,
            'response' => $response,
            'access' => $access,
            'is_scholar' => $is_scholar,
            'can_respond' => $can_respond,
        ])->extends('livewire.main.main-livewire');
    }

    public function delete_response_confirmation()
    {
        if ($this->verifyUser()) return;

        $response = ScholarResponse::find($this->response_id);
        if ( is_null($response) ) {
            $this->response_id = null;
            return;
        } elseif ( isset($response->approval) ) {
            return;
        }

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_response_'.$this->response_id, [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this response!',
            'function' => "delete_response"
        ]);
    }

    public function delete_response()
    {
        if ($this->verifyUser()) return;

        $response = ScholarResponse::find($this->response_id);
        if ( is_null($response) ) {
            return;
        } elseif ( isset($response->approval) ) {
            return;
        }

        if ( $response->delete() ) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Response Deleted Successfully', 
                'text' => ''
            ]);

            $this->response_id = null;
        }
    }
}
