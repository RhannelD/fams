<?php

namespace App\Http\Livewire\Requirement;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseComment;

class RequirementResponseCommentLivewire extends Component
{
    public $response_id;
    public $comment;

    protected $rules = [
        'comment.comment' => 'required|string|min:1|max:60000',
    ];

    public function hydrate()
    {
        if ( Auth::guest() || Auth::user()->cannot('create', [ScholarResponseComment::class, $this->response_id]) ) {
            $this->emitUp('comment_updated');
        }
    }

    public function mount($response_id)
    {
        $this->response_id = $response_id;
        $this->comment = new ScholarResponseComment;
    }

    public function render()
    {
        return view('livewire.pages.requirement.requirement-response-comment-livewire');
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function comment()
    {
        $this->validate();

        if ( Auth::check() && Auth::user()->can('create', [ScholarResponseComment::class, $this->response_id]) ) {
            $this->comment->user_id = Auth::id();
            $this->comment->response_id = $this->response_id;
    
            if ($this->comment->save()) {
                $this->comment = new ScholarResponseComment;
            }
        }

        $this->emitUp('comment_updated');
    }
}
