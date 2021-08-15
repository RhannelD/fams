<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarResponseComment;

class RequirementResponseOpenCommentLivewire extends Component
{
    public $comment;
    
    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount(ScholarResponseComment $comment_id)
    {
        if ($this->verifyUser()) return;

        $this->comment = $comment_id;
    }

    public function render()
    {
        return view('livewire.pages.requirement.requirement-response-open-comment-livewire');
    }

    public function delete_comment_confirmation()
    {
        if ($this->verifyUser()) return;

        $confirm = $this->dispatchBrowserEvent('swal:confirm:delete_comment_'.$this->comment->id, [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this comment!',
            'function' => "delete_comment"
        ]);
    }

    public function delete_comment()
    {
        if ($this->verifyUser()) return;

        $comment_id = $this->comment->id;

        if ($this->comment->delete()) {
            $this->dispatchBrowserEvent('delete_comment_div', [
                'div_class' => $comment_id,  
            ]);

            $this->emitUp('comment_updated');
        }
    }
}
