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
    

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount(ScholarshipPost $id)
    {
        if ($this->verifyUser()) return;

        $this->scholarship = Scholarship::find($id->scholarship_id);
        $this->post = $id;
    }


    public function render()
    {
        $comments = ScholarshipPostComment::select('scholarship_post_comments.*', 'users.firstname', 'users.lastname')
            ->leftJoin('users', 'scholarship_post_comments.user_id', '=', 'users.id')
            ->where('post_id', $this->post->id)
            ->take($this->post_count)
            ->get();

        $this->comment_count = ScholarshipPostComment::where('post_id', $this->post->id)->count();

        $show_more = ($this->comment_count > count($comments));

        return view('livewire.pages.scholarship-page-livewire.scholarship-post-open-livewire', [
                'comments' => $comments,
                'show_more' => $show_more
            ])->extends('livewire.main.main-livewire');
    }

    public function load_more()
    {
        $this->post_count += 10;
    }
}
