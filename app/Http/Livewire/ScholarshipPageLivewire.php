<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipPost;
use App\Models\ScholarshipPostComment;
use App\Models\ScholarshipPostLinkRequirement;
use Illuminate\Support\Facades\DB;

class ScholarshipPageLivewire extends Component
{

    public $scholarship_id;
    public $post_count = 10;
    
    protected $listeners = ['post_updated' => '$refresh'];

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('dashboard');
            return true;
        }
        return false;
    }

    public function mount($scholarship_id)
    {
        if ($this->verifyUser()) return;

        $this->scholarship_id = $scholarship_id;
    }
    
    
    public function render()
    {
        $posts = ScholarshipPost::select('scholarship_posts.*', 'users.firstname', 'users.lastname')
            ->addSelect(['comment_count' => ScholarshipPostComment::select(DB::raw("count(scholarship_post_comments.id)"))
                ->whereColumn('scholarship_post_comments.post_id', 'scholarship_posts.id')
            ])
            ->addSelect(['link_count' => ScholarshipPostLinkRequirement::select(DB::raw("count(scholarship_post_link_requirements.id)"))
                ->whereColumn('scholarship_post_link_requirements.post_id', 'scholarship_posts.id')
            ])
            ->where('scholarship_id', $this->scholarship_id)
            ->leftJoin('users', 'scholarship_posts.user_id', '=', 'users.id')
            ->orderBy('id', 'desc')
            ->take($this->post_count)
            ->get();

        $count = ScholarshipPost::where('scholarship_id', $this->scholarship_id)->count();

        $show_more = ($count > count($posts));

        $this->dispatchBrowserEvent('remove:modal-backdrop');

        return view('livewire.pages.scholarship-page-livewire.scholarship-page-livewire', [
            'posts' => $posts,
            'show_more' => $show_more
        ]);
    }

    public function load_more()
    {
        $this->post_count += 10;
    }
}
