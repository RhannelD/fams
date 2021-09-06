<?php

namespace App\Http\Livewire\ScholarshipPost;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipPost;
use App\Models\ScholarshipPostComment;

class ScholarshipPostCommentLivewire extends Component
{
    public $post_id;
    public $comment;


    protected $rules = [
        'comment.comment' => 'required|string|min:1|max:60000',
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
        $this->comment = new ScholarshipPostComment;
    }


    public function render()
    {
        return view('livewire.pages.scholarship-post.scholarship-post-comment-livewire');
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function comment()
    {
        if ($this->verifyUser()) return;

        $this->validate();

        if ( !ScholarshipPost::where('id', $this->post_id)->exists() ) {
            return redirect()->route('index');
        }
        
        $this->comment->user_id = Auth::id();
        $this->comment->post_id = $this->post_id;

        if ($this->comment->save()) {
            $this->comment = new ScholarshipPostComment;

            $this->emitUp('comment_updated');
        }
    }
}