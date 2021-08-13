<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarResponse;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Facades\Auth;

class RequirementPreviewLivewire extends Component
{
    public $requirement;

    public function mount(ScholarshipRequirement $requirement_id)
    {
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

        return view('livewire.pages.requirement.requirement-preview-livewire', [
            'categories' => $categories,
            'response' => $response,
        ])->extends('livewire.main.main-livewire');
    }
}
