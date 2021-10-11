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

        if (!$access)
            redirect()->route('index');
        
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
    }

    public function render()
    {
        $response = $this->get_user_response();

        $response_id = $this->response_id;
        $options = ScholarshipRequirementItemOption::select('id', 'option')
            ->where('item_id', $this->requirement_item_id)
            ->with(['responses' => function ($query) use ($response_id) {
                $query->where('response_id', $response_id);
            }])
            ->get();

        $response_option = ScholarResponseOption::where('response_id', $this->response_id)
            ->where('item_id', $this->requirement_item_id)
            ->first();
        $this->option_id = (isset($response_option))? $response_option->option_id: null;

        return view('livewire.pages.response.response-radio-livewire', [
                'response' => $response,
                'options' => $options,
            ]);
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

    protected function save()
    {
        ScholarResponseOption::updateOrCreate([
                'response_id' => $this->response_id,
                'item_id' => $this->requirement_item_id
            ],[
                'option_id' => $this->option_id
            ]);
    }

    public function clear_selection()
    {
        if ($this->verifyUser()) return;
        if ($this->verifyUserResponse()) return;
        if ($this->verifyItemAndResponse()) return;
        
        ScholarResponseOption::where('response_id', $this->response_id)
            ->where('item_id', $this->requirement_item_id)
            ->delete();
    }
}
