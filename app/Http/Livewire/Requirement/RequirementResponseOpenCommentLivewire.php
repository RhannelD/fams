<?php

namespace App\Http\Livewire\Requirement;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarResponseComment;

class RequirementResponseOpenCommentLivewire extends Component
{
    public $comment_id;
    
    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount($comment_id)
    {
        if ($this->verifyUser()) return;

        $this->comment_id = $comment_id;
    }

    public function render()
    {
        $comment = ScholarResponseComment::find($this->comment_id);

        return view('livewire.pages.requirement.requirement-response-open-comment-livewire', ['comment' => $comment]);
    }

    public function delete_comment_confirmation()
    {
        if ($this->verifyUser()) return;

        if ( !ScholarResponseComment::find($this->comment_id) ) {
            $this->emitUp('comment_updated');
            return;
        }

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

        $comment = ScholarResponseComment::find($this->comment_id);
        if ( !$comment ) {
            $this->emitUp('comment_updated');
            return;
        }

        if ($comment->delete()) {
            $this->dispatchBrowserEvent('delete_comment_div', [
                'div_class' => $this->comment_id,  
            ]);

            $this->emitUp('comment_updated');
        }
    }
}
