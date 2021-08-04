<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarResponseAnswer;

class ResponseAnswerLivewire extends Component
{ 
    public $requirement_item;
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

    public function mount(ScholarshipRequirementItem $id, $response_id)
    {
        if ($this->verifyUser()) return;
        
        $this->requirement_item = $id;
        $this->response_id = $response_id;

        $this->answer = ScholarResponseAnswer::firstOrNew([
            'response_id' => $this->response_id, 
            'item_id' => $this->requirement_item->id
        ]);
    }

    public function render()
    {
        return view('livewire.pages.response.response-answer-livewire');
    }

    public function updated($propertyName)
    {
        if ($this->verifyUser()) return;

        $this->validateOnly($propertyName);

        $this->save();
    }

    protected function save()
    {
        $this->answer->response_id = $this->response_id;
        $this->answer->item_id = $this->requirement_item->id;
        $this->answer->save();
    }
}
