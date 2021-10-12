<?php

namespace App\Http\Livewire\Response;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipRequirementItem;
use App\Models\ScholarshipRequirementItemOption;
use App\Models\ScholarResponse;
use App\Models\ScholarResponseOption;

class ResponseRadioLivewire extends Component
{
    public $requirement_item_id;
    public $response_id;
    public $option_id;

    protected $rules = [
        'option_id' => 'required|exists:scholarship_requirement_item_options,id',
    ];

    protected $messages = [
        'option.required' => 'The option field is required.',
        'option.exists' => 'The option you selected is not existing.',
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
        $this->set_option_id();
    }

    public function render()
    {
        $this->set_option_id();
        
        return view('livewire.pages.response.response-radio-livewire', [
                'response' => $this->get_user_response(),
                'options' => $this->get_requirement_item_options(),
            ]);
    }

    protected function get_requirement()
    {
        $item = ScholarshipRequirementItem::find($this->requirement_item_id);
        return $item? $item->requirement: null;
    }

    protected function get_requirement_item_options()
    {
        $response_id = $this->response_id;
        return ScholarshipRequirementItemOption::where('item_id', $this->requirement_item_id)
            ->with(['responses' => function ($query) use ($response_id) {
                $query->where('response_id', $response_id);
            }])
            ->get();
    }

    protected function set_option_id()
    {
        $response_option = ScholarResponseOption::where('response_id', $this->response_id)
            ->where('item_id', $this->requirement_item_id)
            ->first();
        $this->option_id = (isset($response_option))? $response_option->option_id: null;
    }

    protected function get_user_response()
    {
        return ScholarResponse::find($this->response_id);
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

        ScholarResponseOption::updateOrCreate([
                'response_id' => $this->response_id,
                'item_id' => $this->requirement_item_id
            ],[
                'option_id' => $this->option_id
            ]);
    }

    public function clear_selection()
    {
        if ( $this->cant_update() )
            return;

        ScholarResponseOption::where('response_id', $this->response_id)
            ->where('item_id', $this->requirement_item_id)
            ->delete();
    }
}
