<?php

namespace App\Http\Livewire\Requirement;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarResponseComment;

class RequirementResponseOpenCommentLivewire extends Component
{
    public $comment_id;
    
    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('view', $this->get_comment()) ) {
            $this->emitUp('comment_updated');
        }
    }

    public function mount($comment_id)
    {
        $this->comment_id = $comment_id;
    }

    public function render()
    {
        return view('livewire.pages.requirement.requirement-response-open-comment-livewire', ['comment' => $this->get_comment()]);
    }

    protected function get_comment()
    {
        return ScholarResponseComment::find($this->comment_id);
    }

    public function delete_comment_confirmation()
    {
        if ( Auth::guest() || Auth::user()->cannot('delete', $this->get_comment()) ) {
            $this->emitUp('comment_updated');
            return;
        }

        $this->dispatchBrowserEvent('swal:confirm:delete_comment_'.$this->comment_id, [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'text' => 'If deleted, you will not be able to recover this comment!',
            'function' => "delete_comment"
        ]);
    }

    public function delete_comment()
    {
        $comment = $this->get_comment();
        if ( Auth::guest() || Auth::user()->cannot('delete', $comment) ) {
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
