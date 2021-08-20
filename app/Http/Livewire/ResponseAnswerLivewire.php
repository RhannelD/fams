<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseAnswer;

class ResponseAnswerLivewire extends Component
{ 
    public $requirement_item_id;
    public $response_id;
    public $answer;

    protected $rules = [
        'answer.answer' => 'required|string|min:1|max:60000',
    ];

    protected $messages = [
        'answer.answer.required' => 'The answer field is required.',
        'answer.answer.min' => 'The answer must be at least 3 characters.',
        'answer.answer.max' => 'The answer must not be greater than 60000 characters.',
        'answer.answer.string' => 'The answer must be a string.',
    ];

    protected function verifyUser()
    {
        if (!Auth::check() || Auth::user()->usertype != 'scholar') {
            redirect()->route('index');
            return true;
        }
        return false;
    }

    protected function verifyUserResponse()
    {
        $access = ScholarResponse::where('id', $this->response_id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$access) {
            redirect()->route('index');
        }
        return !$access;
    }

    protected function verifyItemAndResponse()
    {
        $requirement_item = ScholarshipRequirementItem::find($this->requirement_item_id);
        $response = ScholarResponse::find($this->response_id);
        if ( is_null($requirement_item) || is_null($response) ){
            $this->emitUp('refresh');
            return true;
        }
        return false;
    }

    public function mount($requirement_item_id, $response_id)
    {
        if ($this->verifyUser()) return;
        
        $this->requirement_item_id = $requirement_item_id;
        $this->response_id = $response_id;

        $this->answer = new ScholarResponseAnswer;
    }

    public function render()
    {
        $response = ScholarResponse::find($this->response_id);

        $answer = ScholarResponseAnswer::firstOrNew([
            'response_id' => $this->response_id, 
            'item_id' => $this->requirement_item_id
        ]);

        $this->answer->answer = $answer->answer;

        return view('livewire.pages.response.response-answer-livewire', ['response' => $response]);
    }

    public function updated($propertyName)
    {
        if ($this->verifyUser()) return;
        if ($this->verifyUserResponse()) return;

        $this->validateOnly($propertyName);

        $this->save();
    }

    protected function save()
    {
        if ($this->verifyItemAndResponse()) return;

        $answer = ScholarResponseAnswer::firstOrNew([
            'response_id' => $this->response_id, 
            'item_id' => $this->requirement_item_id
        ]);
        
        $answer->answer = $this->answer->answer;
        $answer->save();
    }
}
