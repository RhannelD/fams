<?php

namespace App\Http\Livewire\ScholarshipPost;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipPostComment;

class ScholarshipPostOpenCommentLivewire extends Component
{
    public $comment_id;
    
    public function mount($id)
    {
        $this->comment_id = $id;
    }

    public function hydrate()
    {
        $comment = $this->get_comment();
        if ( Auth::guest() || Auth::user()->cannot('view', $comment->post) ) {
            $this->emitUp('comment_updated');
        }
    }

    public function render()
    {
        return view('livewire.pages.scholarship-post.scholarship-post-open-comment-livewire', ['comment' => $this->get_comment()]);
    }

    protected function get_comment()
    {
        return ScholarshipPostComment::find($this->comment_id);
    }

    public function delete_comment_confirmation()
    {
        if ( Auth::check() && Auth::user()->can('delete', $this->get_comment()) ) {
            $this->dispatchBrowserEvent('swal:confirm:delete_comment_'.$this->comment_id, [
                'type' => 'warning',  
                'message' => 'Are you sure?', 
                'text' => 'If deleted, you will not be able to recover this comment!',
                'function' => "delete_comment"
            ]);
        }
    }

    public function delete_comment()
    {
        $comment = $this->get_comment();
        if ( !$comment || Auth::guest() || Auth::user()->cannot('delete', $comment) ) {
            return;
        }

        if ($comment->delete()) {
            $this->dispatchBrowserEvent('delete_comment_div', [
                'div_class' => $comment->id,  
            ]);
            
            $this->emitUp('comment_updated');
        }
    }
}
