<?php

namespace App\Http\Livewire\ScholarshipPost;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Scholarship;
use App\Models\ScholarshipPost;
use App\Models\ScholarshipPostComment;
use App\Models\ScholarshipPostLinkRequirement;
use App\Models\ScholarshipRequirement;

class ScholarshipPostOpenLivewire extends Component
{
    public $post_id;
    public $post_count = 10;
    
    protected $listeners = [
        'comment_updated' => '$refresh',
        'post_updated' => '$refresh'
    ];

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount($post_id)
    {
        if ($this->verifyUser()) return;

        $this->post_id = $post_id;
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
        if ($this->verifyUser()) return;
        
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
        if ($this->verifyUser()) return;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_post_'.$this->post_id, [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this post!',
            'function' => "delete_post"
        ]);
    }

    public function delete_post()
    {
        if ($this->verifyUser()) return;

        $post = ScholarshipPost::find($this->post_id);
        if ( !$post ) {
            return;
        }

        $scholarship_id = $post->scholarship_id;

        if ($post->delete()) {
            redirect()->route('scholarship.home', [$scholarship_id]);
        }
    }
}
