<?php

namespace App\Http\Livewire\ScholarshipPost;

use Livewire\Component;
use App\Models\Scholarship;
use App\Models\ScholarshipPost;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipPostComment;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipPostLinkRequirement;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScholarshipPostOpenLivewire extends Component
{
    use AuthorizesRequests;
    
    public $post_id;
    public $post_count = 10;
    
    protected $listeners = [
        'comment_updated' => '$refresh',
        'post_updated' => '$refresh'
    ];

    public function mount($post_id)
    {
        $this->post_id = $post_id;
        $this->authorize('view', $this->get_post());
    }

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('view', $this->get_post()) ) {
            return redirect()->route('post.show', [$this->post_id]);
        }
    }

    public function render()
    {
        $comments = $this->get_comments();
        $comment_count = $this->get_comment_count();
        $show_more = ($comment_count > count($comments));

        return view('livewire.pages.scholarship-post.scholarship-post-open-livewire', [
                'post' => $this->get_post(),
                'comments' => $comments,
                'comment_count' => $comment_count,
                'show_more' => $show_more,
            ])->extends('livewire.main.main-livewire');
    }

    protected function get_post()
    {
        return ScholarshipPost::find($this->post_id);
    }

    protected function get_comments()
    {
        return ScholarshipPostComment::select('scholarship_post_comments.*')
            ->where('post_id', $this->post_id)
            ->latest('id')
            ->when(isset($this->post_count), function ($query) {
                    $query->take($this->post_count);
                })
            ->get()
            ->reverse()
            ->values();
    }

    protected function get_comment_count()
    {
        return ScholarshipPostComment::where('post_id', $this->post_id)->count();
    }

    public function load_more()
    {
        $this->post_count += 10;
    }

    public function view_all()
    {
        $this->post_count = null;
    }

    public function view_latest()
    {
        $this->post_count = 10;
    }

    public function delete_post_confirmation()
    {
        if ( Auth::check() && Auth::user()->can('delete', $this->get_post()) ) {
            $this->dispatchBrowserEvent('swal:confirm:delete_post_'.$this->post_id, [
                'type' => 'warning',  
                'message' => 'Are you sure?', 
                'text' => 'If deleted, you will not be able to recover this post!',
                'function' => "delete_post"
            ]);
        }
    }

    public function delete_post()
    {
        $post = $this->get_post();
        if ( !$post || Auth::guest() || Auth::user()->cannot('delete', $post) ) {
            return;
        }

        $scholarship_id = $post->scholarship_id;

        if ($post->delete()) {
            redirect()->route('scholarship.home', [$scholarship_id]);
        }
    }
}
