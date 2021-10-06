<?php

namespace App\Http\Livewire\ScholarScholarship;

use Livewire\Component;
use App\Models\ScholarshipPost;
use Illuminate\Support\Facades\Auth;

class ScholarScholarshipFindLivewire extends Component
{
    public function render()
    {
        return view('livewire.pages.scholar-scholarship.scholar-scholarship-find-livewire', [
                'posts' => $this->get_posts()
            ]);
    }

    protected function get_posts()
    {
        return ScholarshipPost::with('scholarship')
            ->wherePromote()
            ->orderBy('updated_at', 'desc')
            ->get();
    }
}