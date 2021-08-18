<?php

namespace App\Http\Livewire;

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

    protected function verifyUser()
    {
        if (!Auth::check()) {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    public function mount($response_id)
    {
        if ($this->verifyUser()) return;

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
        if ($this->verifyUser()) return;

        $this->validate();

        if ( !ScholarResponse::find($this->response_id) ) {
            return redirect()->route('index');
        }
   
        $this->comment->user_id = Auth::id();
        $this->comment->response_id = $this->response_id;

        if ($this->comment->save()) {
            $this->comment = new ScholarResponseComment;

            $this->emitUp('comment_updated');
        }
    }
}
