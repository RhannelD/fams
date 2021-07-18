<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipPostComment;

class ScholarshipPostOpenCommentLivewire extends Component
{
    public $comment_id;
    public $comment;
    
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

        $this->comment_id = $id;
        $this->comment = ScholarshipPostComment::select('scholarship_post_comments.*', 'users.firstname', 'users.lastname')
            ->leftJoin('users', 'scholarship_post_comments.user_id', '=', 'users.id')
            ->where('scholarship_post_comments.id', $this->comment_id)
            ->first();
    }


    public function render()
    {
        return view('livewire.pages.scholarship-post-livewire.scholarship-post-open-comment-livewire');
    }

    public function delete_comment_confirmation()
    {
        if ($this->verifyUser()) return;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_comment_'.$this->comment_id, [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this comment!',
            'function' => "delete_comment"
        ]);
    }

    public function delete_comment()
    {
        if ($this->verifyUser()) return;

        $comment = ScholarshipPostComment::find($this->comment_id);

        if ($comment->delete()) {
            $this->dispatchBrowserEvent('delete_comment_div', [
                'div_class' => $comment->id,  
            ]);

            $this->emitUp('comment_updated');
        }
    }
}
