<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Scholarship;
use App\Models\ScholarshipPost;
use App\Models\ScholarshipPostComment;

class ScholarshipPostOpenLivewire extends Component
{
    public $scholarship;
    public $post;
    public $comment_count;
    public $post_count = 10;
    
    protected $listeners = ['comment_updated' => '$refresh'];

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount($id)
    {
        if ($this->verifyUser()) return;

        $this->post = ScholarshipPost::select('scholarship_posts.*', 'users.firstname', 'users.lastname')
            ->leftJoin('users', 'scholarship_posts.user_id', '=', 'users.id')
            ->where('scholarship_posts.id', $id)
            ->first();
        $this->scholarship = Scholarship::find($this->post->scholarship_id);
    }


    public function render()
    {
        $comments = ScholarshipPostComment::select('scholarship_post_comments.*')
            ->where('post_id', $this->post->id)
            ->take($this->post_count)
            ->get();

        $this->comment_count = ScholarshipPostComment::where('post_id', $this->post->id)->count();

        $show_more = ($this->comment_count > count($comments));

        return view('livewire.pages.scholarship-post-livewire.scholarship-post-open-livewire', [
                'comments' => $comments,
                'show_more' => $show_more
            ])->extends('livewire.main.main-livewire');
    }

    public function load_more()
    {
        if ($this->verifyUser()) return;
        
        $this->post_count += 10;
    }

    public function delete_post_confirmation()
    {
        if ($this->verifyUser()) return;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_post_'.$this->post->id, [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this post!',
            'function' => "delete_post"
        ]);
    }

    public function delete_post()
    {
        if ($this->verifyUser()) return;

        $comments = ScholarshipPostComment::where('post_id', $this->post->id)
            ->delete();

        $post = ScholarshipPost::find($this->post->id);

        if ($post->delete()) {
            redirect()->route('scholarship.program', [$this->scholarship->id, 'home']);
        }
    }
}
