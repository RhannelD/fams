<?php

namespace App\Http\Livewire\Response;

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

    public function hydrate()
    {
        $response = $this->get_user_response();
        if ( Auth::guest() || $this->is_admin() || Auth::user()->cannot('view', $response) || Auth::user()->cannot('respond', $response->requirement) )
            return $this->emitUp('refresh');
    }

    protected function is_admin()
    {
        return Auth::check() && Auth::user()->is_admin();
    }

    public function mount($requirement_item_id, $response_id)
    {
        $this->requirement_item_id = $requirement_item_id;
        $this->response_id = $response_id;
        $this->answer = new ScholarResponseAnswer;
    }

    public function render()
    {
        $this->set_answer();

        return view('livewire.pages.response.response-answer-livewire', ['response' => $this->get_user_response()]);
    }

    protected function get_requirement()
    {
        $item = ScholarshipRequirementItem::find($this->requirement_item_id);
        return $item? $item->requirement: null;
    }

    protected function get_user_response()
    {
        return ScholarResponse::find($this->response_id);
    }

    protected function set_answer()
    {
        $answer = ScholarResponseAnswer::firstOrNew([
            'response_id' => $this->response_id, 
            'item_id' => $this->requirement_item_id
        ]);

        $this->answer->answer = $answer->answer;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        $this->save();
    }

    protected function cant_update()
    {
        return Auth::guest() || $this->is_admin() || Auth::user()->cannot('respond', $this->get_requirement()) || Auth::user()->cannot('submit', $this->get_user_response());
    }

    protected function save()
    {
        if ( $this->cant_update() ) 
            return;

        $answer = ScholarResponseAnswer::firstOrNew([
            'response_id' => $this->response_id, 
            'item_id' => $this->requirement_item_id
        ]);
        
        $answer->answer = $this->answer->answer;
        $answer->save();
    }
}
