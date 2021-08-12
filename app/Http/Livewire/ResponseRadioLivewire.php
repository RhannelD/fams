<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseOption;

class ResponseRadioLivewire extends Component
{
    public $requirement_item;
    public $response_id;
    public $option;

    protected $rules = [
        'option.option_id' => 'required|exists:scholarship_requirement_item_options,id',
    ];

    protected $messages = [
        'option.option.required' => 'The option field is required.',
        'option.option.exists' => 'The option you selected is not existing.',
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

    public function mount(ScholarshipRequirementItem $id, $response_id)
    {
        if ($this->verifyUser()) return;
        
        $this->requirement_item = $id;
        $this->response_id = $response_id;

        $this->option = ScholarResponseOption::firstOrNew([
            'response_id' => $this->response_id
        ]);
    }

    public function render()
    {
        $options = ScholarshipRequirementItemOption::select('id', 'option')
            ->where('item_id', $this->requirement_item->id)
            ->get();

        return view('livewire.pages.response.response-radio-livewire', ['options' => $options]);
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
        $this->option->response_id = $this->response_id;
        $this->option->save();
    }

    public function clear_selection()
    {
        if ($this->verifyUser()) return;
        if ($this->verifyUserResponse()) return;

        if ( !$this->option->id ) {
            return;
        }

        $this->option->delete();
        $this->option = ScholarResponseOption::firstOrNew([
            'response_id' => $this->response_id
        ]);
    }
}
