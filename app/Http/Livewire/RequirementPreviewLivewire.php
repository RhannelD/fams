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
    }

    public function render()
    {
        $categories = ScholarshipCategory::whereHas('requirements', function ($query) {
                $query->where('requirement_id', $this->requirement->id);
            })->get();

        $response = ScholarResponse::where('requirement_id', $this->requirement->id)
            ->where('user_id', Auth::id())
            ->first();

        $response_comments = null;
        if ( $response ) {
            $response_comments = ScholarResponseComment::with('user')
                ->where('response_id', $response->id)
                ->get();
        }

        $can_respond = ScholarshipScholar::where('user_id', Auth::id())
            ->whereIn('category_id', function($query){
                $query->select('category_id')
                ->from(with(new ScholarshipRequirementCategory)->getTable())
                ->where('requirement_id', $this->requirement->id);
            })->exists();

        return view('livewire.pages.requirement.requirement-preview-livewire', [
            'categories' => $categories,
            'response' => $response,
            'response_comments' => $response_comments,
            'can_respond' => $can_respond,
        ])->extends('livewire.main.main-livewire');
    }
}
