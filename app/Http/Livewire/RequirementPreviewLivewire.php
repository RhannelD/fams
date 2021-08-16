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
    public $requirement;
    public $categories;
    public $response_id;
    public $response;

    public $access;
    public $is_scholar;

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

    public function mount(ScholarshipRequirement $requirement_id)
    {
        if ($this->verifyUser()) return;

        $this->requirement = $requirement_id;
        
        $this->categories = ScholarshipCategory::whereHas('requirements', function ($query) {
            $query->where('requirement_id', $this->requirement->id);
        })->get();

        $this->response = ScholarResponse::where('requirement_id', $this->requirement->id)
            ->where('user_id', Auth::id())
            ->first();

        $this->response_id = (isset($this->response->id))? $this->response->id: null;

        // access is true if user is under the requirements categories
        $this->access = ScholarshipRequirementCategory::where('requirement_id', $requirement_id)
            ->whereIn('scholarship_requirement_categories.category_id', function($query){
                $query->select('scholarship_scholars.category_id')
                    ->from(with(new ScholarshipScholar)->getTable())
                    ->where('scholarship_scholars.user_id', Auth::id());
            })
            ->exists();

        // is_scholar is true if your under this scholarship program
        $this->is_scholar = ScholarshipScholar::whereHas('categories', function ($query) {
                $query->where('scholarship_id', $this->requirement->id);
            })
            ->where('user_id', Auth::id())
            ->exists();
    }

    public function render()
    {
        $comments = null;
        if ( $this->response_id ) {
            $comments = ScholarResponseComment::with('user')
                ->where('response_id', $this->response_id)
                ->get();
        }

        $can_respond = ScholarshipScholar::where('user_id', Auth::id())
            ->whereIn('category_id', function($query){
                $query->select('category_id')
                ->from(with(new ScholarshipRequirementCategory)->getTable())
                ->where('requirement_id', $this->requirement->id);
            })->exists();

        return view('livewire.pages.requirement.requirement-preview-livewire', [
            'comments' => $comments,
            'can_respond' => $can_respond,
        ])->extends('livewire.main.main-livewire');
    }
}
